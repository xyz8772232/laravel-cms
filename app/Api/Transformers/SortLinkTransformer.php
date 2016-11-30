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
        $sortLink->article->cover_pic = $sortLink->article->cover_pic ? cms_local_to_web($sortLink->article->cover_pic) : null;
        $sortLink->article->url = route('articles.show', ['id' => $sortLink->article->id]);
        return $sortLink->article->toArray();
    }

}
