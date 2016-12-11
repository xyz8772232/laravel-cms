@extends('layouts.wap')

@section('css')
    <link rel="stylesheet" href="{{ asset("/wap/css/reset.css") }}">
    <link rel="stylesheet" href="{{ asset("/wap/css/comments.css?ver=201612101116") }}">
    <link rel="stylesheet" href="{{ asset("/wap/css/show.css?ver=201612101116") }}">
@endsection

@section('title')
    @parent
@endsection

@section('description')
    <meta name="description" content="{{ $article->title }}">
@endsection

@section('js')
<script>
  var PAGE_CONFIG = {
    articleId: 1
  };
  </script>
  <script src="{{ asset ("/packages/admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
  <script src="{{ asset ("/packages/wap/doT.min.js") }}"></script>
  <script src="{{ asset ("/wap/tpl/comments.js") }}"></script>
  <script src="{{ asset ("/wap/js/comment.write.js") }}"></script>
  <script src="{{ asset ("/wap/js/comment.show.js") }}"></script>
  <script src="{{ asset ("/wap/js/article.normal.js") }}"></script>
@endsection

@section('main')
  <section class="module-article">
    <h1 class="title">{{ $article->title }}</h1>
    <div class="extra">
    <div class="extra-info">{{ $article->published_at }} {{$article->source}}</div>
    </div>
    <article class="main-body">
      {!! $article->content !!}
    </article>
    {{--@foreach($comments as $comment)
        <div>{{ $comment->content }} <span>{{ $comment->user_nick }}</span>
            @if ($comment->reply_to_id)回复:<span>{{ $comment->parent->user_nick }}</span> @endif
        </div>
    @endforeach--}}
  </section>

  <section class="module-extend module-comments">
    <div id="writeComment"></div>
    <div id="comments"></div>
    <a class="more-comments" href="/comments?article_id=1">查看全部评论 >></a>
  </section>
@endsection

