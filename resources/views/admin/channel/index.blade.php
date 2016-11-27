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
  <link rel="stylesheet" href="{{ asset("/packages/admin/dragula/dragula.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/packages/admin/sweetalert/sweetalert.css") }}">
  <link rel="stylesheet" href="{{ asset("/css/channel.index.css") }}">
@endsection

@section('admin_js')
  <script>
  var CHANNEL = {!! json_encode($channels) !!};
  </script>
  <script src="{{ asset("/packages/admin/sweetalert/sweetalert.min.js") }}"></script>
  <script src="{{ asset("/packages/admin/dragula/dragula.min.js") }}"></script>
  <script src="{{ asset("/js/channel.index.js") }}"></script>
@endsection
