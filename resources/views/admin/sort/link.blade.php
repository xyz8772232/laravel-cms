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
        <div class="sort-box" id="sortBox">
          @foreach($links as $link)
            <div class="sort-news" data-id="{{$link->id}}">{{$link->article->title}}</div>
          @endforeach
        </div>
      </div>
      <div class="box-footer clearfix">
        <button type="button" class="btn btn-success pull-right">确定</button>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/dragula/dragula.min.css") }}">
  <style>
    .sort-box {
      margin: 0 20px 20px;
      cursor: pointer;
    }
    .sort-news {
      height: 50px;
      line-height: 50px;
      padding: 0 20px;
      border-bottom: 1px dotted #e1e1e1;
      font-size: 15px;
      color: #ffffff;
      font-weight: bold;
      background-color: #337ab7;
    }
    .sort-news.action-el {
      opacity: .7;
    }
    .sort-news:last-child {
      border-bottom: none;
    }
    .btn-success {
      margin-right: 20px;
    }
  </style>
@endsection

@section('admin_js')
  <script src="{{ asset("/packages/admin/dragula/dragula.min.js") }}"></script>
  <script>
    $(function () {
      dragula([document.getElementById('sortBox')])
      .on('drag', function (el, source) {
        el.classList.add('action-el');
      }).on('drop', function (el, target, source) {
        el.classList.remove('action-el');
      });
    });
  </script>
@endsection
