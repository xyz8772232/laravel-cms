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
          <div class="sort-news">这是出啥大事了？怀卡托知名小开被警方开枪击毙</div>
          <div class="sort-news">火上浇油！冬天的房源短缺或进一步助推房价增长</div>
          <div class="sort-news">政府放弃公房署2年股息 新增4000套公屋可还行?</div>
          <div class="sort-news">今天是国际薯条日？告诉你NZ最好吃的薯条在哪!</div>
          <div class="sort-news">新西兰火箭携手美国卫星：送卫星上太空 So Easy</div>
          <div class="sort-news">小伙伴们注意了！NZ发布恶劣天气预警 风雪又来</div>
          <div class="sort-news">吃货们准备好：北地政府呼吁民众吃光这种螃蟹！</div>
          <div class="sort-news">NZ教育系统中的理财教育 为不同年龄段设定目标</div>
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
  <script data-exec-on-popstate src="/packages/admin/dragula/dragula.min.js"></script>
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
