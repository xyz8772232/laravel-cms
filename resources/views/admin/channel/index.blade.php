@extends('layouts.app')

@section('content')
  <section class="content-header">
    <h1>
      header
      <small>description</small>
    </h1>
  </section>
  <section class="content">
    <div class="channel-wrapper">


    </div>
  </section>
@endsection

@section('css')
    <link rel="stylesheet" href="/packages/admin/dragula/dragula.min.css">
    <link rel="stylesheet" href="/packages/admin/sweetalert/sweetalert.css">
    <link rel="stylesheet" href="/css/channel.index.css">
@endsection

@section('admin_js')
    <script data-exec-on-popstate src="/packages/admin/sweetalert/sweetalert.min.js"></script>
    <script data-exec-on-popstate src="/packages/admin/dragula/dragula.min.js"></script>
    <script data-exec-on-popstate src="/js/channel.index.js"></script>
@endsection