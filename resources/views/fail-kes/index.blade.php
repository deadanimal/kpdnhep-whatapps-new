@extends('layouts.main')
<?php use App\Ref;?>
@section('content')
    <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <h2>Muatnaik Fail Kes</h2>
                    <div class="ibox-content">
                        <form method="POST" id="search-form" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('title', 'Nama Fail', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::text('title', '', ['class' => 'form-control input-sm']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('remark', 'Catatan', ['class' => 'col-sm-2 control-label']) }}
                                        <div class="col-sm-10">
                                            {{ Form::text('remark', '', ['class' => 'form-control input-sm']) }}
                                        </div>
                                    </div>
<!--                                    <div class="form-group">
                                        {{-- Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) --}}
                                        <div class="col-sm-10">
                                            {{-- Form::text('status', '', ['class' => 'form-control input-sm', 'id' => 'status']) --}}
                                        </div>
                                    </div>-->
                                </div>
                                <div class="col-sm-6">
                                    
                                </div>
                            </div>
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                                <a class="btn btn-default btn-sm" href="{{ route('fail-kes.index')}}">Semula</a>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <a onclick="Create()" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Fail Kes</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table style="width: 100%" id="fail-kes-table" class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>Nama Fail</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Create Attachment Start -->
<div class="modal fade" id="modal-create" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalCreateContent'></div>
    </div>
</div>

@stop

@section('script_datatable')
<script type="text/javascript">
    
    function Create() {
        $('#modal-create').modal("show").find("#modalCreateContent").load("{{ route('fail-kes.create') }}");
        return false;
    }

$(function() {
    var FailKesTable = $('#fail-kes-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        pagingType: "full_numbers",
        pageLength: 50,
//            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
        dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
//            dom: 'T<"clear">lfrtip',
//            tableTools: {
//                "sSwfPath": "{{ url('js/plugins/dataTables/swf/copy_csv_xls_pdf.swf') }}"
//            },
//            dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'ExampleFile'},
            {extend: 'print',customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                }
            }
        ],
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
                last: 'Terakhir'
            }
        },
        ajax: {
            url: "{{ url('fail-kes/getdatatable')}}",
            data: function (d) {
                d.title = $('#title').val();
                d.remark = $('#remark').val();
            }
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false},
            {data: 'doc_attach_id', name: 'doc_attach_id'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', width: '9%', searchable: false, orderable: false}
        ]
    });
    
    $('#search-form').on('submit', function(e) {
        FailKesTable.draw();
        e.preventDefault();
    });
});

</script>
@stop
