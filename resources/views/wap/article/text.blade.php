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
        <div class="vote-box pk">
          <div class="vote-item fl vote-agree e-vote e-submit @if ($ballotResult->first()['approved']) selected @endif" data-vote="{{ $ballotResult->first()['id'] }}"><span class="vote-words">{{ $ballotResult->first()['content'] }}</span><i class="icon-pk"></i></div>
          <div class="vote-item fr vote-disagree e-vote e-submit @if ($ballotResult->last()['approved']) selected @endif" data-vote="{{ $ballotResult->last()['id'] }}"><span class="vote-words">{{ $ballotResult->last()['content'] }}</span><i class="icon-pk"></i></div>
          <div class="proportion">
            <span class="percent agree-percent fl"></span>
            <span class="percent disagree-percent fr"></span>
            <div class="proportion-bar"><div class="proportion-agree"></div></div>
            <span class="vote-sign">PK</span>
          </div>
        </div>
      </section>
    @else
      <section class="module-extend module-vote">
        <h1 class="title">{{ $ballot->title }}<span class="vote-max">( 最多只能投2票 )</span></h1>
        <div class="vote-box vote">
          @foreach($ballotResult as $choice)
          <div class="vote-item e-vote @if ($choice['approved']) selected @endif" data-vote="{{ $choice['id'] }}">
            <i class="icon-vote"></i>
            <span class="vote-words">{{ $choice['content'] }}</span>
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
    <a class="more-comments" href="{{ route('comments.index', ['article_id' => $article->id]) }}">查看全部评论 >></a>
  </section>
@endsection

