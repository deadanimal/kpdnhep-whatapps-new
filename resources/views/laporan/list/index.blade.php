@extends('layouts.main')

@section('title')
Laporan
@endsection

@section('content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <h2>@yield('title')</h2>
                        <ol class="breadcrumb">
                            <li>
                                {{ link_to('dashboard', 'Dashboard') }}
                            </li>
                            <li class="active">
                                <a href="{{ url()->current() }}">
                                    <strong>@yield('title')</strong>
                                </a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">
                        <div class="panel-group" id="accordion">
                            @isset($collections)
                            @foreach ($collections as $collection)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#{{ $collection['key'] }}">
                                            {{ $collection['name'] }}
                                        </a>
                                    </h5>
                                </div>
                                <div id="{{ $collection['key'] }}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered table-hover">
                                            <tbody>
                                                @foreach ($collection['list'] as $row)
                                                <tr>
                                                    <td width="5%">
                                                        {{ $loop->iteration }}.
                                                    </td>
                                                    <td>
                                                        <a href="{{ url($row['url']) }}">
                                                            {{ $row['title'] }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
