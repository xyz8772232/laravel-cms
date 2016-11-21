@extends('layouts.app')

@section('content')
  <section class="content-header">
    <h1>
      {{ $header or trans('admin::lang.title') }}
      <small>{{ $description or trans('admin::lang.description') }}</small>
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">创建</h3>
            <div class="box-tools">
              <div class="btn-group pull-right">
                <a href="javascript:void(0);" class="btn btn-sm btn-warning item_delete" data-id=""><i
                  class="fa fa-trash"></i>&nbsp;删除</a>
              </div>
              <div class="btn-group pull-right" style="margin-right: 10px">
                <a href="/admin/articles" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
              </div>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form action="/admin/articles" method="post" accept-charset="UTF-8" class="form-horizontal"
                enctype="multipart/form-data">
            <div class="box-body">
              <div class="form-group">
                <label for="type" class="col-sm-2 control-label">图片新闻</label>
                <div class="col-sm-6">
                  <input type="checkbox" id="typeCheckbox"/>
                  <input type="hidden" id="typeForm" name="type" value="0">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">标题</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" name="title[text]" value="" class="form-control" placeholder="输入标题">
                  </div>
                  <div class="title-font">
                    <div class="title-weight">
                      <input type="checkbox" name="title[weight]"/> 粗体
                    </div>
                    <div class="title-color-label">颜色:</div>
                    <div class="title-color input-group" id="titleColor">
                      <span class="input-group-addon"><i></i></span>
                      <input class="form-control" type="text" name="title[color]" value="#333" placeholder="输入标题颜色"/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="subtitle" class="col-sm-2 control-label">副标题</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" name="subtitle" value=""
                           class="form-control" placeholder="输入副标题">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="coverPic" class="col-sm-2 control-label">封面图</label>
                <div class="col-sm-6">
                  <input type="file" id="coverPic" name="coverPic"/>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('keywords','关键字', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::select('keywords[]',$keywords,null,['class'=>'form-control','multiple'=>'multiple', 'data-placeholder' => '选择关键字', 'id' => 'keywords']) !!}
                </div>
              </div>
              <div class="form-group">
                <label for="publishedAt" class="col-sm-2 control-label">发布时间</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" id="publishedAt" name="publishedAt"
                           value="{{ date('Y-m-d H:i:s') }}"
                           class="form-control" placeholder="输入发布时间" style="width: 160px"/>
                    <button type="button" id="restPublishedAt" class="btn btn-default">设为当前时间</button>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="description" class="col-sm-2 control-label">内容简介</label>
                <div class="col-sm-6">
                  <textarea name="description" class="form-control" rows="3"
                                                    placeholder="输入内容简介"></textarea>
                </div>
              </div>
              <div class="form-group" id="normalArticle">
                <label for="content" class="col-sm-2 control-label">正文内容</label>
                <div class="col-sm-8">
                  <script id="content" name="content" type="text/plain">
                    初始化内容
                  </script>
                </div>
              </div>
              <div class="form-group" id="picArticle">
                <label class="col-sm-2 control-label">正文内容</label>
                <div class="col-sm-6">
                  <div id="contentPics"></div>
                </div>
              </div>
              <div class="form-group">
                <label for="source" class="col-sm-2 control-label">信息来源</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" id="source" name="source" value="" class="form-control"
                           placeholder="输入信息来源">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="orgUrl" class="col-sm-2 control-label">原始链接</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" id="orgUrl" name="originalLink" value="" class="form-control"
                           placeholder="输入原始链接">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">文字连接</label>
                <div class="col-sm-6">
                  <input type="checkbox" name="newsLink[effective]" checked class="sub-form-switch"/>
                  <div class="sub-form" id="newsLinkSubForm">
                    <div class="sub-form-add e-add">+新增文字连接</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">PK</label>
                <div class="col-sm-6">
                  <input type="checkbox" name="pk[effective]" class="sub-form-switch" id="pkSFS"/>
                  <div class="sub-form" id="pkSubForm">
                    <div class="sub-form-group clearfix">
                      <div class="sub-form-group-l">
                        <label class="control-label">标题</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          <input class="form-control" type="text" name="pk[title]">
                        </div>
                        <label class="control-label">选项</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          <input class="form-control" type="text" name="pk[option][]">
                        </div>
                        <label class="control-label">选项</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          <input class="form-control" type="text" name="pk[option][]">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">投票</label>
                <div class="col-sm-6">
                  <input type="checkbox" name="vote[effective]" class="sub-form-switch" id="voteSFS"/>
                  <div class="sub-form" id="voteSubForm">
                    <div class="vote-type">
                      <input class="vote-type-radio" type="radio" name="vote_type" value="0" checked>单选
                      <input class="vote-type-radio" type="radio" name="vote_type" value="1">多选(最多可选<input class="vote-type-text" type="text" name="vote_limit">票)
                    </div>
                    <div class="sub-form-group clearfix">
                      <div class="sub-form-group-l">
                        <label class="control-label">标题</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          <input class="form-control" type="text" name="vote[title]">
                        </div>
                      </div>
                    </div>
                    <div class="sub-form-add e-add">+新增文字连接</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">属性</label>
                <div class="col-sm-6 input-line-group">
                  <input class="input-line" type="checkbox" name=""/> 头条
                  <input class="input-line" type="checkbox" name=""/> 软文
                  <input class="input-line" type="checkbox" name=""/> 政治风险
                  <input class="input-line" type="checkbox" name=""/> 国际
                  <input class="input-line" type="checkbox" name=""/> 幻灯片
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">所属频道</label>
                <div class="col-sm-6 input-line-group" id="channel">
                  <select class="select-line e-channel" name="channel1"></select>
                  <select class="select-line e-channel" name="channel2"></select>
                  <select class="select-line e-channel" name="channel3"></select>
                  <select class="select-line e-channel" name="channel4"></select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">上线</label>
                <div class="col-sm-6 input-line-group">
                  <input class="input-line" type="checkbox" name=""/>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              {{ csrf_field() }}
              <div class="col-sm-2">
              </div>
              <div class="col-sm-6">
                <div class="btn-group pull-right">
                  <button type="submit" class="btn btn-info pull-right">提交</button>
                </div>
                <div class="btn-group pull-left">
                  <input type="reset" class="btn btn-warning" value="撤销"/>
                </div>
              </div>
            </div>
            <!-- /.box-footer -->
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/select2/select2.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap-fileinput/css/fileinput.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/dragula/dragula.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/css/article-edit.css") }}">
@endsection

@section('admin_js')
  <script src="{{ asset ("/packages/admin/AdminLTE/plugins/select2/select2.full.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/bootstrap-fileinput/js/fileinput.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/moment/min/moment-with-locales.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/bootstrap-switch/dist/js/bootstrap-switch.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/dragula/dragula.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/ueditor-utf8-php/ueditor.config.js") }}"></script>
  <script src="{{ asset("/packages/admin/ueditor-utf8-php/ueditor.all.min.js") }}"></script>
  <script>
    var CHANNEL = {!! json_encode($channels) !!};
    var PAGE_CONFIG = {

    };
  </script>
  <script src="{{ asset ("/js/article-edit.js") }}"></script>
@endsection
