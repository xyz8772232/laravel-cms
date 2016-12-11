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
  <script src="{{ asset ("/wap/js/comment.show.js") }}"></script>
  <script src="{{ asset ("/wap/js/comment.write.js") }}"></script>
  <script src="{{ asset ("/wap/js/comments.detail.js") }}"></script>
@endsection

@section('main')
  <header class="header">
    <a class="back" href=""></a>
    <span class="title">评论1233</span>
    <span class="edit e-edit" id="edit"></span>
  </header>
  <section class="module-comment">
    <div id="writeComment"></div>
  </section>
  <section id="comments" class="module-comments"></section>
@endsection
