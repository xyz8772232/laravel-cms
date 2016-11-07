@extends('layouts.app')

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

            </div>
            <div class="box-body">

            </div>
            <div class="box-footer clearfix"></div>
        </div>
    </section>
@endsection

@section('css')
    <link data-exec-on-popstate rel="stylesheet" href="/css/photo.index.css">
    <link rel="stylesheet" href="/packages/admin/sweetalert/sweetalert.css">
@endsection

@section('admin_js')
    <script data-exec-on-popstate src="/packages/admin/sweetalert/sweetalert.min.js"></script>
    <script data-exec-on-popstate src="/js/photo.index.js"></script>
@endsection
