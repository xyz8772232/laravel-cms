<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use SoftDeletes;

    /**
     * 访问器被附加到模型数组的形式。
     *
     * @var array
     */
    protected $appends = ['deletable'];

    protected $visible = ['id', 'name', 'grade', 'parent_id'];

    protected $fillable = ['name', 'admin_user_id', 'grade', 'order', 'parent_id'];

    /**
     * @var array
     */
    protected static $branchOrder = [];


    public function parent_channel()
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function children_channel()
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function getDeletableAttribute()
    {
        $children_channel = $this->children_channel()->first();
        if ($children_channel) {
            return false;
        }
        $article = $this->articles()->first();
        if ($article) {
            return false;
        }
        return true;
    }


    /**
     * Format data to tree like array.
     *
     * @param array $elements
     * @param int   $parentId
     * @param boolean $deletable 是否需要deletable属性
     *
     * @return array
     */
    public static function toTree(array $elements = [], $parentId = 0, $deletable = false)
    {
        $branch = [];

        if (empty($elements)) {
            if ($deletable) {
                $elements = static::orderByRaw('`order` = 0,`order`')->orderBy('created_at')->get()->makeVisible('deletable')->toArray();
            } else {
                $elements = static::orderByRaw('`order` = 0,`order`')->orderBy('created_at')->get()->toArray();
            }
        }

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = static::toTree($elements, $element['id']);

                if ($children) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Get the ids of the branches in the tree
     * @param array $elements
     * @param int $parentId
     * @return array
     */
    public static function branchIds($elements = [], $parentId = 0)
    {
        $branch = [];

        if (empty($elements)) {
            $elements = static::orderByRaw('`order` = 0,`order`')->select('id', 'parent_id')->get();
        }

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $branch[] = $element['id'];
                $children = static::branchIds($elements, $element['id']);
                if ($children) {
                    $branch = array_merge($branch, $children);
                }
            }
        }
        return $branch;
    }

    public static function parentIds($childId = 0)
    {
        return array_values(array_reverse(static::parentIdsP($childId), true));
    }

    private static function parentIdsP($childId = 0)
    {
        $parent = [];
        $channel = static::find($childId);
        if ($channel) {
            $parent[$channel->grade] = $channel->id;
            $parent +=  static::parentIdsP($channel->parent_id);
        }
        return $parent;
    }


    /**
     * Set the order of branches in the tree.
     *
     * @param array $order
     *
     * @return void
     */

    protected static function setBranchOrder(array $order)
    {
        static::$branchOrder = array_flip(array_flatten($order));

        static::$branchOrder = array_map(function ($item) {
            return ++$item;
        }, static::$branchOrder);
    }

    /**
     * Save a tree from a tree like array.
     *
     * @param array $tree
     * @param int   $parentId
     */
    public static function saveTree(array $tree = [], $parentId = 0)
    {
            static::setBranchOrder($tree);

            foreach ($tree as $branch) {
                $node = static::find($branch['id']);

                $node->parent_id = $parentId;
                $node->order = static::$branchOrder[$branch['id']];
                $node->save();

                if (isset($branch['children'])) {
                    static::saveTree($branch['children'], $branch['id']);
                }
            }
    }

    /**
     * Build options of select field in form.
     *
     * @param array  $elements
     * @param int    $parentId
     * @param string $prefix
     *
     * @return array
     */
    public static function buildSelectOptions($elements = [], $parentId = 0, $prefix = '')
    {
        $prefix = $prefix ?: str_repeat('&nbsp;', 6);

        $options = [];

        if (empty($elements)) {
            $elements = static::orderBy('parent_id')->orderByRaw('`order` = 0,`order`')->get(['id', 'parent_id', 'name'])->toArray();
        }

        foreach ($elements as $element) {
            $element['name'] = $prefix.'&nbsp;'.$element['name'];
            if ($element['parent_id'] == $parentId) {
                $children = static::buildSelectOptions($elements, $element['id'], $prefix.$prefix);
                $options[$element['id']] = $element['name'];

                if ($children) {
                    $options += $children;
                }
            }
        }

        return $options;
    }

}
