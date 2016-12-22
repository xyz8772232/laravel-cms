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
        <form action="{{ route('admin.watermarks.save') }}" method="post" accept-charset="UTF-8" class="form-horizontal"
              enctype="multipart/form-data" pjax-container>
          <div class="box-body">
            <div class="form-group 1">
              <label for="path" class="col-sm-2 control-label">水印</label>
              <div class="col-sm-6">
                <input type="file" id="path" name="path"/>
                <input type="hidden" id="path_action" name="path_action" value="0"/>
              </div>
            </div>
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
      $("#path").fileinput({
        "overwriteInitial": true,
        "showUpload": false,
        "language": "zh_CN",
        "allowedFileTypes": ["image"],
        "initialCaption": "{{ $image['caption'] ?? null }}",
        "initialPreview": "{!! $image['preview'] ??  null !!}"
      });
 
      $("#path").on('filecleared', function (event) {
        $("#path_action").val(1);
      });
    });
  </script>
@endsection

