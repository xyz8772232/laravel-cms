<?php

namespace App\Api\Transformers;
use App\Comment;
use League\Fractal\TransformerAbstract;

/**
 * Class SortLinkTransformer
 *
 * @package \App\Api\Transformers
 */
class CommentTransformer extends TransformerAbstract
{
    public function transform(Comment $comment)
    {
        return $comment->makeVisible('reply_num')->toArray();
    }

}
