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
        @foreach($photos as $photo)
          @if ($photo->article)
              @if($loop->first || $loop->iteration % 5 == 0)
                <div class="row">
              @endif
                <div class="sort-pic" data-id="{{$photo->id}}">
                  <img class="pic" src="{{asset('/upload/'.$photo->article->cover_pic)}}" alt="">
                  <div class="title">{{$photo->article->title}}</div>
                </div>
              @if($loop->last || $loop->iteration % 4 == 0)
              </div>
              @endif
          @endif
        @endforeach
      </div>
      <div class="box-footer clearfix">
        <button type="button" class="btn btn-success pull-right">确定</button>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/sweetalert/sweetalert.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/dragula/dragula.min.css") }}">
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
        $.post('/admin/sort_links/save', {
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
        .fail(submitFail);
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
