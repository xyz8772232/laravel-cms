@extends('layouts.wap')

@section('css')
@endsection

@section('title')
    @parent
@endsection

@section('description')
    {{--<meta name="description" content="{=$data.title=}">--}}
@endsection

@section('js')
@endsection

@section('main')
    <section class="module-author">
        <div class="inner">
            <a class="face-wrapper">
                {{--<img class="face" src="{=$user.logo=}" alt="">--}}
            </a>
            <div class="info">
                {{--<a class="name" href="{=if $isAuthor==1=}http://{=$smarty.server.HTTP_HOST=}/space/view{=else=}http://{=$smarty.server.HTTP_HOST=}/author/main/{=$data.author_uid=}{=/if=}">{=$user.name=}</a>--}}
                {{--<a class="intro" href="{=if $isAuthor==1=}http://{=$smarty.server.HTTP_HOST=}/space/view{=else=}http://{=$smarty.server.HTTP_HOST=}/author/main/{=$data.author_uid=}{=/if=}">{=$authorSort=}</a>--}}
            </div>
        </div>
    </section>

    <section class="module-article">
        {{--<h1 class="title">{=$data.title=}</h1>--}}
        <div class="extra">
            <!--<span class="collect extra-action {=if $col==1=}collected{=/if=}">{=if $col==1=}已收藏{=else=}收藏{=/if=}</span>-->
            <!--<a class="comments extra-action" href="{=$commentUrl=}">{=$commentSum=}</a>-->
            {{--<div class="extra-info">{=$data.create_time|date_format:"%Y-%m-%d %H:%M:%S"=}</div>--}}
        </div>
        <article class="main-body">
            {{--{=$data.content nofilter=}--}}
        </article>
        <div class="action">
            {{--<div class="like {=if $fav==1=}liked{=/if=} action-btn"><i class="icon icon-like"></i>{=$favourNum=}</div>--}}
            {{--<div class="weibo action-btn"><i class="icon icon-weibo"></i>分享到微博</div>--}}
            {{--<div class="wechat-timeline action-btn"><i class="icon icon-wechat-timeline"></i>朋友圈</div>--}}
            {{--<div class="notice">温馨提示：关心这篇文章，会给您推荐更多此类文章</div>--}}
        </div>
    </section>

    <section class="bar-tab">
        <div class="tab-box">
            <div class="tab back"></div>
            {{--<a class="tab comments" href="{=$commentUrl=}">{=$commentSum=}</a>--}}
            {{--<div class="tab collect {=if $col==1=}collected{=/if=}"></div>--}}
            <div class="tab share"></div>
            <div class="tab font"></div>
        </div>
        <div class="panel-font">
            <div class="btn-smaller">A-</div>
            <div class="btn-bigger">A+</div>
            <div class="gear">
                <div class="gear-inner"></div>
                <div class="handle"></div>
            </div>
        </div>
    </section>
@endsection

