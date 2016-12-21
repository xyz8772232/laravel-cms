<?php

namespace App\Api\Transformers;
use App\Favorite;
use League\Fractal\TransformerAbstract;

/**
 * Class FavoriteTransformer
 *
 * @package \App\Api\Transformers
 */
class FavoriteTransformer extends TransformerAbstract
{
    public function transform(Favorite $Favorite)
    {
        $Favorite->article->cover_pic = $Favorite->article->cover_pic ? cms_local_to_web($Favorite->article->cover_pic) : null;
        $Favorite->article->url = route('articles.show', ['id' => $Favorite->article->id]);
        return $Favorite->article->makeVisible('comment_num')->toArray();
    }

}
