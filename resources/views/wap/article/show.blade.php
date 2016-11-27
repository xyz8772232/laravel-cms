@extends('layouts.wap')

@section('css')
    <link rel="stylesheet" href="{{ asset("/wap/css/reset.css") }}">
    <link rel="stylesheet" href="{{ asset("/wap/css/show.css") }}">
@endsection

@section('title')
    @parent
@endsection

@section('description')
    <meta name="description" content="{{ $article->title }}">
@endsection

@section('js')
@endsection

@section('main')
    <section class="module-article">
        <h1 class="title">{{ $article->title }}</h1>
        <div class="extra">
            <div class="extra-info">2016-11-28 中国日报</div>
            {{--<div class="extra-info">{=$data.create_time|date_format:"%Y-%m-%d %H:%M:%S"=}</div>--}}
        </div>
        <article class="main-body">
            {!! $article->content !!}
        </article>
    </section>
@endsection

