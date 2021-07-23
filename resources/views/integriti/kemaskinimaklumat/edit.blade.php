@extends('layouts.main')
<?php
    use App\Branch;
    use App\Ref;
    use App\Aduan\AdminCase;
    use App\Integriti\IntegritiAdmin;
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
<h2>Kemaskini Maklumat Aduan Integriti</h2>
<!--<div class="row">-->
    <!--<div class="col-lg-12">-->
        <!--<div class="ibox float-e-margins">-->
            <!--<div class="ibox-content">-->
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active">
            <a>
                <!-- <span class="fa-stack"> -->
                    <!-- <span style="font-size: 14px;" class="badge badge-danger">1</span> -->
                <!-- </span> -->
                Maklumat Aduan
            </a>
        </li>
        <!-- <li class=""> -->
            <!-- <a href='{{ $model->CA_INVSTS == "7" ? route("kemaskini.docbuktiaduan", $model->CA_CASEID) : "" }}'> -->
            <!-- <a> -->
                <!-- <span class="fa-stack"> -->
                    <!-- <span style="font-size: 14px;" class="badge badge-danger">2</span> -->
                <!-- </span> -->
                <!-- LAMPIRAN -->
            <!-- </a> -->
        <!-- </li> -->
        <!-- <li class=""> -->
            <!-- <a href='{{ $model->CA_INVSTS == "7" ? route("kemaskini.preview", $model->CA_CASEID) : "" }}'> -->
            <!-- <a> -->
                <!-- <span class="fa-stack"> -->
                    <!-- <span style="font-size: 14px;" class="badge badge-danger">3</span> -->
                <!-- </span> -->
                <!-- SEMAKAN ADUAN -->
            <!-- </a> -->
        <!-- </li> -->
    </ul>
    <div class="tab-content">
        <div id="caseinfo" class="tab-pane active">
            <div class="panel-body">
                {{ Form::open(['route' => ['integritikemaskinimaklumat.update', $model->id], 'class' => 'form-horizontal']) }}
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <h4>Cara Terima</h4>
                    <!--<div class="hr-line-solid"></div>-->
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <p class="form-control-static">
                                        <strong>
                                            <!-- <a  -->
                                                <!-- onclick="showsummaryintegriti('{{-- $mPenugasan->id --}}')"  -->
                                                <!-- class="text-primary" -->
                                                <!-- data-toggle="tooltip" data-placement="right" title="Tekan untuk melihat maklumat aduan" -->
                                            <!-- > -->
                                                {{ $model->IN_CASEID }}
                                            <!-- </a> -->
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RCVDT') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVDT', 'Tarikh Terima', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::text('IN_RCVDT', date('d-m-Y h:i A', strtotime($model->IN_RCVDT)), ['class' => 'form-control input-sm', 'readonly' => 'true']) --}} -->
                                    <p class="form-control-static">
                                        {{ $model->IN_RCVDT ? date('d-m-Y h:i A', strtotime($model->IN_RCVDT)) : '' }}
                                    </p>
                                    @if ($errors->has('IN_RCVDT'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RCVDT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('IN_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVBY', 'Penerima', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!--<div class="input-group">-->
                                        <!-- {{-- Form::hidden('CA_RCVBY', old('CA_RCVBY', $model->CA_RCVBY), ['class' => 'form-control input-sm', 'id' => 'RCVBY_id']) --}} -->
                                        <!-- {{-- Form::text('', $RcvBy, ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'RCVBY_name']) --}} -->
                                        <p class="form-control-static">
                                            {{ $model->rcvby ? $model->rcvby->name : $model->IN_RCVBY }}
                                        </p>
                                        <!--<p class="form-control-static">{{-- $RcvBy --}}</p>-->
                                        <!--<span class="input-group-btn">-->
                                            <!--<a data-toggle="modal" class="btn btn-primary btn-sm" href="#carian-penerima">Carian</a>-->
                                        <!--</span>-->
                                        @if ($errors->has('IN_RCVBY'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_RCVBY') }}</strong></span>
                                        @endif
                                    <!--</div>-->
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVTYP', 'Cara Penerimaan', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!--{{-- Form::select('CA_RCVTYP', AdminCase::getRefList('259', true), old('CA_RCVTYP', $model->CA_RCVTYP), ['class' => 'form-control input-sm', 'id' => 'CA_RCVTYP']) --}}-->
                                    <!-- {{-- Form::text('CA_RCVTYP_DESCR', $model->CA_RCVTYP != ''? Ref::GetDescr('259', $model->CA_RCVTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                                    <!--<p class="form-control-static">{{-- $model->CA_RCVTYP != ''? Ref::GetDescr('259', $model->CA_RCVTYP, 'ms') : '' --}}</p>-->
                                    <p class="form-control-static">
                                        {{ $model->rcvtyp ? $model->rcvtyp->descr : $model->IN_RCVTYP }}
                                    </p>
                                    <!-- {{-- Form::hidden('CA_RCVTYP', $model->CA_RCVTYP, ['class' => 'form-control input-sm']) --}} -->
                                    @if ($errors->has('IN_RCVTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RCVTYP') }}</strong></span>
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
                            <div class="form-group{{ $errors->has('IN_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::text('CA_DOCNO', old('CA_DOCNO', $model->CA_DOCNO), ['class' => 'form-control input-sm', 'id' => 'DOCNO', 'maxlength' => 12, 'readonly' => true]) --}} -->
                                    <p class="form-control-static">
                                        {{ $model->IN_DOCNO }}
                                    </p>
                                    @if ($errors->has('IN_DOCNO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!--<div class="col-lg-6"></div>-->
                            <!--<div class="col-lg-6" style="color: red"><strong>** Diperlukan salah satu</strong></div>-->
                            <div class="form-group{{ $errors->has('IN_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('IN_EMAIL', 'Emel', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::email('CA_EMAIL', old('CA_EMAIL', $model->CA_EMAIL), ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                                    <p class="form-control-static">{{ $model->IN_EMAIL }}</p>
                                    <style scoped>input:invalid, textarea:invalid { color: red; }</style>
                                    @if ($errors->has('IN_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::text('CA_MOBILENO', old('CA_MOBILENO', $model->CA_MOBILENO), ['class' => 'form-control input-sm' ,'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12, 'readonly' => true ]) --}} -->
                                    <p class="form-control-static">{{ $model->IN_MOBILENO }}</p>
                                    @if ($errors->has('IN_MOBILENO'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::text('CA_TELNO', old('CA_TELNO', $model->CA_TELNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10, 'readonly' => true]) --}} -->
                                    <p class="form-control-static">{{ $model->IN_TELNO }}</p>
                                    @if ($errors->has('IN_TELNO'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_FAXNO', 'No. Faks', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::text('CA_FAXNO', old('CA_FAXNO', $model->CA_FAXNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10, 'readonly' => true]) --}} -->
                                    <p class="form-control-static">{{ $model->IN_FAXNO }}</p>
                                    @if ($errors->has('IN_FAXNO'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('IN_ADDR', 'Alamat', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::textarea('CA_ADDR', old('CA_ADDR', $model->CA_ADDR), ['class' => 'form-control input-sm', 'rows'=>'4', 'readonly' => true]) --}} -->
                                    <p class="form-control-static">
                                        {!! nl2br(htmlspecialchars($model->IN_ADDR)) !!}
                                    </p>
                                    @if ($errors->has('IN_ADDR'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('IN_NAME') ? ' has-error' : '' }}">
                                {{ Form::label('IN_NAME', 'Nama', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!-- {{-- Form::text('CA_NAME', old('CA_NAME', $model->CA_NAME), ['class' => 'form-control input-sm', 'id' => 'CA_NAME', 'readonly' => true]) --}} -->
                                    <p class="form-control-static">{{ $model->IN_NAME }}</p>
                                    @if ($errors->has('IN_NAME'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGE', 'Umur', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!-- {{-- Form::text('CA_AGE', old('CA_AGE', $model->CA_AGE), ['class' => 'form-control input-sm', 'id' => 'CA_AGE','onkeypress' => "return isNumberKey(event)", 'maxlength' => 3, 'readonly' => true ]) --}} -->
                                    <!--{{-- Form::select('CA_AGE', Ref::GetList('309', true, 'ms'), old('CA_AGE', $model->CA_AGE), ['class' => 'form-control input-sm', 'disabled' => 'true']) --}}-->
                                    <p class="form-control-static">{{ $model->IN_AGE }}</p>
                                    @if ($errors->has('IN_AGE'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_SEXCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SEXCD', 'Jantina', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!--{{-- Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), old('CA_SEXCD', $model->CA_SEXCD), ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD']) --}}-->
                                    <!-- {{-- Form::text('CA_SEXCD', $model->CA_SEXCD != '' ? Ref::GetDescr('202', $model->CA_SEXCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                                    <p class="form-control-static">
                                        <!-- {{-- $model->CA_SEXCD != '' ? Ref::GetDescr('202', $model->CA_SEXCD, 'ms') : '' --}} -->
                                        {{ $model->sexcd ? $model->sexcd->descr : $model->IN_SEXCD }}
                                    </p>
                                    @if ($errors->has('IN_SEXCD'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RACECD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RACECD', 'Bangsa', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!--{{-- Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), old('CA_RACECD', $model->CA_RACECD), ['class' => 'form-control input-sm']) --}}-->
                                    <!-- {{-- Form::text('CA_RACECD', $model->CA_RACECD != ''? Ref::GetDescr('580', $model->CA_RACECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                                    <p class="form-control-static">
                                        <!-- {{-- $model->CA_RACECD != ''? Ref::GetDescr('580', $model->CA_RACECD, 'ms') : '' --}} -->
                                        {{ $model->racecd ? $model->racecd->descr : $model->IN_RACECD }}
                                    </p>
                                    @if ($errors->has('IN_RACECD'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_RACECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_NATCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_NATCD', 'Warganegara', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <!--<div class="radio radio-success">-->
                                        <!--<input id="CA_NATCD1" type="radio" name="CA_NATCD" value="1" onclick="check(this.value)" {{ $model->CA_NATCD == '1' ? 'checked' : '' }} >-->
                                        <!--<label for="CA_NATCD1"> Warganegara </label>-->
                                    <!--</div>-->
                                    <!--<div class="radio radio-success">-->
                                        <!--<input id="CA_NATCD2" type="radio" name="CA_NATCD" value="0" onclick="check(this.value)" {{ $model->CA_NATCD == '0' ? 'checked' : '' }} >-->
                                        <!--<label for="CA_NATCD2"> Bukan Warganegara </label>-->
                                    <!--</div>-->
                                    <!-- {{-- Form::text('CA_RACECD', $model->CA_NATCD != '' ? Ref::GetDescr('947', $model->CA_NATCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                                    <p class="form-control-static">
                                        <!-- {{-- $model->CA_NATCD != '' ? Ref::GetDescr('947', $model->CA_NATCD, 'ms') : '' --}} -->
                                        {{ $model->natcd ? $model->natcd->descr : $model->IN_NATCD }}
                                    </p>
                                    @if ($errors->has('IN_NATCD'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_NATCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="warganegara" style="display:block">
                                <div class="form-group {{ $errors->has('IN_POSTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_POSTCD', 'Poskod', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <!-- {{-- Form::text('CA_POSCD', old('CA_POSCD', $model->CA_POSCD), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5, 'readonly' => true]) --}} -->
                                        <p class="form-control-static">{{ $model->IN_POSTCD }}</p>
                                        @if ($errors->has('IN_POSTCD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_POSTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('IN_STATECD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_STATECD', 'Negeri', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <!--{{-- Form::select('CA_STATECD', Ref::GetList('17', true, 'ms'), old('CA_STATECD', $model->CA_STATECD), ['class' => 'form-control input-sm required', 'id' => 'CA_STATECD']) --}}-->
                                        <!-- {{-- Form::text('CA_STATECD', $model->CA_STATECD != ''? Ref::GetDescr('17', $model->CA_STATECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                                        <p class="form-control-static">
                                            <!-- {{-- $model->CA_STATECD != '' ? Ref::GetDescr('17', $model->CA_STATECD, 'ms') : '' --}} -->
                                            {{ $model->instatecd ? $model->instatecd->descr : $model->IN_STATECD }}
                                        </p>
                                        @if ($errors->has('IN_STATECD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('IN_DISTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_DISTCD', 'Daerah', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <!--{{-- Form::select('CA_DISTCD', $model->CA_DISTCD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->CA_STATECD, '18', true, 'ms'), old('CA_DISTCD', $model->CA_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) --}}-->
                                        <!-- {{-- Form::text('CA_DISTCD', ($model->CA_DISTCD != '' ? Ref::GetDescr('18', $model->CA_DISTCD, 'ms') : ''), ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                                        <p class="form-control-static">{{ $model->indistcd ? $model->indistcd->descr : $model->IN_DISTCD }}</p>
                                        <!-- <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span> -->
                                        @if ($errors->has('CA_DISTCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="bknwarganegara" style="display: {{ (old('IN_NATCD')? (old('IN_NATCD') == '0'? 'block':'none') : ($model->IN_NATCD == '0' ? 'block' : 'none')) }}">
                                <div class="form-group {{ $errors->has('IN_COUNTRYCD') ? ' has-error' : 'IN_COUNTRYCD' }}">
                                    {{ Form::label('IN_COUNTRYCD', 'Negara Asal', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <!--{{-- Form::select('IN_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('IN_COUNTRYCD', $model->IN_COUNTRYCD), ['class' => 'form-control input-sm']) --}}-->
                                        <p class="form-control-static">
                                            <!-- {{-- $model->IN_COUNTRYCD != ''? Ref::GetDescr('334', $model->IN_COUNTRYCD, 'ms') : '' --}} -->
                                            {{ $model->countrycd ? $model->countrycd->descr : $model->IN_COUNTRYCD }}
                                        </p>
                                        @if ($errors->has('IN_COUNTRYCD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_COUNTRYCD') }}</strong></span>
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
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_AGAINSTNM') ? ' has-error' : '' }}">
                                <!--<label id="" for="" class="col-lg-3 control-label">-->
                                    <!--Nama Pegawai Yang Diadu-->
                                <!--</label>-->
                                {{ Form::label('IN_AGAINSTNM', 'Nama Pegawai Yang Diadu (PYDA)', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ Form::text('IN_AGAINSTNM', old('IN_AGAINSTNM', $model->IN_AGAINSTNM), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_AGAINSTNM'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_AGAINSTNM') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_REFTYPE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_REFTYPE', 'Jenis Rujukan Berkaitan', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::select(
                                            'IN_REFTYPE', 
                                            [
                                                'OTHER' => 'Salah Laku (Umum)', 
                                                'BGK' => 'Salah Laku (Aduan Kepenggunaan)', 
                                                'TTPM' => 'Salah Laku (TTPM)',
                                            ], 
                                            old('IN_REFTYPE', $model->IN_REFTYPE), 
                                            [
                                                'class' => 'form-control input-sm select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        )
                                    }}
                                    @if ($errors->has('IN_REFTYPE'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_REFTYPE') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_bgk" 
                            class="form-group{{ $errors->has('IN_BGK_CASEID') ? ' has-error' : '' }}"
                            style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'BGK' ? 'block' : 'none') : ($model->IN_REFTYPE == 'BGK' ? 'block' : 'none') }};">
                                {{ Form::label('IN_BGK_CASEID', 'Aduan Kepenggunaan', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::select('IN_BGK_CASEID', 
                                            $model->IN_DOCNO ? IntegritiAdmin::getusercomplaintlist($model->IN_DOCNO) : [], 
                                            old('IN_BGK_CASEID', $model->IN_BGK_CASEID), 
                                            [
                                                'class' => 'form-control input-sm select2', 
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_BGK_CASEID'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_BGK_CASEID') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_ttpm" 
                            class="form-group {{ $errors->has('IN_TTPMNO') ? ' has-error' : '' }}"
                            style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none') : ($model->IN_REFTYPE == 'TTPM' ? 'block' : 'none') }};">
                                {{ Form::label('IN_TTPMNO', 'No. TTPM', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::text('IN_TTPMNO',
                                        old('IN_TTPMNO', $model->IN_TTPMNO), 
                                        ['class' => 'form-control input-sm']) 
                                    }}
                                    @if ($errors->has('IN_TTPMNO'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_TTPMNO') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_ttpmform" 
                                class="form-group {{ $errors->has('IN_TTPMFORM') ? ' has-error' : '' }}"
                                style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none') : ($model->IN_REFTYPE == 'TTPM' ? 'block' : 'none') }};">
                                {{ Form::label('IN_TTPMFORM', 'Jenis Borang TTPM', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::select(
                                            'IN_TTPMFORM', 
                                            [
                                                '8' => 'Borang 8 - Award bagi pihak yang menuntut jika penentang tidak hadir', 
                                                '9' => 'Borang 9 - Award dengan persetujuan', 
                                                '10' => 'Borang 10 - Award selepas pendengaran', 
                                            ], 
                                            old('IN_TTPMFORM', $model->IN_TTPMFORM), 
                                            [
                                                'class' => 'form-control input-sm select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        )
                                    }}
                                    @if ($errors->has('IN_TTPMFORM'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_TTPMFORM') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_other" 
                                class="form-group {{ $errors->has('IN_REFOTHER') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'OTHER' ? 'block' : 'none') : ($model->IN_REFTYPE == 'OTHER' ? 'block' : 'none') }};">
                                {{ 
                                    Form::label('IN_REFOTHER', 
                                    'Lain-lain', 
                                    ['class' => 'col-lg-3 control-label']) 
                                }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::text('IN_REFOTHER',
                                        old('IN_REFOTHER', $model->IN_REFOTHER), 
                                        ['class' => 'form-control input-sm']) 
                                    }}
                                    @if ($errors->has('IN_REFOTHER'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_REFOTHER') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_AGAINSTLOCATION') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGAINSTLOCATION', 'Lokasi PYDA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    <div class="radio radio-primary radio-inline">
                                        <input type="radio" id="IN_AGAINSTLOCATION1" value="BRN" name="IN_AGAINSTLOCATION" 
                                        {{ 
                                            old('IN_AGAINSTLOCATION') 
                                            ? (old('IN_AGAINSTLOCATION') == 'BRN'? 'checked':'') 
                                            : ($model->IN_AGAINSTLOCATION == 'BRN' ? 'checked' : '')
                                        }}>
                                        <!-- onclick="againstlocation(this.value)"  -->
                                        <label for="IN_AGAINSTLOCATION1">
                                            KPDNHEP
                                        </label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="IN_AGAINSTLOCATION2" value="AGN" name="IN_AGAINSTLOCATION" 
                                        {{ 
                                            old('IN_AGAINSTLOCATION') 
                                            ? 
                                                old('IN_AGAINSTLOCATION') == 'AGN' && old('IN_REFTYPE') != 'TTPM'
                                                ? 'checked'
                                                : 
                                                (
                                                    old('IN_REFTYPE') == 'TTPM'
                                                    ? 'disabled'
                                                    : '' 
                                                )
                                            : 
                                            (
                                                $model->IN_AGAINSTLOCATION == 'AGN' && $model->IN_REFTYPE != 'TTPM' 
                                                ? 'checked' 
                                                : (
                                                    $model->IN_REFTYPE == 'TTPM' || $model->IN_REFTYPE == 'BGK' 
                                                    ? 'disabled' 
                                                    : ''
                                                )
                                            )
                                        }}>
                                        <!-- onclick="againstlocation(this.value)"  -->
                                        <label for="IN_AGAINSTLOCATION2">
                                            Agensi KPDNHEP
                                        </label>
                                    </div>
                                    @if ($errors->has('IN_AGAINSTLOCATION'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_AGAINSTLOCATION') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_IN_AGAINST_BRSTATECD" 
                                class="form-group {{ $errors->has('IN_AGAINST_BRSTATECD') ? ' has-error' : '' }}" 
                                style="display: {{ 
                                    old('IN_AGAINSTLOCATION') 
                                    ? (old('IN_AGAINSTLOCATION') == 'BRN'? 'block' : 'none') 
                                    : ($model->IN_AGAINSTLOCATION == 'BRN' ? 'block' : 'none')
                                }};">
                                {{ Form::label('IN_AGAINST_BRSTATECD', 
                                    'Negeri', 
                                    ['class' => 'col-lg-3 control-label required']) 
                                }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::select(
                                            'IN_AGAINST_BRSTATECD', 
                                            Ref::GetList('17', false, 'ms'), 
                                            old('IN_AGAINST_BRSTATECD', $model->IN_AGAINST_BRSTATECD), 
                                            [
                                                'class' => 'form-control input-sm select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_AGAINST_BRSTATECD'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_AGAINST_BRSTATECD') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_IN_BRNCD" 
                                class="form-group {{ $errors->has('IN_BRNCD') ? ' has-error' : '' }}" 
                                style="display: {{ 
                                    old('IN_AGAINSTLOCATION') 
                                    ? (old('IN_AGAINSTLOCATION') == 'BRN'? 'block' : 'none') 
                                    : ($model->IN_AGAINSTLOCATION == 'BRN' ? 'block' : 'none')
                                }};">
                                {{ Form::label('IN_BRNCD', 'Bahagian / Cawangan', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    <!-- {{-- 
                                        Form::select('IN_BRNCD', Branch::GetListBranch(), 
                                            old('IN_BRNCD', $model->IN_BRNCD), 
                                            [
                                                'class' => 'form-control input-sm select2', 
                                                'style' => 'width:100%'
                                            ]
                                        ) 
                                    --}} -->
                                    {{ 
                                        Form::select('IN_BRNCD', Branch::GetListByState(old('IN_AGAINST_BRSTATECD', $model->IN_AGAINST_BRSTATECD)), 
                                            old('IN_BRNCD', $model->IN_BRNCD), 
                                            [
                                                'class' => 'form-control input-sm select2', 
                                                'style' => 'width:100%'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_BRNCD'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_BRNCD') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_IN_AGENCYCD" 
                                class="form-group {{ $errors->has('IN_AGENCYCD') ? ' has-error' : '' }}" 
                                style="display: {{ 
                                    old('IN_AGAINSTLOCATION') 
                                    ? (old('IN_AGAINSTLOCATION') == 'AGN'? 'block' : 'none') 
                                    : ($model->IN_AGAINSTLOCATION == 'AGN' ? 'block' : 'none')
                                }};">
                                {{ 
                                    Form::label('IN_AGENCYCD', 
                                    'Agensi KPDNHEP', 
                                    ['class' => 'col-lg-3 control-label required']) 
                                }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::select(
                                            'IN_AGENCYCD', 
                                            IntegritiAdmin::GetMagncdList(), 
                                            old('IN_AGENCYCD', $model->IN_AGENCYCD), 
                                            [
                                                'class' => 'form-control input-sm select2'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_AGENCYCD'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_AGENCYCD') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_SUMMARY_TITLE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SUMMARY_TITLE', 'Tajuk Aduan', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ Form::text('IN_SUMMARY_TITLE', old('IN_SUMMARY_TITLE', $model->IN_SUMMARY_TITLE), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_SUMMARY_TITLE'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_SUMMARY_TITLE') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SUMMARY', trans('public-case.case.CA_SUMMARY'), ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ Form::textarea('IN_SUMMARY', old('IN_SUMMARY', $model->IN_SUMMARY), ['class' => 'form-control input-sm', 'rows'=> '5']) }}
                                    <span class="help-block m-b-none" style="font-weight:bold;color:black">
                                    </span>
                                    @if ($errors->has('IN_SUMMARY'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_SUMMARY') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row"> -->
                        <!-- <div class="col-sm-12"> -->
                            <!-- <div class="form-group"> -->
                                <!-- {{ Form::label('CA_SUMMARY', 'Jawapan Kepada Pengadu', ['class' => 'col-lg-3 control-label']) }} -->
                                <!-- <div class="col-lg-9"> -->
                                    <!-- {{-- Form::textarea('CA_ANSWER', $model->CA_ANSWER, ['class' => 'form-control input-sm', 'rows'=> '5', 'readonly' => true]) --}} -->
                                    <!-- <p class="form-control-static"> -->
                                        <!-- {!! nl2br(htmlspecialchars($model->IN_ANSWER)) !!} -->
                                    <!-- </p> -->
                                <!-- </div> -->
                            <!-- </div> -->
                        <!-- </div> -->
                    <!-- </div> -->
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            <a class="btn btn-default" href="{{ url('integritikemaskinimaklumat') }}">
                                <i class="fa fa-home"></i> Kembali
                            </a>
                            {{ Form::button(
                                'Simpan '.' <i class="fa fa-save"></i>', 
                                ['type' => 'submit', 'class' => 'btn btn-success']
                            ) }}
                        </div>
                    </div>
                {{ Form::close() }}
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

        $('input[type=radio][name=IN_AGAINSTLOCATION]').on('change', function() {
            switch($(this).val()) {
                case 'BRN':
                    // alert("BRN value 1");
                    $('#div_IN_AGAINST_BRSTATECD').show();
                    $('#div_IN_BRNCD').show();
                    $('#div_IN_AGENCYCD').hide();
                    break;
                case 'AGN':
                    // alert("AGN value 2");
                    $('#div_IN_AGAINST_BRSTATECD').hide();
                    $('#div_IN_BRNCD').hide();
                    $('#div_IN_AGENCYCD').show();
                    break;
                default:
                    // alert("default value");
                    $('#div_IN_AGAINST_BRSTATECD').hide();
                    $('#div_IN_BRNCD').hide();
                    $('#div_IN_AGENCYCD').hide();
                    break;
            }
        });

        $('#IN_AGAINST_BRSTATECD').on('change', function (e) {
            var IN_AGAINST_BRSTATECD = $(this).val();
            if(IN_AGAINST_BRSTATECD){
                $.ajax({
                    type: 'GET',
                    url: "{{ url('user/getbrnlist') }}" + '/' + IN_AGAINST_BRSTATECD,
                    dataType: 'json',
                    success: function (data) {
                        $('select[name="IN_BRNCD"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="IN_BRNCD"]').append('<option value="' + key + '">' + value + '</option>');
                        })
                    },
                    complete: function (data) {
                        $('#IN_BRNCD').trigger('change');
                    }
                });
            } else {
                $('select[id="IN_BRNCD"]').empty();
				$('select[id="IN_BRNCD"]').append('<option>-- SILA PILIH --</option>');
                $('select[id="IN_BRNCD"]').trigger('change');
            }
        });
    });

    $(function () {
        $('#IN_REFTYPE').on('change', function () {
            var IN_REFTYPE = $(this).val();
            // var IN_DOCNO = $('#IN_DOCNO').val();
            if(IN_REFTYPE === 'BGK') {
                $("#div_bgk").show();
                $("#div_other").hide();
                $("#div_ttpm").hide();
                $("#div_ttpmform").hide();
                $('#IN_AGAINSTLOCATION1').trigger('click');
                // $('#IN_AGAINSTLOCATION2').attr('disabled', false);
                $('#IN_AGAINSTLOCATION2').attr('disabled', true);
                // if(!IN_DOCNO){
                    // alert('Sila isikan No. Kad Pengenalan Sebelum Memilih Rujukan No. Aduan Kepenggunaan.');
                // }
            } else if(IN_REFTYPE === 'TTPM') {
                $("#div_bgk").hide();
                $("#div_ttpm").show();
                $("#div_ttpmform").show();
                $("#div_other").hide();
                $('#IN_AGAINSTLOCATION1').trigger('click');
                $('#IN_AGAINSTLOCATION2').attr('disabled', true);
            } else if(IN_REFTYPE === 'OTHER') {
                $("#div_bgk").hide();
                $("#div_ttpm").hide();
                $("#div_ttpmform").hide();
                $("#div_other").show();
                $('#IN_AGAINSTLOCATION2').attr('disabled', false);
            } else {
                $("#div_bgk").hide();
                $("#div_ttpm").hide();
                $("#div_ttpmform").hide();
                $("#div_other").hide();
                $('#IN_AGAINSTLOCATION2').attr('disabled', false);
            }
        });
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
