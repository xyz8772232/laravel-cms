@extends('layouts.admin')

@section('content')
  <section class="content-header">
    <h1>
      {{ $header }}
      <small>{{ $description }}</small>
    </h1>
  </section>
  <section class="content">
    <div>
      <div class="box col-md-12">
        <!-- form start -->
        <form action="/admin/app_photos" method="post" accept-charset="UTF-8" class="form-horizontal"
              enctype="multipart/form-data">
          <div class="box-body">
          @for ($i = 1; $i < 9; $i++)
            <div class="form-group">
              <label for="path" class="col-sm-2 control-label">幻灯片{{ $i }}</label>
              <div class="col-sm-6">
                <input type="file" class="photo" name="photo{{ $i }}"/>
              </div>
            </div>
          @endfor
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            {{ csrf_field() }}
            <div class="col-sm-2">
            </div>
            <div class="col-sm-6">
              <div class="btn-group pull-right">
                <button type="submit" class="btn btn-info pull-right">提交</button>
              </div>
            </div>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap-fileinput/css/fileinput.min.css") }}">
@endsection

@section('admin_js')
  <script src="{{ asset("/packages/admin/bootstrap-fileinput/js/fileinput.min.js") }}"></script>
  <script>
    $(function () {
      var photos = INIT_CONFIG.photos || {};

      $('.photo').each(function (index) {
        var photo = photos[index+1];
        $(this).fileinput({
          overwriteInitial: true,
          showUpload: false,
          language: 'zh_CN',
          allowedFileTypes: ['image'],
          initialCaption: photo && photo.title,
          initialPreview: photo && photo.url && [
            '<img src="' + photo.url + '" class="file-preview-image">'
            + '<input type="hidden" name="old_photo_'+(index+1)+'" value="' + photo.url + '">'
          ]
        });
      });
    });
  </script>
@endsection

