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
        $sortPhoto->article->cover_pic = $sortPhoto->article->cover_pic ? image_url($sortPhoto->article->cover_pic) : null;
        $sortPhoto->article->url = route('articles.show', ['id' => $sortPhoto->article->id]);
        return $sortPhoto->article->toArray();
    }

}
