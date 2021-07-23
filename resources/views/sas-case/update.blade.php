@extends('layouts.main')
<?php
use Carbon\Carbon;
use App\Ref;
use App\SasCase;
use App\Branch;
?>
@section('content')
<style>
    textarea {
        resize: vertical;
    }
    span.select2 {
        width: 100% !important;
    }
    .select2-dropdown{
        z-index:3000 !important;
    }
    .help-block-red {
        color: red;
    }
</style>
<h2>Kemaskini Aduan Khas</h2>
<div class="row">
    <!--<div class="col-lg-6">-->
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#case-info">MAKLUMAT ADUAN</a></li>
                <li class=""><a data-toggle="tab" href="#attachment">LAMPIRAN</a></li>
                <li class=""><a data-toggle="tab" href="#transaction">SOROTAN TRANSAKSI</a></li>
            </ul>
            <div class="tab-content">
                <div id="case-info" class="tab-pane active">
                    <div class="panel-body">
                        {!! Form::open(['id' => 'update-form', 'url' => ['sas-case/update',$mSasCase->CA_CASEID], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                        {{ csrf_field() }}{{ method_field('PATCH') }}
                        <h4>Cara Terima</h4>
                        <!--<div class="hr-line-solid"></div>-->
                        <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('CA_RCVDT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RCVDT', 'Tarikh Terima Aduan', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_RCVDT', Carbon::parse($mSasCase->CA_RCVDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm datetime', 'readonly' => true]) }}
                                        @if ($errors->has('CA_RCVDT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_COMPLETEDT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_COMPLETEDT', 'Tarikh Selesai Aduan', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        <!--{{-- Form::text('CA_COMPLETEDT', Carbon::parse($mSasCase->CA_COMPLETEDT)->format('d-m-Y'), ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                        {{ Form::text('CA_COMPLETEDT', $mSasCase->CA_COMPLETEDT != '' ? Carbon::parse($mSasCase->CA_COMPLETEDT)->format('d-m-Y h:i A') : '', ['class' => 'form-control input-sm datetime', 'readonly' => true]) }}
                                        @if ($errors->has('CA_COMPLETEDT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_COMPLETEDT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                        {{ Form::hidden('CA_INVBY', old('CA_INVBY', $mSasCase->CA_INVBY), ['class' => 'form-control input-sm', 'id' => 'CA_INVBY_id']) }}
                                        {{ Form::text('CA_INVBY_NAME', $InvBy, ['class' => 'form-control input-sm', 'readonly' => true, 'id' => 'CA_INVBY_name']) }}
                                        <span class="input-group-btn">
                                            <a data-toggle="modal" class="btn btn-sm btn-primary" href="#carian-penyiasat" title="Carian Pegawai Penyiasat/Serbuan">Carian</a>
                                        </span>
                                        @if ($errors->has('CA_INVBY'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_INVBY') }}</strong></span>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('CA_CASEID') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CASEID', $mSasCase->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                        @if ($errors->has('CA_CASEID'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_CASEID') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_RCVTYP', SasCase::getrcvtyplist('259', true, 'descr'), old('CA_RCVTYP', $mSasCase->CA_RCVTYP), ['class' => 'form-control input-sm select2', 'id' => 'CA_RCVTYP']) }}
                                        @if ($errors->has('CA_RCVTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_BPANO" class="form-group{{ $errors->has('CA_BPANO') ? ' has-error' : '' }}" style="display: {{ (old('CA_RCVTYP')?(in_array(old('CA_RCVTYP'),['S14'])? 'block':'none') : ((in_array($mSasCase->CA_RCVTYP,['S14'])? 'block':'none'))) }};">
                                    {{ Form::label('CA_BPANO', 'No. Aduan BPA', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_BPANO', old('CA_BPANO', $mSasCase->CA_BPANO), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_BPANO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_BPANO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_SERVICENO" class="form-group{{ $errors->has('CA_SERVICENO') ? ' has-error' : '' }}" style="display: {{ (old('CA_RCVTYP')?(in_array(old('CA_RCVTYP'),['S33'])? 'block':'none') : ((in_array($mSasCase->CA_RCVTYP,['S33'])? 'block':'none'))) }};">
                                    {{ Form::label('CA_SERVICENO', 'No. Tali Khidmat', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_SERVICENO', $mSasCase->CA_SERVICENO, ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_SERVICENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SERVICENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h4>Maklumat Pengadu</h4>
                        <!--<div class="hr-line-solid"></div>-->
                        <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('CA_DOCNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        <!--<div class="input-group">-->
                                            {{ Form::text('CA_DOCNO', old('CA_DOCNO', $mSasCase->CA_DOCNO), ['class' => 'form-control input-sm', 'id' => 'DOCNO', 'maxlength' => 12]) }}
                                            <!--<span class="input-group-btn">-->
                                                <!--<button class="ladda-button ladda-button-demo btn btn-primary btn-sm" type="button" data-style="expand-right" id="CheckJpn">Semak JPN</button>-->
                                            <!--</span>-->
                                        <!--</div>-->
                                        @if ($errors->has('CA_DOCNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_DOCNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_EMAIL') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_EMAIL', old('CA_EMAIL', $mSasCase->CA_EMAIL), ['class' => 'form-control input-sm', 'id' => 'CA_EMAIL']) }}
                                        @if ($errors->has('CA_EMAIL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_MOBILENO', old('CA_MOBILENO', $mSasCase->CA_MOBILENO), ['class' => 'form-control input-sm', 'id' => 'CA_MOBILENO', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12]) }}
                                        @if ($errors->has('CA_MOBILENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_TELNO', old('CA_TELNO', $mSasCase->CA_TELNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                        @if ($errors->has('CA_TELNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_FAXNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_FAXNO', '', ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 20 ]) }}
                                        @if ($errors->has('CA_FAXNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_FAXNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::textArea('CA_ADDR', old('CA_ADDR', $mSasCase->CA_ADDR), ['rows' => 6, 'cols' => 30, 'class' => 'form-control input-sm', 'id' => 'CA_ADDR']) }}
                                        {{ Form::hidden('CA_MYIDENTITY_ADDR', old('CA_MYIDENTITY_ADDR', $mSasCase->CA_MYIDENTITY_ADDR), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_ADDR']) }}
                                        @if ($errors->has('CA_ADDR'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ADDR') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('CA_NAME') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_NAME', 'Nama', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_NAME', old('CA_NAME', $mSasCase->CA_NAME), ['class' => 'form-control input-sm', 'id' => 'CA_NAME']) }}
                                        @if ($errors->has('CA_NAME'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_AGE', old('CA_AGE', $mSasCase->CA_AGE), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 3]) }}
                                        <!--{{-- Form::select('CA_AGE', Ref::GetList('309', true), $mSasCase->CA_AGE, ['class' => 'form-control input-sm']) --}}-->
                                        @if ($errors->has('CA_AGE'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_SEXCD', Ref::GetList('202', true), old('CA_SEXCD', $mSasCase->CA_SEXCD), ['class' => 'form-control input-sm select2', 'id' => 'CA_SEXCD']) }}
                                        @if ($errors->has('CA_SEXCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_RACECD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), old('CA_RACECD', $mSasCase->CA_RACECD), ['class' => 'form-control input-sm select2']) }}
                                        @if ($errors->has('CA_RACECD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RACECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
<!--                                <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        <div class="col-sm-9">
                                            <div class="radio"><label> <input type="radio" value="1" name="CA_NATCD" {{ $mSasCase->CA_NATCD == 1? 'checked':'' }} onclick="natcd(this.value)"> <i></i> Warganegara </label></div>
                                            <div class="radio"><label> <input type="radio" value="0" name="CA_NATCD" {{ $mSasCase->CA_NATCD == 0? 'checked':'' }} onclick="natcd(this.value)"> <i></i> Bukan Warganegara</label></div>
                                        </div>
                                    </div>
                                </div>-->
                                <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        <div class="radio radio-success">
                                            <input id="CA_NATCD1" type="radio" name="CA_NATCD" value="1" onclick="natcd(this.value)" {{ $mSasCase->CA_NATCD == '1' ? 'checked' : '' }} >
                                            <label for="CA_NATCD1"> Warganegara </label>
                                        </div>
                                        <div class="radio radio-success">
                                            <input id="CA_NATCD2" type="radio" name="CA_NATCD" value="0" onclick="natcd(this.value)" {{ $mSasCase->CA_NATCD == '0' ? 'checked' : '' }} >
                                            <label for="CA_NATCD2"> Bukan Warganegara </label>
                                        </div>
                                        @if ($errors->has('CA_NATCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_NATCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <!--<div id="citizen" style="display: {{ $mSasCase->CA_NATCD == 1? 'block':'none' }}">-->
                                <!--<div id="warganegara" style="display: {{ (old('CA_NATCD')? (old('CA_NATCD') == '1'? 'block':'none') : ($mSasCase->CA_NATCD == '1' ? 'block' : 'none')) }}">-->
                                <div id="warganegara" style="display:block">
                                    <div class="form-group{{ $errors->has('CA_POSCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::text('CA_POSCD', old('CA_POSCD', $mSasCase->CA_POSCD), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                            {{ Form::hidden('CA_MYIDENTITY_POSCD', old('CA_MYIDENTITY_POSCD', $mSasCase->CA_MYIDENTITY_POSCD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_POSCD']) }}
                                            @if ($errors->has('CA_POSCD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_STATECD') ? ' has-error' : '' }}">
                                        <label for="CA_STATECD" class="col-sm-3 control-label {{ old('CA_ONLINEADD_IND') != ''? (old('CA_ONLINEADD_IND') == '0'? 'required':''):($mSasCase->CA_ONLINEADD_IND == '0'? 'required':'') }}">Negeri</label>
                                        <!--{{-- Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label']) --}}-->
                                        <div class="col-sm-9">
                                            {{ Form::select('CA_STATECD', Ref::getList('17'), old('CA_STATECD', $mSasCase->CA_STATECD), ['class' => 'form-control input-sm select2', 'id' => 'STATECD']) }}
                                            {{ Form::hidden('CA_MYIDENTITY_STATECD', old('CA_MYIDENTITY_STATECD', $mSasCase->CA_MYIDENTITY_STATECD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_STATECD']) }}
                                            @if ($errors->has('CA_STATECD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_DISTCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::select('CA_DISTCD', SasCase::getSelectedDist($mSasCase->CA_STATECD),old('CA_DISTCD', $mSasCase->CA_DISTCD), ['class' => 'form-control input-sm select2']) }}
                                            {{ Form::hidden('CA_MYIDENTITY_DISTCD', old('CA_MYIDENTITY_DISTCD', $mSasCase->CA_MYIDENTITY_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_DISTCD']) }}
                                            <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                            @if ($errors->has('CA_DISTCD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--<div id="noncitizen" style="display: {{ $mSasCase->CA_NATCD == 0? 'block':'none' }}">-->
                                <div id="bknwarganegara" style="display: {{ (old('CA_NATCD')? (old('CA_NATCD') == '0'? 'block':'none') : ($mSasCase->CA_NATCD == '0' ? 'block' : 'none')) }}">
                                    <div class="form-group{{ $errors->has('CA_COUNTRYCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_COUNTRYCD', 'Negara Asal', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::select('CA_COUNTRYCD', Ref::getList('334'), old('CA_COUNTRYCD', $mSasCase->CA_COUNTRYCD), ['class' => 'form-control input-sm select2']) }}
                                            {{ Form::hidden('CA_STATUSPENGADU', old('CA_STATUSPENGADU', $mSasCase->CA_STATUSPENGADU), ['class' => 'form-control input-sm', 'id' => 'CA_STATUSPENGADU']) }}
                                            @if ($errors->has('CA_COUNTRYCD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_COUNTRYCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h4>Maklumat Aduan</h4>
                        <!--<div class="hr-line-solid"></div>-->
                        <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('CA_CMPLCAT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_CMPLCAT', 'Kategori Aduan', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms', 'descr'), old('CA_CMPLCAT', $mSasCase->CA_CMPLCAT), array('class' => 'form-control input-sm select2', 'id' => 'CA_CMPLCAT')) }}
                                        @if ($errors->has('CA_CMPLCAT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_CMPLCD', SasCase::getcmplcdlist((old('CA_CMPLCAT')? old('CA_CMPLCAT') : ($mSasCase->CA_CMPLCAT? $mSasCase->CA_CMPLCAT:''))), (old('CA_CMPLCD')? old('CA_CMPLCD') : ($mSasCase->CA_CMPLCD? $mSasCase->CA_CMPLCD:'')), ['class' => 'form-control input-sm select2', 'id' => 'CA_CMPLCD']) }}
                                        @if ($errors->has('CA_CMPLCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_TTPMTYP" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_TTPMTYP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_TTPMTYP', 'Penuntut/Penentang', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_TTPMTYP', Ref::GetList('1108', true, 'ms', 'descr'), old('CA_TTPMTYP', $mSasCase->CA_TTPMTYP), ['class' => 'form-control input-sm select2']) }}
                                        @if ($errors->has('CA_TTPMTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_TTPMTYP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_TTPMNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_TTPMNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_TTPMNO', 'No. TTPM', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_TTPMNO', old('CA_TTPMNO', $mSasCase->CA_TTPMNO), ['class' => 'form-control input-sm'])}}
                                        @if ($errors->has('CA_TTPMNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_TTPMNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <!--<div id="div_CA_ONLINECMPL_AMOUNT" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">-->
<!--                                <div class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_ONLINECMPL_AMOUNT', '0.00', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                        @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_AMOUNT')</strong></span>
                                        @endif
                                    </div>
                                </div>-->
                                <div class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_ONLINECMPL_AMOUNT', number_format($mSasCase->CA_ONLINECMPL_AMOUNT, 2), ['class' => 'form-control input-sm', 'id'=>'CA_ONLINECMPL_AMOUNT', 'onkeypress' => "return isNumberKey1(event)"]) }}
                                        @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="CA_CMPLKEYWORD" style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($mSasCase->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none')))  }};">
                                    {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_CMPLKEYWORD', Ref::GetList('1051', true, 'ms'), old('CA_CMPLKEYWORD', $mSasCase->CA_CMPLKEYWORD), ['class' => 'form-control input-sm select2'])}}
                                        @if ($errors->has('CA_CMPLKEYWORD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLKEYWORD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_SERVICE_PROVIDER_INFO" style="display: {{ (old('CA_CMPLCAT') ? (old('CA_CMPLCAT') == 'BPGK 19' ? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19' ? 'block':'none')) }}">
                                    <h4>Maklumat Penjual / Pihak Yang Diadu</h4>
                                    <!--<div class="hr-line-solid"></div>-->
                                    <hr style="background-color: #ccc; height: 1px; width: 206%; border: 0;">
                                </div>
                                <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_ONLINECMPL_PROVIDER', Ref::GetList('1091', true, 'ms', 'descr'), old('CA_ONLINECMPL_PROVIDER', $mSasCase->CA_ONLINECMPL_PROVIDER), ['class' => 'form-control input-sm select2', 'id' => 'CA_ONLINECMPL_PROVIDER']) }}
                                        @if ($errors->has('CA_ONLINECMPL_PROVIDER'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PROVIDER') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_URL" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                    <!--{{-- Form::label('CA_ONLINECMPL_URL', 'Laman Web / URL', ['class' => 'col-sm-5 control-label']) --}}-->
                                    <label for="CA_ONLINECMPL_URL" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19' && old ('CA_ONLINECMPL_PROVIDER') == '999' ? 'required':'' }}">Laman Web / URL / ID</label>
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_ONLINECMPL_URL', old('CA_ONLINECMPL_URL', $mSasCase->CA_ONLINECMPL_URL), ['class' => 'form-control input-sm', 'placeholder' => '(Contoh: www.google.com)']) }}
                                        @if ($errors->has('CA_ONLINECMPL_URL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_URL') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_PYMNTTYP" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PYMNTTYP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-sm-5 control-label required']) }}
                                    <div class="col-sm-7">
                                        <!--{{-- Form::text('CA_ONLINECMPL_PYMNTTYP', $mSasCase->CA_ONLINECMPL_PYMNTTYP, ['class' => 'form-control input-sm']) --}}-->
                                        {{ Form::select('CA_ONLINECMPL_PYMNTTYP', Ref::GetList('1207', true, 'ms'), old('CA_ONLINECMPL_PYMNTTYP', $mSasCase->CA_ONLINECMPL_PYMNTTYP), ['class' => 'form-control input-sm select2', 'id' => 'CA_ONLINECMPL_PYMNTTYP']) }}
                                        @if ($errors->has('CA_ONLINECMPL_PYMNTTYP'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PYMNTTYP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_BANKCD" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                                    <label for="CA_ONLINECMPL_BANKCD" class="col-sm-5 control-label {{ old('CA_ONLINECMPL_PYMNTTYP') ? (in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '':'required') : (in_array($mSasCase->CA_ONLINECMPL_PYMNTTYP,['','COD','TB']) ? '' : 'required') }}">Nama Bank</label>
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, 'ms'), old('CA_ONLINECMPL_BANKCD', $mSasCase->CA_ONLINECMPL_BANKCD), ['class' => 'form-control input-sm select2', 'id' => 'CA_ONLINECMPL_BANKCD']) }}
                                        @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_BANKCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_ACCNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                    <label for="CA_ONLINECMPL_ACCNO" class="col-sm-5 control-label {{ old('CA_ONLINECMPL_PYMNTTYP') ? (in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '':'required') : (in_array($mSasCase->CA_ONLINECMPL_PYMNTTYP,['','COD','TB']) ? '' : 'required') }}">No. Akaun Bank</label>
                                    <!--{{-- Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun', ['class' => 'col-sm-5 control-label required']) --}}-->
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_ONLINECMPL_ACCNO', old('CA_ONLINECMPL_ACCNO', $mSasCase->CA_ONLINECMPL_ACCNO), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_ACCNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_PREMISE" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                    <label for="CA_AGAINST_PREMISE" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Jenis Premis</label>
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), old('CA_AGAINST_PREMISE', $mSasCase->CA_AGAINST_PREMISE), ['class' => 'form-control input-sm select2', 'id' => 'CA_AGAINST_PREMISE']) }}
                                        @if ($errors->has('CA_AGAINST_PREMISE'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_PREMISE') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" id="checkpernahadu">
                                    {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        <div class="checkbox checkbox-success">
                                            <input name="CA_ONLINECMPL_IND" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' || $mSasCase->CA_ONLINECMPL_IND == '1'? 'checked':'' }}>
                                            <label for="CA_ONLINECMPL_IND">
                                                Pernah membuat aduan secara rasmi kepada Pembekal Perkhidmatan?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_CASENO" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'? 'block':($mSasCase->CA_CMPLCAT == 'BPGK 19' && $mSasCase->CA_ONLINECMPL_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mSasCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINECMPL_IND') == '' && $mSasCase->CA_ONLINECMPL_IND == '1'? 'block':(old('CA_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_ONLINECMPL_CASENO', old('CA_ONLINECMPL_CASENO', $mSasCase->CA_ONLINECMPL_CASENO), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_ONLINECMPL_CASENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_CASENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div id="div_ONLINECMPL" style="height: 210px; display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }}"></div>
                                <div class="form-group{{ $errors->has('CA_AGAINSTNM') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINSTNM', 'Nama (Syarikat/Premis/Penjual)', ['class' => 'col-sm-6 control-label required']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_AGAINSTNM', old('CA_AGAINSTNM', $mSasCase->CA_AGAINSTNM), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_AGAINSTNM'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTNM') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <!--<div class="col-sm-5"></div>-->
                                <!--<div class="col-sm-7">** Diperlukan salah satu</div>-->
                                <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon (Pejabat)', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_AGAINST_TELNO', old('CA_AGAINST_TELNO', $mSasCase->CA_AGAINST_TELNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                        @if ($errors->has('CA_AGAINST_TELNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_AGAINST_MOBILENO', old('CA_AGAINST_MOBILENO', $mSasCase->CA_AGAINST_MOBILENO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12]) }}
                                        @if ($errors->has('CA_AGAINST_MOBILENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::email('CA_AGAINST_EMAIL', old('CA_AGAINST_EMAIL', $mSasCase->CA_AGAINST_EMAIL), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_AGAINST_EMAIL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_AGAINST_FAXNO', old('CA_AGAINST_FAXNO', $mSasCase->CA_AGAINST_FAXNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                        @if ($errors->has('CA_AGAINST_FAXNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_FAXNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
<!--                                <div id="checkinsertadd" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group">
                                    {{ Form::label('', '', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        <div class="checkbox checkbox-success">
                                            <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' || $mSasCase->CA_ONLINEADD_IND == '1'? 'checked':'' }}>
                                            <label for="CA_ONLINEADD_IND">
                                                Mempunyai alamat penuh penjual / pihak yang diadu?
                                            </label>
                                        </div>
                                    </div>
                                </div>-->
                                <div id="checkinsertadd" class="form-group{{ $errors->has('CA_ONLINEADD_IND') ? ' has-error' : '' }}" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mSasCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                    {{ Form::label('', 'Mempunyai alamat penuh penjual / pihak yang diadu?', ['class' => 'col-lg-12 control-label']) }}
                                    <div class="col-lg-12 col-lg-offset-6">
                                        <div class="radio radio-success radio-inline">
                                            <!--<input id="CA_ONLINEADD_IND1" type="radio" name="CA_ONLINEADD_IND" value="1" onclick="onlineaddind(this.value)" {{ old('CA_ONLINEADD_IND') != ''? (old('CA_ONLINEADD_IND') == '1'? 'checked':''):($mSasCase->CA_ONLINEADD_IND == '1'? 'checked':'') }} >-->
                                            {{ Form::radio('CA_ONLINEADD_IND', '1', 
                                            old('CA_ONLINEADD_IND') != ''? (old('CA_ONLINEADD_IND') == '1'? 'checked':''):($mSasCase->CA_ONLINEADD_IND == '1'? 'checked':''), 
                                            array('id'=> 'CA_ONLINEADD_IND1', 'onclick'=>'onlineaddind(this.value)')) }}
                                            <label for="CA_ONLINEADD_IND1"> Ya </label>
                                        </div>
                                        <div class="radio radio-success radio-inline">
                                            <!--<input id="CA_ONLINEADD_IND0" type="radio" name="CA_ONLINEADD_IND" value="0" onclick="onlineaddind(this.value)" {{ old('CA_ONLINEADD_IND') != ''? (old('CA_ONLINEADD_IND') == '0'? 'checked':''):($mSasCase->CA_ONLINEADD_IND == '0'? 'checked':'') }} >-->
                                            {{ Form::radio('CA_ONLINEADD_IND', '0', 
                                            old('CA_ONLINEADD_IND') != ''? (old('CA_ONLINEADD_IND') == '0'? 'checked':''):($mSasCase->CA_ONLINEADD_IND == '0'? 'checked':''), 
                                            array('id'=> 'CA_ONLINEADD_IND0', 'onclick'=>'onlineaddind(this.value)')) }}
                                            <label for="CA_ONLINEADD_IND0"> Tidak </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('CA_ONLINEADD_IND'))
                                        <div class="col-lg-10 col-lg-offset-2">
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINEADD_IND') }}</strong></span>
                                        </div>
                                    @endif
                                </div>
                                <div id="div_CA_AGAINSTADD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == '1'? 'block':($mSasCase->CA_CMPLCAT == 'BPGK 19' && $mSasCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mSasCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mSasCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == '1'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                                    <!--<label for="CA_AGAINSTADD" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Alamat</label>-->
                                    {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-sm-6 control-label required']) }}
                                    <div class="col-sm-6">
                                        {{ Form::textarea('CA_AGAINSTADD', old('CA_AGAINSTADD', $mSasCase->CA_AGAINSTADD), ['class' => 'form-control input-sm', 'rows'=> '4']) }}
                                        @if ($errors->has('CA_AGAINSTADD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTADD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_POSTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == '1'? 'block':($mSasCase->CA_CMPLCAT == 'BPGK 19' && $mSasCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mSasCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mSasCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == '1'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                                    <!--<label for="CA_AGAINST_POSTCD" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Poskod</label>-->
                                    {{ Form::label('CA_AGAINST_POSTCD', 'Poskod', ['class' => 'col-sm-6 control-label']) }}
                                    <div class="col-sm-6">
                                        {{ Form::text('CA_AGAINST_POSTCD', old('CA_AGAINST_POSTCD', $mSasCase->CA_AGAINST_POSTCD), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                        @if ($errors->has('CA_AGAINST_POSTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_POSTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_STATECD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == '1'? 'block':($mSasCase->CA_CMPLCAT == 'BPGK 19' && $mSasCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mSasCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mSasCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == '1'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-6 control-label required']) }}
                                    <div class="col-sm-6">
                                        {{ Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true, 'ms'), old('CA_AGAINST_STATECD', $mSasCase->CA_AGAINST_STATECD), ['class' => 'form-control input-sm select2', 'id' => 'CA_AGAINST_STATECD']) }}
                                        @if ($errors->has('CA_AGAINST_STATECD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_DISTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == '1'? 'block':($mSasCase->CA_CMPLCAT == 'BPGK 19' && $mSasCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mSasCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mSasCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == '1'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-6 control-label required']) }}
                                    <div class="col-sm-6">
                                        {{ Form::select('CA_AGAINST_DISTCD', ($mSasCase->CA_AGAINST_STATECD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($mSasCase->CA_AGAINST_STATECD, '18', true, 'ms')), $mSasCase->CA_AGAINST_DISTCD, ['class' => 'form-control input-sm select2', 'id' => 'CA_AGAINST_DISTCD']) }}
                                        <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                        @if ($errors->has('CA_AGAINST_DISTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_SSP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_SSP', 'Wujud Kes?', ['class' => 'col-sm-6 control-label required']) }}
                                    <div class="col-sm-6">
                                        <div class="radio radio-success radio-inline">
                                            <input id="yes" type="radio" value="YES" name="CA_SSP" onclick="check(this.value)" {{ old('CA_SSP') == 'YES'||$mSasCase->CA_SSP == 'YES'? 'checked':'' }}><label for="yes">Ya</label>
                                        </div>
                                        <div class="radio radio-success radio-inline">
                                            <input id="no" type="radio" value="NO" name="CA_SSP" onclick="check(this.value)" {{ old('CA_SSP') == 'NO'||$mSasCase->CA_SSP == 'NO'? 'checked':'' }}><label for="no">Tidak</label>
                                        </div>
                                        @if ($errors->has('CA_SSP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_SSP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="ssp" style="display: {{ old('CA_SSP')? (old('CA_SSP') == 'YES'? 'block':'none') : ($mSasCase->CA_SSP == 'YES'? 'block' : 'none') }}">
                                <!--<div id="ssp" style="display: {{-- $errors->has('CA_IPNO')||$errors->has('CA_AKTA')||$errors->has('CA_SUBAKTA')||$mSasCase->CA_SSP == 'YES'? 'block' : 'none' --}}">-->
                                    <h4>Senarai Akta</h4>
                                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a onclick="ModalCreateAkta('{{ $mSasCase->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Akta</a>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="sas-kes-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>Bil.</th>
                                                    <th>No. Kertas Siasatan / EP</th>
                                                    <th>Akta</th>
                                                    <th>Jenis Kesalahan</th>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-2 control-label required']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('CA_SUMMARY', old('CA_SUMMARY', $mSasCase->CA_SUMMARY), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                        <span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>
                                        @if ($errors->has('CA_SUMMARY'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_RESULT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RESULT', 'Hasil Siasatan', ['class' => 'col-sm-2 control-label required']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('CA_RESULT', old('CA_RESULT', $mSasCase->CA_RESULT), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                        @if ($errors->has('CA_RESULT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RESULT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_ANSWER') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-sm-2 control-label required']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('CA_ANSWER', old('CA_ANSWER', $mSasCase->CA_ANSWER), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                        @if ($errors->has('CA_ANSWER'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ANSWER') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12" align="center">
                                {{ Form::submit('Kemaskini', ['class' => 'btn btn-primary btn-sm']) }}
                                <button type="submit" class="btn btn-primary btn-sm" name="submit" value="2">Kemaskini & Hantar Emel</button>
                                <a href="{{ url('sas-case')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div id="attachment" class="tab-pane">
                    <div class="panel-body">
                        <h4>Senarai Lampiran Aduan</h4>
                        <p>(Maksimum 5 Lampiran Aduan sahaja)</p>
                        @if ( $countSasCaseDoc < 5 )
                            <div class="row">
                                <div class="col-sm-12">
                                    <a onclick="ModalAttachmentCreate('{{ $mSasCase->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Lampiran Aduan</a>
                                </div>
                                <br>
                            </div>
                            <br />
                        @endif
                        <div class="table-responsive">
                            <table style="width: 100%" id="sas-case-attachmnt-table" class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>Nama Fail</th>
                                        <th>Catatan</th>
                                        <th>Tarikh Muatnaik</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                        <h4>Senarai Bukti Siasatan</h4>
                        <div class="row">
                            <div class="col-sm-12">
                                <p>(Maksimum 8 Bukti Siasatan sahaja)</p>
                                @if($countSasCaseDocSiasat < 8)
                                    <a onclick="ModalDocSiasatCreate('{{ $mSasCase->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Bukti Siasatan</a>
                                @endif
                            </div>
                        </div>
                        <br />
                        <div class="table-responsive">
                            <table id="sas-case-attachment-siasat-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>Nama Fail</th>
                                        <th>Catatan</th>
                                        <th>Tarikh Muatnaik</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12" align="center">
                                <a class="btn btn-warning btn-sm" href="{{ url('sas-case') }}">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="transaction" class="tab-pane">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table style="width: 100%" id="sas-case-transaction-table" class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>Status Aduan</th>
                                        <!--<th>Daripada</th>-->
                                        <!--<th>Kepada</th>-->
                                        <th>Aktiviti</th>
                                        <th>Pegawai</th>
                                        <th>Tarikh Transaksi</th>
                                        <!--<th>Fail</th>-->
                                        <!--<th>Tindakan</th>-->
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12" align="center">
                                <a class="btn btn-warning btn-sm" href="{{ url('sas-case') }}">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--</div>-->
</div>

<!--<div id="carian-penyiasat" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title">Carian Pegawai Penyiasat / Serbuan</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('icnew', 'No. Kad Pengenalan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('icnew', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('name', 'Nama Pengguna', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('state_cd', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                                    {{-- Form::text('state_cd', Ref::GetDescr('17', $mUser->state_cd), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('brn_cd', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm']) }}
                                    {{-- Form::text('brn_cd', Branch::GetBranchName($mUser->brn_cd), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('role_code', 'Peranan', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('role_code', Ref::GetList('152', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'role_code']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                            {{ Form::reset('Semula', ['class' => 'btn btn-default btn-sm', 'id' => 'resetbtn']) }}
                        </div>
                    </div>
                {!! Form::close() !!}
                <div class="table-responsive">
                    <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>No. Kad Pengenalan</th>
                                <th>Nama Pengguna</th>
                                <th>Negeri</th>
                                <th>Cawangan</th>
                                <th>Peranan</th>
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
</div>-->

@include('sas-case.usersearchmodal')

<!-- Modal Create Attachment Start -->
<div class="modal fade" id="modal-create-attachment" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content" id='modalCreateContent'>
        </div>
    </div>
</div>
<!-- Modal Edit Attachment Start -->
<div class="modal fade" id="modal-edit-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalEditContent'></div>
    </div>
</div>
<!-- Modal Create Attachment Start -->
<div class="modal fade" id="modal-attachment-siasat-create" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalContentSiasatCreate'></div>
    </div>
</div>
<!-- Modal Update Attachment Start -->
<div class="modal fade" id="modal-attachment-siasat-edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalContentSiasatEdit'></div>
    </div>
</div>
<!-- Modal Create Kes Start -->
<div class="modal fade" id="modal-create-kes" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalCreateKes'></div>
    </div>
</div>
<!-- Modal Update Kes Start -->
<div class="modal fade" id="modal-edit-kes" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalEditKes'></div>
    </div>
</div>
@stop

@section('script_datatable')
<script>
    function natcd(cd){
        if(cd === '1') {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        }else{
            $('#warganegara').show();
            $('#bknwarganegara').show();
        }
    }
    
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href='+hash+']').tab('show');
    } 

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
    
//    $('#CheckJpn').on('click', function (e) {
    $('#DOCNO').blur(function(){
        var DOCNO = $('#DOCNO').val();
        var l = $('.ladda-button-demo').ladda();
        $.ajax({
            type: 'GET',
            url: "{{ url('admin-case/tojpn') }}" + "/" + DOCNO,
            dataType: "json",
            beforeSend: function () {
                l.ladda('start');
            },
            success: function (data) {
                console.log(data.Name);
                 if (data.Message) {
                    alert(data.Message);
                }
                $('#CA_EMAIL').val(data.EmailAddress); // Email
                $('#CA_MOBILENO').val(data.MobilePhoneNumber); // Telefon Bimbit
                $('#CA_STATUSPENGADU').val(data.StatusPengadu); // Status Pengadu
                $('#CA_NAME').val(data.Name); // Nama
                $('#CA_AGE').val(data.Age); // Umur
                $('#CA_SEXCD').val(data.Gender).trigger('change'); // Jantina
                if (data.Warganegara != '') {
                    if (data.Warganegara === '1') { // Warganegara
                        document.getElementById('CA_NATCD1').checked = true;
                        $('#warganegara').show();
                        $('#bknwarganegara').hide();
                    } else {
                        document.getElementById('CA_NATCD2').checked = true;
                        $('#warganegara').hide();
                        $('#bknwarganegara').show();
                    }
                }
                // Standard Field
                $('#CA_ADDR').val(data.CorrespondenceAddress1 + '\n' + data.CorrespondenceAddress2); // Alamat
                $('#CA_POSCD').val(data.CorrespondenceAddressPostcode); // Poskod
                $('#STATECD').val(data.CorrespondenceAddressStateCode).trigger('change'); // Negeri
                getDistListFromJpn(data.CorrespondenceAddressStateCode, data.KodDaerah); // Daerah
                // MyIdentity Field
                $('#CA_MYIDENTITY_ADDR').val(data.CorrespondenceAddress1 + '\n' + data.CorrespondenceAddress2); // Alamat MyIdentity
                $('#CA_MYIDENTITY_POSCD').val(data.CorrespondenceAddressPostcode); // Poskod MyIdentity
                $('#CA_MYIDENTITY_STATECD').val(data.CorrespondenceAddressStateCode); // Negeri MyIdentity
                $('#CA_MYIDENTITY_DISTCD').val(data.KodDaerah); // Daerah MyIdentity
                l.ladda('stop');
            },
            error: function (data) {
                console.log(data);
                if (data.status == '500') {
                    alert(data.statusText);
                }
                ;
                l.ladda('stop');
            },
            complete: function (data) {
                console.log(data);
                l.ladda('stop');
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
                    $('select[name="CA_DISTCD"]').empty();
                    $.each(data, function (key, value) {
                        if (DistCd === value)
                            $('select[name="CA_DISTCD"]').append('<option value="' + value + '" selected="selected">' + key + '</option>');
                        else
                            $('select[name="CA_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                },
                complete: function (data) {
                    $('#CA_DISTCD').val(DistCd).trigger('change');
                }
            });
        }else{
            $('select[name="CA_DISTCD"]').empty();
            $('select[name="CA_DISTCD"]').append('<option value="">-- SILA PILIH --</option>');
        }
    }
    
    $(function() {
        $('#CA_RCVTYP').on('change', function (e) {
            var CA_RCVTYP = $(this).val();
            if(CA_RCVTYP === 'S14') {
                $("#div_CA_BPANO").show();
            }else{
                $("#div_CA_BPANO").hide();
            }
            if(CA_RCVTYP === 'S33') {
                $("#div_CA_SERVICENO").show();
            }else{
                $("#div_CA_SERVICENO").hide();
            }
        });
        $('#STATECD').on('change', function (e) {
            var STATECD = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('sas-case/getdistrictlist') }}" + "/" + STATECD,
                dataType: "json",
                success:function(data){
                    $('select[name="CA_DISTCD"]').empty();
                    $.each(data, function(key, value) {
                        if(value === '0')
                            $('select[name="CA_DISTCD"]').append('<option value="">'+ key +'</option>');
                        else
                            $('select[name="CA_DISTCD"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });
                },
                complete: function (data) {
                    $('#CA_DISTCD').trigger('change');
                }
            }); 
        });
        $('#CA_AGAINST_STATECD').on('change', function (e) {
            var STATECD = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('sas-case/getdistrictlist') }}" + "/" + STATECD,
                dataType: "json",
                success:function(data){
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function(key, value) {
                        if(value === '0')
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="">'+ key +'</option>');
                        else
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });
                },
                complete: function (data) {
                    $('#CA_AGAINST_DISTCD').trigger('change');
                }
            }); 
        });
        
        $('#CA_ONLINECMPL_PROVIDER').on('change', function (e) {
            var CA_ONLINECMPL_PROVIDER = $(this).val();
            if(CA_ONLINECMPL_PROVIDER === '999')
                $( "label[for='CA_ONLINECMPL_URL']" ).addClass( "required" );
            else
                $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
        });
        
        $('#CA_ONLINECMPL_PYMNTTYP').on('change', function (e) {
            var CA_ONLINECMPL_PYMNTTYP = $(this).val();
            var requiredPaymentTypes = ['CRC', 'OTF', 'CDM'];
            var isInclude = requiredPaymentTypes.includes(CA_ONLINECMPL_PYMNTTYP);
            // if (CA_ONLINECMPL_PYMNTTYP === 'COD' || CA_ONLINECMPL_PYMNTTYP === '') {
            //     $("label[for='CA_ONLINECMPL_BANKCD']").removeClass("required");
            //     $("label[for='CA_ONLINECMPL_ACCNO']").removeClass("required");
            // } else {
            //     $("label[for='CA_ONLINECMPL_BANKCD']").addClass("required");
            //     $("label[for='CA_ONLINECMPL_ACCNO']").addClass("required");
            // }
            if (isInclude) {
                $('label[for="CA_ONLINECMPL_BANKCD"]').addClass('required');
                $('label[for="CA_ONLINECMPL_ACCNO"]').addClass('required');
            } else {
                $('label[for="CA_ONLINECMPL_BANKCD"]').removeClass('required');
                $('label[for="CA_ONLINECMPL_ACCNO"]').removeClass('required');
            }
        });
        
        $('#CA_CMPLCAT').on('change', function (e) {
            var CA_CMPLCAT = $(this).val();
            
            if(CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
                $( "#CA_CMPLKEYWORD" ).show();
            }else{
                $( "#CA_CMPLKEYWORD" ).hide();
            }
            if(CA_CMPLCAT === 'BPGK 08') {
                $( "#div_CA_TTPMTYP" ).show();
                $( "#div_CA_TTPMNO" ).show();
            }else{
                $( "#div_CA_TTPMTYP" ).hide();
                $( "#div_CA_TTPMNO" ).hide();
            }
            if(CA_CMPLCAT === 'BPGK 19') {
                if (document.getElementById('CA_ONLINECMPL_IND').checked)
                    $('#div_CA_ONLINECMPL_CASENO').show();
                else
                    $('#div_CA_ONLINECMPL_CASENO').hide();
                
                $( "#checkpernahadu" ).show();
                $( "#checkinsertadd" ).show();
                $('#div_CA_ONLINECMPL_PROVIDER').show();
                $('#div_CA_ONLINECMPL_URL').show();
                $('#div_CA_ONLINECMPL_BANKCD').show();
                $('#div_CA_ONLINECMPL_AMOUNT').show();
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_ONLINECMPL_PYMNTTYP').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
                $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINST_PREMISE']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINSTADD']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINST_POSTCD']" ).removeClass( "required" );
                $('#div_SERVICE_PROVIDER_INFO').show();
                $('#div_ONLINECMPL').show();
//                if (document.getElementById('CA_ONLINEADD_IND').checked) {
//                    $('#div_CA_AGAINSTADD').show();
//                    $('#div_CA_AGAINST_POSTCD').show();
//                    $('#div_CA_AGAINST_STATECD').show();
//                    $('#div_CA_AGAINST_DISTCD').show();
//                    $("label[for='CA_STATECD']").removeClass("required");
//                    $("label[for='CA_DISTCD']").removeClass("required");
//                }else{
//                    $('#div_CA_AGAINSTADD').hide();
//                    $('#div_CA_AGAINST_POSTCD').hide();
//                    $('#div_CA_AGAINST_STATECD').hide();
//                    $('#div_CA_AGAINST_DISTCD').hide();
//                    $("label[for='CA_STATECD']").addClass("required");
//                    $("label[for='CA_DISTCD']").addClass("required");
//                }
                onlineaddind($('input[name=CA_ONLINEADD_IND]:checked').val());
            }else{
                $( "#checkpernahadu" ).hide();
                $( "#checkinsertadd" ).hide();
                $('#div_CA_ONLINECMPL_CASENO').hide();
                $('#div_CA_ONLINECMPL_PROVIDER').hide();
                $('#div_CA_ONLINECMPL_URL').hide();
                $('#div_CA_ONLINECMPL_BANKCD').hide();
                $('#div_CA_ONLINECMPL_AMOUNT').hide();
                $('#div_CA_ONLINECMPL_PYMNTTYP').hide();
                $('#div_CA_ONLINECMPL_ACCNO').hide();
                $('#div_CA_AGAINST_PREMISE').show();
                $('#div_CA_AGAINSTADD').show();
                $('#div_CA_AGAINST_POSTCD').show();
                $('#div_CA_AGAINST_STATECD').show();
                $('#div_CA_AGAINST_DISTCD').show();
//                $( "label[for='CA_AGAINST_PREMISE']" ).addClass( "required" );
//                $( "label[for='CA_AGAINSTADD']" ).addClass( "required" );
//                $( "label[for='CA_AGAINST_POSTCD']" ).addClass( "required" );
                $('#div_SERVICE_PROVIDER_INFO').hide();
                $('#div_ONLINECMPL').hide();
                $("label[for='CA_STATECD']").removeClass("required");
                $("label[for='CA_DISTCD']").removeClass("required");
                onlineaddind('1');
            }
            
            $.ajax({
                type: 'GET',
                url: "{{ url('sas-case/getcmplcdlist') }}" + "/" + CA_CMPLCAT,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('select[name="CA_CMPLCD"]').empty();
                    $.each(data, function (key, value) {
                        if (value === '0'){
                            $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                            $('select[name="CA_CMPLCD"]').trigger('change');
                        }else{
                            $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                            $('select[name="CA_CMPLCD"]').trigger('change');
                        }
                    });
                }
            });
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
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('sas-case/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd').val();
                    d.brn_cd = $('#brn_cd').val();
                    d.role_code = $('#role_code').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ]
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
        
        var CaseAttachmentTable = $('#sas-case-attachmnt-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bPaginate: false,
            bInfo: false,
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod.',
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
                url: "{{ url('sas-case/getdataattach',$mSasCase->CA_CASEID)}}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
//                {data: 'doc_title', name: 'doc_title'},
//                {data: 'file_name_sys', name: 'file_name_sys'},
//                {data: 'file_name_sys', name: 'file_name_sys', searchable: false, orderable: false}
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
                {data: 'CC_REMARKS', name: 'CC_REMARKS'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ]
        });
    });
    
    function myFunction(id) {
        $.ajax({
            url: "{{ url('sas-case/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("CA_INVBY_name").value = key;
                    document.getElementById("CA_INVBY_id").value = value;
                });
                $('#carian-penyiasat').modal('hide');
            }
        });
    };
    
    function onlinecmplind() {
        if (document.getElementById('CA_ONLINECMPL_IND').checked){
            $('#div_CA_ONLINECMPL_CASENO').show();
        } else {
            $('#div_CA_ONLINECMPL_CASENO').hide();
        }
    };
    
//    function onlineaddind() {
//        if (document.getElementById('CA_ONLINEADD_IND').checked) {
//            $('#div_CA_AGAINSTADD').show();
//            $('#div_CA_AGAINST_POSTCD').show();
//            $('#div_CA_AGAINST_STATECD').show();
//            $('#div_CA_AGAINST_DISTCD').show();
//            $("label[for='CA_STATECD']").removeClass("required");
//            $("label[for='CA_DISTCD']").removeClass("required");
//        }else{
//            $('#div_CA_AGAINSTADD').hide();
//            $('#div_CA_AGAINST_POSTCD').hide();
//            $('#div_CA_AGAINST_STATECD').hide();
//            $('#div_CA_AGAINST_DISTCD').hide();
//            $("label[for='CA_STATECD']").addClass("required");
//            $("label[for='CA_DISTCD']").addClass("required");
//        }
//    };
    function onlineaddind(cd){
        if(cd === '1') {
            $('#div_CA_AGAINSTADD').show();
            $('#div_CA_AGAINST_POSTCD').show();
            $('#div_CA_AGAINST_STATECD').show();
            $('#div_CA_AGAINST_DISTCD').show();
            $("label[for='CA_STATECD']").removeClass("required");
            $("label[for='CA_DISTCD']").removeClass("required");
        }else{
            $('#div_CA_AGAINSTADD').hide();
            $('#div_CA_AGAINST_POSTCD').hide();
            $('#div_CA_AGAINST_STATECD').hide();
            $('#div_CA_AGAINST_DISTCD').hide();
            $("label[for='CA_STATECD']").addClass("required");
            $("label[for='CA_DISTCD']").addClass("required");
        }
    }
    
    $(function() {
        var CaseTransactionTable = $('#sas-case-transaction-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bPaginate: false,
            bInfo: false,
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
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('sas-case/getdatatransaction',$mSasCase->CA_CASEID)}}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
//                {data: 'CD_ACTTYPE', name: 'CD_ACTTYPE'},
                {data: 'CD_INVSTS', name: 'CD_INVSTS'},
//                {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
//                {data: 'CD_ACTTO', name: 'CD_ACTTO'},
                {data: 'CD_DESC', name: 'CD_DESC'},
                {data: 'CD_CREBY', name: 'CD_CREBY'},
                {data: 'CD_CREDT', name: 'CD_CREDT'},
//                {data: 'file_name_sys', name: 'file_name_sys'},
//                {data: 'file_name_sys', name: 'file_name_sys', searchable: false, orderable: false}
            ]
        });
        
        $('#btnsubmitcreate').click(function (e) {
            e.preventDefault();
            var $form = $('#form-create-attachment');
            var formData = {};
            $form.find(':input').each(function () {
                formData[ $(this).attr('name') ] = $(this).val();
            });
            $.ajax({
                type: 'POST',
                url: "{{ url('sas-case/ajaxvalidatestoreattachment') }}",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if (data['fails']) {
                        $('.form-group').removeClass('has-error');
                        $('.help-block').hide().text();
                        $.each(data['fails'], function (key, value) {
                            $("#form-create-attachment div[id=" + key + "_field]").addClass('has-error');
                            $("#form-create-attachment span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                            console.log(key);
                        });
                        console.log("fails");
                    } else {
                        $('#form-create-attachment').submit();
                        console.log("success");
                    }
                }
            });
        });
    });
    
    function ModalAttachmentCreate(id) {
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('sas-case.createdoc','') }}" + "/" + id);
        return false;
        $("#btnsubmit").click(function(){
//            var formData = new FormData($(this)[0]);
//            var formData = new FormData($("#attachment-form")[0]);
            var file = $('#image').get(0).files[0];
//            console.log(formData);
//            var formData = $('#attachment-form').serialize();
//            
//             Setup Ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: "{{ url('sas-case/create-attachment') }}",
                type: 'POST',
                enctype: 'multipart/form-data',
                data: file,
                success: function (data) {
                    console.log(data);
//                    $('#ModalAttachment').modal("hide");
                },
                error: function(data)
                {
                    console.log(data);
                },
                cache: false,
                processData: false
            });
        });
    }
    
    function CloseModal() {
        $('#ModalAttachment').modal("hide");
    }
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    function isNumberKey1(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46)
            return false;
        return true;
    }
    function ModalAttachmentEdit(id) {
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('sas-case.editdoc','') }}" + "/" + id);
        return false;
    }
    function ModalDocSiasatCreate(CASEID) {
        $('#modal-attachment-siasat-create').modal("show").find("#modalContentSiasatCreate").load("{{ route('sas-case.createdocsiasat','') }}" + "/" + CASEID);
        return false;
    }
    function ModalDocSiasatEdit(CASEID) {
        $('#modal-attachment-siasat-edit').modal("show").find("#modalContentSiasatEdit").load("{{ route('sas-case.editdocsiasat','') }}" + "/" + CASEID);
        return false;
    }
    function ModalCreateAkta(CASEID) {
        $('#modal-create-kes').modal("show").find("#modalCreateKes").load("{{ route('sas-case.createakta','') }}" + "/" + CASEID);
        return false;
    }
    function ModalEditAkta(id) {
        $('#modal-edit-kes').modal("show").find("#modalEditKes").load("{{ route('sas-case.editakta','') }}" + "/" + id);
        return false;
    }
    
    function check(value) {
        if (value == 'YES') {
            $('#ssp').show();
        } else {
            $('#ssp').hide();
        }
    }
    
    $(document).ready(function(){
//        $("select").select2();
        $(".select2").select2();
        $('.datetime').one('click',function(){
            $(this).datetimepicker({
                format: 'DD-MM-YYYY hh:mm A',
                showMeridian: true,
                todayHighlight: true,
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                useCurrent: false,
                defaultDate: moment().startOf('day')
            });
            $(this).data("DateTimePicker").show();
        });
    });
    
    $('#sas-case-attachment-siasat-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        bInfo: false,
        language: {
            lengthMenu: 'Paparan _MENU_ rekod',
            zeroRecords: 'Tiada rekod ditemui',
            info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
            infoEmpty: 'Tiada rekod ditemui',
            infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
        },
        ajax: {
            url: "{{ url('sas-case/getdocsiasat', $mSasCase->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
    
    $('#sas-kes-table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        bInfo: false,
        language: {
            lengthMenu: 'Paparan _MENU_ rekod',
            zeroRecords: 'Tiada rekod ditemui',
            info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
            infoEmpty: 'Tiada rekod ditemui',
            infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
        },
        ajax: {
            url: "{{ url('sas-case/getakta', $mSasCase->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CT_IPNO', name: 'CT_IPNO'},
            {data: 'CT_AKTA', name: 'CT_AKTA'},
            {data: 'CT_SUBAKTA', name: 'CT_SUBAKTA'},
            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
</script>
@stop