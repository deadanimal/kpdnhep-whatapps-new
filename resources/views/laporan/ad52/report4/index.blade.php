@extends('layouts.main')

@section('title')
{{ $title }}
@endsection

@section('content')
	<style>
        .form-control[readonly][type="text"] {
            background-color: #ffffff;
        }
    </style>
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
                            <li>
                                {{ link_to_route('laporan.list', 'Laporan') }}
                            </li>
                            <li>
                                AD52
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
                <div class="ibox-title">
                    <h5>Carian</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    {{ Form::open([
                        'url' => url()->current(),
                        'method' => 'GET'
                    ]) }}
                        <div class="row">
                            <div class="col-md-1 col-lg-3"></div>
                            <div class="col-md-10 col-lg-6">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <i class="fa fa-info-circle"></i>&nbsp;Carian
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group" id="date">
                                            {{ Form::label('date_start', 'Tarikh Penerimaan Aduan', ['class' => 'control-label']) }}
                                            <div class="input-daterange input-group" id="datepicker">
                                                {{ Form::text(
                                                	'date_start',
                                                	date('d-m-Y', strtotime($date_start)),
                                                	[
                                                		'class' => 'form-control',
                                                		'id' => 'date_start',
	                                                    'onkeypress' => "return false",
	                                                    'onpaste' => "return false",
	                                                    'placeholder' => 'HH-BB-TTTT',
	                                                    'readonly' => true
                                        			]
                                    			) }}
                                                <span class="input-group-addon">hingga</span>
                                                {{ Form::text(
                                                	'date_end',
                                                	date('d-m-Y', strtotime($date_end)),
                                                	[
                                                		'class' => 'form-control',
                                                		'id' => 'date_end',
	                                                    'onkeypress' => "return false",
	                                                    'onpaste' => "return false",
	                                                    'placeholder' => 'HH-BB-TTTT',
	                                                    'readonly' => true
                                        			]
                                    			) }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('state', 'Negeri', ['class' => 'control-label']) }}
                                            {{ Form::select('state', $states, null, ['class' => 'form-control', 'placeholder' => '-- SEMUA NEGERI --']) }}
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="text-center">
                                            <a href="{{ url()->current() }}" class='btn btn-rounded btn-success btn-outline'>
                                                <i class="fa fa-refresh"></i>&nbsp;Semula
                                            </a>
                                            {{ Form::button(
                                                '<i class="fa fa-search"></i>&nbsp;Carian',
                                                [
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-rounded btn-success'
                                                ]
                                            ) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-lg-3"></div>
                        </div>
                        @if($isSearch)
                            <div class="text-center">
                                {{ Form::button(
                                    '<i class="fa fa-file-excel-o"></i> Muat Turun Excel',
                                    [
                                        'type' => 'submit',
                                        'class' => 'btn btn-rounded btn-primary',
                                        'name' => 'gen',
                                        'value' => 'xls',
                                        'formtarget' => '_blank'
                                    ]
                                ) }}
                                {{ Form::button(
                                    '<i class="fa fa-file-pdf-o"></i> Muat Turun PDF',
                                    [
                                        'type' => 'submit',
                                        'class' => 'btn btn-rounded btn-danger btn-outline',
                                        'name' => 'gen',
                                        'value' => 'pdf',
                                        'formtarget' => '_blank'
                                    ]
                                ) }}
                            </div>
                        @endif
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    @if($isSearch)
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Laporan</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="text-center">
                            <h3>
                                @yield('title')
                            </h3>
                            <h3>
                                Tarikh Penerimaan Aduan : Dari 
                                {{ date('d-m-Y', strtotime($date_start)) }}
                                Hingga 
                                {{ date('d-m-Y', strtotime($date_end)) }}
                            </h3>
                            <h3>
                                Negeri : {{ $stateDesc }}
                            </h3>
                        </div>
                        <div class="table-responsive">
                            @include('laporan.ad52.report4.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script_datatable')
    <script>
        $(document).ready(function(){
            $('#date .input-daterange').datepicker({
                autoclose: true,
                calendarWeeks: true,
                forceParse: false,
                format: 'dd-mm-yyyy',
                keyboardNavigation: false,
                todayBtn: "linked",
                todayHighlight: true,
                weekStart: 1,
            });
        });
    </script>
@endsection
