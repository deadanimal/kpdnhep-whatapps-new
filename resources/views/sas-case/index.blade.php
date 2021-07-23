@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Senarai Aduan Khas</h2>
            <div class="ibox-content">
                <form method="POST" id="search-form" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm','id' => 'CA_CASEID']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_NAME', 'Nama Pengadu', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('CA_NAME', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_SUMMARY', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="col-sm-6">-->
                            <!--                                <div class="form-group">
                                                                {{ Form::label('CA_RCVDT', 'Tarikh Terima', ['class' => 'col-sm-4 control-label']) }}
                                                                <div class="col-sm-8">
                                                                    {{ Form::text('CA_RCVDT', '', ['class' => 'form-control input-sm']) }}
                                                                </div>
                                                            </div>-->
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
                        <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                        <a class="btn btn-default btn-sm" href="{{ url('sas-case')}}">Semula</a>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-9">
                        <a href="{{ url('sas-case/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Aduan Khas Baru</a>
                    </div>
                    <br>
                </div>
                <div class="table-responsive">
                    <table style="width: 100%" id="sas-case-table" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>No. Aduan</th>
                                <th>Keterangan Aduan</th>
                                <th>Nama Pengadu</th>
                                <th>Tarikh Penerimaan</th>
                                <!--<th>Status Aduan</th>-->
                                <!--<th>Status Perkembangan</th>-->
                                <th>Penerima</th>
                                <th>Penyiasat</th>
                                <th>Negeri Diadu</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
<div id="modal-show-invby" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowInvBy'></div>
    </div>
</div>
<!-- Modal End -->
@stop

@section('script_datatable')
<script type="text/javascript">

    function ShowSummary(CASEID)
    {
//    var CASEID = $("#penugasan-table a").value;
      $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
    }
    
    function ShowInvBy(id)
    {
        $('#modal-show-invby').modal("show").find("#ModalShowInvBy").load("{{ route('carian.showinvby','') }}" + "/" + id);
    }

    $('#CA_RCVDT').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });

    $('#date .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });

    $(function () {
        var CaseTable = $('#sas-case-table').DataTable({
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
                {extend: 'print',text: 'Cetak', customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ],
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
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
//            ajax: 'http://localhost:1338/eaduanV2/public/ref/get_datatable',
            ajax: {
                url: "{{ url('sas-case/getdata')}}",
                data: function (d) {
                    d.caseid = $('#CA_CASEID').val();
                    d.summary = $('#CA_SUMMARY').val();
                    d.name = $('#CA_NAME').val();
//                    d.rcvdate = $('#CA_RCVDT').val();
                    d.CA_RCVDT_FROM = $('#CA_RCVDT_FROM').val();
                    d.CA_RCVDT_TO = $('#CA_RCVDT_TO').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CA_CASEID', name: 'CA_CASEID'},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                {data: 'CA_NAME', name: 'CA_NAME'},
                {data: 'CA_RCVDT', name: 'CA_RCVDT'},
//                {data: 'CA_INVSTS', name: 'CA_INVSTS'},
//                {data: 'CA_CASESTS', name: 'CA_CASESTS'},
                {data: 'CA_RCVBY', name: 'CA_RCVBY'},
                {data: 'CA_INVBY', name: 'CA_INVBY'},
                {data: 'CA_AGAINST_STATECD', name: 'CA_AGAINST_STATECD'},
//                {data: 'sort', name: 'sort'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });

        $('#search-form').on('submit', function (e) {
            CaseTable.draw();
            e.preventDefault();
        });
    });
</script>
@stop