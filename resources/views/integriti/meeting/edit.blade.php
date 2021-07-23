@extends('layouts.main')
<?php
    use App\Ref;
    use App\Penugasan;
    use Carbon\Carbon;
    use App\Aduan\Penyiasatan;
    use App\Integriti\IntegritiAdmin;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
    .help-block-red {
        color: red;
    }
</style>
<h2>Kemaskini Tetapan Mesyuarat JMA (Integriti)</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab">
                    Mesyuarat
                </a>
            </li>
            <!-- <li class=""><a data-toggle="tab" href="#case-info"> Maklumat Aduan</a></li> -->
            <!-- <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li> -->
            <!-- <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li> -->
            <!--<li class=""><a data-toggle="tab" href="#letter">Surat</a></li>-->
            <!-- <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li> -->
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="panel-body">
                    {{ Form::open(['route' => ['integritimeeting.update', $model->id], 'class' => 'form-horizontal']) }}
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IM_MEETINGNUM') ? ' has-error' : '' }}">
                                {{ Form::label('IM_MEETINGNUM', 'No. Bilangan Mesyuarat JMA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-4">
                                    {{ Form::text('IM_MEETINGNUM', old('IM_MEETINGNUM', $model->IM_MEETINGNUM), ['class' => 'form-control']) }}
                                    @if ($errors->has('IM_MEETINGNUM'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IM_MEETINGNUM') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IM_MEETINGDATE') ? ' has-error' : '' }}">
                                {{ Form::label('IM_MEETINGDATE', 'Tarikh Mesyuarat JMA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-4">
                                    {{ 
                                        Form::text(
                                            'IM_MEETINGDATE', 
                                            old('IM_MEETINGDATE', date('d-m-Y', strtotime($model->IM_MEETINGDATE))), 
                                            [
                                                'class' => 'form-control', 
                                                'onkeypress' => "return false", 
                                                'onpaste' => "return false",
                                                'readonly' => "readonly",
                                                'style' => 'background-color : #ffffff'
                                            ]
                                        ) 
                                    }}
                                    <!-- {{-- Form::text('dateStart',date('d-m-Y', strtotime($model->IM_MEETINGDATE)), ['class' => 'form-control input-sm', 'id' => 'dateStart', 'onkeypress' => "return false", 'onpaste' => "return false", 'readonly' => "readonly"]) --}} -->
                                    @if ($errors->has('IM_MEETINGDATE'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IM_MEETINGDATE') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IM_CHAIRPERSON') ? ' has-error' : '' }}">
                                {{ Form::label('IM_CHAIRPERSON', 'Pengerusi Mesyuarat JMA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-4">
                                    {{ Form::text('IM_CHAIRPERSON', old('IM_CHAIRPERSON', $model->IM_CHAIRPERSON), ['class' => 'form-control']) }}
                                    @if ($errors->has('IM_CHAIRPERSON'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IM_CHAIRPERSON') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IM_STATUS') ? ' has-error' : '' }}">
                                {{ Form::label('IM_STATUS', 'Status Mesyuarat JMA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-4">
                                    <div class="radio">
                                        <input type="radio" name="IM_STATUS" id="radio1" value="1" 
                                        {{ old('IM_STATUS') ? (old('IM_STATUS') == '1'? 'checked':'') : ($model->IM_STATUS == '1' ? 'checked' : '') }}>
                                        <label for="radio1">
                                            Selesai
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <input type="radio" name="IM_STATUS" id="radio2" value="2" 
                                        {{ old('IM_STATUS') ? (old('IM_STATUS') == '2'? 'checked':'') : ($model->IM_STATUS == '2' ? 'checked' : '') }}>
                                        <label for="radio2">
                                            Tangguh
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <input type="radio" name="IM_STATUS" id="radio3" value="0" 
                                        {{ old('IM_STATUS') ? (old('IM_STATUS') == '0'? 'checked':'') : ($model->IM_STATUS == '0' ? 'checked' : '') }}>
                                        <label for="radio3">
                                            Batal
                                        </label>
                                    </div>
                                    @if ($errors->has('IM_STATUS'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IM_STATUS') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="text-center">
                                    <a class="btn btn-warning" href="{{ url('integritimeeting') }}">
                                        <i class="fa fa-home"></i> Kembali
                                    </a>
                                    {{ Form::button('Kemaskini'.' <i class="fa fa-save"></i>', 
                                    ['type' => 'submit', 'class' => 'btn btn-success']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    {!! Form::close() !!}
                </div>
            </div>
            
            
            
            
        </div>
    </div>
</div>
<!-- Modal Start -->
<!-- {{-- @include('aduan.tugas.usersearchmodal') --}} -->
<!-- {{-- @include('aduan.tugas.multiusersearchmodal') --}} -->
<!-- Modal End -->
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
    function do_remove(user_idx) {
        var oRep = oMembers;
        
        var arr_persons = oRep.arr_persons;
        for(var i=0; i < arr_persons.length; i++) {
            if (user_idx == arr_persons[i].user_id) {
                console.log(i);
                arr_persons.splice(i, 1);
            }
        }
        oRep.arr_persons = arr_persons;
        
        
        var arr_persons = oMembers.arr_persons;
        //alert('koko'+arr_persons.length);
        var obj1 = $('#recipient1').html(''); // 
        var obj2 = $('#recipient2').html(''); //

        for(var i=0; i < arr_persons.length; i++) {
            str = "<input type='hidden' name='recipient[]' value='"+arr_persons[i].user_id+"' />";
            obj1.append(str);
            str = arr_persons[i].name + " <a href='#' onclick='do_remove(\""+arr_persons[i].user_id+"\")'> </a> <br />";
            obj2.append(str);
        }
        
    }
    
    function ShowSummary(CASEID)
    {
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
    }

    function showsummaryintegriti(id)
    {
        $('#modal-show-summary-integriti')
            .modal("show")
            .find("#ModalShowSummaryIntegriti")
            .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
    }

    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href=' + hash + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    $('#IN_INVSTS').on('change', function (e) {
        var IN_INVSTS = $(this).val();
        if(IN_INVSTS === ''){
            $('#div_BLOCK').hide();
            $('#magncd').hide();
            $('#div_CD_DESC').hide();
            $('#div_IN_ANSWER').hide();
        }
        // else
        //     $('#div_BLOCK').show();
        else if(IN_INVSTS === '04'){
            $('#div_BLOCK').hide();
            $('#magncd').show();
            $('#div_CD_DESC').hide();
            $('#div_IN_ANSWER').show();
        }
        // else
        //     $('#magncd').hide();
        else if(IN_INVSTS === '02')
        {
            // $('#div_IN_CMPLCAT').show();
            // $('#div_IN_CMPLCD').show();
            // $('#div_IN_INVBY').show();
            // $('#div_IN_INVBYOTH').show();
            $('#div_CD_DESC').show();
            $('#div_IN_ANSWER').hide();
            $('#div_BLOCK').show();
            $('#magncd').hide();
        }
        else
        {
            // $('#div_IN_CMPLCAT').hide();
            // $('#div_IN_CMPLCD').hide();
            // $('#div_IN_INVBY').hide();
            // $('#div_IN_INVBYOTH').hide();
            $('#div_CD_DESC').hide();
            $('#div_IN_ANSWER').show();
            $('#div_BLOCK').hide();
            $('#magncd').hide();
        }
    });

    function myFunction(id) {
        $.ajax({
            url: "{{ url('tugas/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("InvByName").value = key;
                    document.getElementById("InvById").value = value;
                });
                $('#carian-penerima').modal('hide');
            }
        });
    }
    ;

    $(document).ready(function () {

        $('#UserSearchModal').on('click', function (e) {
            $("#carian-penerima").modal();
        });
        
        $('#MultiUserSearchModal').on('click', function (e) {
            $("#carian-lain2-penerima").modal();
        });

        $('#carian-penerima').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        });

        var oTable = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(filtered from _MAX_ total records)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                // url: "{{-- url('tugas/getdatatableuser') --}}",
                url: "{{ url('integrititugas/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd_user').val();
                    d.brn_cd = $('#brn_cd').val();
                    d.role_cd = $('#role_cd').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                // {data: 'state_cd', name: 'state_cd'},
                // {data: 'brn_cd', name: 'brn_cd'},
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });
        
        // var oTableMulti = $('#users-multi-table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     bFilter: false,
        //     aaSorting: [],
        //     pagingType: "full_numbers",
        //     dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
        //         "<'row'<'col-sm-12'tr>>" +
        //         "<'row'<'col-sm-12'p>>",
        //     language: {
        //         lengthMenu: 'Memaparkan _MENU_ rekod',
        //         zeroRecords: 'Tiada rekod ditemui',
        //         info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
        //         infoEmpty: 'Tiada rekod ditemui',
        //         infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
        //         paginate: {
        //             previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
        //             next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
        //             first: 'Pertama',
        //             last: 'Terakhir',
        //         },
        //     },
        //     ajax: {
        //         url: "{{ url('tugas/getdatatablemultiuser') }}",
        //         data: function (d) {
        //             d.name = $('#name_multi').val();
        //             d.icnew = $('#icnew_multi').val();
        //         }
        //     },
        //     columns: [
        //         {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
        //         {data: 'username', name: 'username'},
        //         {data: 'name', name: 'name'},
        //         {data: 'state_cd', name: 'state_cd'},
        //         {data: 'brn_cd', name: 'brn_cd'},
        //         {data: 'count_case', name: 'count_case'},
        //         {data: 'role.role_code', name: 'role.role_code'},
        //         {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
        //     ],
        // });

        $('#state_cd_user').on('change', function (e) {
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

        $('#resetbtn').on('click', function (e) {
            document.getElementById("search-form").reset();
            oTable.draw();
            oTable.columns.adjust();
            e.preventDefault();
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        
        $('#search-form-multi').on('submit', function (e) {
            oTableMulti.draw();
            e.preventDefault();
        });
        
        $('#resetbtnmulti').on('click', function(e) {
            document.getElementById("search-form-multi").reset();
            oTableMulti.draw();
            e.preventDefault();
        });
        
        $('#IM_MEETINGDATE').datepicker({
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });

        // $('#dateStart').on('click', function(e) {
        //     $('#dateStart').datepicker({
        //         format: 'dd-mm-yyyy',
        //         todayBtn: "linked",
        //         todayHighlight: true,
        //         keyboardNavigation: false,
        //         forceParse: false,
        //         autoclose: true
        //     }); 
        // });
    });
</script>
@stop