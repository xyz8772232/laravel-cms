@extends('layouts.admin')

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
          <!-- /.box-header -->
          <!-- form start -->
          <form action="/admin/articles" method="post" accept-charset="UTF-8" class="form-horizontal"
                enctype="multipart/form-data">
            <div class="box-body">
              <div class="form-group">
                <label for="type" class="col-sm-2 control-label">图片新闻</label>
                <div class="col-sm-6">
                  <input type="checkbox" id="typeCheckbox" name="type" value="1" @if(old('type')) checked @endif/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">标题</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="输入标题">
                  </div>
                  <div class="title-font">
                    <div class="title-weight">
                      <input type="checkbox" name="title_bold" value="1" @if(old('title_bold')) checked @endif/> 粗体
                    </div>
                    <div class="title-color-label">颜色:</div>
                    <div class="title-color input-group" id="titleColor">
                      <span class="input-group-addon"><i></i></span>
                      <input class="form-control" type="text" name="title_color" value="{{old('title_color') ?: '#333333'}}" placeholder="输入标题颜色"/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="subtitle" class="col-sm-2 control-label">副标题</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                           class="form-control" placeholder="输入副标题">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="cover_pic" class="col-sm-2 control-label">封面图</label>
                <div class="col-sm-6">
                  <input type="file" id="coverPic" name="cover_pic"/>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('keywords','关键字', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::select('keywords[]',$keywords,old('keywords'),['class'=>'form-control','multiple'=>'multiple', 'data-placeholder' => '选择关键字', 'id' => 'keywords']) !!}
                </div>
              </div>
              <div class="form-group">
                <label for="published_at" class="col-sm-2 control-label">发布时间</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" id="publishedAt" name="published_at"
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
                                                    placeholder="输入内容简介">{{old('description')}}</textarea>
                </div>
              </div>
              <div class="form-group" id="normalArticle">
                <label for="content" class="col-sm-2 control-label">正文内容</label>
                <div class="col-sm-8">
                  <script id="content" name="content" type="text/plain">@if(old('type') == 0){!! old('content') !!}@endif</script>
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
                    <input type="text" id="source" name="source" value="{{old('source')}}" class="form-control"
                           placeholder="输入信息来源">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="original_url" class="col-sm-2 control-label">原始链接</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" id="orgUrl" name="original_url" value="{{old('original_url')}}" class="form-control"
                           placeholder="输入原始链接">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">文字连接</label>
                <div class="col-sm-6">
                  <input type="checkbox" name="newsLink[effective]" class="sub-form-switch" value="1"/>
                  <div class="sub-form" id="newsLinkSubForm">
                    <span class="sub-form-add e-add">+新增文字连接</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">PK</label>
                <div class="col-sm-6">
                  <input type="checkbox" name="pk[effective]" class="sub-form-switch" id="pkSFS" value="1"/>
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
                          <input class="form-control" type="text" name="pk[options][]">
                        </div>
                        <label class="control-label">选项</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          <input class="form-control" type="text" name="pk[options][]">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">投票</label>
                <div class="col-sm-6">
                  <input type="checkbox" name="vote[effective]" class="sub-form-switch" id="voteSFS" value="1"/>
                  <div class="sub-form" id="voteSubForm">
                    <div class="vote-type">
                      <input class="vote-type-radio" type="radio" name="vote[type]" value="0" checked>单选
                      <input class="vote-type-radio" type="radio" name="vote[type]" value="1">多选(最多可选<input class="vote-type-text" type="text" name="vote[limit]">票)
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
                    <span class="sub-form-add e-add">+新增选项</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">所属频道</label>
                <div class="col-sm-6 input-line-group" id="channel">
                  <select class="select-line e-channel" name="channels[1]"></select>
                  <select class="select-line e-channel" name="channels[2]"></select>
                  <select class="select-line e-channel" name="channels[3]"></select>
                  <select class="select-line e-channel" name="channels[4]"></select>
                </div>
              </div>
              @if(Admin::user()->isRole(config('admin.admin_editors')))
                <div class="form-group">
                  <label class="col-sm-2 control-label">属性</label>
                  <div class="col-sm-6 input-line-group">
                    <input class="input-line" type="checkbox" name="is_headline" value="1" @if(old('is_headline')) checked @endif/> 头条
                    <input class="input-line" type="checkbox" name="is_soft" value="1" @if(old('is_soft')) checked @endif/> 软文
                    <input class="input-line" type="checkbox" name="is_political" value="1" @if(old('is_political')) checked @endif/> 政治风险
                    <input class="input-line" type="checkbox" name="is_international" value="1" @if(old('is_international')) checked @endif/> 国际
                    <input class="input-line" type="checkbox" name="is_slide" value="1" @if(old('is_slide')) checked @endif/> 幻灯片
                    <input class="input-line" type="checkbox" name="online" value="1" @if(old('online')) checked @endif/> 上线
                  </div>
                </div>
              @endif
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
  <link rel="stylesheet" href="{{ asset("/css/article.edit.css") }}">
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
  <script src="{{ asset_with_version("/js/article.edit.js") }}"></script>
@endsection
