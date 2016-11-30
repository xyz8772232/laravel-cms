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
        @foreach($photos as $photo)
          @if ($photo->article)
            <div class="sort-pic" data-id="{{$photo->id}}">
              <i class="fa fa-arrows-alt e-drag"></i>
              <img class="pic" src="{{asset('/upload/'.$photo->article->cover_pic)}}" alt="">
              <div class="title">{{$photo->article->title}}</div>
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
  <link rel="stylesheet" href="{{ asset("/packages/admin/sweetalert/sweetalert.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/dragula/dragula.min.css") }}">
  <style>
    .sort-pic {
      position: relative;
      float: left;
      margin: 15px 20px;
      width: 243px;
      height: 220px;
      background-color: #1fa67a;
      border: 1px solid #1fa67a;
      overflow: hidden;
    }
    .sort-pic .pic {
      display: block;
      width: 100%;
      height: 160px;
      background-color: #fff;
    }
    .sort-pic .title {
      padding: 10px;
      line-height: 1.3;
      font-size: 15px;
      color: #fff;
    }
    .e-drag {
      position: absolute;
      right: 0;
      top: 0;
      padding: 8px;
      font-size: 18px;
      color: #1fa67a;
      border: 1px solid #1fa67a;
      border-top: none;
      border-right: none;
      background-color: #fff;
      cursor: move;
      visibility: hidden;
    }
    .sort-box.active .e-drag {
      visibility: visible;
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
  <script src="{{ asset("/packages/admin/sweetalert/sweetalert.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/dragula/dragula.min.js") }}"></script>
  <script>
    $(function () {
      var $sortBox = $('#sortBox');
      /**
       * 绑定拖拽事件
       */
      dragula([$sortBox[0]], {
        moves: function (el, container, handle) {
          return handle.classList.contains('e-drag');
        }
      });

      /**
       * 绑定排序事件
       */
      var orgElList = Array.prototype.slice.call($sortBox.children(), 0);
      $('.box-footer').on('click', '.e-sort', function (e) {
        $sortBox.addClass('active');
        e.delegateTarget.classList.add('action-sort');
      }).on('click', '.e-submit', function (e) {
        swal({
          title: '',
          text: '<i class="icon-submit-loading">',
          customClass: 'submit-loading',
          showConfirmButton: false,
          html: true
        });
        setTimeout(function () {
          submitSort();
        }, 250);
      }).on('click', '.e-cancel', function (e) {
        $sortBox.removeClass('active');
        e.delegateTarget.classList.remove('action-sort');
        $sortBox.html('').append(orgElList);
      });

      function submitSort(){
        var tree = [];

        $sortBox.children().each(function () {
          tree.push(this.getAttribute('data-id'));
        });
        setTimeout(function() {})
        $.post('/admin/sort_photos/save', {
          _tree: JSON.stringify(tree)
        })
        .done(function (res) {
          if (res && res.result.status.code === 0) {
            swal({
              title: '修改成功',
              type: 'success'
            }, function () {
              location.reload();
            });
          } else {
            submitFail(res && res.result.status.msg);
          }
        })
        .fail(function (){
          submitFail();
        });
      }

      function submitFail(failMsg) {
        swal({
          title: '修改失败',
          type: 'error',
          text: failMsg || ''
        });
      }
    });
  </script>
@endsection
