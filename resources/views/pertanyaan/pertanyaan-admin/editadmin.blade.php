@extends('layouts.main')
<?php
    use App\Ref;
    use App\Pertanyaan\PertanyaanAdmin;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
    span.select2 {
        width: 100% !important;
    }
</style>
<h2>Kemaskini Pertanyaan / Cadangan</h2>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active">
            <!--<a data-toggle="tab" href="#enq_info">@lang('pertanyaan.tab.enquiry')</a>-->
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                MAKLUMAT PERTANYAAN / CADANGAN
            </a>
        </li>
        <li class="">
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                LAMPIRAN
            </a>
        </li>
        <li>
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                SEMAKAN
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="enq_info" class="tab-pane active">
            <div class="panel-body">
                {!! Form::open(['route' => ['pertanyaan-admin.updateadmin', $model->id], 'class'=>'form-horizontal']) !!}
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <h4><strong>Maklumat Pihak Yang Bertanya</strong></h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('AS_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('AS_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('AS_RCVTYP', PertanyaanAdmin::getAskRcvTyp('259', true), old('AS_RCVTYP', $model->AS_RCVTYP), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('AS_RCVTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('AS_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('AS_DOCNO', old('AS_DOCNO', $model->AS_DOCNO), ['class' => 'form-control input-sm', 'id' => 'AS_DOCNO', 'maxlength' => 15 ]) }}
                                    @if ($errors->has('AS_DOCNO'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('AS_AGE', 'Umur', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('AS_AGE', old('AS_AGE', $model->AS_AGE), ['class' => 'form-control input-sm', 'id' => 'AS_AGE', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 3 ]) }}
                                    @if ($errors->has('AS_AGE'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('AS_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::email('AS_EMAIL', old('AS_EMAIL', $model->AS_EMAIL), ['class' => 'form-control input-sm', 'maxlength' => 191]) }}
                                    <style scoped>input:invalid, textarea:invalid { color: red; }</style>
                                    @if ($errors->has('AS_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('AS_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('AS_MOBILENO', old('AS_MOBILENO', $model->AS_MOBILENO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 20 ]) }}
                                    @if ($errors->has('AS_MOBILENO'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('AS_ADDR', 'Alamat', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('AS_ADDR', old('AS_ADDR', $model->AS_ADDR), ['class' => 'form-control input-sm', 'rows' => '4']) }}
                                    @if ($errors->has('AS_ADDR'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('AS_NAME') ? ' has-error' : '' }}">
                                {{ Form::label('AS_NAME', 'Nama', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('AS_NAME', old('AS_NAME', $model->AS_NAME), ['class' => 'form-control input-sm', 'id' => 'AS_NAME', 'maxlength' => 60]) }}
                                    @if ($errors->has('AS_NAME'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_SEXCD') ? ' has-error' : '' }}"> 
                                {{ Form::label('AS_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('AS_SEXCD', Ref::GetList('202', true, 'ms'), old('AS_SEXCD', $model->AS_SEXCD), ['class' => 'form-control input-sm', 'id' => 'AS_SEXCD']) }}
                                    @if ($errors->has('AS_SEXCD'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_NATCD') ? ' has-error' : '' }}">
                                {{ Form::label('AS_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    <div class="radio radio-success">
                                        <input id="AS_NATCD1" type="radio" name="AS_NATCD" value="1" onclick="check(this.value)" {{ (old('AS_NATCD') != ''? (old('AS_NATCD') == '1'? 'checked':''):'checked') }} >
                                        <label for="AS_NATCD1"> Warganegara </label>
                                    </div>
                                    <div class="radio radio-success">
                                        <input id="AS_NATCD2" type="radio" name="AS_NATCD" value="0" onclick="check(this.value)" {{ (old('AS_NATCD') != ''? (old('AS_NATCD') == '0'? 'checked':''):'') }} >
                                        <label for="AS_NATCD2"> Bukan Warganegara </label>
                                    </div>
                                    @if ($errors->has('AS_NATCD'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_NATCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="warganegara" style="display:block">
                            <!--<div id="warganegara" style="display: {{-- (old('AS_NATCD') != ''? (old('AS_NATCD') == '1'? 'block':'none'):'block') --}}">-->
                                <div class="form-group {{ $errors->has('AS_POSCD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('AS_POSCD', old('AS_POSCD', $model->AS_POSCD), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                        @if ($errors->has('AS_POSCD'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_POSCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('AS_STATECD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('AS_STATECD', Ref::GetList('17', true, 'ms'), old('AS_STATECD', $model->AS_STATECD), ['class' => 'form-control input-sm required', 'id' => 'AS_STATECD']) }}
                                        @if ($errors->has('AS_STATECD'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('AS_DISTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        <!--{{-- Form::select('AS_DISTCD', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'AS_DISTCD']) --}}-->
                                        {{ Form::select('AS_DISTCD', $model->AS_DISTCD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->AS_STATECD, '18', true, 'ms'), old('AS_DISTCD', $model->AS_DISTCD), ['class' => 'form-control input-sm', 'id' => 'AS_DISTCD']) }}
                                        {{ Form::hidden('AS_STATUSPENGADU', '', ['class' => 'form-control input-sm', 'id' => 'AS_STATUSPENGADU']) }}
                                        @if ($errors->has('AS_DISTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('AS_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="bknwarganegara" style="display: {{ (old('AS_NATCD') != ''? (old('AS_NATCD') == '0'? 'block':'none'):'none') }}">
                                <div class="form-group {{ $errors->has('AS_COUNTRYCD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_COUNTRYCD', 'Negara Asal', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('AS_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('AS_COUNTRYCD', $model->AS_COUNTRYCD), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('AS_COUNTRYCD'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_COUNTRYCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4><strong>Maklumat Pertanyaan / Cadangan</strong></h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('AS_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('AS_SUMMARY', 'Keterangan Pertanyaan/Cadangan', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::textarea('AS_SUMMARY', old('AS_SUMMARY', $model->AS_SUMMARY), ['class' => 'form-control input-sm', 'rows' => 4]) }}
                                    @if ($errors->has('AS_SUMMARY'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_SUMMARY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            <a class="btn btn-default btn-sm" href="{{ route('pertanyaan-admin.index') }}">Kembali</a>
                            <!--{{-- Form::submit(trans('button.send'), ['name' => 'btnHantar','class' => 'btn btn-success btn-sm']) --}}-->
                            <!--{{-- Form::submit(trans('button.save'), ['name' => 'btnSimpan','class' => 'btn btn-primary btn-sm']) --}}-->
                            {{ Form::button('Simpan & Seterusnya'.' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm']) }}
                        </div>  
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
    $(function () {

        $('#module_ind').on('change', function(e){
            alert($('#module_ind').val());
        });
        
        $('#AS_STATECD').on('change', function (e) {
            var AS_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('admin-case/getdistlist') }}" + "/" + AS_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="AS_DISTCD"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="AS_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
        });
        
        $("select").select2();
        
    });
    
    function check(value) {
        if (value === '1') {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        } else {
            $('#warganegara').show();
            $('#bknwarganegara').show();
        }
    }
    
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    
    $('#AS_DOCNO').blur(function(){
        var AS_DOCNO = $('#AS_DOCNO').val();
        $.ajax({
            type: 'GET',
            url: "{{ url('admin-case/tojpn') }}" + "/" + AS_DOCNO,
            dataType: "json",
            success: function (data) {
                if (data.Message) {
                    alert(data.Message);
                }
                $('#AS_EMAIL').val(data.EmailAddress); // Email
                $('#AS_MOBILENO').val(data.MobilePhoneNumber); // Telefon Bimbit
                $('#AS_STATUSPENGADU').val(data.StatusPengadu); // Status Pengadu
                $('#AS_NAME').val(data.Name); // Nama
                $('#AS_AGE').val(data.Age); // Umur
                $('#AS_SEXCD').val(data.Gender).trigger('change'); // Jantina
                if (data.Warganegara != '') {
                    if (data.Warganegara === '1') { // Warganegara
                        document.getElementById('AS_NATCD1').checked = true;
                        $('#warganegara').show();
                        $('#bknwarganegara').hide();
                    } else {
                        document.getElementById('AS_NATCD2').checked = true;
                        $('#warganegara').show();
                        $('#bknwarganegara').show();
                    }
                }
                // Standard Field
                $('#AS_ADDR').val(data.CorrespondenceAddress1 + '\n' + data.CorrespondenceAddress2); // Alamat
                $('#AS_POSCD').val(data.CorrespondenceAddressPostcode); // Poskod
                $('#AS_STATECD').val(data.CorrespondenceAddressStateCode).trigger('change'); // Negeri
                getDistListFromJpn(data.CorrespondenceAddressStateCode, data.KodDaerah); // Daerah
            },
            error: function (data) {
                console.log(data);
                if (data.status == '500') {
                    alert(data.statusText);
                }
            },
            complete: function (data) {
                console.log(data);
            }
        });
    });
    
    function getDistListFromJpn(StateCd, DistCd) {
        if(StateCd != '' && DistCd != '') {
            $.ajax({
                type: 'GET',
                url: "{{ url('admin-case/getdistlist') }}" + "/" + StateCd,
                dataType: "json",
                success: function (data) {
                    $('select[name="AS_DISTCD"]').empty();
                    $.each(data, function (key, value) {
                        if (DistCd === value)
                            $('select[name="AS_DISTCD"]').append('<option value="' + value + '" selected="selected">' + key + '</option>');
                        else
                            $('select[name="AS_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
        }else{
            $('select[name="AS_DISTCD"]').empty();
            $('select[name="AS_DISTCD"]').append('<option value="">-- SILA PILIH --</option>');
        }
    }
</script>
@stop
