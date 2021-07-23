@extends('layouts.main')
<?php
    use App\Ref;
    use App\Aduan\AdminCase;
?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Kemaskini Aduan Integriti (Maklumat Tidak Lengkap)</h2>
                <!-- {{-- @include('nota') --}} -->
                <div class="ibox-content">
                    {{ Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) }}
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('IN_CASEID', '', ['class' => 'form-control input-sm','id' => 'IN_CASEID']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('IN_SUMMARY', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('IN_NAME', 'Nama Pengadu', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('IN_NAME', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group" id="date">
                                    {{ Form::label('IN_RCVDT_FROM', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <div class="input-daterange input-group" id="datepicker">
                                            {{ Form::text('IN_RCVDT_FROM', '', ['class' => 'form-control input-sm', 'id' => 'IN_RCVDT_FROM']) }}
                                            <span class="input-group-addon">hingga</span>
                                            {{ Form::text('IN_RCVDT_TO', '', ['class' => 'form-control input-sm', 'id' => 'IN_RCVDT_TO']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                            {{ link_to('integritikemaskini', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    {{ Form::close() }}
                    <div class="table-responsive">
                        <table id="kemaskini-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>No. Aduan</th>
                                    <th>Keterangan Aduan</th>
                                    <th>Nama Pengadu</th>
                                    <th>Tarikh Penerimaan</th>
                                    <!-- <th><center>Hari</center></th> -->
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
<!-- Modal Start -->
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
<div id="modal-show-summary-integriti" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummaryIntegriti'></div>
    </div>
</div>
<!-- Modal End -->
@stop

@section('script_datatable')
<script type="text/javascript">
        
    function ShowSummary(CASEID)
    {
//        var CASEID = $("#kemaskini-table a").value;
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
    }

    function showsummaryintegriti(id)
    {
        $('#modal-show-summary-integriti')
            .modal("show")
            .find("#ModalShowSummaryIntegriti")
            .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
    }

        $(function() {
            var oTable = $('#kemaskini-table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
//                order: [ 6, 'desc' ],
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
                    // infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                    infoFiltered: '',
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir',
                    },
                },
                ajax: {
                    // url: "{{-- url('kemaskini/getdatatable') --}}",
                    url: "{{ url('integritikemaskini/getdatatable') }}",
                    data: function (d) {
                        d.IN_CASEID = $('#IN_CASEID').val();
                        d.IN_SUMMARY = $('#IN_SUMMARY').val();
                        d.IN_NAME = $('#IN_NAME').val();
                        d.IN_RCVDT_FROM = $('#IN_RCVDT_FROM').val();
                        d.IN_RCVDT_TO = $('#IN_RCVDT_TO').val();
                        d.SEARCH = $('#SEARCH').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                    {data: 'IN_CASEID', name: 'IN_CASEID'},
                    // {data: 'IN_CASEID', render: function (data, type) {
                    //     return type === 'export' ?
                    //         "' " + data :
                    //         data;
                    // }, name: 'IN_CASEID'},
                    {data: 'IN_SUMMARY', name: 'IN_SUMMARY'},
                    {data: 'IN_NAME', name: 'IN_NAME'},
                    {data: 'IN_RCVDT', name: 'IN_RCVDT'},
                    // {data: 'tempoh', name: 'tempoh'},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ],
                buttons: [
//                    {extend: 'excel'},
                    {
                        extend: 'excel',
                        title: 'Senarai Aduan (Maklumat Tidak Lengkap)',
                        exportOptions: { 
                            orthogonal: 'export'
                        }
                    },
                    {extend: 'pdf'},
                    {extend: 'print',text: 'Cetak',customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    }
                ]
            });

            $('#search-form').on('submit', function(e) {
                $('#SEARCH').val(1);
                oTable.draw();
                e.preventDefault();
            });
        });
        
        $('#date .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });
    </script>
@stop