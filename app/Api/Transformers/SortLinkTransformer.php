<?php

namespace App\Api\Transformers;
use App\SortLink;
use League\Fractal\TransformerAbstract;

/**
 * Class SortLinkTransformer
 *
 * @package \App\Api\Transformers
 */
class SortLinkTransformer extends TransformerAbstract
{
    public function transform(SortLink $sortLink)
    {
        $sortLink->article->cover_pic = $sortLink->article->cover_pic ? image_url($sortLink->article->cover_pic) : null;
        $sortLink->article->url = route('articles.show', ['id' => $sortLink->article->id]);
        return $sortLink->article->makeVisible('comment_num')->toArray();
    }

}
