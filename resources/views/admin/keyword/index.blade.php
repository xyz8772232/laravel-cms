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
        <form>
          {{--{{ csrf_field() }}--}}
        {{--<input type="checkbox" class="e-select-all"> 全选
        <a class="e-delete btn btn-sm btn-danger">删除</a>--}}
        <a class="e-add btn btn-sm btn-success">添加关键词</a>
        <div class="search">
          <input class="ipt" type="text" placeholder="请输入关键词" name="keyword" value="{{ $filterValues['keyword'] ?? null}}">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
        </div>
        </form>
      </div>
      <div class="box-body">
        <div class="keyword-box" id="keywordBox">
        @foreach ($keywords as $keyword)
          <div class="keyword" data-id="{{ $keyword->id }}">
            <input class="ipt" value="{{ $keyword->name }}" readonly>
            <div class="footer">
              <i class="fa fa-pencil e-edit"></i>
              <i class="fa fa-trash-o e-delete"></i>
            </div>
          </div>
        @endforeach
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
