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
          {!! Form::model($article, ['url'=> route('articles.update', [$article->id]), 'class'=> 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
          {!! Form::hidden('id',$article->id) !!}
          {!! Form::hidden('_method','PUT') !!}
          <div class="box-body">
              <div class="form-group">
                {!! Form::label('type', '图片新闻', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::checkbox('type', 1, (bool)$article->type, ['id' => 'typeCheckbox', 'readonly' => 'readonly']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('title', '标题', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    {!! Form::text('title', $article->title, ['class' => 'form-control', 'placeholder' => '输入标题'] ) !!}
                  </div>
                  <div class="title-font">
                    <div class="title-weight">
                      {!! Form::checkbox('title_bold', 1, (bool)$article->title_bold) !!} 粗体
                    </div>
                    <div class="title-color-label">颜色:</div>
                    <div class="title-color input-group" id="titleColor">
                      <span class="input-group-addon"><i></i></span>
                      {!! Form::text('title_color', $article->title_color, ['class' => 'form-control', 'placeholder' => '输入标题颜色'] ) !!}
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('subtitle', '副标题', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    {!! Form::text('subtitle', $article->subtitle, ['class' => 'form-control', 'placeholder' => '输入副标题']) !!}
                  </div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('cover_pic', '封面图', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::file('cover_pic', ['id' => 'coverPic']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('keywords','关键字', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::select('keywords[]', $keywords, null,['class'=>'form-control','multiple'=>'multiple', 'data-placeholder' => '选择关键字', 'id' => 'keywords']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('published_at','发布时间', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!! Form::text('published_at', null, ['id' => 'publishedAt', 'class' => 'form-control', 'placeholder' => '输入发布时间', 'style'=>'width: 160px']) !!}
                    {!! Form::button('设为当前时间', ['id' => 'restPublishedAt', 'class' => 'btn btn-default']) !!}
                  </div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('description','内容简介', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => '输入内容简介' ]) !!}
                </div>
              </div>
              <div class="form-group" id="normalArticle">
                {!! Form::label('content','正文内容', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                  <script id="content" name="content" type="text/plain">@if($article->type == 0){!! $article->content !!}@endif</script>
                </div>
              </div>
              <div class="form-group" id="picArticle">
                {!! Form::label('content', '正文内容', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  <div id="contentPics"></div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('source', '信息来源', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    {!! Form::text('source', null, ['id' => 'source', 'class' => 'form-control', 'placeholder' => '输入信息来源']) !!}
                  </div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('original_url', '原始链接', ['class' => 'col-sm-2 control-label ']) !!}
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-internet-explorer"></i></span>
                    {!! Form::text('original_url', null, ['id' => 'orgUrl', 'class' => 'form-control', 'placeholder' => '输入原始链接']) !!}
                  </div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('newsLink', '文字连接', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::checkbox('newsLink[effective]', 1, (bool)$article->title_bold, ['class' => 'sub-form-switch']) !!}
                  <div class="sub-form" id="newsLinkSubForm">
                    <div class="sub-form-add e-add">+新增文字连接</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('pk', 'PK', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::checkbox('pk[effective]', 1, isset($ballot->type) && $ballot->type == 2, ['id' => 'pkSFS', 'class' => 'sub-form-switch']) !!}
                  @if(isset($ballot->type) && $ballot->type == 2)
                    {!! Form::hidden('pk[id]', $ballot->id) !!}
                  @endif
                  <div class="sub-form" id="pkSubForm">
                    <div class="sub-form-group clearfix">
                      <div class="sub-form-group-l">
                        <label class="control-label">标题</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            {!! Form::text('pk[title]', isset($ballot->type) && $ballot->type == 2 ? $ballot->title : null, ['class'=> 'form-control']) !!}
                        </div>
                        <label class="control-label">选项</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          {!! Form::text('pk[options][]', isset($ballot->type) && $ballot->type == 2 ? $ballot->choices->first()->content : null, ['class'=> 'form-control']) !!}
                        </div>
                        <label class="control-label">选项</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          {!! Form::text('pk[options][]', isset($ballot->type) && $ballot->type == 2 ? $ballot->choices->last()->content : null, ['class'=> 'form-control']) !!}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('vote', '投票', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                  {!! Form::checkbox('vote[effective]', 1, $ballot->vote ?? null, ['id' => 'voteSFS', 'class' => 'sub-form-switch']) !!}
                  @if($ballot->vote ?? false)
                    {!! Form::hidden('vote[id]', $ballot->id) !!}
                  @endif
                  <div class="sub-form" id="voteSubForm">
                    <div class="vote-type">
                      {!! Form::radio('vote[type]', 0, $ballot->single_vote ?? false, ['class' => 'vote-type-radio']) !!}单选
                      {!! Form::radio('vote[type]', 0, $ballot->multi_vote ?? false, ['class' => 'vote-type-radio']) !!}多选
                      (最多可选{!! Form::text('vote[limit]', isset($ballot->multi_vote) && $ballot->multi_vote ? $ballot->max_num : null, ['class' => 'vote-type-text']) !!}票)
                    </div>
                    <div class="sub-form-group clearfix">
                      <div class="sub-form-group-l">
                        <label class="control-label">标题</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          {!! Form::text('vote[title]', isset($ballot->vote) && $ballot->vote ? $ballot->title : null, ['class' => 'form-control']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="sub-form-add e-add">+新增选项</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label(null, '属性', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6 input-line-group">
                  {!! Form::checkbox('is_headline', 1, null, ['class' => 'input-line']) !!} 头条
                  {!! Form::checkbox('is_soft', 1, null, ['class' => 'input-line']) !!} 软文
                  {!! Form::checkbox('is_political', 1, null, ['class' => 'input-line']) !!} 政治风险
                  {!! Form::checkbox('is_international', 1, null, ['class' => 'input-line']) !!} 国际
                  {!! Form::checkbox('is_slide', 1, (bool)$slide, ['class' => 'input-line']) !!} 幻灯片
                </div>
              </div>
              <div class="form-group">
                {!! Form::label(null, '所属频道', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6 input-line-group" id="channel">
                  {!! Form::select('channels[1]', [], null, ['class' => 'select-line e-channel']) !!}
                  {!! Form::select('channels[2]', [], null, ['class' => 'select-line e-channel']) !!}
                  {!! Form::select('channels[3]', [], null, ['class' => 'select-line e-channel']) !!}
                  {!! Form::select('channels[4]', [], null, ['class' => 'select-line e-channel']) !!}
                </div>
              </div>
              @if(Admin::user()->can('article-online'))
                <div class="form-group">
                  {!! Form::label(null, '上线', ['class' => 'col-sm-2 control-label']) !!}
                  <div class="col-sm-6 input-line-group">
                    {!! Form::checkbox('state', 1, null, ['class' => 'input-line']) !!} 上线
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
                  {!! Form::button('修改文章', ['type' => 'submit', 'class' => 'btn btn-info pull-right']) !!}
                </div>
                <div class="btn-group pull-left">
                  {!! Form::reset('撤销', ['class' => 'btn btn-warning']) !!}
                </div>
              </div>
            </div>
            <!-- /.box-footer -->
          {!! Form::close() !!}
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
    var INIT_CONFIG = {!! json_encode($initConfig) !!};
  </script>
  <script src="{{ asset ("/js/article-edit.js") }}"></script>
@endsection
