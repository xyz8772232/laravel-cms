<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Exposure;

class ExposureController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('lang.app'));
            $content->description(trans('lang.exposure'));

            $content->body($this->grid());
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Exposure::class, function (Grid $grid) {

            //$grid->id('ID')->sortable();
            $grid->created_at('报料日期')->value(function ($value) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $value)->toDateString();
            });

            $grid->title('标题');

            $grid->desc('内容');

//            $grid->desc('内容')->value(function($text) {
//                return '<p style="width: 200px">'.$text.'</p>';
//            });
            $grid->column('报料人')->value(function () {
                return '<span>姓名: ' . $this->uname . '</span><br><span>联系方式: ' . $this->contact .
                '</span><br><span>微信: ' . $this->wechat . '</span>';
            });

            $grid->pics('报料图片')->value(function ($pics) {
                $result = '';
                if (!empty($pics)) {
                    $pics = json_decode($pics, true);
                    foreach ($pics as $pic) {
                        $src = cms_local_to_web($pic);
                        $result .= "<img src='$src' style='max-width:100px;max-height:100px' class='img img-thumbnail' /><br>";
                    }
                }
                return $result;
            });


            $grid->rows(function ($row) {
                $row->actions('delete');
            });

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->between('created_at', '报料日期')->datetime('YYYY-MM-DD');
            });

            $grid->model()->orderBy('id', 'desc');
            $grid->disablePerPageSelector();
            if ($created_at = app('request')->get('created_at')) {
                $created_at['start'] =  $created_at['start'] ? $created_at['start'].' 00:00:00' : '';
                $created_at['end'] = $created_at['end'] ?  $created_at['end'].' 23:59:00' : '';
                app('request')->merge(['created_at'=> $created_at]);
            }
            app('request')->merge(['per_page'=> 5]);
            $grid->disableCreation();
            $grid->disableExport();
        });
    }

    public function destroy($id)
    {
        if (Exposure::destroy($id)) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin::lang.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin::lang.delete_failed'),
            ]);
        }
    }
}
