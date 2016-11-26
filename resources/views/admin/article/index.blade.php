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
        <div class="box">
          <div class="box-header">
          <form>
            <a href="{{ route('articles.create') }}" class="btn btn-sm btn-primary link-create-news">发表文章</a>
            <label class="label-type">频道</label>
            <select class="btn-choose-type" data-placeholder="选择关键字" id="channel_id" name="channel_id">
              @foreach( $options as $key => $option)
              <option value="{{ $key }}" @if(isset($filterValues['channel_id']) && ($key == $filterValues['channel_id']))selected @endif>{{ $option }}</option>
              @endforeach
            </select>
            <fieldset>
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><strong>Id</strong></span>
                <input type="text" class="form-control" name="id" value="{{ $filterValues['id'] ?? null }}"></div>
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><strong>标题</strong></span>
                <input type="text" class="form-control" name="title" value="{{ $filterValues['title'] ?? null }}"></div>
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><strong>创建时间</strong></span>
                <input type="text" class="form-control" id="created_at_start"
                       name="created_at[start]" value="{{ $filterValues['create_at[start]'] ?? null }}">
                <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
                <input type="text" class="form-control" id="created_at_end"
                       name="created_at[end]" value="{{ $filterValues['create_at[end]'] ?? null }}">
              </div>
              <div class="input-group input-group-sm">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </fieldset>
          </form>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="news-list table table-hover">
              <tr>
                <th><input type="checkbox" class="grid-select-all"></th>
                @foreach($tableHeaders as $val)
                  {!! \App\Tool::tableHeader($val) !!}
                @endforeach
                <th>编辑</th>
              </tr>
                @foreach($articles as $article)
                  <tr >
                    <td><input type="checkbox" class="grid-item" data-id="{{ $article->id }}"></td>
                    <td><i class="fa @if($article->is_important) fa-star @else fa-star-o @endif text-danger"></i></td>
                    <td>{{ $article->id }}</td>
                    <td><i @if($article->state == 0) class="fa fa-close" style="color:green" @else class="fa fa-check" style="color:red" @endif></i></td>
                    <td class="news-title">@if($article->link_id)<i class="fa fa-link"></i> @endif @if($article->is_headline)<span class="news-sign">[头]</span> @endif @if($article->type == 1) <i class="fa fa-file-image-o"></i> @endif <a href="{{ route('articles.preview', ['id' => $article->id]) }}">{{ $article->title }}</a></td>
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
                      @endunless
                    </td>
                  </tr>
                @endforeach
            </table>
          </div>
          <div class="box-footer clearfix">
            <form class="form-news">
              <span class="btn btn-sm btn-danger batch-delete">删除</span>
              <span class="btn btn-sm btn-success batch-check">上线</span>
              <span class="btn btn-sm btn-default">设置头条</span>
              <span class="btn btn-sm btn-default">转移</span>
              <span class="btn btn-sm btn-default">创建文字连接</span>
              <span class="pull-right">
                共<span class="text-primary">{{ $articles->total() }}</span>篇文章
              </span>
            </form>
              {{ $articles->appends(['sort' => 'votes'])->links('admin::pagination') }}
          </div>
          <!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/select2/select2.min.css") }}">
  <style>
    .link-create-news{
      display: inline-block;
      margin-right: 25px;
    }
    .btn-choose-type{
      display: inline-block;
      margin-left: 10px;
      width: 100px;
      height: 30px;
    }
    .label-type{
      margin-bottom: 0;
      vertical-align: middle;
      color: #666;
    }
    .news-list,
    .news-list th{
      text-align: center;
    }
    .news-list .news-title{
      text-align: left;
      min-width: 300px;
    }
    .news-list .news-sign{
      font-weight: bold;
      color: #337ab7;
    }
    .news-list .fa-file-image-o{
      margin: 4px 3px 0;
    }
    .form-news{
      line-height: 30px;
      margin-bottom: 5px;
    }
    .box-footer .btn{
      margin-right: 3px;
    }
    .show-count{width: 50px!important; margin: 0 5px;}
  </style>
@endsection

@section('admin_js')
  <script src="{{ asset ("/packages/admin/AdminLTE/plugins/select2/select2.full.min.js") }}"></script>
  <script>
    $(function () {
       $("#channel_id").select2({
        minimumResultsForSearch: Infinity,
        allowClear: true
       });
    });
  </script>
@endsection
