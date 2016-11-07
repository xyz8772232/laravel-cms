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

    protected $visible = ['id', 'name', 'parent_id', 'deletable'];

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
        if ($this->grade == 4) {
            $article = $this->articles()->first();
            if ($article) {
                return false;
            }
        } else {
            $children_channel = $this->children_channel()->first();
            if ($children_channel) {
                return false;
            }
        }
        return true;
    }


    /**
     * Format data to tree like array.
     *
     * @param array $elements
     * @param int   $parentId
     *
     * @return array
     */
    public static function toTree(array $elements = [], $parentId = 0)
    {
        $branch = [];

        if (empty($elements)) {
            $elements = static::orderByRaw('`order` = 0,`order`')->get()->toArray();
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
     * Set the order of branches in the tree.
     *
     * @param array $tree
     *
     * @return array
     */
    protected static function setBranchOrder(array &$tree)
    {
//        static::$branchOrder = array_flip(array_flatten($order));
//
//        static::$branchOrder = array_map(function ($item) {
//            return ++$item;
//        }, static::$branchOrder);
        foreach ($tree as $key => &$branch) {
            $branch['order'] = $key;
            if (isset($branch['children'])) {
               self::setBranchOrder($branch['children']);
            }
        }
    }

    /**
     * Save a tree from a tree like array.
     *
     * @param array $tree
     * @param int   $parentId
     */
    public static function saveTree($tree = [], $parentId = 0)
    {
            static::setBranchOrder($tree);

//        foreach ($tree as $branch) {
//            $node = static::find($branch['id']);
//
//            $node->parent_id = $parentId;
//            $node->order = static::$branchOrder[$branch['id']];
//            $node->save();
//
//            if (isset($branch['children'])) {
//                static::saveTree($branch['children'], $branch['id']);
//            }
//        }

        foreach ($tree as $branch) {
            if ($branch['id']) {
                $node =static::findOrFail($branch['id']);
                $node->name = $branch['name'];
                $node->order = $branch['order'];
                $node->save();
            } else {
                static::create(['name' => $branch['name'], 'order' => $branch['order'], '']);
            }
        }

    }
}
