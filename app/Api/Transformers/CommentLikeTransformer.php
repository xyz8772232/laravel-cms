<?php

namespace App\Api\Transformers;
use App\CommentLike;
use League\Fractal\TransformerAbstract;

/**
 * Class CommentLikeTransformer
 *
 * @package \App\Api\Transformers
 */
class CommentLikeTransformer extends TransformerAbstract
{
    public function transform(CommentLike $commentLike)
    {
        return $commentLike->toArray();
    }

}
