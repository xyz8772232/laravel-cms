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
    articleId: 1,
    pk: {
      agree: 99,
      disagree: 13,
      //vote: 1 // 1 -- 赞成 0 -- 反对
    },
    vote: {
      agree: [10, 87, 19],
      //vote: [0,1]
    }
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

  <section class="module-extend module-vote">
    <h1 class="title">你觉得第三次世界大战会在20年内爆发吗?</h1>
    <div class="pk">
      <div class="pk-item fl e-pk pk-agree" data-vote="1"><i class="icon-pk"></i></div>
      <div class="pk-item fr e-pk pk-disagree" data-vote="0"><i class="icon-pk"></i></div>
      <div class="proportion">
        <span class="percent agree-percent fl"></span>
        <span class="percent disagree-percent fr"></span>
        <div class="proportion-bar"><div class="proportion-agree"></div></div>
        <span class="pk-words">PK</span>
      </div>
    </div>
  </section>

  <section class="module-extend module-vote">
    <h1 class="title">你最喜欢的几本书?</h1>
    <div class="vote">
      <div class="vote-item e-vote" data-vote="0">
        <i class="icon-vote"></i>
        <span class="vote-words">哈利·波特</span>
        <span class="percent"></span>
        <div class="proportion-bar"><div class="proportion-agree"></div></div>
      </div>
      <div class="vote-item e-vote" data-vote="1">
        <i class="icon-vote"></i>
        <span class="vote-words">旧时代与大革命</span>
        <span class="percent"></span>
        <div class="proportion-bar"><div class="proportion-agree"></div></div>
      </div>
      <div class="vote-item e-vote" data-vote="2">
        <i class="icon-vote"></i>
        <span class="vote-words">通往奴役之路</span>
        <span class="percent"></span>
        <div class="proportion-bar"><div class="proportion-agree"></div></div>
      </div>
      <div class="submit e-submit">提交</div>
    </div>
  </section>

  <section class="module-extend module-comments">
    <div id="writeComment"></div>
    <div id="comments"></div>
    <a class="more-comments" href="/comments?article_id=1">查看全部评论 >></a>
  </section>
@endsection

