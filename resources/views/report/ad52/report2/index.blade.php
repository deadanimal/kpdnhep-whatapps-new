@extends('layouts.main')

@section('title')
{{ $data['title'] ?? '' }}
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
                                <a href="{{ url()->full() }}">
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
                                            {{ Form::label('datestart', 'Tarikh Penerimaan Aduan', ['class' => 'control-label']) }}
                                            <div class="input-daterange input-group" id="datepicker">
                                                {{ Form::text(
                                                	'datestart',
                                                	date('d-m-Y', strtotime($data['datestart'])),
                                                	[
                                                		'class' => 'form-control',
                                                		'id' => 'datestart',
	                                                    'onkeypress' => "return false",
	                                                    'onpaste' => "return false",
	                                                    'placeholder' => 'HH-BB-TTTT',
	                                                    'readonly' => true
                                        			]
                                    			) }}
                                                <span class="input-group-addon">hingga</span>
                                                {{ Form::text(
                                                	'dateend',
                                                	date('d-m-Y', strtotime($data['dateend'])),
                                                	[
                                                		'class' => 'form-control',
                                                		'id' => 'dateend',
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
                                            {{ Form::select('state', $data['states'] ?? [], null, ['class' => 'form-control', 'placeholder' => '-- SEMUA --']) }}
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="text-center">
                                            <a href="{{ url()->current() }}" class='btn btn-rounded btn-success btn-outline'>
                                                <i class="fa fa-refresh"></i>&nbsp;Semula
                                            </a>
                                            {{ Form::button(
                                                '<i class="fa fa-search"></i>&nbsp;Jana',
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
                        @if($data['issearch'])
                            <div class="text-center">
                                {{ Form::button(
                                    '<i class="fa fa-file-excel-o"></i> Muat Turun Excel',
                                    [
                                        'type' => 'submit',
                                        'class' => 'btn btn-rounded btn-primary',
                                        'name' => 'generate',
                                        'value' => 'excel',
                                        'formtarget' => '_blank'
                                    ]
                                ) }}
                                {{ Form::button(
                                    '<i class="fa fa-file-pdf-o"></i> Muat Turun PDF',
                                    [
                                        'type' => 'submit',
                                        'class' => 'btn btn-rounded btn-danger btn-outline',
                                        'name' => 'generate',
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
    @if($data['issearch'])
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
                            <h4 class="text-center">@yield('title')</h4>
                            <h4 class="text-center">{{ $data['datetext'] ?? '' }}</h4>
                            <h4 class="text-center">{{ $data['statetext'] ?? '' }}</h4>
                        </div>
                        <div class="table-responsive">
                            @includeIf('report.ad52.report2.table')
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
                startDate: '01-01-2013',
                endDate: '+1d'
            });
        });

        function changeTextColor(element) {
            element.style.color = 'purple';
        }
    </script>
@endsection
