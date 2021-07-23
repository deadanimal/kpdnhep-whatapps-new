@extends('layouts.main')
<?php use App\Ref;?>
@section('content')
<!--<style>
    table.table-bordered.dataTable tbody td a:visited {
        color: purple !important;
    }
</style>-->
    <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <h2>Senarai Tetapan Mesyuarat JMA (Integriti)</h2>
                    <!-- {{-- @include('nota') --}} -->
                    <div class="ibox-content">
                        <form method="POST" id="search-form" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('IM_MEETINGNUM', 'Bilangan', ['class' => 'col-lg-4 control-label']) }}
                                        <div class="col-lg-8">
                                            {{ Form::text('IM_MEETINGNUM', '', ['class' => 'form-control input-sm', 'id' => 'IM_MEETINGNUM']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('IM_MEETINGDATE', 'Tarikh', ['class' => 'col-lg-4 control-label']) }}
                                        <div class="col-lg-8">
                                            {{ Form::text('IM_MEETINGDATE', '', ['class' => 'form-control input-sm']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('IM_CHAIRPERSON', 'Pengerusi', ['class' => 'col-lg-4 control-label']) }}
                                        <div class="col-lg-8">
                                            {{ Form::text('IM_CHAIRPERSON', '', ['class' => 'form-control input-sm', 'id' => 'IM_CHAIRPERSON']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="IN_INVSTS" class="col-sm-4 control-label">@lang('public-case.case.IN_CASESTS')</label> -->
                                        {{ Form::label('IM_STATUS', 'Status', ['class' => 'col-lg-4 control-label']) }}
                                        <div class="col-lg-8">
                                            <!-- {{-- Form::select('IN_INVSTS', Ref::GetList('292', true) , '', ['class' => 'form-control input-sm', 'id' => 'IN_INVSTS']) --}} -->
                                            {{
                                                Form::select(
                                                    'IM_STATUS', [
                                                        '1' => 'Selesai', 
                                                        '2' => 'Tangguh',
                                                        '0' => 'Batal'
                                                    ], null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '-- Semua Status --'
                                                    ]
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('IN_SUMMARY', '', ['class' => 'form-control input-sm', 'id' => 'IN_SUMMARY']) }}
                                        </div>
                                    </div> -->
                                </div>
                                <!-- <div class="col-sm-6"> -->
                                    <!-- <div class="form-group"> -->
                                        <!-- {{ Form::label('IN_RCVDT', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }} -->
                                        <!-- <div class="col-sm-8"> -->
                                            <!-- {{ Form::text('IN_RCVDT', '', ['class' => 'form-control input-sm']) }} -->
                                        <!-- </div> -->
                                    <!-- </div> -->
                                <!-- </div> -->
                            </div>
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                                <a class="btn btn-default btn-sm" href="{{ url('integritimeeting') }}">Semula</a>
                            </div>
                        </form>
                        <!-- <form action="{{-- route('gabung.edit') --}}" method="GET"> -->
                            <!-- {{-- csrf_field() --}} -->
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="{{ url('integritimeeting/create') }}" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> 
                                    Tetapan Mesyuarat Baru
                                </a>
                            </div>
                        </div>
                            <div class="table-responsive">
                                <!-- <button type="submit" class="btn btn-success btn-sm" id="btngabung" name="submit" value="1" disabled="true">Gabung Aduan</button> -->
                                <!-- <button type="submit" class="btn btn-success btn-sm" id="btnkelompok" name="submit" value="2" disabled="true">Kelompok Aduan</button> -->
                                <table style="width: 100%" id="penugasan-table" class="table table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Bilangan</th>
                                            <th>Tarikh</th>
                                            <th>Pengerusi</th>
                                            <th>Status</th>
                                            <!-- <th>Nama Yang Diadu</th> -->
                                            <!-- <th>Akses Maklumat Pengadu</th> -->
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <!-- <tbody></tbody> -->
                                </table>
                            </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Start -->
<!-- <div id="modal-show-summary" class="modal fade" aria-hidden="true"> -->
    <!-- <div class="modal-dialog modal-lg"> -->
        <!-- <div class="modal-content" id='ModalShowSummary'></div> -->
    <!-- </div> -->
<!-- </div> -->
<!-- <div id="modal-show-summary-integriti" class="modal fade" aria-hidden="true"> -->
    <!-- <div class="modal-dialog modal-lg"> -->
        <!-- <div class="modal-content" id='ModalShowSummaryIntegriti'></div> -->
    <!-- </div> -->
<!-- </div> -->
<!-- Modal End -->
@stop

@section('script_datatable')
<script type="text/javascript">

// function ShowSummary(CASEID)
// {
//    var CASEID = $("#penugasan-table a").value;
    // $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
// }

// function showsummaryintegriti(id)
// {
    // $('#modal-show-summary-integriti')
        // .modal("show")
        // .find("#ModalShowSummaryIntegriti")
        // .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
// }

// function anyCheck(){
    // var CheckCount = $("#penugasan-table [type=checkbox]:checked").length;
//    alert(CheckCount);
    // if(CheckCount >= 2) {
//        alert("Lebih atau sama 2");
        // document.getElementById("btngabung").disabled = false;
        // document.getElementById("btnkelompok").disabled = false;
    // }else{
        // document.getElementById("btngabung").disabled = true;
        // document.getElementById("btnkelompok").disabled = true;
    // }
// }

$(function() {
    var PenugasanTable = $('#penugasan-table').DataTable({
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
//            {extend: 'excel', title: 'ExampleFile'},
            {
                extend: 'excel',
                title: 'Senarai Tetapan Mesyurat (JMA)',
                // exportOptions: { 
                //     orthogonal: 'export'
                // }
            },
            {extend: 'pdf', title: 'ExampleFile'},
            {
                extend: 'print',text: 'Cetak',customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                }
            }
        ],
        language: {
            lengthMenu: 'Paparan _MENU_ rekod',
            zeroRecords: 'Tiada rekod ditemui',
            info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod.',
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
            url: "{{ url('integritimeeting/getdatatable') }}",
            data: function (d) {
                d.IM_MEETINGNUM = $('#IM_MEETINGNUM').val();
                d.IM_MEETINGDATE = $('#IM_MEETINGDATE').val();
                d.IM_CHAIRPERSON = $('#IM_CHAIRPERSON').val();
                d.IM_STATUS = $('#IM_STATUS').val();
            }
        },
        columns: [
            // {data: 'check', name: 'check', 'width': '1%', searchable: false, orderable: false},
            {data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false},
            {data: 'IM_MEETINGNUM', name: 'IM_MEETINGNUM'},
            // {data: 'IN_CASEID', render: function (data, type) {
            //     return type === 'export' ?
            //         "' " + data :
            //         data;
            // }, name: 'IN_CASEID'},
            {data: 'IM_MEETINGDATE', name: 'IM_MEETINGDATE'},
            {data: 'IM_CHAIRPERSON', name: 'IM_CHAIRPERSON'},
            {data: 'IM_STATUS', name: 'IM_STATUS'},
            {data: 'action', name: 'action', searchable: false, orderable: false}
        ]
    });

    $('#search-form').on('submit', function(e) {
        PenugasanTable.draw();
        e.preventDefault();
    });
});

    $('#IM_MEETINGDATE').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });

</script>
@stop
