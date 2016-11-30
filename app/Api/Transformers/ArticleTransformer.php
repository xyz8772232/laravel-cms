<?php

namespace App\Api\Transformers;
use App\Article;
use App\SortLink;
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
        return $article->toArray();
    }

}
