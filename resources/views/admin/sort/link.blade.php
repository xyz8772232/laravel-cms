@extends('layouts.admin')

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
        <div class="sort-box" id="sortBox">
          @foreach($links as $link)
            @if ($link->article)
            <div class="sort-news" data-id="{{$link->id}}">
              <i class="fa fa-arrows-alt text-default e-drag"></i>
              <div class="color">
                颜色:#
                <input class="e-color" type="text" value="{{ltrim($link->article->title_color, '#')}}" maxLength="6"/>
              </div>
              <div class="bold">
                粗体:
                <input class="e-bold" type="checkbox" {{$link->article->title_bold === 1 ? 'checked' : ''}}/>
              </div>
              <span class="title" style="color:{{$link->article->title_color}};{{$link->article->title_bold === 1 ? 'font-weight:bold;' : ''}}">{{$link->article->title}}</span>
            </div>
            @endif
          @endforeach
        </div>
      </div>
      <div class="box-footer clearfix">
        <button type="button" class="btn btn-primary pull-right e-sort">排序</button>
        <button type="button" class="btn btn-success pull-right e-submit">确定</button>
        <button type="button" class="btn btn-danger pull-right e-cancel">取消</button>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/sweetalert/sweetalert.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/dragula/dragula.min.css") }}">
  <style>
    .sort-box {
      margin: 0 20px 20px;
    }
    .sort-box.active .color,
    .sort-box.active .bold {
      display: none;
    }
    .sort-box.active .e-drag {
      display: block;
    }
    .sort-news {
      margin-bottom: 5px;
      height: 50px;
      line-height: 50px;
      padding: 0 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 15px;
      color: #333;
      background-color: #fff;
    }
    .sort-news:last-child {
      margin-bottom: 0;
    }
    .sort-news .bold {
      float: right;
      margin-right: 15px;
    }
    .sort-news .e-bold {
      vertical-align: top;
      margin-top: 17px;
      font-size: 16px;
      outline: none;
    }
    .sort-news .color {
      float: right;
    }
    .sort-news .e-color {
      display: inline-block;
      vertical-align: top;
      margin-top: 15px;
      padding: 0 5px;
      width: 65px;
      line-height: 20px;
      border: none;
      border-bottom: 1px solid #aaa;
      background-color: transparent;
      outline: none;
    }
    .e-drag {
      float: right;
      margin-top: 17px;
      cursor: move;
      display: none;
    }
    .box-footer {
      padding-right: 30px;
    }
    .e-submit,
    .e-cancel {
      margin-left: 5px;
      display: none;
    }
    .action-sort .e-submit,
    .action-sort .e-cancel {
      display: block;
    }
    .action-sort .e-sort {
      display: none;
    }
    .submit-loading {
      padding-bottom: 17px!important;
    }
    .icon-submit-loading {
      display: inline-block;
      width: 50px;
      height: 50px;
      background: url('/img/loading.gif') no-repeat;
    }
  </style>
@endsection

@section('admin_js')
  <script src="{{ asset ("/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/sweetalert/sweetalert.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/dragula/dragula.min.js") }}"></script>
  <script src="{{ asset ("/js/sort.link.js") }}"></script>
@endsection
