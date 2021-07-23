@extends('layouts.main')
<?php
    use App\Ref;
?>

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Penyiasatan Aduan (Integriti)</h2>
            @include('nota')
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('IN_CASEID', '', ['class' => 'form-control input-sm','id' => 'IN_CASEID']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('IN_AGAINSTNM', 'Nama Yang Diadu', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('IN_AGAINSTNM', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('IN_SUMMARY', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('IN_RCVDT', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('IN_RCVDT', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                            {{ link_to('integritisiasat', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                
                <div class="table-responsive">
                    <table id="siasat-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>No. Aduan</th>
                                <th>Aduan</th>
                                <th>Nama Yang Diadu</th>
                                <th>Status Aduan</th>
                                <th>Tarikh Penerimaan</th>
                                <!-- <th><center>Hari </center></th> -->
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
<div id="modal-show-summary-integriti" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummaryIntegriti'></div>
    </div>
</div>
<!-- Modal End -->
@stop

@section('script_datatable')
<script type="text/javascript">

    function showsummaryintegriti(id)
    {
        $('#modal-show-summary-integriti')
            .modal("show")
            .find("#ModalShowSummaryIntegriti")
            .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
    }

    $(function() {
        var siasat = $('#siasat-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            pageLength: 50,
            dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
            buttons: [
//                {extend: 'excel'},
                {
                    extend: 'excel',
                    title: 'Senarai Penyiasatan Aduan',
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
                    last: 'Terakhir'
                }
            },
            ajax: {
                url: "{{ route('integritisiasat.getdatatable') }}",
                data: function (d) {
                    d.IN_CASEID = $('#IN_CASEID').val();
                    d.IN_SUMMARY = $('#IN_SUMMARY').val();
                    d.IN_AGAINSTNM = $('#IN_AGAINSTNM').val();
                    d.IN_RCVDT = $('#IN_RCVDT').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
//                {data: 'IN_CASEID', name: 'IN_CASEID'},
                {data: 'IN_CASEID', render: function (data, type) {
                    return type === 'export' ?
                        "' " + data :
                        data;
                }, name: 'IN_CASEID'},
                {data: 'IN_SUMMARY', name: 'IN_SUMMARY', width: '35%'},
                {data: 'IN_AGAINSTNM', name: 'IN_AGAINSTNM'},
                {data: 'IN_INVSTS', name: 'IN_INVSTS'},
                {data: 'IN_RCVDT', name: 'IN_RCVDT'},
                // {data: 'tempoh', name: 'tempoh'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });

        $('#search-form').on('submit', function(e) {
            siasat.draw();
            e.preventDefault();
        });
    });
    
    $('#IN_RCVDT').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
    
</script>
@stop