@extends('layouts.main')
<?php
use App\Ref;
use Carbon\Carbon;
use App\Penugasan;
use App\Aduan\Penyiasatan;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
</style>
<h2>Penugasan Aduan (Gabung)</h2>
<div class="row wrapper white-bg">
    {!! Form::open(['route' => 'gabung.update', 'class' => 'form-horizontal']) !!}
    {{ csrf_field() }}
        <div class="col-sm-12">
            <div class="form-group">
                {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-2 control-label']) }}
                <div class="col-sm-10" style="padding-top: 7px;">
                    @foreach($arrCASEID as $CASEID)
                    <span class="label label-primary">{{ $CASEID }}</span>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('CA_FILEREF', 'No. Rujukan Fail', ['class' => 'col-sm-2 control-label']) }}
                <div class="col-sm-10">
                    @foreach($arrCASEID as $CASEID)
                    <input value="" name="CA_FILEREF[{{ $CASEID }}]" placeholder="{{ $CASEID }}" class="form-control inline input-sm" style="width: 15%;">
                    @endforeach
                </div>
            </div>
        </div>
        <h4>MAKLUMAT PENUGASAN</h4>
        <div class="hr-line-solid"></div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {{ Form::label('CA_ASGBY','Penugasan Aduan Oleh ', ['class' => 'col-md-4 control-label']) }}
                    <div class="col-sm-8">
                        {{-- Form::text('CA_ASGBY', $mUser->name, ['class' => 'form-control input-sm','readonly' => true]) --}}
                        <p class="form-control-static">{{ $mUser->name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('CA_INVSTS') ? ' has-error' : '' }}">
                    {{ Form::label('CA_INVSTS', 'Status Aduan', ['class' => 'col-sm-5 control-label required']) }}
                    <div class="col-sm-7">
                        {{ Form::select('CA_INVSTS', Penugasan::GetStatusListGabung(), '', ['class' => 'form-control input-sm', 'id' => 'CA_INVSTS']) }}
                        @if ($errors->has('CA_INVSTS'))
                        <span class="help-block"><strong>{{ $errors->first('CA_INVSTS') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div id="div_CA_MAGNCD" class="form-group{{ $errors->has('CA_MAGNCD') ? ' has-error' : '' }}" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '4' ? 'block':'none') : 'none') }}" class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">
                    {{ Form::label('CA_MAGNCD', 'Agensi', ['class' => 'col-sm-5 control-label required']) }}
                    <div class="col-sm-7">
                        {{ Form::select('CA_MAGNCD', Penyiasatan::GetMagncdList(), '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('CA_MAGNCD'))
                        <span class="help-block"><strong>{{ $errors->first('CA_MAGNCD') }}</strong></span>
                        @endif
                    </div>
                </div>
                <!--<div id="div_CA_INVBY" class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">-->
                <div id="div_CA_INVBY" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : 'none') }}" class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">
                    {{ Form::label('CA_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-sm-5 control-label required']) }}
                    <div class="col-sm-7">
                        <div class="input-group">
                            {{ Form::text('CA_INVBY_NAME', '', ['class' => 'form-control input-sm', 'readonly' => true, 'id' => 'CA_INVBY_name']) }}
                            {{ Form::hidden('CA_INVBY', '', ['class' => 'form-control input-sm', 'id' => 'CA_INVBY_id']) }}
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-sm" id="PenyiasatSearchModal">Carian</button>
                            </span>
                        </div>
                        @if ($errors->has('CA_INVBY'))
                            <span class="help-block"><strong>{{ $errors->first('CA_INVBY') }}</strong></span>
                        @endif
                    </div>
                </div>
                <!--<div id="div_CA_INVBYOTH" class="form-group">-->
                <div id="div_CA_INVBYOTH" style="display: {{ old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : 'none' }}" class="form-group">
                    {{ Form::label('', 'Pegawai-pegawai Lain', ['class' => 'col-sm-5 control-label']) }}
                    <div class="col-sm-7">
                        <button type="button" class="btn btn-primary btn-sm" id="MultiUserSearchModal">Carian</button>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                    <div class="col-sm-7">
                        <div id="recipient1"></div>
                        <div id="recipient2"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <!--<div class="form-group{{ $errors->has('CD_DESC') ? ' has-error' : '' }}">-->
                <div class="form-group{{ $errors->has('CD_DESC') ? ' has-error' : '' }}" id="div_CD_DESC" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : 'none') }}">
                    {{ Form::label('CD_DESC', 'Saranan', ['class' => 'col-md-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::textarea('CD_DESC', '', ['class' => 'form-control input-sm', 'rows' => 3]) }}
                        @if ($errors->has('CD_DESC'))
                        <span class="help-block"><strong>{{ $errors->first('CD_DESC') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('CA_ANSWER') ? ' has-error' : '' }}" id="div_CA_ANSWER" style="display: {{ old('CA_INVSTS') ? (old('CA_INVSTS') != '2' ? 'block':'none') : 'none' }}">
                    {{ Form::label('CA_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::textarea('CA_ANSWER', '', ['class' => 'form-control input-sm', 'rows' => 5]) }}
                        @if ($errors->has('CA_ANSWER'))
                            <span class="help-block"><strong>{{ $errors->first('CA_ANSWER') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="cd_reason"
                style="display: {{ $errors->has('CD_REASON') || !empty(old('CA_INVSTS')) ? 'block' : 'none' }} ;">
                <div class="col-md-6">
                    @foreach($arrCASEID as $CASEID)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">No. Aduan</label>
                                <p class="form-control-static">{{ $CASEID }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Hari</label>
                                <p class="form-control-static">{{ $countDuration[$CASEID] }}</p>
                                <input type="hidden" name="CD_REASON_DURATION['{{ $CASEID }}']" id="CD_REASON_DURATION['{{ $CASEID }}']" value="{{ $countDuration[$CASEID] }}" class="form-control" readonly>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($countDurationIndicator)
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('CD_REASON') ? ' has-error' : '' }}">
                            {{ Form::label('CD_REASON', 'Alasan', ['class' => 'control-label required']) }}
                            {{ Form::select('CD_REASON', $caseReasonTemplates, null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                            @if ($errors->has('CD_REASON'))
                                <span class="help-block"><strong>{{ $errors->first('CD_REASON') }}</strong></span>
                            @endif
                        </div>
                        <div id="data_5"
                            class="form-group {{ $errors->has('CD_REASON_DATE_FROM') || $errors->has('CD_REASON_DATE_TO') ? ' has-error' : '' }}"
                            style="display: {{
                                ($errors->has('CD_REASON_DATE_FROM') || $errors->has('CD_REASON_DATE_TO'))
                                || !empty(old('CA_INVSTS')) && old('CD_REASON') == 'P2'
                                ? 'block' : 'none'
                            }} ;">
                            {{ Form::label('CD_REASON_DATE_FROM', 'Tarikh', ['class' => 'control-label required']) }}
                            <div class="input-daterange input-group" id="datepicker">
                                {{ Form::text('CD_REASON_DATE_FROM', null, ['class' => 'form-control input-sm', 'readonly' => 'true', 'placeholder' => 'HH-BB-TTTT']) }}
                                <span class="input-group-addon">Hingga</span>
                                {{ Form::text('CD_REASON_DATE_TO', null, ['class' => 'form-control input-sm', 'readonly' => 'true', 'placeholder' => 'HH-BB-TTTT']) }}
                            </div>
                            @if ($errors->has('CD_REASON_DATE_FROM'))
                                <span class="help-block"><strong>{{ $errors->first('CD_REASON_DATE_FROM') }}</strong></span>
                            @endif
                            @if ($errors->has('CD_REASON_DATE_TO'))
                                <span class="help-block"><strong>{{ $errors->first('CD_REASON_DATE_TO') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="col-sm-12">
            <div class="form-group" align="center">
                {{-- Form::submit('Hantar', ['class' => 'btn btn-success btn-sm', 'value' => 1]) --}}
                <button type="submit" class="btn btn-success btn-sm" id="btnkelompok" name="submit" value="1">Hantar</button>
                {{ link_to('tugas', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
            </div>
        </div>
    {!! Form::close() !!}
</div>

<!-- Modal Create Attachment Start -->
@include('aduan.gabung.modal_carian_penyiasat')
@include('aduan.gabung.multiusersearchmodal')
<!-- Modal Create Attachment End -->

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
    
    function myFunction(id) {
        $.ajax({ 
            url: "{{ url('tugas/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success:function(data){
                $.each(data, function(key, value) {
                    document.getElementById("CA_INVBY_name").value = key;
                    document.getElementById("CA_INVBY_id").value = value;
                });
                $('#carian-penyiasat').modal('hide');
            }
        });
    };
    
    $(document).ready(function(){
        
        $('#PenyiasatSearchModal').on('click', function (e) {
            $("#carian-penyiasat").modal();
        });
        
        $('#MultiUserSearchModal').on('click', function (e) {
            $("#carian-lain2-penerima").modal();
        });
        
        $('#carian-penyiasat').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
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
            ajax: {
                url: "{{ url('tugas/getdatatableuser') }}",
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
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });
        
        var oTableMulti = $('#users-multi-table').DataTable({
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
                url: "{{ url('tugas/getdatatablemultiuser') }}",
                data: function (d) {
                    d.name = $('#name_multi').val();
                    d.icnew = $('#icnew_multi').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });

        $('#state_cd_user').on('change', function (e) {
            var state_cd = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('user/getbrnlist') }}" + "/" + state_cd,
                dataType: "json",
                success:function(data){
                    $('select[name="brn_cd"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="brn_cd"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            }); 
        });

        $('#btn-reset').on('click', function(e) {
            document.getElementById("user-search-form").reset();
            oTable.draw();
            e.preventDefault();
        });

        $('#user-search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
        
        $('#resetbtn').on('click', function(e) {
            document.getElementById("search-form-multi").reset();
            oTableMulti.draw();
            e.preventDefault();
        });

        $('#search-form-multi').on('submit', function(e) {
            oTableMulti.draw();
            e.preventDefault();
        });
    });
    
    function carianpenyiasat(id) {
        $.ajax({ 
            url: "{{ url('tugas-semula/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success:function(data){
                $.each(data, function(key, value) {
                    document.getElementById("CA_INVBY_name").value = key;
                    document.getElementById("CA_INVBY_id").value = value;
                });
                $('#carian-penyiasat').modal('hide');
            }
        });
    };
    
    $('#CA_INVSTS').on('change', function (e) {
        var CA_INVSTS = $(this).val();
        if(CA_INVSTS == 2)
        {
            $('#div_CA_INVBY').show();
            $('#div_CA_INVBYOTH').show();
            $('#div_CD_DESC').show();
            $('#div_CA_ANSWER').hide();
        }
        else
        {
            $('#div_CA_INVBY').hide();
            $('#div_CA_INVBYOTH').hide();
            $('#div_CD_DESC').hide();
            $('#div_CA_ANSWER').show();
        }
        
        if(CA_INVSTS == 4)
            $('#div_CA_MAGNCD').show();
        else
            $('#div_CA_MAGNCD').hide();

        if(CA_INVSTS !== '') {
            $('#cd_reason').show();
        } else {
            $('#cd_reason').hide();
        }
    });

    $('select[name="CD_REASON"]').on('change', function () {
        switch ($(this).val()) {
            case 'P2':
                $('#data_5').show();
                break;
            default:
                $('#data_5').hide();
                break;
        }
    });

    $('#data_5 .input-daterange').datepicker({
        autoclose: true,
        calendarWeeks: true,
        forceParse: false,
        format: 'dd-mm-yyyy',
        keyboardNavigation: false,
        todayBtn: "linked",
        todayHighlight: true,
        weekStart: 1,
    });
</script>
@stop