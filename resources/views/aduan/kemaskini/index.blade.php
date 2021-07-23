@extends('layouts.main')
<?php
    use App\Ref;
    use App\Aduan\AdminCase;
?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Kemaskini Aduan (Maklumat Tidak Lengkap)</h2>
                @include('nota')
                <div class="ibox-content">
                    {{ Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) }}
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm','id' => 'CA_CASEID']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_SUMMARY', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_NAME', 'Nama Pengadu', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_NAME', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group" id="date">
                                    {{ Form::label('CA_RCVDT_FROM', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <div class="input-daterange input-group" id="datepicker">
                                            {{ Form::text('CA_RCVDT_FROM', '', ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_FROM']) }}
                                            <span class="input-group-addon">hingga</span>
                                            {{ Form::text('CA_RCVDT_TO', '', ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_TO']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                            {{ link_to('kemaskini', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
                                    <th><center>Hari</center></th>
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
<!-- Modal End -->
@stop

@section('script_datatable')
<script type="text/javascript">
        
    function ShowSummary(CASEID)
    {
//        var CASEID = $("#kemaskini-table a").value;
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
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
                    url: "{{ url('kemaskini/getdatatable') }}",
                    data: function (d) {
                        d.CA_CASEID = $('#CA_CASEID').val();
                        d.CA_SUMMARY = $('#CA_SUMMARY').val();
                        d.CA_NAME = $('#CA_NAME').val();
                        d.CA_RCVDT_FROM = $('#CA_RCVDT_FROM').val();
                        d.CA_RCVDT_TO = $('#CA_RCVDT_TO').val();
                        d.SEARCH = $('#SEARCH').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
//                    {data: 'CA_CASEID', name: 'CA_CASEID'},
                    {data: 'CA_CASEID', render: function (data, type) {
                        return type === 'export' ?
                            "' " + data :
                            data;
                    }, name: 'CA_CASEID'},
                    {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                    {data: 'CA_NAME', name: 'CA_NAME'},
                    {data: 'CA_RCVDT', name: 'CA_RCVDT'},
                    {data: 'tempoh', name: 'tempoh'},
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