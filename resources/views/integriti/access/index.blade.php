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
                    <h2>Senarai Akses Maklumat Pengadu (Integriti)</h2>
                    <!-- {{-- @include('nota') --}} -->
                    <div class="ibox-content">
                        <form method="POST" id="search-form" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('IN_CASEID', '', ['class' => 'form-control input-sm', 'id' => 'IN_CASEID']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('IN_AGAINSTNM', 'Nama yang Diadu', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('IN_AGAINSTNM', '', ['class' => 'form-control input-sm', 'id' => 'IN_AGAINSTNM']) }}
                                        </div>
                                    </div>
<!--                                    <div class="form-group">
                                        <label for="IN_INVSTS" class="col-sm-4 control-label">@lang('public-case.case.IN_CASESTS')</label>
                                        <div class="col-sm-8">
                                            {{-- Form::select('IN_INVSTS', Ref::GetList('292', true) , '', ['class' => 'form-control input-sm', 'id' => 'IN_INVSTS']) --}}
                                        </div>
                                    </div>-->
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('IN_SUMMARY', '', ['class' => 'form-control input-sm', 'id' => 'IN_SUMMARY']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    
                                </div>
                            </div>
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                                <a class="btn btn-default btn-sm" href="{{ url('integritiaccess') }}">Semula</a>
                            </div>
                        </form>
                        <!-- <form action="{{-- route('gabung.edit') --}}" method="GET"> -->
                            <!-- {{-- csrf_field() --}} -->
                            <div class="table-responsive">
                                <!-- <button type="submit" class="btn btn-success btn-sm" id="btngabung" name="submit" value="1" disabled="true">Gabung Aduan</button> -->
                                <!-- <button type="submit" class="btn btn-success btn-sm" id="btnkelompok" name="submit" value="2" disabled="true">Kelompok Aduan</button> -->
                                <table style="width: 100%" id="penugasan-table" class="table table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>Bil.</th>
                                            <!-- <th><center>Hari</center></th> -->
                                            <th>No. Aduan</th>
                                            <th>Aduan</th>
                                            <th>Nama Yang Diadu</th>
                                            <th>Tarikh Penerimaan</th>
                                            <th>Status</th>
                                            <th>Akses Maklumat Pengadu</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        <!-- </form> -->
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
//    var CASEID = $("#penugasan-table a").value;
    $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
}

function showsummaryintegriti(id)
{
    $('#modal-show-summary-integriti')
        .modal("show")
        .find("#ModalShowSummaryIntegriti")
        .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
}

function anyCheck(){
    var CheckCount = $("#penugasan-table [type=checkbox]:checked").length;
//    alert(CheckCount);
    if(CheckCount >= 2) {
//        alert("Lebih atau sama 2");
        document.getElementById("btngabung").disabled = false;
        document.getElementById("btnkelompok").disabled = false;
    }else{
        document.getElementById("btngabung").disabled = true;
        document.getElementById("btnkelompok").disabled = true;
    }
}

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
                title: 'Senarai Akses Maklumat Pengadu',
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
            // url: "{{-- url('tugas/get_datatable') --}}",
            url: "{{ url('integritiaccess/getdatatable') }}",
            data: function (d) {
                d.IN_CASEID = $('#IN_CASEID').val();
                d.IN_SUMMARY = $('#IN_SUMMARY').val();
                d.IN_AGAINSTNM = $('#IN_AGAINSTNM').val();
                d.IN_RCVDT = $('#IN_RCVDT').val();
                d.IN_INVSTS = $('#IN_INVSTS').val();
            }
        },
        columns: [
            // {data: 'check', name: 'check', 'width': '1%', searchable: false, orderable: false},
            {data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false},
            {data: 'IN_CASEID', name: 'IN_CASEID'},
            // {data: 'IN_CASEID', render: function (data, type) {
            //     return type === 'export' ?
            //         "' " + data :
            //         data;
            // }, name: 'IN_CASEID'},
            {data: 'IN_SUMMARY', name: 'IN_SUMMARY'},
            {data: 'IN_AGAINSTNM', name: 'IN_AGAINSTNM'},
            {data: 'IN_RCVDT', name: 'IN_RCVDT'},
            {data: 'IN_INVSTS', name: 'IN_INVSTS'},
            {data: 'IN_ACCESSIND', name: 'IN_ACCESSIND'},
            // {data: 'tempoh', name: 'tempoh'},
            {data: 'action', name: 'action', searchable: false, orderable: false}
        ]
    });
    
//    $('#btngabung').on('click', function(e) {
//        var rowcollection =  PenugasanTable.$(".i-checks:checked", {"page": "all"});
//        window.open("route('gabung.edit','12')");
//        var checkbox_value = [];
//        rowcollection.each(function(index,elem){
//            checkbox_value.push($(elem).val()); // insert rowid's to array
//            checkbox_value = $(elem).val();
//        });
//        alert(checkbox_value);
//        $.pjax({
//            headers: {
//                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//            },
//            type: "POST",
//            url: "{{ route('gabung.edit') }}",
//            data: rowcollection,
//            dataType: "json",
//            success: function (data) {
//            $("#investigatesearch-countycd").html(data["arrcountylist"]);
//            $("#investigatesearch-divcd").html(data["arrdivlist"]);
//            },
//        });
//        $.post("{{ url('/') }}");
//    });
    
    $('#search-form').on('submit', function(e) {
        PenugasanTable.draw();
        e.preventDefault();
    });
});

</script>
@stop
