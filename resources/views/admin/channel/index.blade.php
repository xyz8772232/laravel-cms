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
      <div class="channel-group">
        <div class="channel-header">
          一级频道
          <div class="btn btn-sm btn-primary channel-edit">编辑</div>
        </div>
        <div class="channel-box clearfix">
          <div class="channel channel-selected">
            <input readonly type="text" class="channel-ipt" value="A">
            <i class="fa fa-minus-circle text-danger channel-btn-del"></i>
          </div>
          <div class="channel">
            <input readonly type="text" class="channel-ipt" value="B">
            <i class="fa fa-minus-circle text-danger channel-btn-del"></i>
          </div>
        </div>
      </div>
      <div class="channel-group">
        <div class="channel-header">
          二级频道
          <div class="btn btn-sm btn-primary channel-edit">编辑</div>
        </div>
        <div class="channel-box clearfix">
          <div class="channel">
            <input type="text" class="channel-ipt" value="A1">
            <i class="fa fa-minus-circle text-danger channel-btn-del"></i>
          </div>
          <div class="channel">
            <input type="text" class="channel-ipt" value="A2">
            <i class="fa fa-minus-circle text-danger channel-btn-del"></i>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('css')
    <link rel="stylesheet" href="/packages/admin/dragula/dragula.min.css">
    <link rel="stylesheet" href="/css/channel.index.css">
@endsection

@section('admin_js')
    <script data-exec-on-popstate src="/packages/admin/dragula/dragula.min.js"></script>
    <script data-exec-on-popstate src="/js/channel.index.js"></script>
@endsection