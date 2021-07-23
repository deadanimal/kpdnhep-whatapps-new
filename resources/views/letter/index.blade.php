@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Templat Surat</h2>
                <div class="ibox-content">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('title', 'Tajuk', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::text('title', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('letter_type', 'Jenis', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('letter_type', Ref::GetList('143', true), null, ['class' => 'form-control input-sm', 'id' => 'letter_type']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('letter_code', 'Kod', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('letter_code',  Ref::GetList('292', true), null, ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('status', Ref::GetList('256', true), null, ['class' => 'form-control input-sm', 'id' => 'status']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                            <a class="btn btn-default btn-sm" href="{{ url('letter')}}">Semula</a>
                        </div>
                    {!! Form::close() !!}
                    <div class="row">
                        <div class="col-md-9">
                            <a href="{{ url('letter/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Templat Surat</a>
                        </div>
                        <br>
                    </div>
                    <div class="table-responsive">
                        <table id="letter-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tajuk</th>
                                    <th>Jenis</th>
                                    <th>Kod</th>
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
        var oTable = $('#letter-table').DataTable({
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
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
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
//            ajax: 'http://localhost:1338/eaduanV2/public/letter/get_datatable',
            ajax: {
                url: "{{ url('letter/get_datatable')}}",
//                url: 'http://localhost:1338/eaduanV2/public/letter/get_datatable',
                data: function (d) {
                    d.title = $('#title').val();
                    d.letter_type = $('#letter_type').val();
                    d.letter_code = $('#letter_code').val();
                    d.status = $('#status').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'title', name: 'title'},
                {data: 'letter_type', name: 'letter_type'},
                {data: 'letter_code', name: 'letter_code'},
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