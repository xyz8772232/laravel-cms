@extends('layouts.wap')

@section('css')
    <link rel="stylesheet" href="{{ asset("/wap/css/reset.css") }}">
    <link rel="stylesheet" href="{{ asset("/wap/css/comments.css") }}">
    <link rel="stylesheet" href="{{ asset("/wap/css/comments.detail.css") }}">
@endsection

@section('title')
    @parent
@endsection

@section('description')
    <meta name="description" content="">
@endsection

@section('js')
  <script>
    var PAGE_CONFIG = {
      articleId: 4
    };
  </script>
  <script src="{{ asset ("/packages/admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
  <script src="{{ asset ("/packages/wap/doT.min.js") }}"></script>
  <script src="{{ asset ("/wap/tpl/comments.js") }}"></script>
  <script src="{{ asset ("/wap/js/comments.detail.js") }}"></script>
@endsection

@section('main')
  <header class="header">
    <a class="back" href=""></a>
    <span class="title">评论1233</span>
  </header>
  <div class="comment-edit">
    <textarea class="ipt-edit"></textarea>
    <button class=""></button>
    <div class="error"></span>
  </div>
  <div class="comment-list">
    <div class="box"></div>
    <div class="comment-footer">
       <i class="icon-loading"></i>
       <span class="load e-load">点击加载更多<span>
       <span class="error e-load">加载失败,点击重试</span>
       <span class="end">没有更多了</span>
    </div>
    <i class="icon-empty"></i>
  </div>
@endsection
