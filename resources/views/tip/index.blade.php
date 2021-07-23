@extends('layouts.main')

@section('title')
Senarai Data Kes
@endsection

@section('content')
    <style>
        .form-control[readonly] {
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
                    {{ Form::open(['id' => 'search-form']) }}
                        <div class="row">
                            <div class="col-md-1 col-lg-3"></div>
                            <div class="col-md-10 col-lg-6">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <i class="fa fa-info-circle"></i>&nbsp;Carian
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {{ Form::label('NO_KES', 'Nombor Kes', ['class' => 'control-label']) }}
                                            {{ Form::text('NO_KES', null, ['class' => 'form-control', 'placeholder' => 'Nombor Kes']) }}
                                        </div>
                                        <div class="form-group" id="date">
                                            {{ Form::label('date_start', 'Tarikh Kejadian', ['class' => 'control-label']) }}
                                            <div class="input-daterange input-group" id="datepicker">
                                                {{ Form::text('date_start', null,
                                                    [
                                                        'class' => 'form-control',
                                                        'id' => 'date_start',
                                                        'placeholder' => 'HH-BB-TTTT',
                                                        'readonly' => true
                                                    ]
                                                ) }}
                                                <span class="input-group-addon">hingga</span>
                                                {{ Form::text('date_end', null,
                                                    [
                                                        'class' => 'form-control',
                                                        'id' => 'date_end',
                                                        'placeholder' => 'HH-BB-TTTT',
                                                        'readonly' => true
                                                    ]
                                                ) }}
                                            </div>
                                        </div>
                                        {{ Form::hidden('search_ind', 1, [
                                            'class' => 'form-control',
                                            'id' => 'search_ind',
                                            'readonly' => true
                                        ]) }}
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
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Senarai</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('tips.create') }}" class="btn btn-success" style="color:#FFF;">
                            <i class="fa fa-plus"></i> Data Kes
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="tips-table" style="width: 100%">
                            <thead>
                                @include('tip.table_header')
                            </thead>
                            <tfoot>
                                @include('tip.table_header')
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_datatable')
    <script type="text/javascript">
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
            var tipsTable = $('#tips-table').DataTable({
                aaSorting: [],
                bFilter: false,
                processing: true,
                responsive: true,
                serverSide: true,
                language: {
                    lengthMenu: 'Paparan _MENU_ rekod',
                    zeroRecords: 'Tiada rekod ditemui',
                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                    infoEmpty: 'Tiada rekod ditemui',
                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                    processing: "<span class=\"font-md\"></span><i class=\"fa fa-spinner fa-spin ml5\"></i>",
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir'
                    },
                    search: 'Carian', 
                    searchPlaceholder: 'Carian'
                },
                ajax: {
                    url: "{{ url('tips/dt') }}",
                    data: function (d) {
                        d.date_start = $('#search-form #date_start').val();
                        d.date_end = $('#search-form #date_end').val();
                        d.no_kes = $('#search-form #NO_KES').val();
                        d.search_ind = $('#search-form #search_ind').val();
                    },
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false },
                    { data: 'NO_KES', name: 'NO_KES' },
                    { data: 'ASAS_TINDAKAN', name: 'ASAS_TINDAKAN' },
                    { data: 'TKH_KEJADIAN', name: 'TKH_KEJADIAN' },
                    { data: 'action', name: 'action', width: '10%', searchable: false, orderable: false }
                ]
            });
            $('#search-form').on('submit', function (e) {
                $('#search-form #search_ind').val(1);
                tipsTable.draw();
                e.preventDefault();
            });
        });
    </script>
@endsection
