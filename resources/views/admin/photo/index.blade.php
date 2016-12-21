@extends('layouts.admin')

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
        <a class="e-delete btn btn-sm btn-danger">删除</a>
        <a class="e-upload btn btn-sm btn-success">上传新图片</a>
        <span class="instructions">支持类似windows资源管理器的操作方式，单选：鼠标左键、多选：ctrl+鼠标左键、连选：shift+鼠标左键</span>
      </div>
      <div class="box-body">
        <div class="photo-box" id="photoBox">
        @foreach($photos as $photo)
          <div class="photo e-select" data-id="{{ $photo->id }}" data-index="{{ $loop->index }}">
            <img class="pic" src="{{ image_url($photo->path) }}" alt="">
            <div class="footer">
              <i class="fa fa-check-circle icon-check"></i>
              <span class="e-copy" data-clipboard-text="{{ image_url($photo->path) }}">复制地址</span>
            </div>
          </div>
        @endforeach
        </div>
      </div>
      <div class="box-footer clearfix">{{ $photos->links('admin::pagination') }}</div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap-fileinput/css/fileinput.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/sweetalert/sweetalert.css") }}">
  <link rel="stylesheet" href="{{ asset("/css/photo.index.css") }}">
@endsection

@section('admin_js')
  <script src="{{ asset("/packages/admin/bootstrap-fileinput/js/fileinput.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/sweetalert/sweetalert.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/clipboard/clipboard.min.js") }}"></script>
  <script src="{{ asset_with_version("/js/photo.index.js") }}"></script>
@endsection
