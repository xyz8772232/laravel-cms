<?php

namespace App\Api\Transformers;
use App\SortPhoto;
use League\Fractal\TransformerAbstract;

/**
 * Class SortLinkTransformer
 *
 * @package \App\Api\Transformers
 */
class SortPhotoTransformer extends TransformerAbstract
{
    public function transform(SortPhoto $sortPhoto)
    {
        return [
            'id' => $sortPhoto->id,
            'article_id' => $sortPhoto->article->id,
            'url' => url('articles', ['id' => $sortPhoto->article->id]),
            'title' => $sortPhoto->article->title,
            'cover_pic' => $sortPhoto->article->cover_pic ? cms_local_to_web($sortPhoto->article->cover_pic) : null,
        ];
    }

}
