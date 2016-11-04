@extends('layouts.app')

@section('content')
  <section class="content-header">
    <h1>
      {{ $header }}
      <small>{{ $description }}</small>
    </h1>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header">
        <input type="checkbox" class="img-select-all"> 全选
        <a class="img-delete btn btn-sm btn-danger">删除</a>
        <a class="img-upload btn btn-sm btn-success">上传新图片</a>
      </div>
      <div class="box-body">
        @foreach($photos as $photo)
          @if($loop->first	|| $loop->iteration % 7 == 0)
            <div class="row">
          @endif
          <div class="col-md-2">
            <div class="img-box">
              <img class="img"
                   src="{{ asset('upload/'.$photo->path) }}"
                   alt="">
              <div class="img-action">
                <input type="checkbox" data-index="{{ $loop->index }}" class="img-select input-lg">
                <button type="button" class="img-copy-url btn btn-primary btn-xs pull-right">复制地址</button>
              </div>
            </div>
          </div>
          @if($loop->last || $loop->iteration % 6 == 0)
            </div>
          @endif
        @endforeach
      </div>
      <div class="box-footer clearfix">{{ $photos->links('admin::pagination') }}</div>
    </div>
  </section>
@endsection

@section('css')
    <link data-exec-on-popstate rel="stylesheet" href="/css/photo.index.css">
    <link rel="stylesheet" href="/packages/admin/sweetalert/sweetalert.css">
@endsection

@section('admin_js')
    <script data-exec-on-popstate src="/packages/admin/sweetalert/sweetalert.min.js"></script>
    <script data-exec-on-popstate src="/js/photo.index.js"></script>
@endsection
