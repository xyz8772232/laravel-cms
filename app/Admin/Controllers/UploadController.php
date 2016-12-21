<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Photo;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\Input;

class UploadController extends Controller
{
    public function ueditorUpload()
    {
        //\Debugbar::disable();
        $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(public_path('packages/admin/ueditor-utf8-php/php/config.json'))), true);
        $action = Input::get('action');
        switch ($action) {
            case 'config':
                $result = json_encode($config);
                break;

            /* 上传图片 */
            case 'uploadimage':
                $photo =  Input::file('upfile');
                $uid = Admin::user()->id;
                $path = app('fileUpload')->prepare($photo);
                $result = Photo::create(['admin_user_id' => $uid, 'path' => $path]);

                if ($result) {
                    $result = json_encode(array(
                        "state" => "SUCCESS",
                        "url" => $path,
                    ));
                } else {
                        header("Access-Control-Allow-Origin: *");
                        $result = json_encode(array('state' => '上传出错'));
                }
                break;

            /* 列出图片 */
            case 'listimage':
                /* 获取参数 */
                $listSize = $config['imageManagerListSize'];
                $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
                $start = Input::get('start');
                Input::merge(['page' => intval(floor($start/$size))+1]);
                $photos = Photo::orderBy('id', 'desc')->paginate($size, ['path']);
                $lists = collect($photos->toArray()['data'])->map(function($val) {
                    return ['url' => asset('upload/'.$val['path'])];
                })->all();

                /* 获取文件列表 */
                if (!$lists) {
                    $result =  json_encode([
                        "state" => "no match file",
                        "list" => array(),
                        "start" => $start,
                        "total" => 0,]
                    );
                } else {
                    $result = json_encode([
                        "state" => "SUCCESS",
                        "list" => $lists,
                        "start" => $start,
                        "total" => $photos->total(),
                    ]);
                }
                break;

            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
    public function uploadImg() {
        $url = $_GET['url'];
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            Message::showError('url不合法');
        }
        $newImg = DownloadImage::downloadNotSinaImg($url);
        if ($newImg) {
            Message::showSucc('成功', array('old' => $url, 'newPic' => $newImg));
        }
        Message::showError('失败');
    }
}
