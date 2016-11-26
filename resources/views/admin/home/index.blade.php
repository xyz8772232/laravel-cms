@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ $header }}
            <small>{{ $description }}</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{$boxes['unaudited'] ?? 0}}</h3>
                        <p>未审核文章</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <a href="/admin/articles" class="small-box-footer">
                        更多&nbsp;
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{$boxes['soft'] ?? 0}}</h3>
                        <p>商业软文</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-file"></i>
                    </div>
                    <a href="/admin/orders" class="small-box-footer">
                        更多&nbsp;
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @foreach($tables as $table)
    @if($loop->index % 2 == 0)
        <div class="row">
    @endif
            <div class="col-md-6">
                <div class="box box-info box-solid">
                    {{--<div class="box-header with-border">--}}
                        {{--<h3 class="box-title">二级频道</h3>--}}
                        {{--<div class="box-tools pull-right">--}}
                        {{--</div><!-- /.box-tools -->--}}
                    {{--</div><!-- /.box-header -->--}}
                    <div class="box-body" style="display: block;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{$table['headers'][0]}}</th>
                                <th>{{$table['headers'][1]}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($table['rows'] as $row)
                            <tr>
                                <td>{!! $row[0] !!}</td>
                                <td>{{$row[1]}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div><!-- /.box-body -->
                </div>
            </div>
    @if($loop->index % 2 == 1)
        </div>
    @endif
        @endforeach
    </section>
@endsection

@section('css')
    <link rel="stylesheet" href="/packages/admin/sweetalert/sweetalert.css">
@endsection

@section('admin_js')
    <script data-exec-on-popstate src="/packages/admin/sweetalert/sweetalert.min.js"></script>
@endsection
