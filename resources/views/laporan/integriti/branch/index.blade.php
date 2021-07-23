@extends('layouts.main')

@section('title')
{{ $vars['title'] }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2>@yield('title')</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ url('dashboard') }}">Dashboard</a>
                        </li>
                        <li>
                            Laporan
                        </li>
                        <li>
                            Integriti
                        </li>
                        <li class="active">
                            <a href="{{ $request->url() }}">
                                <strong>@yield('title')</strong>
                            </a>
                        </li>
                    </ol>
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
                        'url' => $request->url(),
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
                                                {{ Form::text('datestart', date('d-m-Y', strtotime($vars['datestart'])), ['class' => 'form-control']) }}
                                                <span class="input-group-addon">hingga</span>
                                                {{ Form::text('dateend', date('d-m-Y', strtotime($vars['dateend'])), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('location', 'Lokasi Pihak Yang Diadu (PYDA)', ['class' => 'control-label']) }}
                                            {{ Form::select(
                                                'location',
                                                $vars['locations'],
                                                null,
                                                ['class'=>'form-control']
                                            ) }}
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="text-center">
                                            <a href="{{ $request->url() }}" class='btn btn-rounded btn-success btn-outline'>
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
                        @if($vars['is_search'])
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
    @if($vars['is_search'])
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
                                {{ date('d-m-Y', strtotime($vars['datestart'])) }}
                                Hingga 
                                {{ date('d-m-Y', strtotime($vars['dateend'])) }}
                            </h3>
                            <h3>
                                Lokasi Pihak Yang Diadu (PYDA) : {{ $vars['locationname'] }}
                            </h3>
                        </div>
                        <div class="table-responsive">
                            @include('laporan.integriti.branch.table')
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
            });
        });
    </script>
@endsection
