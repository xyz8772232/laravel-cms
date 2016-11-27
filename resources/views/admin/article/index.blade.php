@extends('layouts.admin')

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
        <div class="box">
          <div class="box-header">
          <form>
            <a href="{{ route('articles.create', ['channel_id' => $filterValues['channel_id']]) }}" class="btn btn-sm btn-primary link-create-news">发布新闻</a>
            <div class="form-inline pull-right">
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><strong>频道</strong></span>
                <select class="select-line" data-placeholder="选择关键字" name="channel_id" id="channelId">
                  @foreach( $options as $key => $option)
                  <option value="{{ $key }}" @if($filterValues['channel_id'] == $key) selected @endif>{{ $option }}</option>
                  @endforeach
                </select>
              </div>
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><strong>Id</strong></span>
                <input type="text" class="form-control" name="id" value="{{ $filterValues['id'] ?? null }}">
              </div>
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><strong>标题</strong></span>
                <input type="text" class="form-control" name="title" value="{{ $filterValues['title'] ?? null }}">
              </div>
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><strong>创建时间</strong></span>
                <input type="text" class="form-control" id="createdAtStart"
                       name="created_at[start]" value="{{ $filterValues['created_at']['start'] ?? null }}">
                <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
                <input type="text" class="form-control" id="createdAtEnd"
                       name="created_at[end]" value="{{ $filterValues['created_at']['end'] ?? null }}">
              </div>
              <div class="input-group input-group-sm">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </form>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="news-list table table-hover">
              <tr>
                <th><input type="checkbox" id="selectAll"></th>
                @foreach($tableHeaders as $val)
                  {!! \App\Tool::tableHeader($val) !!}
                @endforeach
                <th>编辑</th>
              </tr>
                @foreach($articles as $article)
                  <tr >
                    <td><input type="checkbox" class="grid-item e-select" data-id="{{ $article->id }}"></td>
                    <td><i class="fa @if($article->is_important) fa-star @else fa-star-o @endif text-danger"></i></td>
                    <td>{{ $article->id }}</td>
                    <td>@if($article->online)<i class="fa fa-check" style="color:red"></i>@elseif($article->state==0) <span class="badge bg-green">未提交</span> @else <span class="badge bg-red">待审核</span> @endif</td>
                    <td class="news-title">@if($article->link_id)<i class="fa fa-link text-danger"></i> @endif @if($article->is_headline)<span class="news-sign text-danger">[头]</span> @endif @if($article->type == 1) <i class="fa fa-file-image-o text-danger"></i> @endif <a href="{{ route('articles.preview', ['id' => $article->id]) }}">{{ $article->title }}</a></td>
                    <td>{{ $article->author_name }}</td>
                    <td>{{ $article->created_at }}</td>
                    <td>{{ $article->view_num }}</td>
                    <td>{{ $article->comment_num }}</td>
                    <td>
                      {{--
                      <a href='/url/1'><i class='fa fa-eye'></i></a> <a href='/url/1'><i class='fa fa-gear'></i>
                      <a href="{{route('articles.show', [$article->id])}}"><i class='fa fa-eye'></i></a>
                      <a href="javascript:void(0);" data-id="{{ $article->id }}" class="_delete"><i class="fa fa-trash"></i></a>
                      --}}
                      @unless ($article->link_id)
                      <a href="{{route('articles.edit', [$article->id])}}"><i class="fa fa-edit"></i></a>
                      <i class="fa fa-link e-link" data-id="{{ $article->id }}"></i>
                      @endunless
                    </td>
                  </tr>
                @endforeach
            </table>
          </div>
          <div class="box-footer clearfix">
            <div class="actions" id="batchActions">
              <span class="btn btn-sm btn-danger e-delete">删除</span>
              <span class="btn btn-sm btn-success e-publish">提交审核</span>
              <span class="btn btn-sm btn-default e-top">设置头条</span>
              <span class="btn btn-sm btn-default e-transfer">转移</span>
              <span class="pull-right">
                共<span class="text-primary">{{ $articles->total() }}</span>篇文章
              </span>
            </div>
              {{ $articles->appends(['sort' => 'votes'])->links('admin::pagination') }}
          </div>
          <!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/sweetalert/sweetalert.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/select2/select2.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/css/article.index.css") }}">
@endsection

@section('admin_js')
  <script src="{{ asset ("/packages/admin/sweetalert/sweetalert.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/moment/min/moment-with-locales.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/AdminLTE/plugins/select2/select2.full.min.js") }}"></script>
  <script>
    var CHANNEL = {!! json_encode($channels) !!};
  </script>
  <script src="{{ asset ("/js/article.index.js") }}"></script>
@endsection
