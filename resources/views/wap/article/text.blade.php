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
  </section>

  @if ($ballot)
    @if ($ballot->type == 2)
      <section class="module-extend module-vote">
        <h1 class="title">{{ $ballot->title }}</h1>
        <div class="pk">
          <div class="pk-item fl e-pk pk-agree" data-vote="{{ $ballot->choices->first()->id }}"><span>{{ $ballot->choices->first()->content }}</span><i class="icon-pk"></i></div>
          <div class="pk-item fr e-pk pk-disagree" data-vote="{{ $ballot->choices->last()->id }}"><span>{{ $ballot->choices->last()->content }}</span><i class="icon-pk"></i></div>
          <div class="proportion">
            <span class="percent agree-percent fl"></span>
            <span class="percent disagree-percent fr"></span>
            <div class="proportion-bar"><div class="proportion-agree"></div></div>
            <span class="pk-words">PK</span>
          </div>
        </div>
      </section>
    @else
      <section class="module-extend module-vote">
        <h1 class="title">{{ $ballot->title }}</h1>
        <div class="vote">
          @foreach($ballot->choices as $choice)
          <div class="vote-item e-vote" data-vote="{{ $choice->id }}">
            <i class="icon-vote"></i>
            <span class="vote-words">{{ $choice->content }}</span>
            <span class="percent"></span>
            <div class="proportion-bar"><div class="proportion-agree"></div></div>
          </div>
          @endforeach
          <div class="submit e-submit">提交</div>
        </div>
      </section>
    @endif
  @endif
  <section class="module-extend module-comments">
    <div id="writeComment"></div>
    <div id="comments"></div>
    <a class="more-comments" href="/comments?article_id={{ $article->id }}">查看全部评论 >></a>
  </section>
@endsection

