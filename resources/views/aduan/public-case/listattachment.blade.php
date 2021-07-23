@extends('layouts.main_public')
<?php
use App\Ref;
?>
@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">Senarai Bahan Bukti</div>
    <!--<div class="ibox-content">-->
    <div class="row">
        <div class="col-sm-12">
            @include('public-case._tab')
        </div>
    </div>
    <div class="ibox-content">
        <br>
        <!--<form enctype="multipart/form-data" id="modal_form_id"  method="POST" action="">-->
        {!! Form::open(['url' => ['public-case/updateattach', $mPcase->CA_CASEID], 'class' => 'form-horizontal', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}{{ method_field('PATCH') }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="doc_title" class="col-sm-4 control-label required">@lang('public-case.case.doc_title')</label>
                        <div class="col-sm-8">
                            {{ Form::text('doc_title','', ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('file_name'))
                            <span class="help-block"><strong>@lang('public-case.validation.doc_title')</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                        <!--<input type="file" name="documents">-->
                    <div class="form-group">
                        <label for="file_name" class="col-sm-3 control-label required">@lang('public-case.attachment.file_name')</label>
                        <div class="col-sm-6">
                                    {{ Form::text('file_name', null, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="file_name" class="col-sm-3 control-label required"></label>
                        <div class="col-sm-6">
                            <span class="input-group-addon btn btn-default btn-file">
                            {{ Form::file('file_doc', ['class' => 'fileinput-filename','id' => 'file_doc']) }}
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" onclick = "return resetFunc()" data-dismiss="fileinput">Remove</a>
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="remarks" class="col-sm-4 control-label required">@lang('public-case.attachment.remarks')</label>
                        <div class="col-sm-8">
                            {{ Form::textarea('remarks','', ['class' => 'form-control input-sm', 'rows' => '2']) }}
                            @if ($errors->has('doc_title'))
                            <span class="help-block"><strong>@lang('public-case.validation.remarks')</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-sm-12" align="center">
                    {{ Form::submit('Hantar', ['name' => 'btnHantar','class' => 'btn btn-success btn-sm']) }}
                    {{ Form::submit('Simpan', ['name' => 'btnSimpan','class' => 'btn btn-primary btn-sm']) }}
                    <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}">Kembali</a>
                </div>
            </div>
            {!! Form::close() !!}
        <!--</form>-->
        <br>
        <!--<div class="row">-->
            <table id="attach-table" class="table table-striped table-bordered table-hover dataTables-example" >
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tajuk</th>
                        <!--<th>No Aduan</th>-->
                        <!--<th>Nama Fail</th>-->
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        <!--</div>-->
    </div>
</div>
@stop

@section('script_datatable')
<!--<script type="text/javascript">
 $(function () {
    var oTable = $('#attach-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ items.',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(filtered from _MAX_ total records)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('public-case/get_datatable_attach',$mPcaseDoc->CC_CASEID)}}",
                data: function (d) {
                    d.doc_title = $('#doc_title').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'doc_title', name: 'doc_title', searchable: false, orderable: false},
//                {data: 'descr', name: 'descr'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
    });
</script>-->
<script type="text/javascript">
    $(function () {
        var oTable = $('#attach-table').DataTable({
//            processing: true,
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
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Nothing found - sorry',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ items.',
                infoEmpty: 'No records available',
                infoFiltered: '(filtered from _MAX_ total records)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('public-case/get_datatable_attach',$mPcaseDoc->CC_CASEID)}}",
                data: function (d) {
//                    d.name = $('#name').val();
//                    d.username = $('#username').val();
//                    d.status = $('#status').val();
//                    d.email = $('#email').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'doc_title', name: 'doc_title', searchable: false, orderable: false},
//                {data: 'username', name: 'username'},
//                {data: 'email', name: 'email'},
//                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
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
</script>
@stop