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
      <div class="box-body" id="sortBox">
        <div class="sort-pic">
          <img class="pic" src="http://img.cool80.com/i/png/381/02.png" alt="">
          <div class="title">瓦力1</div>
        </div>
        <div class="sort-pic">
          <img class="pic" src="http://img.cool80.com/i/png/381/02.png" alt="">
          <div class="title">瓦力2</div>
        </div>
        <div class="sort-pic">
          <img class="pic" src="http://img.cool80.com/i/png/381/02.png" alt="">
          <div class="title">瓦力3</div>
        </div>
        <div class="sort-pic">
          <img class="pic" src="http://img.cool80.com/i/png/381/02.png" alt="">
          <div class="title">力瓦力瓦力瓦力瓦力瓦力瓦</div>
        </div>
        <div class="sort-pic">
          <img class="pic" src="http://img.cool80.com/i/png/381/02.png" alt="">
          <div class="title">瓦力4</div>
        </div>
        <div class="sort-pic">
          <img class="pic" src="http://img.cool80.com/i/png/381/02.png" alt="">
          <div class="title">瓦力5</div>
        </div>
      </div>
      <div class="box-footer clearfix">
        <button type="button" class="btn btn-success pull-right">确定</button>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="/packages/admin/dragula/dragula.min.css">
  <style>
    .sort-pic {
      float: left;
      margin: 15px 0 0 15px;
      width: 200px;
      height: 200px;
      padding: 0 25px;
    }
    .sort-pic .pic {
      display: block;
      border: 1px solid #ddd;
      width: 100%;
      height: 150px;
    }
    .sort-pic .title {
      margin-top: 10px;
      height: 40px;
      line-height: 1.2;
      font-size: 16px;
      color: #333;
    }
  </style>
@endsection

@section('admin_js')
  <script data-exec-on-popstate src="/packages/admin/dragula/dragula.min.js"></script>
  <script>
    $(function () {
      dragula([document.getElementById('sortBox')]);
    });
  </script>
@endsection
