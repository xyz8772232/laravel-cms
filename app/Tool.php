<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Tool extends Model
{
    public static function resource()
    {
        $route = app('router')->current();
        $prefix = $route->getPrefix();

        $resource = trim(preg_replace("#$prefix#", '', $route->getUri(), 1), '/').'/';

        return "/$prefix/".substr($resource, 0, strpos($resource, '/'));
    }

    public static function showSuccess($msg = '成功', $data=array())
    {
        $result = self::formatData(0, $msg, $data);
        return response()->json($result);

    }

    public static function showError($msg = '失败', $data=array(),$otherData = array(), $code=11)
    {
        $result = self::formatData($code, $msg, $data, $otherData);
        return response()->json($result);

    }

    public static function formatData($code, $msg, $data = array(), $otherData = array())
    {
        $code = intval($code);
        $outArr = array();
        if (!is_array($msg)) {
            $outArr['result']['status']['code'] = $code;
            $outArr['result']['status']['msg'] = $msg;
            $outArr['result']['timestamp'] = date('D M d H:i:s O Y');
            if (is_array($otherData)) {
                foreach ($otherData as $k=>$v) {
                    if (!in_array($k, array('status', 'data'), true)) {
                        $outArr['result'][$k] = $v;
                    }
                }
            }
            $outArr['result']['data'] = $data;
        } else {
            $outArr = $msg;
        }
        return $outArr;
    }

    /**
     * 通过前端传入的数组返回最后一层有效的channelId
     * @param $channels
     * @return int
     */
    public static function getChannelId($channels)
    {
        $channels = array_filter($channels);
        return (int)array_pop($channels);
    }

    public static function tableHeader($header) {
        if ($header['name'] == 'title') {
            $columnStyle = 'style="width:400px;"';
        } else {
            $columnStyle = '';
        }
        if (!$header['sortable']) {
            return "<th $columnStyle>{$header['label']}</th>";
        }
        $icon = 'fa-sort';
        $type = 'desc';
        if (self::isSorted($header['name'])) {
            $currentType = app('request')->get('_sort')['type'];
            $type = $currentType == 'desc' ? 'asc' : 'desc';
            $icon .= "-amount-{$currentType}";
        }
        $query = app('request')->all();
        $query = array_merge($query, ['_sort' => ['column' => $header['name'], 'type' => $type]]);
        $url = Url::current().'?'.http_build_query($query);
        return "<th $columnStyle>{$header['label']}<a class=\"fa fa-fw $icon\" href=\"$url\"></a></th>";
    }

    public static function isSorted($name)
    {
        $sort = app('request')->get('_sort');

        if (empty($sort)) {
            return false;
        }

        return isset($sort['column']) && $sort['column'] == $name;
    }

    /**
     * 处理热点区
     * @param \App\Article $article
     * @param string       $action
     *
     * @return bool
     */
    public static function handleSortLink(Article $article, $action = 'add')
    {
        switch ($action) {
            case 'add':
                if ($article->is_headline) {
                    return false;
                }
                $existedNum = SortLink::count();
                if ($existedNum >= config('article.sortPhotoMaxNum')) {
                    $oldestLink = SortLink::orderBy('created_at')->first();
                    $oldestLink->delete();
                }
                return $article->sortLink()->create([]);
                break;
            case 'delete':
                if (!$article->is_headline) {
                    return false;
                }
                return $article->sortLink()->first()->delete();
                break;
            default:
                return false;
                break;
        }
    }

    public static function handleSortPhoto(Article $article, $action = 'add')
    {
        switch ($action) {
            case 'add':
                if ($article->is_slide) {
                    return false;
                }
                $existedNum = SortPhoto::count();
                if ($existedNum >= config('article.sortPhotoMaxNum')) {
                    $oldestSlide = SortPhoto::orderBy('created_at')->first();
                    $oldestSlide->delete();
                }
                return $article->sortPhoto()->create([]);
                break;
            case 'delete':
                if (!$article->is_slide) {
                    return false;
                }
                return $article->sortPhoto()->first()->delete();
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * menu是否active
     * @param       $channelActive
     * @param       $channelSidebar
     * @param int   $id
     * @param array $tree
     *
     * @return bool
     */
    public static function isActive($channelActive, $channelSidebar, $id = 0, $tree = [])
    {
        if (($channelActive === $channelSidebar) && (empty($id) && empty($tree) || in_array($id, $tree))) {
            return true;
        }
        return false;
    }
}
