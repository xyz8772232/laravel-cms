<?php

namespace App\Api\Transformers;
use App\Article;
use League\Fractal\TransformerAbstract;

/**
 * Class SortLinkTransformer
 *
 * @package \App\Api\Transformers
 */
class ArticleTransformer extends TransformerAbstract
{
    public function transform(Article $article)
    {
        $article->cover_pic = $article->cover_pic ? image_url($article->cover_pic) : null;
        $article->url = route('articles.show', ['id' => $article->id]);
        return $article->makeVisible('comment_num')->toArray();
    }

}
