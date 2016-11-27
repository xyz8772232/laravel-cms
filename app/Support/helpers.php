<?php

use Illuminate\Contracts\Routing\UrlGenerator;

if (! function_exists('asset_with_version')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool    $secure
     * @return string
     */
    function asset_with_version($path, $secure = null)
    {
        return app('url')->asset($path, $secure).'?version='.env('ASSET_VERSION');
    }
}

if (! function_exists('cms_local_uri')) {
    function cms_local_uri($local_path)
    {
        return config('admin.local_uri_str').$local_path;
    }
}
