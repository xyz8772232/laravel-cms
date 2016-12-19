<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SortLink extends Model
{

    protected $fillable = ['article_id'];

    protected static $branchOrder = [];

    public function article()
    {
        return $this->belongsTo('\App\Article');
    }

    /**
     * @return $this
     */
    public static function online()
    {
        $links = static::whereHas('article', function($query) {$query->where('state', 2);})->with('article')->orderByRaw('`order` = 0,`order`')->orderBy('created_at');
        return $links;
    }

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
     */
    public static function saveTree(array $tree = [])
    {
        static::setBranchOrder($tree);

        foreach ($tree as $branch) {
            $node = static::find($branch);
            $node->order = static::$branchOrder[$branch];
            $node->save();
        }
    }
}
