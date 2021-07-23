@extends('layouts.main')
<?php
    use App\Ref;
    use App\CallCenterCase;
    use App\Branch;
?>
@section('content')
<div class="row">
<div class="col-lg-12">
<div class="ibox float-e-margins">
<h2>Senarai Aduan Call Center </h2>
@include('nota')
<div class="ibox-content">
<form method="POST" id="search-form" class="form-horizontal">
<div class="form-group">
    <div class="col-sm-6">
        <div class="form-group">
            {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
            <div class="col-sm-6">
             {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm', 'id' => 'CA_CASEID']) }}
             {{-- Form::hidden('SEARCH', 0, ['class' => 'form-control input-sm', 'id' => 'SEARCH']) --}}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('CA_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
            <div class="col-sm-6">
               {{ Form::text('CA_SUMMARY', '', ['class' => 'form-control input-sm']) }}
            </div>
        </div>
        </div>
        <div class="col-sm-6">
        <div class="form-group">
         {{ Form::label('CA_NAME', 'Nama Pengadu', ['class' => 'col-sm-5 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text('CA_NAME', '', ['class' => 'form-control input-sm']) }}
        </div>
        </div>
        <div class="form-group">
            {{ Form::label('CA_RCVDT', 'Tarikh Penerimaan', ['class' => 'col-sm-5 control-label']) }}
            <div class="col-sm-6">
                {{ Form::text('CA_RCVDT', '', ['class' => 'form-control input-sm']) }}
            </div>
        </div>
        </div>
        </div>
        <div class="form-group" align="center">
            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
            <a class="btn btn-default btn-sm" href="{{ url('call-center-case')}}">Semula</a>
        </div>
    </form>
    <div class="row">
        <div class="col-md-9">
            <a href="{{ url('call-center-case/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Aduan Baru</a>
        </div>
        <br>
    </div>
                    <div class="table-responsive">
                        <table id="case-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Aduan</th>
                                    <th>Aduan</th>
                                    <th>Nama Pengadu</th>
                                    <th>Tarikh Penerimaan</th>
                                    <th>Hari</th>
                                    <th>Status</th>
                                    <th></th>
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
        function ShowSummary(CASEID)
{
    $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
}
    $('#CA_RCVDT').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
    
    $(function() {
        var oTable = $('#case-table').DataTable({
            processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
                pagingType: "full_numbers",
                pageLength: 50,
//                order: [ 5, 'desc' ],
            dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            buttons: [
                    {extend: 'excel'},
                    {extend: 'pdf'},
                    {extend: 'print',text: 'Cetak', customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    }
                ],
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
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
                url: "{{ url('call-center-case/getdatatable') }}",
                data: function (d) {
//                    d.SEARCH = $('#SEARCH').val();
                    d.CA_CASEID = $('#CA_CASEID').val();
                    d.CA_SUMMARY = $('#CA_SUMMARY').val();
                    d.CA_NAME = $('#CA_NAME').val();
                    d.CA_RCVDT =$('#CA_RCVDT').val();
                },
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '2%', searchable: false, orderable: false},
                {data: 'CA_CASEID', name: 'CA_CASEID'},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY', width: '35%'},
                {data: 'CA_NAME', name: 'CA_NAME'},
                {data: 'CA_RCVDT', name: 'CA_RCVDT'},
                {data: 'tempoh', name: 'tempoh', width: '5%'},
                {data: 'CA_INVSTS', name: 'CA_INVSTS'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
        
        $('#search-form').on('submit', function(e) {
//            $('#SEARCH').val(1);
            oTable.draw();
            e.preventDefault();
        });
    });
</script>
@stop

