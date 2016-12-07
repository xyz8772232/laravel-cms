<?php

namespace App\Api\Transformers;
use App\AppPhoto;
use League\Fractal\TransformerAbstract;

/**
 * Class SortLinkTransformer
 *
 * @package \App\Api\Transformers
 */
class AppPhotoTransformer extends TransformerAbstract
{
    public function transform(AppPhoto $appPhoto)
    {
        $appPhoto->path = $appPhoto->path ? cms_local_to_web($appPhoto->path) : null;
        return $appPhoto->toArray();
    }

}
