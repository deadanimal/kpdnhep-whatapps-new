@extends('layouts.main')
<?php 
    use App\Ref;
    use App\Branch;
?>
@section('content')
    <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <h2>Laporan Fail Kes</h2>
                    <div class="ibox-content">
                        <form method="POST" id="search-form" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('NO_KES', 'No. Kes', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::text('NO_KES', '', ['class' => 'form-control input-sm']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            @if(substr(Auth::user()->Role->role_code, 0, 1) == 2 || substr(Auth::user()->Role->role_code, 0, 1) == 3)
                                                {{ Form::select('state_cd_disabled', Ref::GetList('17', true), Auth::user()->state_cd, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                                                {{ Form::hidden('state_cd', Auth::user()->state_cd, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                                            @else
                                                {{ Form::select('state_cd', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            @if(substr(Auth::user()->Role->role_code, 0, 1) == 2)
                                            {{ Form::select('brn_cd', Branch::GetListByState(Auth::user()->state_cd), null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                                            @elseif(substr(Auth::user()->Role->role_code, 0, 1) == 3)
                                            {{ Form::select('brn_cd_disabled', Branch::GetListByState(Auth::user()->state_cd), Auth::user()->brn_cd, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                                            {{ Form::hidden('brn_cd', Auth::user()->brn_cd, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                                            @else
                                            {{ Form::select('brn_cd', [''=>'-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group" id="date">
                                        {{ Form::label('CA_RCVDT_FROM', 'Tarikh Penerimaan', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            <div class="input-daterange input-group" id="datepicker">
                                                {{ Form::text('CA_RCVDT_FROM', '', ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_FROM']) }}
                                                <span class="input-group-addon">hingga</span>
                                                {{ Form::text('CA_RCVDT_TO', '', ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_TO']) }}
                                            </div>
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
                                <a class="btn btn-default btn-sm" href="{{ route('fail-kes.report')}}">Semula</a>
                            </div>
                        </form>
<!--                        <div class="row">
                            <div class="col-md-12">
                                <a onclick="Create()" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Fail Kes</a>
                            </div>
                        </div>-->
                        <div class="table-responsive">
                            <table style="width: 100%" id="fail-kes-table" class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>No. Kes</th>
                                        <th>No. Aduan</th>
                                        <th>Asas Tindakan</th>
                                        <th>Tarikh Kejadian</th>
                                        <!--<th></th>-->
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
<!-- Modal End -->

@stop

@section('script_datatable')
<script type="text/javascript">
    
    function ShowSummary(CASEID)
    {
    //    var CASEID = $("#penugasan-table a").value;
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('pindah.showsummary','') }}" + "/" + CASEID);
    }
    
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
        pageLength: 100,
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
            url: "{{ url('fail-kes/getdatareport')}}",
            data: function (d) {
                d.title = $('#NO_KES').val();
                d.remark = $('#CA_CASEID').val();
            }
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false},
            {data: 'NO_KES', name: 'NO_KES'},
            {data: 'CA_CASEID', name: 'CA_CASEID'},
            {data: 'ASAS_TINDAKAN', name: 'ASAS_TINDAKAN'},
            {data: 'TKH_KEJADIAN', name: 'TKH_KEJADIAN'},
//            {data: 'action', name: 'action', width: '9%', searchable: false, orderable: false}
        ]
    });
    
    $('#search-form').on('submit', function(e) {
        FailKesTable.draw();
        e.preventDefault();
    });
    
    $('#state_cd').on('change', function (e) {
        var state_cd = $(this).val();
        $.ajax({
            type: 'GET',
            url: "{{ url('user/getbrnlist') }}" + "/" + state_cd,
            dataType: "json",
            success: function (data) {
                $('select[name="brn_cd"]').empty();
                $.each(data, function (key, value) {
                    $('select[name="brn_cd"]').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
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
