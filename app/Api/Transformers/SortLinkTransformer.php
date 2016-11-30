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
        return $sortLink;
//        return [
//            'id' => $sortLink->id,
//            'article_id' => $sortLink->article->id,
//            'url' => url('articles', ['id' => $sortLink->article->id]),
//            'title' => $sortLink->article->title,
//        ];
    }

}
