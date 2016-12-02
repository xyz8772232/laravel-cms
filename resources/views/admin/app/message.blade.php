@extends('layouts.admin')

@section('content')
  <section class="content-header">
    <h1>
      {{ $header }}
      <small>{{ $description }}</small>
    </h1>
  </section>

  <section class="content">
    <div class="box col-md-12">
      <!-- form start -->
      <form action="/admin/app_messages" method="post" class="form-horizontal"
            enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="content" class="col-sm-2 control-label">自定义推送内容</label>
            <div class="col-sm-6 emoji-box">
              <textarea name="content" class="form-control" data-emojiable="true"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label for="article_id" class="col-sm-2 control-label">文章ID</label>
            <div class="col-sm-6">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" name="article_id" value="" class="form-control"
                       placeholder="输入文章ID">
              </div>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          {{ csrf_field() }}
          <div class="col-sm-2"></div>
          <div class="col-sm-6">
            <div class="btn-group pull-right" style="margin-right: -5px;">
              <button type="submit" class="btn btn-info">确定并推送</button>
            </div>
          </div>
        </div>
        <!-- /.box-footer -->
      </form>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/emoji-picker/css/nanoscroller.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/emoji-picker/css/emoji.css") }}">
  <style>
    .emoji-box{
        position: relative;
    }
    .emoji-box .emoji-wysiwyg-editor {
      min-height: 200px;
    }
    .emoji-box .emoji-picker{
      right: 25px;
    }
  </style>
@endsection

@section('admin_js')
  <script src="{{ asset ("/packages/admin/emoji-picker/js/nanoscroller.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/emoji-picker/js/tether.min.js") }}"></script>
  <script src="{{ asset ("/packages/admin/emoji-picker/js/config.js") }}"></script>
  <script src="{{ asset ("/packages/admin/emoji-picker/js/util.js") }}"></script>
  <script src="{{ asset ("/packages/admin/emoji-picker/js/jquery.emojiarea.js") }}"></script>
  <script src="{{ asset ("/packages/admin/emoji-picker/js/emoji-picker.js") }}"></script>
  <script>
    $(function() {
      window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: '/packages/admin/emoji-picker/img/',
        popupButtonClasses: 'fa fa-smile-o'
      });
      window.emojiPicker.discover();
    });
  </script>
@endsection
