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
                    {!! Form::model($article, ['url'=> route('articles.update', [$article->id]), 'class'=> 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            {!! Form::hidden('id',$article->id) !!}

                            <div class="form-group 1">

                                <label for="type" class="col-sm-2 control-label">图片新闻</label>

                                <div class="col-sm-6">


                                    <input type="checkbox" id="type_checkbox"/>
                                    <input type="hidden" id="type" name="type" class="" value="off">

                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="title" class="col-sm-2 control-label">标题</label>

                                <div class="col-sm-6">


                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        {!! Form::text('title', null,['class'=>'form-control', 'id' => 'title', 'placeholder' => '输入 标题']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="title_color" class="col-sm-2 control-label">标题颜色</label>

                                <div class="col-sm-6">


                                    <div class="input-group title_color" id="title_color">
                                        <span class="input-group-addon"><i></i></span>
                                        <input type="text" name="title_color" value="#ccc" class="form-control"
                                               placeholder="输入 标题颜色" style="width: 100px"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="title_font" class="col-sm-2 control-label">标题粗体</label>

                                <div class="col-sm-6">


                                    <input type="checkbox" id="title_font_checkbox"/>
                                    <input type="hidden" id="title_font" name="title_font" class="" value="off">

                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="subtitle" class="col-sm-2 control-label">副标题</label>

                                <div class="col-sm-6">


                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="subtitle" name="subtitle" value=""
                                               class="form-control" placeholder="输入 副标题">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="author" class="col-sm-2 control-label">作者</label>

                                <div class="col-sm-6">
                                    <div class="box box-solid box-info no-margin">
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            {{ $article->author->name }}
                                        </div><!-- /.box-body -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="author" class="col-sm-2 control-label">浏览数</label>

                                <div class="col-sm-6">
                                    <div class="box box-solid box-info no-margin">
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            {{ $article->view_num }}
                                        </div><!-- /.box-body -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="author" class="col-sm-2 control-label">评论数</label>

                                <div class="col-sm-6">
                                    <div class="box box-solid box-info no-margin">
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            {{ $article->comment_num }}
                                        </div><!-- /.box-body -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="cover_pic" class="col-sm-2 control-label">封面图</label>

                                <div class="col-sm-6">

                                    <input type="file" id="cover_pic" name="cover_pic"/>
                                    <input type="hidden" id="cover_pic_action" name="cover_pic_action" value="0"/>
                                </div>
                            </div>
                            <div class="form-group 1">

                                {!! Form::label('keywords','关键字', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::select('keywords[]',$keywords,null,['class'=>'form-control','multiple'=>'multiple', 'data-placeholder' => '选择关键字', 'id' => 'keywords']) !!}
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="created_at" class="col-sm-2 control-label">创建时间</label>

                                <div class="col-sm-6">


                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" id="created_at" name="created_at"
                                               value="{{ $article->created_at }}"
                                               class="form-control" placeholder="输入 创建时间" style="width: 160px"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="description" class="col-sm-2 control-label">内容简介</label>

                                <div class="col-sm-6">


                                        <textarea id="description" name="description" class="form-control" rows="3"
                                                  placeholder="输入 内容简介"></textarea>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="content" class="col-sm-2 control-label">正文内容</label>

                                <div class="col-sm-6">


                                        <textarea class="form-control" id="content" name="content"
                                                  placeholder="输入 正文内容">{{ $article->content->content }}</textarea>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="source" class="col-sm-2 control-label">信息来源</label>

                                <div class="col-sm-6">


                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="source" name="source" value="" class="form-control"
                                               placeholder="输入 信息来源">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group 1">

                                <label for="is_top" class="col-sm-2 control-label">头条</label>

                                <div class="col-sm-6">


                                    <input type="checkbox" id="is_top_checkbox"/>
                                    <input type="hidden" id="is_top" name="is_top" class="" value="off">

                                </div>
                            </div>
                            <input type="hidden" name="_method" value="PUT">

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
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
                    {!! Form::close() !!}
                </div>

            </div>
        </div>

    </section>
@endsection

@section('admin_js')
    <script data-exec-on-popstate>

        $(function () {
            $('.item_delete').click(function () {
                var id = $(this).data('id');
                if (confirm('确认删除?')) {
                    $.post('/admin/articles/' + id, {
                        _method: 'delete',
                        '_token': 'cBhuCiUomMmrIvqNXzYrKnKQSUY6J06uYBAk0lkk'
                    }, function (data) {
                        $.pjax({
                            timeout: 2000,
                            url: '/admin/articles',
                            container: '#pjax-container'
                        });
                        return false;
                    });
                }
            });

            $('#type_checkbox').bootstrapSwitch({
                size: 'small',
                onSwitchChange: function (event, state) {
                    $('#type').val(state ? 'on' : 'off');
                }
            });

            $('#title_color').colorpicker();

            $('#title_font_checkbox').bootstrapSwitch({
                size: 'small',
                onSwitchChange: function (event, state) {
                    $('#title_font').val(state ? 'on' : 'off');
                }
            });


            $("#cover_pic").fileinput({
                "overwriteInitial": true,
                "showUpload": false,
                "language": "zh_CN",
                "allowedFileTypes": ["image"],
                "initialCaption": ""
            });

            $("#cover_pic").on('filecleared', function (event) {
                $("#cover_pic_action").val(1);
            });

            $("#keywords").select2({allowClear: true});
            $('#created_at').datetimepicker({"format": "YYYY-MM-DD HH:mm:ss", "locale": "zh_CN"});
            CKEDITOR.replace('content');

            $('#is_top_checkbox').bootstrapSwitch({
                size: 'small',
                onSwitchChange: function (event, state) {
                    $('#is_top').val(state ? 'on' : 'off');
                }
            });
        });
    </script>
@endsection