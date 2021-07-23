@extends('layouts.main')
<?php
use App\Ref;
use Carbon\Carbon;
use App\Aduan\PindahAduan;
?>
@section('content')
<style>
    textarea {
        resize: vertical;
    }
</style>
<h2>Pindah Aduan (Kelompok)</h2>
<div class="ibox-content">
    {!! Form::open(['route' => 'pindah.submitkelompok', 'class' => 'form-horizontal']) !!}
    {{ csrf_field() }}
    <div class="row">
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
                {{ Form::label('CA_FILEREF', 'No. Rujukan', ['class' => 'col-sm-2 control-label']) }}
                <div class="col-sm-10">
                    @foreach($arrCASEID as $CASEID)
                    <input value="" name="CA_FILEREF[{{ $CASEID }}]" placeholder="{{ $CASEID }}" class="form-control inline input-sm" style="width: 15%;">
                    @endforeach
                </div>
            </div>
        </div>
        <h4>MAKLUMAT PEMINDAHAN</h4>
        <div class="hr-line-solid"></div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('CA_ASGBY', 'Dipindahkan Oleh', ['class' => 'col-md-4 control-label']) }}
                <div class="col-md-8">
                    <!--{{-- Form::text('CA_ASGBY', $mUser->name, ['class' => 'form-control input-sm','readonly' => true]) --}}-->
                    {{-- Form::text('CA_ASGBY_NAME', $mUser->name, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                    <p class="form-control-static">{{ $mUser->name }}</p>
                    {{ Form::hidden('CA_ASGBY', $mUser->id, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                </div>
            </div>
            <div class="form-group{{ $errors->has('CA_INVSTS') ? ' has-error' : '' }}">
                {{ Form::label('CA_INVSTS', 'Status Aduan', ['class' => 'col-md-4 control-label required']) }}
                <div class="col-md-8">
                    {{ Form::select('CA_INVSTS', PindahAduan::GetListStatusAduan(), null, ['class' => 'form-control input-sm', 'id'=>'CA_INVSTS', 'placeholder' => '-- SILA PILIH --']) }}
                    @if ($errors->has('CA_INVSTS'))
                    <span class="help-block"><strong>{{ $errors->first('CA_INVSTS') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="div_CA_MAGNCD" style="display: {{ $errors->has('CA_MAGNCD')||old('CA_INVSTS') == '4' ? 'block' : 'none' }} ;">
                <div class="form-group{{ $errors->has('CA_MAGNCD') ? ' has-error' : '' }}">
                    {{ Form::label('CA_MAGNCD', 'Agensi', ['class' => 'col-lg-4 control-label required']) }}
                    <div class="col-lg-8">
                        {{ Form::select('CA_MAGNCD', PindahAduan::GetListMagn(), null, ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('CA_MAGNCD'))
                            <span class="help-block"><strong>{{ $errors->first('CA_MAGNCD') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <!--<div class="form-group{{ $errors->has('CA_INVBY_NAME') ? ' has-error' : '' }}">-->
            {{-- <div class="form-group{{ $errors->has('CA_INVBY_NAME') ? ' has-error' : '' }}" id="div_CA_INVBY" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '0' ? 'block':'none') : 'block') }}"> --}}
            <div class="form-group{{ $errors->has('CA_INVBY_NAME') ? ' has-error' : '' }}" id="div_CA_INVBY" style="display: none">
                {{ Form::label('CA_INVBY','Dipindahkan Kepada', ['class' => 'col-md-4 control-label required']) }}
                <div class="col-md-8">
                    <div class="input-group">
                        {{ Form::text('CA_INVBY_NAME', '', ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'InvByName'])}}
                        {{ Form::hidden('CA_INVBY', '', ['class' => 'form-control input-sm', 'id' => 'InvById']) }}
                        {{ Form::hidden('CA_INVBT', Carbon::now(), ['class' => 'form-control input-sm', 'id' => 'InvById']) }}
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-sm" id="UserSearchModal">Carian</button>
                        </span>
                    </div>
                    @if ($errors->has('CA_INVBY_NAME'))
                        <span class="help-block"><strong>{{ $errors->first('CA_INVBY_NAME') }}</strong></span>
                    @endif
                </div>
            </div>
            <div id="ca_br_statecd"
                class="form-group {{ $errors->has('CA_BR_STATECD') ? ' has-error' : '' }}"
                style="display: {{ $errors->has('CA_BR_STATECD') || old('CA_INVSTS') == '0' ? 'block' : 'none' }} ;">
                {{ Form::label('CA_BR_STATECD', 'Negeri', ['class' => 'col-lg-4 control-label required']) }}
                <div class="col-lg-8">
                    {{ Form::select('CA_BR_STATECD', $mRefState, null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                    @if ($errors->has('CA_BR_STATECD'))
                        <span class="help-block"><strong>{{ $errors->first('CA_BR_STATECD') }}</strong></span>
                    @endif
                </div>
            </div>
            <div id="ca_brncd"
                class="form-group {{ $errors->has('CA_BRNCD') ? ' has-error' : '' }}"
                style="display: {{ $errors->has('CA_BRNCD') || old('CA_INVSTS') == '0' ? 'block' : 'none' }} ;">
                {{ Form::label('CA_BRNCD', 'Cawangan', ['class' => 'col-lg-4 control-label required']) }}
                <div class="col-lg-8">
                    {{ Form::select('CA_BRNCD', [], null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                    @if ($errors->has('CA_BRNCD'))
                        <span class="help-block"><strong>{{ $errors->first('CA_BRNCD') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group{{ $errors->has('CD_DESC') ? ' has-error' : '' }}">
                {{ Form::label('CD_DESC', 'Saranan', ['class' => 'col-md-2 control-label required']) }}
                <div class="col-md-10">
                    {{ Form::textarea('CD_DESC', '', ['class' => 'form-control input-sm', 'rows' => 3]) }}
                    @if ($errors->has('CD_DESC'))
                    <span class="help-block"><strong>{{ $errors->first('CD_DESC') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('CA_ANSWER') ? ' has-error' : '' }}" id="div_CA_ANSWER" style="display: {{ old('CA_INVSTS') ? ((old('CA_INVSTS') == '4' || (old('CA_INVSTS') == '5'))? 'block':'none') : 'none' }}">
                {{ Form::label('CA_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-md-2 control-label required']) }}
                <div class="col-md-10">
                    {{ Form::textarea('CA_ANSWER', old('CA_ANSWER'), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                    @if ($errors->has('CA_ANSWER'))
                        <span class="help-block"><strong>{{ $errors->first('CA_ANSWER') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12" id="cd_reason"
            style="display: {{ $errors->has('CD_REASON.*') || old('CA_INVSTS') != '' ? 'block' : 'none' }} ;">
            @foreach($arrCASEID as $CASEID)
                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">No. Aduan</label>
                            <p class="form-control-static">{{ $CASEID }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Hari</label>
                            <p class="form-control-static">{{ $countDuration[$CASEID] }}</p>
                            <input type="hidden" name="CD_REASON_DURATION['{{ $CASEID }}']" id="CD_REASON_DURATION['{{ $CASEID }}']" value="{{ $countDuration[$CASEID] }}" class="form-control" readonly>
                        </div>
                    </div>
                    @if ($countDuration[$CASEID] >= 4)
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has("CD_REASON.'$CASEID'") ? ' has-error' : '' }}">
                            <label for="CD_REASON['{{ $CASEID }}']" class="control-label required">Alasan</label>
                            <select name="CD_REASON['{{ $CASEID }}']" id="CD_REASON['{{ $CASEID }}']" class="form-control input-sm" onchange="selectCaseReason('{{ $CASEID }}')">
                                @if(old("CD_REASON.'$CASEID'"))
                                    <option value="" selected="selected">-- SILA PILIH --</option>
                                @else
                                    <option value="">-- SILA PILIH --</option>
                                @endif
                                @foreach($caseReasonTemplates as $key => $caseReasonTemplate)
                                    @if(old("CD_REASON.'$CASEID'") && ($key == old("CD_REASON.'$CASEID'")))
                                        <option value="{{ $key }}" selected="selected">{{ $caseReasonTemplate }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $caseReasonTemplate }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has("CD_REASON.'$CASEID'"))
                                <span class="help-block"><strong>{{ $errors->first("CD_REASON.'$CASEID'") }}</strong></span>
                            @endif
                        </div>
                        <div id="date_picker_reason_{{ $CASEID }}"
                            class="form-group {{ $errors->has("CD_REASON_DATE_FROM.'$CASEID'") || $errors->has("CD_REASON_DATE_TO.'$CASEID'") ? ' has-error' : '' }}"
                            style="display: {{
                                ($errors->has("CD_REASON_DATE_FROM.'$CASEID'") || $errors->has("CD_REASON_DATE_TO.'$CASEID'"))
                                || !empty(old("CD_REASON.'$CASEID'")) && old("CD_REASON.'$CASEID'") == 'P2'
                                ? 'block' : 'none'
                            }} ;">
                            <label for="CD_REASON_DATE_FROM['{{ $CASEID }}']" class="control-label required">Tarikh</label>
                            <div class="input-daterange input-group" id="datepicker">
                                <input class="form-control input-sm" readonly="" id="CD_REASON_DATE_FROM['{{ $CASEID }}']" name="CD_REASON_DATE_FROM['{{ $CASEID }}']" type="text" value="{{old("CD_REASON_DATE_FROM.'$CASEID'")}}" placeholder="HH-BB-TTTT">
                                <span class="input-group-addon">Hingga</span>
                                <input class="form-control input-sm" readonly="" id="CD_REASON_DATE_TO['{{ $CASEID }}']" name="CD_REASON_DATE_TO['{{ $CASEID }}']" type="text" value="{{old("CD_REASON_DATE_TO.'$CASEID'")}}" placeholder="HH-BB-TTTT">
                            </div>
                            @if ($errors->has("CD_REASON_DATE_FROM.'$CASEID'"))
                                <span class="help-block"><strong>{{ $errors->first("CD_REASON_DATE_FROM.'$CASEID'") }}</strong></span>
                            @endif
                            @if ($errors->has("CD_REASON_DATE_TO.'$CASEID'"))
                                <span class="help-block"><strong>{{ $errors->first("CD_REASON_DATE_TO.'$CASEID'") }}</strong></span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="col-sm-12">
            <div class="form-group" align="center">
                {{-- Form::submit('Hantar', ['class' => 'btn btn-success btn-sm', 'value' => 1]) --}}
                <button type="submit" class="btn btn-success btn-sm" id="btnkelompok" name="submit" value="1">Hantar</button>
                {{ link_to('pindah', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<!-- Modal Start -->
@include('aduan.pindah.usersearchmodal')
<!-- Modal End -->

@stop

@section('script_datatable')
<script type="text/javascript">
    function myFunction(id) {
        $.ajax({ 
            url: "{{ url('pindah/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success:function(data){
                console.log(data);
                document.getElementById("InvByName").value = data.name;
                document.getElementById("InvById").value = data.id;
                $('#carian-penerima').modal('hide');
            }
        });
    };
    
    $(document).ready(function(){
        
        $('#UserSearchModal').on('click', function (e) {
            $("#carian-penerima").modal();
        });
        
        $('#carian-penyiasat').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        });
        
        $('#state_cd').on('change', function (e) {
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

        $('select[name="CA_BR_STATECD"]').on('change', function () {
            var CA_BR_STATECD = $(this).val();
            if(CA_BR_STATECD){
                $.ajax({
                    type:'GET',
                    url:"{{ url('user/getbrnlist') }}" + "/" + CA_BR_STATECD,
                    dataType: "json",
                    success:function(data){
                        $('select[name="CA_BRNCD"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="CA_BRNCD"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            } else {
                $('select[name="CA_BRNCD"]').empty();
                $('select[name="CA_BRNCD"]').append('<option>-- SILA PILIH --</option>');
                $('select[name="CA_BRNCD"]').trigger('change');
            }
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
                url: "{{ url('pindah/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd').val();
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
//                {data: 'count_case', name: 'count_case'},
//                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'role_code', name: 'role_code'},
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

        $('#resetbtn').on('click', function(e) {
            document.getElementById("search-form").reset();
            oTable.draw();
            e.preventDefault();
        });

        $('#search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });

        $('.input-daterange').datepicker({
            autoclose: true,
            calendarWeeks: true,
            forceParse: false,
            format: 'dd-mm-yyyy',
            keyboardNavigation: false,
            todayBtn: "linked",
            todayHighlight: true,
            weekStart: 1,
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
        if($(this).val() === '4'){
            $('#div_CA_MAGNCD').show();
            $('#ca_br_statecd').hide();
            $('#ca_brncd').hide();
        } else if($(this).val() === '0'){
            $('#div_CA_MAGNCD').hide();
            $('#ca_br_statecd').show();
            $('#ca_brncd').show();
        } else {
            $('#div_CA_MAGNCD').hide();
            $('#ca_br_statecd').hide();
            $('#ca_brncd').hide();
        }

        if($(this).val() === '4' || $(this).val() === '5')
        {
            document.getElementById("UserSearchModal").disabled = true;
            $( "label[for='CA_INVBY']" ).removeClass( "required" );
            // $('#div_CA_INVBY').hide();
            $('#div_CA_ANSWER').show();
        }else{
            document.getElementById("UserSearchModal").disabled = false;
            $( "label[for='CA_INVBY']" ).addClass( "required" );
            // $('#div_CA_INVBY').show();
            $('#div_CA_ANSWER').hide();
        }
        switch ($(this).val()) {
            case '4':
            case '5':
            case '0':
                $('#cd_reason').show();
                break;
            default:
                $('#cd_reason').hide();
                break;
        }
    });

    function selectCaseReason(caseid) {
        var reasoncode = document.getElementById("CD_REASON['"+caseid+"']").value;
        switch (reasoncode) {
            case 'P2':
                $("#date_picker_reason_"+caseid).show();
                break;
            default:
                $("#date_picker_reason_"+caseid).hide();
                break;
        }
    }
</script>
@stop