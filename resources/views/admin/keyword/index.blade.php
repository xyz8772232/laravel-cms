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
        {{--<input type="checkbox" class="e-select-all"> 全选
        <a class="e-delete btn btn-sm btn-danger">删除</a>--}}
        <a class="e-add btn btn-sm btn-success">添加关键词</a>
      </div>
      <div class="box-body">
        <div class="keyword-box" id="keywordBox">
        @for ($i = 1; $i < 9; $i++)
          <div class="keyword" data-id="{{ $i }}">
            <input class="ipt" value="关键词{{ $i }}" readonly>
            <div class="footer">
              <i class="fa fa-pencil e-edit"></i>
              <i class="fa fa-trash-o e-delete"></i>
            </div>
          </div>
        @endfor
        </div>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/sweetalert/sweetalert.css") }}">
  <link rel="stylesheet" href="{{ asset("/css/keyword.index.css") }}">
@endsection

@section('admin_js')
  <script src="{{ asset("/packages/admin/sweetalert/sweetalert.min.js") }}"></script>
  <script src="{{ asset_with_version("/js/keyword.index.js") }}"></script>
@endsection
