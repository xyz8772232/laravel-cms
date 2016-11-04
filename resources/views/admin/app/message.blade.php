@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ $header }}
            <small>{{ $description }}</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- form start -->
                <form action="/admin/app_messages" method="post" accept-charset="UTF-8" class="form-horizontal"
                      enctype="multipart/form-data" pjax-container>
                    <div class="box-body">
                        <div class="form-group 1">
                            <label for="content" class="col-sm-2 control-label">自定义推送内容</label>
                            <div class="col-sm-6">
                                <textarea id="content" name="content" class="form-control" rows="3"
                                          placeholder="输入 内容">
                                </textarea>
                            </div>
                        </div>

                        <div class="form-group 1">
                            <label for="article_id" class="col-sm-2 control-label">文章ID</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" id="article_id" name="article_id" value="" class="form-control"
                                           placeholder="输入 文章ID">
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        {{ csrf_field() }}
                        <div class="col-sm-2"></div>
                        <div class="col-sm-6">
                            <div class="btn-group pull-right">
                                <button type="submit" class="btn btn-info pull-right">确定并推送</button>
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
    </section>
@endsection
