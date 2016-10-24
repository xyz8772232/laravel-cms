@extends('layouts.app')

@section('content')
  <section class="content-header">
    <h1>
      header
      <small>description</small>
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
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
                      <input type="text" class="form-control" id="created_at_start" placeholder="创建时间"
                             name="created_at[start]" value="">
                      <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
                      <input type="text" class="form-control" id="created_at_end" placeholder="创建时间"
                             name="created_at[end]" value="">
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
                <th>重要<a class="fa fa-fw fa-sort" href=""></a></th>
                <th>ID<a class="fa fa-fw fa-sort fa-sort-amount-asc" href=""></a></th>
                <th>上线<a class="fa fa-fw fa-sort fa-sort-amount-desc" href=""></a></th>
                <th>标题</th>
                <th>发布者</th>
                <th>发布时间<a class="fa fa-fw fa-sort" href=""></a></th>
                <th>点击量<a class="fa fa-fw fa-sort" href=""></a></th>
                <th>评论数<a class="fa fa-fw fa-sort" href=""></a></th>
                <th>操作</th>
              </tr>
              <tr>
                <td><input type="checkbox" class="grid-item" data-id="1"></td>
                <td><i class="fa fa-star text-primary"></i></td>
                <td>1</td>
                <td>上线</td>
                <td>新闻1</td>
                <td>张三</td>
                <td>2016-10-15 16:51:44</td>
                <td>200</td>
                <td>3</td>
                <td>
                  <a href="/admin/articles/1/edit"><i class="fa fa-edit"></i></a>
                  <a href="javascript:void(0);" data-id="1" class="_delete"><i
                  class="fa fa-trash"></i></a>
                </td>
              </tr>
              <tr>
                <td><input type="checkbox" class="grid-item" data-id="1"></td>
                <td><i class="fa fa-star-o text-primary"></i></td>
                <td>2</td>
                <td>-</td>
                <td>新闻2</td>
                <td>李四</td>
                <td>2016-10-16 16:51:44</td>
                <td>1000</td>
                <td>16</td>
                <td>
                  <a href="/admin/articles/1/edit"><i class="fa fa-edit"></i></a>
                  <a href="javascript:void(0);" data-id="1" class="_delete"><i
                  class="fa fa-trash"></i></a>
                </td>
              </tr>
            </table>
          </div>
          <div class="box-footer clearfix">
            <form class="form-inline">
              <a class="btn btn-sm btn-danger batch-delete">批量删除</a>
              <a class="btn btn-sm btn-success batch-check">审核通过</a>
              <span class="pull-right">
                每页显示
                <select class="form-control input-sm show-count">
                  <option>10</option>
                  <option>20</option>
                  <option>30</option>
                  <option>40</option>
                  <option>50</option>
                </select>
                共<span class="text-primary">1000</span>篇文章
              </span>
            </form>
            <ul class="pagination pagination-sm">
              <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
              <li class="page-item active"><span class="page-link">1</span></li>
              <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            </ul>
          </div>
          <!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/article.index.css">
@endsection

@section('admin_js')
    <script data-exec-on-popstate src="/js/article.index.js"></script>
@endsection