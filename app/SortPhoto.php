<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SortPhoto extends Model
{
    use SoftDeletes;

    protected $fillable = ['article_id', 'deleted_at'];

    protected static $branchOrder = [];

    public function article()
    {
        return $this->belongsTo('\App\Article');
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
