@extends('layouts.main')
<?php
    use Carbon\Carbon;
    use App\Ref;
    use App\Aduan\AdminCase;
    use App\Aduan\PublicCase;
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
<h2>Kemaskini Aduan Baru (Data Entry)</h2>
<!--<div class="row">-->
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
                        {!! Form::open(['route' => ['dataentry.update', $model->CA_CASEID], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                        {{ csrf_field() }}{{ method_field('PATCH') }}
                        <h4>Cara Terima</h4>
                        <!--<div class="hr-line-solid"></div>-->
                        <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('CA_RCVDT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RCVDT', 'Tarikh Terima Aduan', ['class' => 'col-lg-6 control-label required']) }}
                                    <div class="col-lg-6">
                                        {{ Form::text('CA_RCVDT', Carbon::parse($model->CA_RCVDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm datetime', 'readonly' => true]) }}
                                        @if ($errors->has('CA_RCVDT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_COMPLETEDT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_COMPLETEDT', 'Tarikh Selesai Aduan', ['class' => 'col-lg-6 control-label']) }}
                                    <div class="col-lg-6">
                                        <!--{{-- Form::text('CA_COMPLETEDT', Carbon::parse($model->CA_COMPLETEDT)->format('d-m-Y'), ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                        {{ Form::text('CA_COMPLETEDT', $model->CA_COMPLETEDT != '' ? Carbon::parse($model->CA_COMPLETEDT)->format('d-m-Y h:i A') : '', ['class' => 'form-control input-sm datetime', 'readonly' => true]) }}
                                        @if ($errors->has('CA_COMPLETEDT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_COMPLETEDT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-lg-6 control-label required']) }}
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            {{ Form::hidden('CA_INVBY', old('CA_INVBY', $model->CA_INVBY), ['class' => 'form-control input-sm', 'id' => 'CA_INVBY_id']) }}
                                            {{ Form::text('CA_INVBY_NAME', $InvBy, ['class' => 'form-control input-sm', 'readonly' => true, 'id' => 'CA_INVBY_name']) }}
                                            <span class="input-group-btn">
                                                <a data-toggle="modal" class="btn btn-sm btn-primary" href="#carian-penyiasat" title="Carian Pegawai Penyiasat/Serbuan">Carian</a>
                                            </span>
                                        </div>
                                        @if ($errors->has('CA_INVBY'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_INVBY') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('CA_CASEID') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_CASEID', $model->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                        @if ($errors->has('CA_CASEID'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CASEID') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_RCVTYP', AdminCase::getRefList('259', true), old('CA_RCVTYP', $model->CA_RCVTYP), ['class' => 'form-control input-sm select2', 'id' => 'CA_RCVTYP']) }}
                                        @if ($errors->has('CA_RCVTYP'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_BPANO" class="form-group{{ $errors->has('CA_BPANO') ? ' has-error' : '' }}" style="display: {{ (old('CA_RCVTYP')?(in_array(old('CA_RCVTYP'),['S14'])? 'block':'none') : ((in_array($model->CA_RCVTYP,['S14'])? 'block':'none'))) }};">
                                    {{ Form::label('CA_BPANO', 'No. Aduan BPA', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_BPANO', old('CA_BPANO', $model->CA_BPANO), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_BPANO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_BPANO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_SERVICENO" class="form-group{{ $errors->has('CA_SERVICENO') ? ' has-error' : '' }}" style="display: {{ (old('CA_RCVTYP')?(in_array(old('CA_RCVTYP'),['S33'])? 'block':'none') : ((in_array($model->CA_RCVTYP,['S33'])? 'block':'none'))) }};">
                                    {{ Form::label('CA_SERVICENO', 'No. Tali Khidmat', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_SERVICENO', old('CA_SERVICENO', $model->CA_SERVICENO), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_SERVICENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SERVICENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_INVSTS') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_INVSTS', 'Status Aduan', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        {{ Form::text('', $model->statusAduan ? $model->statusAduan->descr : $model->CA_INVSTS, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                        @if ($errors->has('CA_INVSTS'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_INVSTS') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="magncd" style="display: {{ $errors->has('CA_MAGNCD')||$model->CA_INVSTS == '4' ? 'block' : 'none' }} ;">
                                    <div class="form-group{{ $errors->has('CA_MAGNCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_MAGNCD', 'Agensi', ['class' => 'col-lg-5 control-label']) }}
                                        <div class="col-lg-7">
                                            {{ Form::text('', $model->agensi ? $model->agensi->MI_DESC : $model->CA_MAGNCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                            @if ($errors->has('CA_MAGNCD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_MAGNCD') }}</strong></span>
                                            @endif
                                        </div>
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
                                    {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-lg-7 control-label required']) }}
                                    <div class="col-lg-5">
                                        <!--<div class="input-group">-->
                                        {{ Form::text('CA_DOCNO', old('CA_DOCNO', $model->CA_DOCNO), ['class' => 'form-control input-sm', 'id' => 'CA_DOCNO', 'maxlength' => 15]) }}
                                            <!--<span class="input-group-btn">-->
                                                <!--<button class="ladda-button ladda-button-demo btn btn-primary btn-sm" type="button" data-style="expand-right" id="CheckJpn">Semak JPN</button>-->
                                            <!--</span>-->
                                        <!--</div>-->
                                        @if ($errors->has('CA_DOCNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_DOCNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6" style="color: red"><strong>** Diperlukan salah satu</strong></div>
                                <div class="form-group{{ $errors->has('CA_EMAIL') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-lg-7 control-label required1']) }}
                                    <div class="col-lg-5">
                                        <!--{{-- Form::text('CA_EMAIL', old('CA_EMAIL', $model->CA_EMAIL), ['class' => 'form-control input-sm']) --}}-->
                                        {{ Form::email('CA_EMAIL', old('CA_EMAIL', $model->CA_EMAIL), ['class' => 'form-control input-sm', 'id' => 'CA_EMAIL']) }}
                                        <style scoped>input:invalid, textarea:invalid { color: red; }</style>
                                        @if ($errors->has('CA_EMAIL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-lg-7 control-label required1']) }}
                                    <div class="col-lg-5">
                                        {{ Form::text('CA_MOBILENO', old('CA_MOBILENO', $model->CA_MOBILENO), ['class' => 'form-control input-sm', 'id' => 'CA_MOBILENO', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12 ]) }}
                                        @if ($errors->has('CA_MOBILENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-lg-7 control-label required1']) }}
                                    <div class="col-lg-5">
                                        {{ Form::text('CA_TELNO', old('CA_TELNO', $model->CA_TELNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                        @if ($errors->has('CA_TELNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_FAXNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-lg-7 control-label']) }}
                                    <div class="col-lg-5">
                                        {{ Form::text('CA_FAXNO', old('CA_FAXNO', $model->CA_FAXNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                        @if ($errors->has('CA_FAXNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_FAXNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-lg-7 control-label']) }}
                                    <div class="col-lg-5">
                                        {{ Form::textarea('CA_ADDR', old('CA_ADDR', $model->CA_ADDR), ['class' => 'form-control input-sm', 'rows'=>'4', 'id' => 'CA_ADDR']) }}
                                        {{ Form::hidden('CA_MYIDENTITY_ADDR', old('CA_MYIDENTITY_ADDR', $model->CA_MYIDENTITY_ADDR), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_ADDR']) }}
                                        @if ($errors->has('CA_ADDR'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ADDR') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('CA_NAME') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_NAME', 'Nama', ['class' => 'col-lg-4 control-label required']) }}
                                    <div class="col-lg-8">
                                        {{ Form::text('CA_NAME', old('CA_NAME', $model->CA_NAME), ['class' => 'form-control input-sm', 'id' => 'CA_NAME']) }}
                                        @if ($errors->has('CA_NAME'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-lg-4 control-label']) }}
                                    <div class="col-lg-8">
                                        {{ Form::text('CA_AGE', old('CA_AGE', $model->CA_AGE), ['class' => 'form-control input-sm', 'id' => 'CA_AGE','onkeypress' => "return isNumberKey(event)", 'maxlength' => 3 ]) }}
                                        <!--{{-- Form::select('CA_AGE', Ref::GetList('309', true, 'ms'), old('CA_AGE', $model->CA_AGE), ['class' => 'form-control input-sm', 'disabled' => 'true']) --}}-->
                                        @if ($errors->has('CA_AGE'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-lg-4 control-label']) }}
                                    <div class="col-lg-8">
                                        {{ Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), old('CA_SEXCD', $model->CA_SEXCD), ['class' => 'form-control input-sm select2', 'id' => 'CA_SEXCD']) }}
                                        @if ($errors->has('CA_SEXCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_RACECD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-lg-4 control-label']) }}
                                    <div class="col-lg-8">
                                        {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), old('CA_RACECD', $model->CA_RACECD), ['class' => 'form-control input-sm select2']) }}
                                        @if ($errors->has('CA_RACECD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RACECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-lg-4 control-label']) }}
                                    <div class="col-lg-8">
                                        <div class="radio radio-success">
                                            <input id="CA_NATCD1" type="radio" name="CA_NATCD" value="1" onclick="check(this.value)" {{ $model->CA_NATCD == '1' ? 'checked' : '' }} >
                                            <label for="CA_NATCD1"> Warganegara </label>
                                        </div>
                                        <div class="radio radio-success">
                                            <input id="CA_NATCD2" type="radio" name="CA_NATCD" value="0" onclick="check(this.value)" {{ $model->CA_NATCD == '0' ? 'checked' : '' }} >
                                            <label for="CA_NATCD2"> Bukan Warganegara </label>
                                        </div>
                                        @if ($errors->has('CA_NATCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_NATCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <!--<div id="warganegara" style="display: {{-- $model->CA_NATCD == '1' ? 'block' : 'none' --}}">-->
                                <div id="warganegara" style="display:block">
                                    <div class="form-group {{ $errors->has('CA_POSCD') ? ' has-error' : 'CA_POSCD' }}">
                                        {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-lg-4 control-label']) }}
                                        <div class="col-lg-8">
                                            {{ Form::text('CA_POSCD', old('CA_POSCD', $model->CA_POSCD), ['class' => 'form-control input-sm', 'id' => 'CA_POSCD', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                            {{ Form::hidden('CA_MYIDENTITY_POSCD', old('CA_MYIDENTITY_POSCD', $model->CA_MYIDENTITY_POSCD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_POSCD']) }}
                                            @if ($errors->has('CA_POSCD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('CA_STATECD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-lg-4 control-label required']) }}
                                        <div class="col-lg-8">
                                            {{ Form::select('CA_STATECD', Ref::GetList('17', true, 'ms'), old('CA_STATECD', $model->CA_STATECD), ['class' => 'form-control input-sm required select2', 'id' => 'CA_STATECD']) }}
                                            {{ Form::hidden('CA_MYIDENTITY_STATECD', old('CA_MYIDENTITY_STATECD', $model->CA_MYIDENTITY_STATECD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_STATECD']) }}
                                            @if ($errors->has('CA_STATECD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('CA_DISTCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-lg-4 control-label required']) }}
                                        <div class="col-lg-8">
                                            <!--{{-- Form::select('CA_DISTCD', $model->CA_DISTCD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->CA_STATECD, '18', true, 'ms'), old('CA_DISTCD', $model->CA_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) --}}-->
                                            {{ Form::select('CA_DISTCD', PublicCase::GetDstrtList((old('CA_STATECD')? old('CA_STATECD') : $model->CA_STATECD)), (old('CA_DISTCD')? old('CA_DISTCD') : ($model->CA_DISTCD? $model->CA_DISTCD:'')), ['class' => 'form-control input-sm select2', 'id' => 'CA_DISTCD']) }}
                                            {{ Form::hidden('CA_MYIDENTITY_DISTCD', old('CA_MYIDENTITY_DISTCD', $model->CA_MYIDENTITY_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_DISTCD']) }}
                                            <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                            @if ($errors->has('CA_DISTCD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="bknwarganegara" style="display: {{ (old('CA_NATCD')? (old('CA_NATCD') == '0'? 'block':'none') : ($model->CA_NATCD == '0' ? 'block' : 'none')) }}">
                                    <div class="form-group {{ $errors->has('CA_COUNTRYCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_COUNTRYCD', 'Negara Asal', ['class' => 'col-lg-4 control-label required']) }}
                                        <div class="col-lg-8">
                                            {{ Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('CA_COUNTRYCD', $model->CA_COUNTRYCD), ['class' => 'form-control input-sm select2']) }}
                                            {{ Form::hidden('CA_STATUSPENGADU', old('CA_STATUSPENGADU', $model->CA_STATUSPENGADU), ['class' => 'form-control input-sm', 'id' => 'CA_STATUSPENGADU']) }}
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
                                    {{ Form::label('CA_CMPLCAT', 'Kategori', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms', 'descr'), old('CA_CMPLCAT', $model->CA_CMPLCAT), array('class' => 'form-control input-sm select2', 'id' => 'CA_CMPLCAT')) }}
                                        @if ($errors->has('CA_CMPLCAT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_CMPLCD', PublicCase::GetCmplCd((old('CA_CMPLCAT')? old('CA_CMPLCAT') : $model->CA_CMPLCAT), 'ms', 'ms'), (old('CA_CMPLCD')? old('CA_CMPLCD') : ($model->CA_CMPLCD? $model->CA_CMPLCD:'')), ['class' => 'form-control input-sm select2', 'id' => 'CA_CMPLCD']) }}
                                        @if ($errors->has('CA_CMPLCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_TTPMTYP" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_TTPMTYP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_TTPMTYP', 'Penuntut/Penentang', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_TTPMTYP', Ref::GetList('1108', true, 'ms', 'descr'), old('CA_TTPMTYP', $model->CA_TTPMTYP), ['class' => 'form-control input-sm select2']) }}
                                        @if ($errors->has('CA_TTPMTYP'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_TTPMTYP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_TTPMNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_TTPMNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_TTPMNO', 'No. TTPM', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_TTPMNO', old('CA_TTPMNO', $model->CA_TTPMNO), ['class' => 'form-control input-sm'])}}
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
                                    {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_ONLINECMPL_AMOUNT', number_format($model->CA_ONLINECMPL_AMOUNT, 2), ['class' => 'form-control input-sm', 'id'=>'CA_ONLINECMPL_AMOUNT', 'onkeypress' => "return isNumberKey1(event)"]) }}
                                        @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="CA_CMPLKEYWORD" style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($model->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none')))  }};">
                                    {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_CMPLKEYWORD', Ref::GetList('1051', true, 'ms'), old('CA_CMPLKEYWORD', $model->CA_CMPLKEYWORD), ['class' => 'form-control input-sm select2'])}}
                                        @if ($errors->has('CA_CMPLKEYWORD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLKEYWORD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_SERVICE_PROVIDER_INFO" style="display: {{ (old('CA_CMPLCAT') ? (old('CA_CMPLCAT') == 'BPGK 19' ? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19' ? 'block':'none')) }}">
                                    <h4>Maklumat Penjual / Pihak Yang Diadu</h4>
                                    <!--<div class="hr-line-solid"></div>-->
                                    <hr style="background-color: #ccc; height: 1px; width: 206%; border: 0;">
                                </div>
                                <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_ONLINECMPL_PROVIDER', Ref::GetList('1091', true, 'ms', 'descr'), old('CA_ONLINECMPL_PROVIDER', $model->CA_ONLINECMPL_PROVIDER), ['class' => 'form-control input-sm select2', 'id' => 'CA_ONLINECMPL_PROVIDER']) }}
                                        @if ($errors->has('CA_ONLINECMPL_PROVIDER'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PROVIDER') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_URL" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                    <!--{{-- Form::label('CA_ONLINECMPL_URL', 'Laman Web / URL', ['class' => 'col-sm-5 control-label']) --}}-->
                                    <label for="CA_ONLINECMPL_URL" class="col-lg-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19' && old ('CA_ONLINECMPL_PROVIDER') == '999' ? 'required':'' }}">Laman Web / URL / ID</label>
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_ONLINECMPL_URL', old('CA_ONLINECMPL_URL', $model->CA_ONLINECMPL_URL), ['class' => 'form-control input-sm', 'placeholder' => '(Contoh: www.google.com)']) }}
                                        @if ($errors->has('CA_ONLINECMPL_URL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_URL') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_PYMNTTYP" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PYMNTTYP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        <!--{{-- Form::text('CA_ONLINECMPL_PYMNTTYP', $model->CA_ONLINECMPL_PYMNTTYP, ['class' => 'form-control input-sm']) --}}-->
                                        {{ Form::select('CA_ONLINECMPL_PYMNTTYP', Ref::GetList('1207', true, 'ms'), old('CA_ONLINECMPL_PYMNTTYP', $model->CA_ONLINECMPL_PYMNTTYP), ['class' => 'form-control input-sm select2', 'id' => 'CA_ONLINECMPL_PYMNTTYP']) }}
                                        @if ($errors->has('CA_ONLINECMPL_PYMNTTYP'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PYMNTTYP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_BANKCD" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                                    <label for="CA_ONLINECMPL_BANKCD" class="col-lg-5 control-label {{ old('CA_ONLINECMPL_PYMNTTYP') ? (in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD']) ? '':'required') : (in_array($model->CA_ONLINECMPL_PYMNTTYP,['','COD']) ? '' : 'required') }}">Nama Bank</label>
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, 'ms'), old('CA_ONLINECMPL_BANKCD', $model->CA_ONLINECMPL_BANKCD), ['class' => 'form-control input-sm select2', 'id' => 'CA_ONLINECMPL_BANKCD']) }}
                                        @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_BANKCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_ACCNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                    <label for="CA_ONLINECMPL_ACCNO" class="col-lg-5 control-label {{ old('CA_ONLINECMPL_PYMNTTYP') ? (in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD']) ? '':'required') : (in_array($model->CA_ONLINECMPL_PYMNTTYP,['','COD']) ? '' : 'required') }}">No. Akaun Bank</label>
                                    <!--{{-- Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun', ['class' => 'col-sm-5 control-label required']) --}}-->
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_ONLINECMPL_ACCNO', old('CA_ONLINECMPL_ACCNO', $model->CA_ONLINECMPL_ACCNO), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_ACCNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_PREMISE" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($model->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                    <label for="CA_AGAINST_PREMISE" class="col-lg-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Jenis Premis</label>
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), old('CA_AGAINST_PREMISE', $model->CA_AGAINST_PREMISE), ['class' => 'form-control input-sm select2', 'id' => 'CA_AGAINST_PREMISE']) }}
                                        @if ($errors->has('CA_AGAINST_PREMISE'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_PREMISE') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" id="checkpernahadu">
                                    {{ Form::label('', '', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        <div class="checkbox checkbox-success">
                                            <input name="CA_ONLINECMPL_IND" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' || $model->CA_ONLINECMPL_IND == '1'? 'checked':'' }}>
                                            <label for="CA_ONLINECMPL_IND">
                                                Pernah membuat aduan secara rasmi kepada Pembekal Perkhidmatan?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_CASENO" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINECMPL_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINECMPL_IND') == '' && $model->CA_ONLINECMPL_IND == '1'? 'block':(old('CA_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_ONLINECMPL_CASENO', old('CA_ONLINECMPL_CASENO', $model->CA_ONLINECMPL_CASENO), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_ONLINECMPL_CASENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_CASENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div id="div_ONLINECMPL" style="height: 210px; display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }}"></div>
                                <div class="form-group{{ $errors->has('CA_AGAINSTNM') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINSTNM', 'Nama (Syarikat/Premis/Penjual)', ['class' => 'col-lg-7 control-label required']) }}
                                    <div class="col-lg-5">
                                        {{ Form::text('CA_AGAINSTNM', old('CA_AGAINSTNM', $model->CA_AGAINSTNM), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_AGAINSTNM'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTNM') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <!--<div class="col-sm-5"></div>-->
                                <!--<div class="col-sm-7">** Diperlukan salah satu</div>-->
                                <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon (Pejabat)', ['class' => 'col-lg-7 control-label']) }}
                                    <div class="col-lg-5">
                                        {{ Form::text('CA_AGAINST_TELNO', old('CA_AGAINST_TELNO', $model->CA_AGAINST_TELNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                        @if ($errors->has('CA_AGAINST_TELNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-lg-7 control-label']) }}
                                    <div class="col-lg-5">
                                        {{ Form::text('CA_AGAINST_MOBILENO', old('CA_AGAINST_MOBILENO', $model->CA_AGAINST_MOBILENO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12]) }}
                                        @if ($errors->has('CA_AGAINST_MOBILENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-lg-7 control-label']) }}
                                    <div class="col-lg-5">
                                        {{ Form::email('CA_AGAINST_EMAIL', old('CA_AGAINST_EMAIL', $model->CA_AGAINST_EMAIL), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_AGAINST_EMAIL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-lg-7 control-label']) }}
                                    <div class="col-lg-5">
                                        {{ Form::text('CA_AGAINST_FAXNO', old('CA_AGAINST_FAXNO', $model->CA_AGAINST_FAXNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                        @if ($errors->has('CA_AGAINST_FAXNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_FAXNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="checkinsertadd" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group">
                                    <!--{{-- Form::label('', '', ['class' => 'col-lg-7 control-label']) --}}-->
                                    <div class="col-lg-11 col-lg-push-1">
                                        <div class="checkbox checkbox-success">
                                            <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' || $model->CA_ONLINEADD_IND == '1'? 'checked':'' }}>
                                            <label for="CA_ONLINEADD_IND">
                                                Mempunyai alamat penuh penjual / pihak yang diadu?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_CA_AGAINSTADD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                                    <!--<label for="CA_AGAINSTADD" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Alamat</label>-->
                                    {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-lg-7 control-label required']) }}
                                    <div class="col-lg-5">
                                        {{ Form::textarea('CA_AGAINSTADD', old('CA_AGAINSTADD', $model->CA_AGAINSTADD), ['class' => 'form-control input-sm', 'rows'=> '4']) }}
                                        @if ($errors->has('CA_AGAINSTADD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTADD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_POSTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                                    <!--<label for="CA_AGAINST_POSTCD" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Poskod</label>-->
                                    {{ Form::label('CA_AGAINST_POSTCD', 'Poskod', ['class' => 'col-lg-7 control-label']) }}
                                    <div class="col-lg-5">
                                        {{ Form::text('CA_AGAINST_POSTCD', old('CA_AGAINST_POSTCD', $model->CA_AGAINST_POSTCD), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                        @if ($errors->has('CA_AGAINST_POSTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_POSTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_STATECD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-lg-7 control-label required']) }}
                                    <div class="col-lg-5">
                                        {{ Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true, 'ms'), old('CA_AGAINST_STATECD', $model->CA_AGAINST_STATECD), ['class' => 'form-control input-sm select2', 'id' => 'CA_AGAINST_STATECD']) }}
                                        @if ($errors->has('CA_AGAINST_STATECD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_DISTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-lg-7 control-label required']) }}
                                    <div class="col-lg-5">
                                        {{ Form::select('CA_AGAINST_DISTCD', ($model->CA_AGAINST_STATECD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->CA_AGAINST_STATECD, '18', true, 'ms')), $model->CA_AGAINST_DISTCD, ['class' => 'form-control input-sm select2', 'id' => 'CA_AGAINST_DISTCD']) }}
                                        <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                        @if ($errors->has('CA_AGAINST_DISTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
<!--                                <div class="form-group {{ $errors->has('CA_ROUTETOHQIND') ? ' has-error' : '' }}">
                                    {{ Form::label('', '', ['class' => 'col-lg-6 control-label']) }}
                                    <div class="col-lg-6">
                                        <div class="checkbox checkbox-primary">
                                            <input id="CA_ROUTETOHQIND" type="checkbox" name="CA_ROUTETOHQIND" {{ $model->CA_ROUTETOHQIND == '1'? 'checked':'' }}>
                                            <label for="CA_ROUTETOHQIND">
                                                Hantar aduan ke Ibu Pejabat Penguatkuasa
                                            </label>
                                        </div>
                                    </div>
                                </div>-->
                                <div class="form-group{{ $errors->has('CA_SSP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_SSP', 'Wujud Kes?', ['class' => 'col-lg-7 control-label required']) }}
                                    <div class="col-lg-5">
                                        <div class="radio radio-success radio-inline">
                                            <input id="yes" type="radio" value="YES" name="CA_SSP" onclick="ssp(this.value)" {{ old('CA_SSP') == 'YES'||$model->CA_SSP == 'YES'? 'checked':'' }}><label for="yes">Ya</label>
                                        </div>
                                        <div class="radio radio-success radio-inline">
                                            <input id="no" type="radio" value="NO" name="CA_SSP" onclick="ssp(this.value)" {{ old('CA_SSP') == 'NO'||$model->CA_SSP == 'NO'? 'checked':'' }}><label for="no">Tidak</label>
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
                                <div id="ssp" style="display: {{ old('CA_SSP')? (old('CA_SSP') == 'YES'? 'block':'none') : ($model->CA_SSP == 'YES'? 'block' : 'none') }}">
                                <!--<div id="ssp" style="display: {{-- $errors->has('CA_IPNO')||$errors->has('CA_AKTA')||$errors->has('CA_SUBAKTA')||$model->CA_SSP == 'YES'? 'block' : 'none' --}}">-->
                                    <h4>Senarai Akta</h4>
                                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <a onclick="ModalCreateAkta('{{ $model->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Akta</a>
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
                            <div class="col-lg-12">
                                <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_SUMMARY', 'Keterangan Aduan', ['class' => 'col-lg-2 control-label required']) }}
                                    <div class="col-lg-10">
                                        {{ Form::textarea('CA_SUMMARY', old('CA_SUMMARY', $model->CA_SUMMARY), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                        <!--<span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>-->
                                        @if ($errors->has('CA_SUMMARY'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_RESULT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RESULT', 'Hasil Siasatan', ['class' => 'col-lg-2 control-label required']) }}
                                    <div class="col-lg-10">
                                        {{ Form::textarea('CA_RESULT', old('CA_RESULT', $model->CA_RESULT), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                        @if ($errors->has('CA_RESULT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RESULT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_ANSWER') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-lg-2 control-label required']) }}
                                    <div class="col-lg-10">
                                        {{ Form::textarea('CA_ANSWER', old('CA_ANSWER', $model->CA_ANSWER), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                        @if ($errors->has('CA_ANSWER'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ANSWER') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12" align="center">
                                {{ Form::submit('Kemaskini', ['class' => 'btn btn-success btn-sm']) }}
                                <!--<button type="submit" class="btn btn-primary btn-sm" name="submit" value="2">Kemaskini & Hantar Emel</button>-->
                                <!--<a href="{{ url('sas-case')}}" type="button" class="btn btn-default btn-sm">Kembali</a>-->
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div id="attachment" class="tab-pane">
                    <div class="panel-body">
                        <h4>Senarai Lampiran Aduan</h4>
                        <p>(Maksimum 5 Lampiran Aduan sahaja)</p>
                        @if ( $countDocAduan < 5 )
                            <div class="row">
                                <div class="col-lg-12">
                                    <a onclick="ModalAttachmentCreate('{{ $model->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Lampiran Aduan</a>
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
                            <div class="col-lg-12">
                                <p>(Maksimum 8 Bukti Siasatan sahaja)</p>
                                @if($countDocSiasat < 8)
                                    <a onclick="ModalDocSiasatCreate('{{ $model->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Bukti Siasatan</a>
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
                                <!--<a class="btn btn-warning btn-sm" href="{{-- url('sas-case') --}}">Kembali</a>-->
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
                                <!--<a class="btn btn-warning btn-sm" href="{{-- url('sas-case') --}}">Kembali</a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--</div>-->
<!--</div>-->

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
    function check(value){
        if(value === '1') {
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
    $('#CA_DOCNO').blur(function(){
        var DOCNO = $('#CA_DOCNO').val();
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
        $('#CA_STATECD').on('change', function (e) {
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
            if (CA_ONLINECMPL_PYMNTTYP === 'COD' || CA_ONLINECMPL_PYMNTTYP === '') {
                $("label[for='CA_ONLINECMPL_BANKCD']").removeClass("required");
                $("label[for='CA_ONLINECMPL_ACCNO']").removeClass("required");
            } else {
                $("label[for='CA_ONLINECMPL_BANKCD']").addClass("required");
                $("label[for='CA_ONLINECMPL_ACCNO']").addClass("required");
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
                if (document.getElementById('CA_ONLINEADD_IND').checked) {
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
//                    d.role_code = $('#role_code').val();
                    d.role_cd = $('#role_cd').val();
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
//                url: "{{ url('sas-case/getdataattach',$model->CA_CASEID)}}",
                url: "{{ route('dataentrydoc.getdatadoc', $model->CA_CASEID) }}",
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
    
    function onlineaddind() {
        if (document.getElementById('CA_ONLINEADD_IND').checked) {
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
    };
    
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
                url: "{{ url('sas-case/getdatatransaction',$model->CA_CASEID)}}",
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
//        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('sas-case.createdoc','') }}" + "/" + id);
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('dataentrydoc.createdoc','') }}" + "/" + id);
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
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('dataentrydoc.editdoc','') }}" + "/" + id);
        return false;
    }
    function ModalDocSiasatCreate(CASEID) {
        $('#modal-attachment-siasat-create').modal("show").find("#modalContentSiasatCreate").load("{{ route('dataentrydocsiasat.createdoc','') }}" + "/" + CASEID);
        return false;
    }
    function ModalDocSiasatEdit(CASEID) {
        $('#modal-attachment-siasat-edit').modal("show").find("#modalContentSiasatEdit").load("{{ route('dataentrydocsiasat.editdocsiasat','') }}" + "/" + CASEID);
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
    
    function ssp(value) {
        if (value == 'YES') {
            $('#ssp').show();
        } else {
            $('#ssp').hide();
        }
    }
    
    $(document).ready(function(){
//        $("select").select2();
        $(".select2").select2();
        
        $('#CA_ONLINECMPL_AMOUNT').blur(function(){
            $(this).val(amountchange($(this).val()));
        });
        
        function amountchange(amount) {
            var delimiter = ","; // replace comma if desired
            var a = amount.split('.',2);
            var d = a[1];
            if(d){
                if(d.length === 1){
                    d = d + '0';
                }else if(d.length === 2){
                    d = d;
                }else{
                    d = d;
                }
            }else{
                d = '00';
            }
            var i = parseInt(a[0]);
            if(isNaN(i)) { return ''; }
            var minus = '';
            if(i < 0) { minus = '-'; }
            i = Math.abs(i);
            var n = new String(i);
            var a = [];
            while(n.length > 3) {
                var nn = n.substr(n.length-3);
                a.unshift(nn);
                n = n.substr(0,n.length-3);
            }
            if(n.length > 0) { a.unshift(n); }
            n = a.join(delimiter);
            if(d.length < 1) { amount = n; }
            else { amount = n + '.' + d; }
            amount = minus + amount;
            return amount;
        }
        
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
//            url: "{{ url('sas-case/getdocsiasat', $model->CA_CASEID) }}"
            url: "{{ route('dataentrydocsiasat.getdatadoc', $model->CA_CASEID) }}"
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
            url: "{{ url('sas-case/getakta', $model->CA_CASEID) }}"
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