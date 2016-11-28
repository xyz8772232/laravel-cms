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
        $version = env('ASSET_VERSION');
        $version_str = isset($version) ? '?v='.$version : '';
        return app('url')->asset($path, $secure).$version_str;
    }
}

if (! function_exists('cms_web_to_local')) {
    function cms_web_to_local($path)
    {
        if (strpos($path, config('admin.upload.host')) === 0) {
            return str_replace(config('admin.upload.host'), config('admin.local_uri_str'), $path);
        }
        return config('admin.local_uri_str').$path;
    }
}

if (! function_exists('cms_local_to_web')) {
    function cms_local_to_web($local_path)
    {
        if (strpos($local_path, config('admin.local_uri_str')) === 0) {
            return str_replace(config('admin.local_uri_str'), config('admin.upload.host'), $local_path);
        }
        return config('admin.upload.host').$local_path;
    }
}
