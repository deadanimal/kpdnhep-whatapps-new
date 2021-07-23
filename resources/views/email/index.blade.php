@extends('layouts.main')
<?php
    use App\Ref;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Templat Emel</h2>
                <div class="ibox-content">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('title', 'Tajuk', ['class' => 'col-md-1 control-label']) }}
                                    <div class="col-sm-11">
                                        {{ Form::text('title', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('email_type', 'Jenis', ['class' => 'col-md-1 control-label']) }}
                                    <div class="col-sm-11">
                                        {{ Form::select('email_type', Ref::GetList('149', true), null, ['class' => 'form-control input-sm', 'id' => 'email_type']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('status', Ref::GetList('256', true), null, ['class' => 'form-control input-sm', 'id' => 'status']) }}
<!--                                        {{ Form::select('status', [
                                            '' => '-- SILA PILIH --', '1' => 'AKTIF', '2' => 'TIDAK AKTIF'
                                            ], null, ['class' => 'form-control input-sm']) }}-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                            <a class="btn btn-default btn-sm" href="{{ url('email')}}">Semula</a>
                        </div>
                    {!! Form::close() !!}
                    <div class="row">
                        <div class="col-md-9">
                            <a href="{{ url('email/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Templat Emel</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="email-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tajuk</th>
                                    <th>Jenis</th>
                                    <!--<th>Kategori</th>-->
                                    <th>Status</th>
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
    $(function() {
        var oTable = $('#email-table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [],
            // bFilter: false, disable search field
            bFilter: false,
            // bLengthChange: true, enable records selection per page
            bLengthChange: true,
            pagingType: "full_numbers",
            rowId: 'id',
            // bStateSave: true, simpan pilihan paparan rekod
            bStateSave: true,
//            dom: '<"top"i>rt<"bottom"flp><"clear">',
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" + 
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod.',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan dari _MAX_ total rekod)',
                paginate: {
//                    previous: "<",
//                    next: ">"
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                }
            },
            ajax: {
                url: "{{ url('email/get_datatable')}}",
                data: function (d) {
                    d.title = $('#title').val();
                    d.email_type = $('#email_type').val();
                    d.status = $('#status').val();
                }
            },
            columns: [
//                {data: 'id', name: 'id', 'width': '5%'},
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'title', name: 'title'},
                {data: 'email_type', name: 'email_type'},
//                {data: 'email_cat', name: 'email_cat'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
        
        $('#search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });
</script>
@stop