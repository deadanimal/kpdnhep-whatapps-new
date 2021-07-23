@extends('layouts.main')
<?php
    use App\Pertanyaan\PertanyaanAdmin;
?>
@section('content')
<h2>Senarai Pertanyaan / Cadangan</h2>
<div class="ibox-content" style="padding-bottom: 15px !important">
        {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {{ Form::label('AS_ASKID', 'No. Pertanyaan/Cadangan', ['class' => 'col-sm-5 control-label']) }}
                    <div class="col-sm-7">
                        {{ Form::text('AS_ASKID', '', ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('AS_SUMMARY', 'Keterangan Pertanyaan/Cadangan', ['class' => 'col-sm-5 control-label']) }}
                    <div class="col-sm-7">
                        {{ Form::text('AS_SUMMARY', '', ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {{ Form::label('AS_ASKSTS', 'Status', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-9">
                        {{ Form::select('AS_ASKSTS', PertanyaanAdmin::GetListStatus(), 2, ['class' => 'form-control input-sm', 'id' => 'AS_ASKSTS']) }}
                    </div>
                </div>
                <div class="form-group" id="rcvdt">
                    {{ Form::label('AS_RCVDT', 'Tarikh Terima', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-9">
                        <div class="input-daterange input-group" id="datepicker">
                            {{ Form::text('date_start', '', ['class' => 'form-control input-sm', 'id' => 'date_start']) }}
                            <span class="input-group-addon">hingga</span>
                            {{ Form::text('date_end', '', ['class' => 'form-control input-sm', 'id' => 'date_end']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div align="center">
            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
            {{ link_to('pertanyaan-admin', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
        </div>
        {!! Form::close() !!}
    </div>

<div class="ibox-content" style="padding-bottom: 15px !important">
    <div class="row">
        <div class="col-sm-12">
            <a href="{{ url('pertanyaan-admin/create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Pertanyaan / Cadangan Baru</a>
        </div>
    </div>
    <div class="table-responsive">
        <table style="width: 100%" id="index-table" class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Pertanyaan/Cadangan</th>
                    <th>Keterangan Pertanyaan/Cadangan</th>
                    <th>Nama</th>
                    <th>Tarikh Terima</th>
                    <th>Tarikh Dijawab</th>
                    <th>Dijawab Oleh</th>
                    <th>Status</th>
                    <th><center>Hari</center></th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<!-- Modal Start -->
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
<!-- Modal End --> 
@stop

@section('script_datatable')
<script type="text/javascript">
function ShowSummary(ASKID)
{
    $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('pertanyaan-admin.showsummary','') }}" + "/" + ASKID);
}
    var indexTable = $('#index-table').DataTable({
            processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
                pageLength: 50,
                pagingType: "full_numbers",
                dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
                language: {
                    lengthMenu: 'Memaparkan _MENU_ rekod',
                    zeroRecords: 'Tiada rekod ditemui',
                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                    infoEmpty: 'Tiada rekod ditemui',
//                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                    infoFiltered: '',
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir',
                    },
                },
            ajax: {
                url: "{{ url('pertanyaan-admin/getdatattable')}}",
                data: function (d) {
                        d.AS_ASKID = $('#AS_ASKID').val();
                        d.AS_SUMMARY = $('#AS_SUMMARY').val();
                        d.AS_ASKSTS = $('#AS_ASKSTS').val();
                        d.date_start = $('#date_start').val();
                        d.date_end = $('#date_end').val();
                    }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false},
                {data: 'AS_ASKID', name: 'AS_ASKID'},
                {data: 'AS_SUMMARY', name: 'AS_SUMMARY'},
//                {data: 'AS_USERID', name: 'AS_USERID'},
                {data: 'AS_NAME', name: 'AS_NAME'},
                {data: 'AS_RCVDT', name: 'AS_RCVDT'},
                {data: 'AS_COMPLETEDT', name: 'AS_COMPLETEDT'},
                {data: 'AS_COMPLETEBY', name: 'AS_COMPLETEBY'},
                {data: 'AS_ASKSTS', name: 'AS_ASKSTS'},
                {data: 'tempoh', name: 'tempoh', searchable: false, orderable: false},
                {data: 'action', name: 'action', width: '1%', searchable: false, orderable: false}
            ],
            buttons: [
                {extend: 'excel'},
                {extend: 'pdf'},
                {extend: 'print',text: 'Cetak', customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ]
        });
        
        $('#search-form').on('submit', function(e) {
            indexTable.draw();
            e.preventDefault();
        });
        
        $('#rcvdt .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
</script>
@stop
