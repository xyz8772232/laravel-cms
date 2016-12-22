@extends('layouts.admin')

@section('content')
  <section class="content-header">
    <h1>
      {{ $header }}
      <small>{{ $description }}</small>
    </h1>
  </section>
  <section class="content">
    <div class="channel-wrapper">
    </div>
  </section>
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset("/packages/admin/sweetalert/sweetalert.css") }}">
  <link rel="stylesheet" href="{{ asset_with_version("/css/channel.index.css") }}">
@endsection

@section('admin_js')
  <script>
  var CHANNEL = {!! json_encode($channels) !!};
  </script>
  <script src="{{ asset("/packages/admin/sweetalert/sweetalert.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/sortable/sortable.min.js") }}"></script>
  <script src="{{ asset_with_version("/js/channel.index.js") }}"></script>
@endsection
