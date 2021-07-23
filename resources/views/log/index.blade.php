@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Audit Trail</h2>
            <div class="ibox-content">
                {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-group" id="data_5">
                                {{ Form::label('created_at', 'Tarikh Dari', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('date_start', '', ['class' => 'form-control input-sm', 'id' => 'date_start']) }}
                                        <span class="input-group-addon">hingga</span>
                                        {{ Form::text('date_end', '', ['class' => 'form-control input-sm', 'id' => 'date_end']) }}
                                    </div>
                                </div>
                            </div>
                        <div class="form-group">
                            {{ Form::label('details', 'Penerangan', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::text('details', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('parameter', 'Parameter', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::text('parameter', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('ip_address', 'Alamat IP', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('ip_address', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                             {{ Form::label('created_by', 'Nama Pengguna', ['class' => 'col-sm-4 control-label']) }}
                             <div class="col-sm-8">
                                 {{ Form::text('created_by', '', ['class' => 'form-control input-sm']) }}
                             </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" align="center">
                    <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                    <a class="btn btn-default btn-sm" id="btnreset" href="{{-- url('ref/listparam',$mRef->id) --}}">Semula</a>
                </div>
                {!! Form::close() !!}
                <table id="log-table" class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pengguna</th>
                            <th>Detail</th>
                            <th>Parameter</th>
                            <th>Alamat IP</th>
                            <th>Tarikh Dicipta</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
    $(function () {
        var oTable = $('#log-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
//            order: [[ 3, 'asc' ]],
            bLengthChange: false,
            rowId: 'id',
            bStateSave: true,
            pagingType: "full_numbers",
            dom: '<"top"i>rt<"bottom"flp><"clear">',
//            dom: 'T<"clear">lfrtip',
//            tableTools: {
//                "sSwfPath": "{{ url('js/plugins/dataTables/swf/copy_csv_xls_pdf.swf') }}"
//            },
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod.',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('log/getdatatable')}}",
                data: function (d) {
                    d.details = $('#details').val();
                    d.created_by = $('#created_by').val();
                    d.parameter = $('#parameter').val();
                    d.ip_address = $('#ip_address').val();
                    d.date_start = $('#date_start').val();
                    d.date_end = $('#date_end').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'name', name: 'name'},
                {data: 'details', name: 'details'},
                {data: 'parameter', name: 'parameter'},
                {data: 'ip_address', name: 'ip_address'},
                {data: 'created_at', name: 'created_at'},
            ]
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        
        $('#btnreset').on('click', function (e) {
//            alert("Berjaya");
//            $('#users-table').DataTable({
//                bStateSave: false
//            });
            oTable.order.neutral().draw();
            oTable.page('first');
            oTable.fnSort([]);
            oTable.state.clear();
//            oTable.draw();
            e.preventDefault();
        });
        
    });
    
    $('#data_5 .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
//        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
</script>
@stop