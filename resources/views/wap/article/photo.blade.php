@extends('layouts.wap')

@section('css')
    <link rel="stylesheet" href="{{ asset("/wap/css/reset.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/wap/swiper/css/swiper.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/wap/css/article.photo.css") }}">
@endsection

@section('title')
    @parent
@endsection

@section('description')
    <meta name="description" content="{{ $article->title }}">
@endsection

@section('js')
  <script src="{{ asset ("/packages/wap/swiper/js/swiper.min.js") }}"></script>
  <script src="{{ asset ("/wap/js/article.photo.js") }}"></script>
@endsection

@section('main')
  <section class="module-photo-article">
    <header class="header">
      <a class="comments" href="{{ route('comments.index', ['article_id' => $article->id]) }}"><i class="icon icon-comments"></i>{{ $commentNum }}</a>
    </header>
    <div class="photo-box swiper-container">
      <div class="swiper-wrapper">
        @foreach ($contentPics as $photo)
          <div class="photo swiper-slide">
            <div class="swiper-zoom-container">
              <img class="photo-img swiper-lazy" data-src="{{ $photo['img'] }}">
            </div>
            <span class="photo-title">{{ $photo['title'] }}</span>
          </div>
        @endforeach
      </div>
    </div>
    <div class="summary-box">
      <div class="title">{{ $article->title }}<div class="pagination"></div></div>
      <p class="summary"></p>
    </div>
  </section>
@endsection

