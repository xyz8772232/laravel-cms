<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public static function formatData($code, $msg, $data = array(), $otherData = array()) {
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

}
