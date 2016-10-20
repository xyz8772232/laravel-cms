@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            header
            <small>description</small>
        </h1>

    </section>

    <section class="content">

        <div class="row"><div class="col-md-12"><div class="box">
                    <div class="box-header">
                        <h3 class="box-title"></h3>

                        <div class="box-tools">
                            <div class="form-inline pull-right">
                                <form action="" method="get">
                                    <fieldset>

                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon"><strong>Id</strong></span>
                                            <input type="text" class="form-control" placeholder="Id" name="id" value=""></div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon"><strong>标题</strong></span>
                                            <input type="text" class="form-control" placeholder="标题" name="title" value=""></div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon"><strong>创建时间</strong></span>
                                            <input type="text" class="form-control" id="created_at_start" placeholder="创建时间" name="created_at[start]" value="">
                                            <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
                                            <input type="text" class="form-control" id="created_at_end" placeholder="创建时间" name="created_at[end]" value="">
                                        </div>

                                        <div class="input-group input-group-sm">
                                            <div class="input-group-btn">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                            </div>
                            <div class="btn-group pull-right" style="margin-right: 10px">
                                <a href="/admin/articles/create" class="btn btn-sm btn-success">新增</a>

                            </div>

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th><input type="checkbox" class="grid-select-all"></th>
                                <th>ID<a class="fa fa-fw fa-sort" href="http://cms.app/admin/articles?_sort%5Bcolumn%5D=id&_sort%5Btype%5D=desc"></a></th>
                                <th>标题</th>
                                <th>作者</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            @foreach($articles as $article)
                            <tr >
                                <td><input type="checkbox" class="grid-item" data-id="{{ $article->id }}"></td>
                                <td>{{ $article->id }}</td>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->author_id }}</td>
                                <td>{{ $article->created_at }}</td>
                                <td>
                                    <a href="{{route('articles.edit', [$article->id])}}"><i class="fa fa-edit"></i></a>  <a href="javascript:void(0);" data-id="{{ $article->id }}" class="_delete"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                                @endforeach
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <a class="btn btn-sm btn-danger batch-delete">批量删除</a>

                        <a class="btn btn-sm btn-primary grid-refresh"><i class="fa fa-refresh"></i></a>

                        {{ $articles->appends(['sort' => 'votes'])->links('admin::pagination') }}

                    </div>
                    <!-- /.box-body -->
                </div></div></div>

    </section>
@endsection

@section('js')
    <script data-exec-on-popstate>

        $(function () {
            $('#created_at_start').datetimepicker({"format":"YYYY-MM-DD HH:mm:ss","locale":"zh_CN"});
            $('#created_at_end').datetimepicker({"format":"YYYY-MM-DD HH:mm:ss","locale":"zh_CN","useCurrent":false});
            $("#created_at_start").on("dp.change", function (e) {
                $('#created_at_end').data("DateTimePicker").minDate(e.date);
            });
            $("#created_at_end").on("dp.change", function (e) {
                $('#created_at_start').data("DateTimePicker").maxDate(e.date);
            });
            $('._delete').click(function() {
                var id = $(this).data('id');
                if(confirm("确认删除?")) {
                    $.post('/admin/articles/' + id, {_method:'delete','_token':'Jr5SNyPLbN90mspGD0X042QqwVNVi787k08pqcS8'}, function(data){
                        $.pjax.reload('#pjax-container');
                    });
                }
            });

            $('.grid-select-all').change(function() {
                if (this.checked) {
                    $('.grid-item').prop("checked", true);
                } else {
                    $('.grid-item').prop("checked", false);
                }
            });

            $('.batch-delete').on('click', function() {
                var selected = [];
                $('.grid-item:checked').each(function(){
                    selected.push($(this).data('id'));
                });

                if (selected.length == 0) {
                    return;
                }

                if(confirm("确认删除?")) {
                    $.post('/admin/articles/' + selected.join(), {_method:'delete','_token':'Jr5SNyPLbN90mspGD0X042QqwVNVi787k08pqcS8'}, function(data){
                        $.pjax.reload('#pjax-container');
                    });
                }
            });

            $('.grid-refresh').on('click', function() {
                $.pjax.reload('#pjax-container');
            });


        });
    </script>
@endsection