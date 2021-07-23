@extends('layouts.main')
<?php

use App\Ref;
use App\Holiday;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Cuti</h2>
                <div class="ibox-content">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('holiday_name', 'Nama Cuti', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-6">
                                    {{ Form::text('holiday_name', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('holiday_date', 'Tahun', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-6">
                                    {{ Form::select('year', Holiday::GetListYear(true), null, ['class' => 'form-control input-sm' , 'id' => 'year']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('holiday_date', 'Bulan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-6">
                                    {{ Form::select('month', Ref::GetList('206',true), null, ['class' => 'form-control input-sm', 'id' => 'month']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
<!--                            <div class="form-group">
                                {{ Form::label('work_code', 'Sepenuh Hari/Separa Hari', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('work_code', Ref::GetList('146',true), null, ['class' => 'form-control input-sm', 'id' => 'work_code']) }}
                                </div>
                            </div>-->
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('state_code','Negeri', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    {{ Form::select('state_code', Ref::GetList('17',true), null, ['class' => 'form-control input-sm', 'id' => 'state_code']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('repeat_yearly','Berulang Setiap Tahun?', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    {{ Form::select('repeat_yearly',array
                                        ( '' => '-- SILA PILIH --',
                                        '1' => 'YA', 
                                        '2' => 'TIDAK'), '',array('class' => 'form-control input-sm'))}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                        <a class="btn btn-default btn-sm" href="{{ url('holiday')}}">Semula</a>
                    </div>
                    {!! Form::close() !!}
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ url('holiday/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Cuti Baru</a>
                        </div>
                        <br>
                    </div>
                    <div class="table-responsive">
                        <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Cuti</th>
                                    <th>Tarikh Cuti</th>
                                    <th>Negeri</th>
                                    <!--<th>Cuti</th>-->
                                    <th>Berulang Setiap Tahun?</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    

@stop

@section('script_datatable')
<!-- Page-Level Scripts -->
<script type="text/javascript">
    $(function () {
        var oTable = $('#users-table').DataTable({
//            processing: true,
//            serverSide: true,
//            aaSorting: [],
//            bFilter: false,
//            bLengthChange: false,
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
//            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
            rowId: 'id',
            bStateSave: true,
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan dari _MAX_ total rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
//            ajax: 'http://localhost:1338/eaduanV2/public/holiday/get_datatable',
            ajax: {
                url: "{{ url('holiday/getdatatable')}}",
//                url: 'http://localhost:1338/eaduanV2/public/holiday/get_datatable',
                data: function (d) {
                    d.holiday_name = $('#holiday_name').val();
                    d.year = $('#year').val();
                    d.month = $('#month').val();
                    d.work_code = $('#work_code').val();
                    d.state_code = $('#state_code').val();
                    d.repeat_yearly = $('#repeat_yearly').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'holiday_name', name: 'holiday_name'},
                {data: 'holiday_date', name: 'holiday_date'},
                {data: 'state_code', name: 'state_code'},
//                {data: 'work_code', name: 'work_code'},
                {data: 'repeat_yearly', name: 'repeat_yearly'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    $('#data_6 .input-daterange').datepicker({
        format: 'Y-m-d',
//        format: 'yyyy-mm-dd',
//        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
</script>
@stop