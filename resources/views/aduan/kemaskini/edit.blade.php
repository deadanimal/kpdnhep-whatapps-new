@extends('layouts.main')
<?php
    use App\Ref;
    use App\Aduan\AdminCase;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
    span.select2 {
        width: 100% !important;
    }
    .help-block-red {
        color: red;
    }
</style>
<h2>Kemaskini Aduan (Maklumat Tidak Lengkap)</h2>
<!--<div class="row">-->
    <!--<div class="col-lg-12">-->
        <!--<div class="ibox float-e-margins">-->
            <!--<div class="ibox-content">-->
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active">
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                MAKLUMAT ADUAN
            </a>
        </li>
        <li class="">
            <a href='{{ $model->CA_INVSTS == "7" ? route("kemaskini.docbuktiaduan", $model->CA_CASEID) : "" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                LAMPIRAN
            </a>
        </li>
        <li class="">
            <a href='{{ $model->CA_INVSTS == "7" ? route("kemaskini.preview", $model->CA_CASEID) : "" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                SEMAKAN ADUAN
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="caseinfo" class="tab-pane active">
            <div class="panel-body">
                {!! Form::open(['route' => ['kemaskini.update', $model->CA_CASEID], 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <h4>Cara Terima</h4>
                    <!--<div class="hr-line-solid"></div>-->
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVDT', 'Tarikh', ['class' => 'col-lg-2 control-label']) }}
                                <div class="col-lg-10">
                                    {{ Form::text('CA_RCVDT', date('d-m-Y h:i A', strtotime($model->CA_RCVDT)), ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                    <!--<p class="form-control-static">{{ date('d-m-Y h:i A', strtotime($model->CA_RCVDT)) }}</p>-->
                                    @if ($errors->has('CA_RCVDT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-lg-2 control-label']) }}
                                <div class="col-lg-10">
                                    <!--<div class="input-group">-->
                                        {{ Form::hidden('CA_RCVBY', old('CA_RCVBY', $model->CA_RCVBY), ['class' => 'form-control input-sm', 'id' => 'RCVBY_id']) }}
                                        {{ Form::text('', $RcvBy, ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'RCVBY_name']) }}
                                        <!--<p class="form-control-static">{{-- $RcvBy --}}</p>-->
                                        <!--<span class="input-group-btn">-->
                                            <!--<a data-toggle="modal" class="btn btn-primary btn-sm" href="#carian-penerima">Carian</a>-->
                                        <!--</span>-->
                                        @if ($errors->has('CA_RCVBY'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RCVBY') }}</strong></span>
                                        @endif
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <!--{{-- Form::select('CA_RCVTYP', AdminCase::getRefList('259', true), old('CA_RCVTYP', $model->CA_RCVTYP), ['class' => 'form-control input-sm', 'id' => 'CA_RCVTYP']) --}}-->
                                    {{ Form::text('CA_RCVTYP_DESCR', $model->CA_RCVTYP != ''? Ref::GetDescr('259', $model->CA_RCVTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_RCVTYP != ''? Ref::GetDescr('259', $model->CA_RCVTYP, 'ms') : '' --}}</p>-->
                                    {{ Form::hidden('CA_RCVTYP', $model->CA_RCVTYP, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_RCVTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_BPANO" class="form-group{{ $errors->has('CA_BPANO') ? ' has-error' : '' }}" style="display: {{ (old('CA_RCVTYP')?(in_array(old('CA_RCVTYP'),['S14'])? 'block':'none') : ((in_array($model->CA_RCVTYP,['S14'])? 'block':'none'))) }};">
                                {{ Form::label('CA_BPANO', 'No. Aduan BPA', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    <!--{{-- Form::text('CA_BPANO', old('CA_BPANO', $model->CA_BPANO), ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                    <p class="form-control-static">{{ $model->CA_BPANO }}</p>
                                    @if ($errors->has('CA_BPANO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_BPANO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_SERVICENO" class="form-group{{ $errors->has('CA_SERVICENO') ? ' has-error' : '' }}" style="display: {{ (old('CA_RCVTYP')?(in_array(old('CA_RCVTYP'),['S33'])? 'block':'none') : ((in_array($model->CA_RCVTYP,['S33'])? 'block':'none'))) }};">
                                {{ Form::label('CA_SERVICENO', 'No. Tali Khidmat', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    <!--{{-- Form::text('CA_SERVICENO', old('CA_SERVICENO', $model->CA_SERVICENO), ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                    <p class="form-control-static">{{ $model->CA_SERVICENO }}</p>
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
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('CA_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('CA_DOCNO', old('CA_DOCNO', $model->CA_DOCNO), ['class' => 'form-control input-sm', 'id' => 'DOCNO', 'maxlength' => 12, 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_DOCNO --}}</p>-->
                                    @if ($errors->has('CA_DOCNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!--<div class="col-lg-6"></div>-->
                            <!--<div class="col-lg-6" style="color: red"><strong>** Diperlukan salah satu</strong></div>-->
                            <div class="form-group{{ $errors->has('CA_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::email('CA_EMAIL', old('CA_EMAIL', $model->CA_EMAIL), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_EMAIL --}}</p>-->
                                    <style scoped>input:invalid, textarea:invalid { color: red; }</style>
                                    @if ($errors->has('CA_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('CA_MOBILENO', old('CA_MOBILENO', $model->CA_MOBILENO), ['class' => 'form-control input-sm' ,'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12, 'readonly' => true ]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_MOBILENO --}}</p>-->
                                    @if ($errors->has('CA_MOBILENO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('CA_TELNO', old('CA_TELNO', $model->CA_TELNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10, 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_TELNO --}}</p>-->
                                    @if ($errors->has('CA_TELNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('CA_FAXNO', old('CA_FAXNO', $model->CA_FAXNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10, 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_FAXNO --}}</p>-->
                                    @if ($errors->has('CA_FAXNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::textarea('CA_ADDR', old('CA_ADDR', $model->CA_ADDR), ['class' => 'form-control input-sm', 'rows'=>'4', 'readonly' => true]) }}
                                    @if ($errors->has('CA_ADDR'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('CA_NAME') ? ' has-error' : '' }}">
                                {{ Form::label('CA_NAME', 'Nama', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    {{ Form::text('CA_NAME', old('CA_NAME', $model->CA_NAME), ['class' => 'form-control input-sm', 'id' => 'CA_NAME', 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_NAME --}}</p>-->
                                    @if ($errors->has('CA_NAME'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    {{ Form::text('CA_AGE', old('CA_AGE', $model->CA_AGE), ['class' => 'form-control input-sm', 'id' => 'CA_AGE','onkeypress' => "return isNumberKey(event)", 'maxlength' => 3, 'readonly' => true ]) }}
                                    <!--{{-- Form::select('CA_AGE', Ref::GetList('309', true, 'ms'), old('CA_AGE', $model->CA_AGE), ['class' => 'form-control input-sm', 'disabled' => 'true']) --}}-->
                                    <!--<p class="form-control-static">{{-- $model->CA_AGE --}}</p>-->
                                    @if ($errors->has('CA_AGE'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!--{{-- Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), old('CA_SEXCD', $model->CA_SEXCD), ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD']) --}}-->
                                    {{ Form::text('CA_SEXCD', $model->CA_SEXCD != '' ? Ref::GetDescr('202', $model->CA_SEXCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_SEXCD != '' ? Ref::GetDescr('202', $model->CA_SEXCD, 'ms') : '' --}}</p>-->
                                    @if ($errors->has('CA_SEXCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_RACECD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!--{{-- Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), old('CA_RACECD', $model->CA_RACECD), ['class' => 'form-control input-sm']) --}}-->
                                    {{ Form::text('CA_RACECD', $model->CA_RACECD != ''? Ref::GetDescr('580', $model->CA_RACECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_RACECD != ''? Ref::GetDescr('580', $model->CA_RACECD, 'ms') : '' --}}</p>-->
                                    @if ($errors->has('CA_RACECD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_RACECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!--<div class="radio radio-success">-->
                                        <!--<input id="CA_NATCD1" type="radio" name="CA_NATCD" value="1" onclick="check(this.value)" {{ $model->CA_NATCD == '1' ? 'checked' : '' }} >-->
                                        <!--<label for="CA_NATCD1"> Warganegara </label>-->
                                    <!--</div>-->
                                    <!--<div class="radio radio-success">-->
                                        <!--<input id="CA_NATCD2" type="radio" name="CA_NATCD" value="0" onclick="check(this.value)" {{ $model->CA_NATCD == '0' ? 'checked' : '' }} >-->
                                        <!--<label for="CA_NATCD2"> Bukan Warganegara </label>-->
                                    <!--</div>-->
                                    {{ Form::text('CA_RACECD', $model->CA_NATCD != '' ? Ref::GetDescr('947', $model->CA_NATCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    <!--<p class="form-control-static">{{-- $model->CA_NATCD != '' ? Ref::GetDescr('947', $model->CA_NATCD, 'ms') : '' --}}</p>-->
                                    @if ($errors->has('CA_NATCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_NATCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="warganegara" style="display:block">
                                <div class="form-group {{ $errors->has('CA_POSCD') ? ' has-error' : 'CA_POSCD' }}">
                                    {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        {{ Form::text('CA_POSCD', old('CA_POSCD', $model->CA_POSCD), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5, 'readonly' => true]) }}
                                        <!--<p class="form-control-static">{{-- $model->CA_POSCD --}}</p>-->
                                        @if ($errors->has('CA_POSCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_STATECD') ? ' has-error' : 'CA_STATECD' }}">
                                    {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <!--{{-- Form::select('CA_STATECD', Ref::GetList('17', true, 'ms'), old('CA_STATECD', $model->CA_STATECD), ['class' => 'form-control input-sm required', 'id' => 'CA_STATECD']) --}}-->
                                        {{ Form::text('CA_STATECD', $model->CA_STATECD != ''? Ref::GetDescr('17', $model->CA_STATECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        <!--<p class="form-control-static">{{-- $model->CA_STATECD != '' ? Ref::GetDescr('17', $model->CA_STATECD, 'ms') : '' --}}</p>-->
                                        @if ($errors->has('CA_STATECD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_DISTCD') ? ' has-error' : 'CA_DISTCD' }}">
                                    {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <!--{{-- Form::select('CA_DISTCD', $model->CA_DISTCD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->CA_STATECD, '18', true, 'ms'), old('CA_DISTCD', $model->CA_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) --}}-->
                                        {{ Form::text('CA_DISTCD', ($model->CA_DISTCD != '' ? Ref::GetDescr('18', $model->CA_DISTCD, 'ms') : ''), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        <!--<p class="form-control-static">{{-- $model->CA_DISTCD != '' ? Ref::GetDescr('18', $model->CA_DISTCD, 'ms') : '' --}}</p>-->
                                        <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                        @if ($errors->has('CA_DISTCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="bknwarganegara" style="display: {{ (old('CA_NATCD')? (old('CA_NATCD') == '0'? 'block':'none') : ($model->CA_NATCD == '0' ? 'block' : 'none')) }}">
                                <div class="form-group {{ $errors->has('CA_COUNTRYCD') ? ' has-error' : 'CA_COUNTRYCD' }}">
                                    {{ Form::label('CA_COUNTRYCD', 'Negara Asal', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <!--{{-- Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('CA_COUNTRYCD', $model->CA_COUNTRYCD), ['class' => 'form-control input-sm']) --}}-->
                                        <p class="form-control-static">{{ $model->CA_COUNTRYCD != ''? Ref::GetDescr('334', $model->CA_COUNTRYCD, 'ms') : '' }}</p>
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
                                {{ Form::label('CA_CMPLCAT', 'Kategori', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <!--{{-- Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms','descr'), old('CA_CMPLCAT', $model->CA_CMPLCAT), array('class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT')) --}}-->
                                    {{ Form::text('CA_CMPLCAT_DESCR', $model->CA_CMPLCAT != ''? Ref::GetDescr('244', $model->CA_CMPLCAT, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    {{ Form::hidden('CA_CMPLCAT', $model->CA_CMPLCAT, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_CMPLCAT'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <!--{{-- Form::select('CA_CMPLCD', $model->CA_CMPLCAT == '' ? [''=>'-- SILA PILIH --'] : AdminCase::getcmplcdlist($model->CA_CMPLCAT), old('CA_CMPLCD', $model->CA_CMPLCD), ['class' => 'form-control input-sm']) --}}-->
                                    {{ Form::text('CA_CMPLCD_DESCR', $model->CA_CMPLCD != ''? Ref::GetDescr('634', $model->CA_CMPLCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    {{ Form::hidden('CA_CMPLCD', $model->CA_CMPLCD, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_CMPLCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="CA_TTPMTYP" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_TTPMTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_TTPMTYP', 'Penuntut/Penentang', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_TTPMTYP', Ref::GetList('1108', true, 'ms', 'descr'), old('CA_TTPMTYP', $model->CA_TTPMTYP), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_TTPMTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_TTPMTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="CA_TTPMNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_TTPMNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_TTPMNO', 'No. TTPM', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_TTPMNO', old('CA_TTPMNO', $model->CA_TTPMNO), ['class' => 'form-control input-sm'])}}
                                    @if ($errors->has('CA_TTPMNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_TTPMNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_ONLINECMPL_AMOUNT', $model->CA_ONLINECMPL_AMOUNT, ['class' => 'form-control input-sm']) --}}
                                    {{ Form::text('CA_ONLINECMPL_AMOUNT', number_format($model->CA_ONLINECMPL_AMOUNT, 2), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey1(event)", 'id' => 'CA_ONLINECMPL_AMOUNT']) }}
                                    @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="CA_CMPLKEYWORD" style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($model->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none')))  }};">
                                {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_CMPLKEYWORD', Ref::GetList('1051', true, 'ms'), old('CA_CMPLKEYWORD', $model->CA_CMPLKEYWORD), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_CMPLKEYWORD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CMPLKEYWORD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_SERVICE_PROVIDER_INFO" style="display: {{ (old('CA_CMPLCAT') ? (old('CA_CMPLCAT') == 'BPGK 19' ? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19' ? 'block':'none')) }}">
                                <h4>Maklumat Pembekal Perkhidmatan</h4>
                                <!--<div class="hr-line-solid"></div>-->
                                <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                            </div>
                            <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_ONLINECMPL_PROVIDER', Ref::GetList('1091', true, 'ms', 'descr'), old('CA_ONLINECMPL_PROVIDER', $model->CA_ONLINECMPL_PROVIDER), ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PROVIDER']) }}
                                    @if ($errors->has('CA_ONLINECMPL_PROVIDER'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PROVIDER') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_URL" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                <label for="CA_ONLINECMPL_URL" class="col-sm-5 control-label {{ old('CA_ONLINECMPL_PROVIDER') == '999'? 'required':'' }}">Laman Web / URL</label>
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_URL', old('CA_ONLINECMPL_URL', $model->CA_ONLINECMPL_URL), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_ONLINECMPL_URL'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_URL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_BANKCD" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_BANKCD', 'Nama Bank', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, 'ms'), old('CA_ONLINECMPL_BANKCD', $model->CA_ONLINECMPL_BANKCD), ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_BANKCD']) }}
                                    @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_BANKCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_PYMNTTYP" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PYMNTTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_ONLINECMPL_PYMNTTYP', Ref::GetList('1207', true, 'ms'), old('CA_ONLINECMPL_PYMNTTYP', $model->CA_ONLINECMPL_PYMNTTYP), ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PYMNTTYP']) }}
                                    @if ($errors->has('CA_ONLINECMPL_PYMNTTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PYMNTTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_ACCNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun Bank / No. Transaksi FPX', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_ACCNO', old('CA_ONLINECMPL_ACCNO', $model->CA_ONLINECMPL_ACCNO), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_ACCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_PREMISE" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($model->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_PREMISE', 'Jenis Premis', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), old('CA_AGAINST_PREMISE', $model->CA_AGAINST_PREMISE), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_PREMISE']) }}
                                    @if ($errors->has('CA_AGAINST_PREMISE'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_PREMISE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" id="checkpernahadu">
                                {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <div class="checkbox checkbox-success">
                                        <input name="CA_ONLINECMPL_IND" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' || $model->CA_ONLINECMPL_IND == '1'? 'checked':'' }}>
                                        <label for="CA_ONLINECMPL_IND">
                                            Pernah membuat aduan secara rasmi kepada Pembekal Perkhidmatan?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_CASENO" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINECMPL_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINECMPL_IND') == '' && $model->CA_ONLINECMPL_IND == '1'? 'block':(old('CA_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : ''}}">
                                {{ Form::label('CA_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_CASENO', old('CA_ONLINECMPL_CASENO', $model->CA_ONLINECMPL_CASENO), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_ONLINECMPL_CASENO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_CASENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_AGAINSTNM') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINSTNM', 'Nama (Syarikat/Premis)', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINSTNM', old('CA_AGAINSTNM', $model->CA_AGAINSTNM), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_AGAINSTNM'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTNM') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon (Pejabat)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINST_TELNO', old('CA_AGAINST_TELNO', $model->CA_AGAINST_TELNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                    @if ($errors->has('CA_AGAINST_TELNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINST_MOBILENO', old('CA_AGAINST_MOBILENO', $model->CA_AGAINST_MOBILENO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12]) }}
                                    @if ($errors->has('CA_AGAINST_MOBILENO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::email('CA_AGAINST_EMAIL', old('CA_AGAINST_EMAIL', $model->CA_AGAINST_EMAIL), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_AGAINST_EMAIL'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINST_FAXNO', old('CA_AGAINST_FAXNO', $model->CA_AGAINST_FAXNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                    @if ($errors->has('CA_AGAINST_FAXNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="checkinsertadd" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group">
                                {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <div class="checkbox checkbox-success">
                                        <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' || $model->CA_ONLINEADD_IND == '1'? 'checked':'' }}>
                                        <label for="CA_ONLINEADD_IND">
                                            Mempunyai alamat penuh pembekal perkhidmatan?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="div_CA_AGAINSTADD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('CA_AGAINSTADD', old('CA_AGAINSTADD', $model->CA_AGAINSTADD), ['class' => 'form-control input-sm', 'rows'=> '4']) }}
                                    @if ($errors->has('CA_AGAINSTADD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTADD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_POSTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_POSTCD', 'Poskod', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINST_POSTCD', old('CA_AGAINST_POSTCD', $model->CA_AGAINST_POSTCD), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                    @if ($errors->has('CA_AGAINST_POSTCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_POSTCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_STATECD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <!--{{-- Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true), old('CA_AGAINST_STATECD', $model->CA_AGAINST_STATECD), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_STATECD']) --}}-->
                                    {{ Form::text('CA_AGAINST_STATECD_DESCR', $model->CA_AGAINST_STATECD != ''? Ref::GetDescr('17', $model->CA_AGAINST_STATECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    {{ Form::hidden('CA_AGAINST_STATECD', $model->CA_AGAINST_STATECD, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_AGAINST_STATECD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_STATECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_DISTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <!--{{-- Form::select('CA_AGAINST_DISTCD', ($model->CA_AGAINST_STATECD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->CA_AGAINST_STATECD, '18', true, 'ms')), old('CA_AGAINST_DISTCD', $model->CA_AGAINST_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) --}}-->
                                    {{ Form::text('CA_AGAINST_DISTCD_DESCR', ($model->CA_AGAINST_DISTCD != '' ? Ref::GetDescr('18', $model->CA_AGAINST_DISTCD, 'ms') : ''), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    {{ Form::hidden('CA_AGAINST_DISTCD', $model->CA_AGAINST_DISTCD, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_AGAINST_DISTCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_DISTCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
<!--                            <div class="form-group {{ $errors->has('CA_ROUTETOHQIND') ? ' has-error' : '' }}">
                                {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <div class="checkbox checkbox-primary">
                                        <input id="CA_ROUTETOHQIND" type="checkbox" name="CA_ROUTETOHQIND" {{ $model->CA_ROUTETOHQIND == '1'? 'checked':'' }}>
                                        <label for="CA_ROUTETOHQIND">
                                            Hantar aduan ke Ibu Pejabat Penguatkuasa
                                        </label>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-1 control-label required']) }}
                                <div class="col-sm-11">
                                    {{ Form::textarea('CA_SUMMARY', old('CA_SUMMARY', $model->CA_SUMMARY), ['class' => 'form-control input-sm', 'rows'=> '5']) }}
                                    <span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>
                                    @if ($errors->has('CA_SUMMARY'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_SUMMARY', 'Jawapan Kepada Pengadu', ['class' => 'col-sm-1 control-label']) }}
                                <div class="col-sm-11">
                                    {{ Form::textarea('CA_ANSWER', $model->CA_ANSWER, ['class' => 'form-control input-sm', 'rows'=> '5', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            <a class="btn btn-default btn-sm" href="{{ url('kemaskini') }}">Kembali</a>
                            <!--{{-- Form::button('Kemaskini', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm']) --}}-->
                            {{ Form::button('Simpan & Seterusnya'.' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm']) }}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Attachment Start -->
<div class="modal fade" id="modal-create-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalCreateContent'></div>
    </div>
</div>
<!-- Modal Edit Attachment Start -->
<div class="modal fade" id="modal-edit-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalEditContent'></div>
    </div>
</div>


<!-- Modal Start -->
@include('aduan.admin-case.usersearchmodal')
<!-- Modal End -->

@stop

@section('script_datatable')
<script type="text/javascript">
    
    $( document ).ready(function() {
        $("select").select2();
    });
    
    function onlinecmplind() {
        if (document.getElementById('CA_ONLINECMPL_IND').checked)
        {
            $('#div_CA_ONLINECMPL_CASENO').show();
        }
        else
        {
            $('#div_CA_ONLINECMPL_CASENO').hide();
        }
    };
    
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href='+hash+']').tab('show');
    } 
    
    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
    
    var CA_INVSTS = $('#CA_INVSTS').val();
    if(CA_INVSTS === 1){
        $('#deletebutton').hide();
    }
    
    function check(value) {
        if (value === '1') {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        } else {
            $('#warganegara').show();
            $('#bknwarganegara').show();
        }
    }
    
    function onlineaddind() {
        if (document.getElementById('CA_ONLINEADD_IND').checked) {
            $('#div_CA_AGAINSTADD').show();
            $('#div_CA_AGAINST_POSTCD').show();
            $('#div_CA_AGAINST_STATECD').show();
            $('#div_CA_AGAINST_DISTCD').show();
        }else{
            $('#div_CA_AGAINSTADD').hide();
            $('#div_CA_AGAINST_POSTCD').hide();
            $('#div_CA_AGAINST_STATECD').hide();
            $('#div_CA_AGAINST_DISTCD').hide();
        }
    };
    
    $( document ).ready(function () {
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
            var CA_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('admin-case/getdistlist') }}" + "/" + CA_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_DISTCD"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                },
                complete: function (data) {
                    $('#CA_DISTCD').trigger('change');
                }
            });
        });
        $('#CA_AGAINST_STATECD').on('change', function (e) {
            var CA_AGAINST_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('sas-case/getdistrictlist') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success:function(data){
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function(key, value) {
                        if(value == '0')
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="">'+ key +'</option>');
                        else
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });
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
        
        $('#CA_CMPLCAT').on('change', function (e) {
            var CA_CMPLCAT = $(this).val();
            
            if(CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
                $( "#CA_CMPLKEYWORD" ).show();
            }else{
                $( "#CA_CMPLKEYWORD" ).hide();
            }
            
            if(CA_CMPLCAT === 'BPGK 08') {
                $( "#CA_TTPMTYP" ).show();
                $( "#CA_TTPMNO" ).show();
            }else{
                $( "#CA_TTPMTYP" ).hide();
                $( "#CA_TTPMNO" ).hide();
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
                $('#div_CA_ONLINECMPL_AMOUNT').show();
                $('#div_CA_ONLINECMPL_BANKCD').show();
                $('#div_CA_ONLINECMPL_PYMNTTYP').show();
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
                $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
                $('#div_SERVICE_PROVIDER_INFO').show();
            }else{
                $( "#checkpernahadu" ).hide();
                $( "#checkinsertadd" ).hide();
                $('#div_CA_ONLINECMPL_CASENO').hide();
                $('#div_CA_ONLINECMPL_PROVIDER').hide();
                $('#div_CA_ONLINECMPL_URL').hide();
                $('#div_CA_ONLINECMPL_AMOUNT').hide();
                $('#div_CA_ONLINECMPL_BANKCD').hide();
                $('#div_CA_ONLINECMPL_PYMNTTYP').hide();
                $('#div_CA_ONLINECMPL_ACCNO').hide();
                $('#div_CA_AGAINST_PREMISE').show();
                $('#div_CA_AGAINSTADD').show();
                $('#div_CA_AGAINST_POSTCD').show();
                $('#div_CA_AGAINST_STATECD').show();
                $('#div_CA_AGAINST_DISTCD').show();
                $('#div_SERVICE_PROVIDER_INFO').hide();
            }
            
            $.ajax({
                type: 'GET',
                url: "{{ url('admin-case/getcmpllist') }}" + "/" + CA_CMPLCAT,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('select[name="CA_CMPLCD"]').empty();
                    $.each(data, function (key, value) {
                        if (value === '0'){
                            $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                            $('select[name="CA_CMPLCD"]').trigger('change');
                        } else {
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
                lengthMenu: 'Paparan _MENU_ rekod',
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
                url: "{{ url('admin-case/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = '{{ Auth::User()->state_cd }}';
                    d.brn_cd = '{{ Auth::User()->brn_cd }}';
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '5%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '8%'}
            ]
        });

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
            e.preventDefault();
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        
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
    });
    
    function myFunction(id) {
        $.ajax({
            url: "{{ url('admin-case/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("RCVBY_name").value = key;
                    document.getElementById("RCVBY_id").value = value;
                });
                $('#carian-penerima').modal('hide');
            }
        });
    };
    function isNumberKey(evt) {
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

</script>
@stop
