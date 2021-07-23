@extends('layouts.main')
<?php
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
<h2>Kemaskini Aduan</h2>
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
            <a href='{{ $model->CA_INVSTS == "10" ? route("admin-case.attachment", $model->id) : "" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                LAMPIRAN
            </a>
        </li>
        <li class="">
            <a href='{{ $model->CA_INVSTS == "10" ? route("admin-case.preview", $model->id) : "" }}'>
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
                {!! Form::open(['route' => ['admin-case.update', $model->id], 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }}{{ method_field('PATCH') }}
                    <h4>Cara Terima</h4>
                    <!--<div class="hr-line-solid"></div>-->
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVDT', 'Tarikh', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::text('CA_RCVDT', date('d-m-Y h:i A', strtotime($model->CA_RCVDT)), ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                    @if ($errors->has('CA_RCVDT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        {{ Form::hidden('CA_RCVBY', old('CA_RCVBY', $model->CA_RCVBY), ['class' => 'form-control input-sm', 'id' => 'RCVBY_id']) }}
                                        {{ Form::text('', $RcvBy, ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'RCVBY_name']) }}
                                        <span class="input-group-btn">
                                            <a data-toggle="modal" class="btn btn-primary btn-sm" href="#carian-penerima">Carian</a>
                                        </span>
                                        @if ($errors->has('CA_RCVBY'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVBY') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_RCVTYP', AdminCase::getRefList('259', true), old('CA_RCVTYP', $model->CA_RCVTYP), ['class' => 'form-control input-sm', 'id' => 'CA_RCVTYP']) }}
                                    @if ($errors->has('CA_RCVTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_BPANO" class="form-group{{ $errors->has('CA_BPANO') ? ' has-error' : '' }}" style="display: {{ (old('CA_RCVTYP')?(in_array(old('CA_RCVTYP'),['S14'])? 'block':'none') : ((in_array($model->CA_RCVTYP,['S14'])? 'block':'none'))) }};">
                                {{ Form::label('CA_BPANO', 'No. Aduan BPA', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_BPANO', old('CA_BPANO', $model->CA_BPANO), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_BPANO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_BPANO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_SERVICENO" class="form-group{{ $errors->has('CA_SERVICENO') ? ' has-error' : '' }}" style="display: {{ (old('CA_RCVTYP')?(in_array(old('CA_RCVTYP'),['S33'])? 'block':'none') : ((in_array($model->CA_RCVTYP,['S33'])? 'block':'none'))) }};">
                                {{ Form::label('CA_SERVICENO', 'No. Tali Khidmat', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_SERVICENO', old('CA_SERVICENO', $model->CA_SERVICENO), ['class' => 'form-control input-sm']) }}
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
                                {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-6 control-label required']) }}
                                <div class="col-sm-6">
                                    <!--<div class="input-group">-->
                                        {{ Form::text('CA_DOCNO', old('CA_DOCNO', $model->CA_DOCNO), ['class' => 'form-control input-sm', 'id' => 'CA_DOCNO', 'maxlength' => 12]) }}
                                        <!--<span class="input-group-btn">-->
                                            <!--<button class="ladda-button ladda-button-demo btn btn-primary btn-sm" type="button" data-style="expand-right" id="CheckJpn">Semak JPN</button>-->
                                        <!--</span>-->
                                    <!--</div>-->
                                    @if ($errors->has('CA_DOCNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6" style="color: red"><strong>** Diperlukan salah satu</strong></div>
                            <div class="form-group{{ $errors->has('CA_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-sm-6 control-label required1']) }}
                                <div class="col-sm-6">
                                    <!--{{-- Form::text('CA_EMAIL', old('CA_EMAIL', $model->CA_EMAIL), ['class' => 'form-control input-sm']) --}}-->
                                    {{ Form::email('CA_EMAIL', old('CA_EMAIL', $model->CA_EMAIL), ['class' => 'form-control input-sm', 'id' => 'CA_EMAIL']) }}
                                    <style scoped>input:invalid, textarea:invalid { color: red; }</style>
                                    @if ($errors->has('CA_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-6 control-label required1']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('CA_MOBILENO', old('CA_MOBILENO', $model->CA_MOBILENO), ['class' => 'form-control input-sm', 'id' => 'CA_MOBILENO', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12 ]) }}
                                    @if ($errors->has('CA_MOBILENO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-sm-6 control-label required1']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('CA_TELNO', old('CA_TELNO', $model->CA_TELNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                    @if ($errors->has('CA_TELNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('CA_FAXNO', old('CA_FAXNO', $model->CA_FAXNO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10]) }}
                                    @if ($errors->has('CA_FAXNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
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
                                {{ Form::label('CA_NAME', 'Nama', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('CA_NAME', old('CA_NAME', $model->CA_NAME), ['class' => 'form-control input-sm', 'id' => 'CA_NAME']) }}
                                    @if ($errors->has('CA_NAME'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('CA_AGE', old('CA_AGE', $model->CA_AGE), ['class' => 'form-control input-sm', 'id' => 'CA_AGE','onkeypress' => "return isNumberKey(event)", 'maxlength' => 3 ]) }}
                                    {{-- Form::select('CA_AGE', Ref::GetList('309', true, 'ms'), old('CA_AGE', $model->CA_AGE), ['class' => 'form-control input-sm', 'disabled' => 'true']) --}}
                                    @if ($errors->has('CA_AGE'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), old('CA_SEXCD', $model->CA_SEXCD), ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD']) }}
                                    @if ($errors->has('CA_SEXCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_RACECD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), old('CA_RACECD', $model->CA_RACECD), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_RACECD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_RACECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
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
                                    {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_POSCD', old('CA_POSCD', $model->CA_POSCD), ['class' => 'form-control input-sm', 'id' => 'CA_POSCD', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                        {{ Form::hidden('CA_MYIDENTITY_POSCD', old('CA_MYIDENTITY_POSCD', $model->CA_MYIDENTITY_POSCD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_POSCD']) }}
                                        @if ($errors->has('CA_POSCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_STATECD') ? ' has-error' : 'CA_STATECD' }}">
                                    {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_STATECD', Ref::GetList('17', true, 'ms'), old('CA_STATECD', $model->CA_STATECD), ['class' => 'form-control input-sm required', 'id' => 'CA_STATECD']) }}
                                        {{ Form::hidden('CA_MYIDENTITY_STATECD', old('CA_MYIDENTITY_STATECD', $model->CA_MYIDENTITY_STATECD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_STATECD']) }}
                                        @if ($errors->has('CA_STATECD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_DISTCD') ? ' has-error' : 'CA_DISTCD' }}">
                                    {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{-- Form::select('CA_DISTCD', $model->CA_DISTCD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->CA_STATECD, '18', true, 'ms'), old('CA_DISTCD', $model->CA_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) --}}
                                        {{ Form::select('CA_DISTCD', PublicCase::GetDstrtList((old('CA_STATECD')? old('CA_STATECD') : $model->CA_STATECD)), (old('CA_DISTCD')? old('CA_DISTCD') : ($model->CA_DISTCD? $model->CA_DISTCD:'')), ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) }}
                                        {{ Form::hidden('CA_MYIDENTITY_DISTCD', old('CA_MYIDENTITY_DISTCD', $model->CA_MYIDENTITY_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_DISTCD']) }}
                                        <!--<span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf">Bantuan?</a></em></span>-->
                                        <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                        @if ($errors->has('CA_DISTCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="bknwarganegara" style="display: {{ (old('CA_NATCD')? (old('CA_NATCD') == '0'? 'block':'none') : ($model->CA_NATCD == '0' ? 'block' : 'none')) }}">
                                <div class="form-group {{ $errors->has('CA_COUNTRYCD') ? ' has-error' : 'CA_COUNTRYCD' }}">
                                    {{ Form::label('CA_COUNTRYCD', 'Negara Asal', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('CA_COUNTRYCD', $model->CA_COUNTRYCD), ['class' => 'form-control input-sm']) }}
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
                            {{ Form::label('CA_CMPLCAT', 'Kategori', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms','descr'), old('CA_CMPLCAT', $model->CA_CMPLCAT), array('class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT', 'onchange'=>"selectCategoryOrPremise(this.value, document.getElementById('CA_AGAINST_PREMISE').value)")) }}
                                @if ($errors->has('CA_CMPLCAT'))
                                <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{-- Form::select('CA_CMPLCD', $model->CA_CMPLCAT == '' ? [''=>'-- SILA PILIH --'] : AdminCase::getcmplcdlist($model->CA_CMPLCAT), old('CA_CMPLCD', $model->CA_CMPLCD), ['class' => 'form-control input-sm']) --}}
                                {{ Form::select('CA_CMPLCD', PublicCase::GetCmplCd((old('CA_CMPLCAT')? old('CA_CMPLCAT') : $model->CA_CMPLCAT), 'ms', 'ms'), (old('CA_CMPLCD')? old('CA_CMPLCD') : ($model->CA_CMPLCD? $model->CA_CMPLCD:'')), ['class' => 'form-control input-sm', 'id' => 'CA_CMPLCD', 'style' => 'width:100%']) }}
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
                                {{ Form::text('CA_ONLINECMPL_AMOUNT', old('CA_ONLINECMPL_AMOUNT', number_format($model->CA_ONLINECMPL_AMOUNT, 2)), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey1(event)", 'id' => 'CA_ONLINECMPL_AMOUNT']) }}
                                @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="CA_CMPLKEYWORD" style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($model->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none')))  }};">
                            {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                <!-- {{-- Form::select('CA_CMPLKEYWORD', Ref::GetList('1051', true, 'ms'), old('CA_CMPLKEYWORD', $model->CA_CMPLKEYWORD), ['class' => 'form-control input-sm']) --}} -->
                                {{ Form::select('CA_CMPLKEYWORD', Ref::GetListProductType(old('CA_CMPLCAT', $model->CA_CMPLCAT), true, 'ms', 'sort'), old('CA_CMPLKEYWORD', $model->CA_CMPLKEYWORD), ['class' => 'form-control input-sm']) }}
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
                        <div id="div_CA_AGAINST_PREMISE" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($model->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_PREMISE', 'Jenis Premis', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), old('CA_AGAINST_PREMISE', $model->CA_AGAINST_PREMISE), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_PREMISE', 'onchange'=>"selectCategoryOrPremise(document.getElementById('CA_CMPLCAT').value, this.value)"]) }}
                                @if ($errors->has('CA_AGAINST_PREMISE'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_PREMISE') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        {{-- <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}"> --}}
                        <div id="div_CA_ONLINECMPL_PROVIDER"
                            style="display: {{ $data['againstonlinecomplaint'] ? 'block' : 'none' }};"
                            class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                            {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::select('CA_ONLINECMPL_PROVIDER', Ref::GetList('1091', true, 'ms', 'descr'), old('CA_ONLINECMPL_PROVIDER', $model->CA_ONLINECMPL_PROVIDER), ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PROVIDER']) }}
                                @if ($errors->has('CA_ONLINECMPL_PROVIDER'))
                                <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PROVIDER') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        {{-- <div id="div_CA_ONLINECMPL_URL" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}"> --}}
                        <div id="div_CA_ONLINECMPL_URL"
                            style="display: {{ $data['againstonlinecomplaint'] ? 'block' : 'none' }};"
                            class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                            <label for="CA_ONLINECMPL_URL" class="col-sm-5 control-label {{ old('CA_ONLINECMPL_PROVIDER') == '999'? 'required':'' }}">
                                Laman Web / URL / ID
                            </label>
                            <!--{{-- Form::label('CA_ONLINECMPL_URL', 'Laman Web / URL', ['class' => 'col-sm-5 control-label']) --}}-->
                            <div class="col-sm-7">
                                {{ Form::text('CA_ONLINECMPL_URL', old('CA_ONLINECMPL_URL', $model->CA_ONLINECMPL_URL), ['class' => 'form-control input-sm', 'placeholder' => '(Contoh: www.google.com)']) }}
                                @if ($errors->has('CA_ONLINECMPL_URL'))
                                <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_URL') }}</strong></span>
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
                        <div id="div_CA_ONLINECMPL_BANKCD" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                            <!--<label for="CA_ONLINECMPL_BANKCD" class="col-sm-5 control-label {{-- old('CA_ONLINECMPL_PYMNTTYP') ? (old('CA_ONLINECMPL_PYMNTTYP') == 'COD' ? '' : 'required') : ($model->CA_ONLINECMPL_PYMNTTYP == 'COD' ? '' : 'required') --}}">Nama Bank</label>-->
                            <label for="CA_ONLINECMPL_BANKCD" class="col-sm-5 control-label {{ old('CA_ONLINECMPL_PYMNTTYP') ? (in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '':'required') : (in_array($model->CA_ONLINECMPL_PYMNTTYP,['','COD','TB']) ? '' : 'required') }}">
                                Nama Bank
                            </label>
                            <!--{{-- Form::label('CA_ONLINECMPL_BANKCD', 'Nama Bank', ['class' => 'col-sm-5 control-label required']) --}}-->
                            <div class="col-sm-7">
                                {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, 'ms'), old('CA_ONLINECMPL_BANKCD', $model->CA_ONLINECMPL_BANKCD), ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_BANKCD']) }}
                                @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_BANKCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="div_CA_ONLINECMPL_ACCNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                            <!--<label for="CA_ONLINECMPL_ACCNO" class="col-sm-5 control-label {{-- old('CA_ONLINECMPL_PYMNTTYP') ? (old('CA_ONLINECMPL_PYMNTTYP') == 'COD' ? '' : 'required') : ($model->CA_ONLINECMPL_PYMNTTYP == 'COD' ? '' : 'required') --}}">No. Akaun Bank</label>-->
                            <label for="CA_ONLINECMPL_ACCNO" class="col-sm-5 control-label {{ old('CA_ONLINECMPL_PYMNTTYP') ? (in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '':'required') : (in_array($model->CA_ONLINECMPL_PYMNTTYP,['','COD','TB']) ? '' : 'required') }}">
                                No. Akaun Bank / No. Transaksi FPX
                            </label>
                            <!--{{-- Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun Bank', ['class' => 'col-sm-5 control-label required']) --}}-->
                            <div class="col-sm-7">
                                {{ Form::text('CA_ONLINECMPL_ACCNO', old('CA_ONLINECMPL_ACCNO', $model->CA_ONLINECMPL_ACCNO), ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_ACCNO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" id="checkpernahadu"> --}}
                        <div class="form-group"
                            style="display: {{ $data['againstonlinecomplaint'] ? 'block' : 'none' }};"
                            id="checkpernahadu">
                            <!--{{-- Form::label(old('CA_ONLINECMPL_IND'), null, ['class' => 'col-md-5 control-label']) --}}-->
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
                        <!--<div id="div_CA_ONLINECMPL_CASENO" style="display: {{-- $model->CA_CMPLCAT == 'BPGK 19'? 'block':'none' --}} ;" class="form-group{{-- $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' --}}">-->
                        {{-- <div id="div_CA_ONLINECMPL_CASENO" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINECMPL_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINECMPL_IND') == '' && $model->CA_ONLINECMPL_IND == '1'? 'block':(old('CA_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : ''}}"> --}}
                        <div id="div_CA_ONLINECMPL_CASENO"
                            style="display: {{ $data['providercaseno'] ? 'block' : 'none' }};"
                            class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : ''}}">
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
                        <div id="div_ONLINECMPL" style="height: 190px; display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }}"></div>
                        <div class="form-group{{ $errors->has('CA_AGAINSTNM') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINSTNM', 'Nama (Syarikat/Premis/Penjual)', ['class' => 'col-sm-5 control-label required']) }}
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
                        {{-- <div id="checkinsertadd" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group"> --}}
                        <div id="checkinsertadd"
                            style="display: {{ $data['againstonlinecomplaint'] ? 'block' : 'none' }};"
                            class="form-group">
                            {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                <div class="checkbox checkbox-success">
                                    <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' || $model->CA_ONLINEADD_IND == '1'? 'checked':'' }}>
                                    <label for="CA_ONLINEADD_IND">
                                        Mempunyai alamat penuh penjual / pihak yang diadu?
                                    </label>
                                </div>
                            </div>
                        </div>
                        {{-- <div id="div_CA_AGAINSTADD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}"> --}}
                        <div id="div_CA_AGAINSTADD"
                            style="display: {{ $data['againstaddress'] ? 'block' : 'none' }};"
                            class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::textarea('CA_AGAINSTADD', old('CA_AGAINSTADD', $model->CA_AGAINSTADD), ['class' => 'form-control input-sm', 'rows'=> '4']) }}
                                @if ($errors->has('CA_AGAINSTADD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTADD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        {{-- <div id="div_CA_AGAINST_POSTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}"> --}}
                        <div id="div_CA_AGAINST_POSTCD"
                            style="display: {{ $data['againstaddress'] ? 'block' : 'none' }};"
                            class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_POSTCD', 'Poskod', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('CA_AGAINST_POSTCD', old('CA_AGAINST_POSTCD', $model->CA_AGAINST_POSTCD), ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('CA_AGAINST_POSTCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_POSTCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        {{-- <div id="div_CA_AGAINST_STATECD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}"> --}}
                        <div id="div_CA_AGAINST_STATECD"
                            style="display: {{ $data['againstaddress'] ? 'block' : 'none' }};"
                            class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true), old('CA_AGAINST_STATECD', $model->CA_AGAINST_STATECD), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_STATECD']) }}
                                @if ($errors->has('CA_AGAINST_STATECD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_STATECD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        {{-- <div id="div_CA_AGAINST_DISTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}"> --}}
                        <div id="div_CA_AGAINST_DISTCD"
                            style="display: {{ $data['againstaddress'] ? 'block' : 'none' }};"
                            class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{-- Form::select('CA_AGAINST_DISTCD', ($model->CA_AGAINST_STATECD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->CA_AGAINST_STATECD, '18', true, 'ms')), old('CA_AGAINST_DISTCD', $model->CA_AGAINST_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) --}}
                                {{ Form::select('CA_AGAINST_DISTCD', PublicCase::GetDstrtList((old('CA_AGAINST_STATECD')? old('CA_AGAINST_STATECD') : $model->CA_AGAINST_STATECD)), (old('CA_AGAINST_DISTCD')? old('CA_AGAINST_DISTCD') : ($model->CA_AGAINST_DISTCD? $model->CA_AGAINST_DISTCD:'')), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) }}
                                <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                @if ($errors->has('CA_AGAINST_DISTCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_DISTCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
<!--                        <div class="form-group {{ $errors->has('CA_ROUTETOHQIND') ? ' has-error' : '' }}">
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
                                <!--<span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>-->
                                @if ($errors->has('CA_SUMMARY'))
                                <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-12" align="center">
                        <!--<input style="visibility:visible" type="button" id="btncontinue" value="{{-- trans('button.continue') --}}" onClick="btncontinueclick();" class="btn btn-primary btn-sm" />-->
                        <!--<input style="visibility:hidden" type="submit" id="btnupdate" value="{{-- trans('button.send') --}}" class="btn btn-primary btn-sm" />-->
                        <!--<input style="visibility:hidden" type="button" id="btnsave" value="{{-- trans('button.update') --}}" onClick="btnupdateclick();" class="btn btn-primary btn-sm" />-->
                        <a class="btn btn-warning btn-sm" href="{{ url('admin-case') }}">Kembali</a>
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

<!--<div id="carian-penerima" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title">Carian Penerima</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['id' => 'user-search-form', 'class' => 'form-horizontal']) !!}
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
                            {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::select('state_cd', Ref::GetList('17', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'state_cd_user']) }}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('brn_cd') ? ' has-error' : '' }}">
                            {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::select('brn_cd', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" align="center">
                        {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                        {{ Form::reset('Semula', ['class' => 'btn btn-default btn-sm', 'id' => 'btn-reset']) }}
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="table-responsive">
                    <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>No. Kad Pengenalan</th>
                                <th>Nama</th>
                                <th>Negeri</th>
                                <th>Cawangan</th>
                                <th></th>
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

<!-- Modal Start -->
@include('aduan.admin-case.usersearchmodal')
<!-- Modal End -->

@stop

@section('script_datatable')
<script type="text/javascript">
    
    $( document ).ready(function() {
//        var status = <?php // echo $model->CA_INVSTS; ?>;
//        if(status != '10') {
//            $('.form-control').attr("disabled", true);
//        }
        $("select").select2();
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
                $('#CA_RACECD').val(data.RaceCd).trigger('change'); // Bangsa
                if (data.Warganegara != '') {
                    if (data.Warganegara === '1') { // Warganegara
                        document.getElementById('CA_NATCD1').checked = true;
                        $('#warganegara').show();
                        $('#bknwarganegara').hide();
                    } else {
                        document.getElementById('CA_NATCD2').checked = true;
                        $('#warganegara').show();
                        $('#bknwarganegara').show();
                    }
                }
                // Standard Field
                $('#CA_ADDR').val(data.CorrespondenceAddress1 + '\n' + data.CorrespondenceAddress2); // Alamat
                $('#CA_POSCD').val(data.CorrespondenceAddressPostcode); // Poskod
                $('#CA_STATECD').val(data.CorrespondenceAddressStateCode).trigger('change'); // Negeri
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
//            $('#warganegara').hide();
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
            if (CA_CMPLCAT) {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('admin-case/getcmpllist') }}" + "/" + CA_CMPLCAT,
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                        $('select[name="CA_CMPLCD"]').empty();
                        $.each(data, function (key, value) {
                            // if (value === '0')
                            //     $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                            // else
                            //     $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                            if (value == '0'){
                                $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                                // $('select[name="CA_CMPLCD"]').trigger('change');
                            } else {
                                $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                                // $('select[name="CA_CMPLCD"]').trigger('change');
                            }
                        });
                    },
                    complete: function (data) {
                        $('select[name="CA_CMPLCD"]').trigger('change');
                    }
                });
                if(CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('admin-case/getcmplkeywordlist') }}" + '/' + CA_CMPLCAT,
                        dataType: 'json',
                        success: function (data) {
                            // console.log(data)
                            $('select[name="CA_CMPLKEYWORD"]').empty();
                            $('select[name="CA_CMPLKEYWORD"]').append('<option value="">-- SILA PILIH --</option>');
                            $.each(data, function (key, value) {
                                if (value == '0') {
                                    $('select[name="CA_CMPLKEYWORD"]').append('<option value="">' + key + '</option>');
                                    // $('select[name="CA_CMPLKEYWORD"]').trigger('change');
                                } else {
                                    $('select[name="CA_CMPLKEYWORD"]').append('<option value="' + value + '">' + key + '</option>');
                                    // $('select[name="CA_CMPLKEYWORD"]').trigger('change');
                                }
                            });
                        },
                        complete: function (data) {
                            $('select[name="CA_CMPLKEYWORD"]').trigger('change');
                        }
                    });
                    $( "#CA_CMPLKEYWORD" ).show();
                } else {
                    $('select[name="CA_CMPLKEYWORD"]').empty();
                    $('select[name="CA_CMPLKEYWORD"]').append('<option value="">-- SILA PILIH --</option>');
                    $('select[name="CA_CMPLKEYWORD"]').trigger('change');
                    $( "#CA_CMPLKEYWORD" ).hide();
                }
            } else {
                $( "#CA_CMPLKEYWORD" ).hide();
                $('select[name="CA_CMPLCD"]').empty();
                $('select[name="CA_CMPLCD"]').append('<option value="">-- SILA PILIH --</option>');
                $('select[name="CA_CMPLCD"]').trigger('change');
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
                } else {
                    $('#div_CA_AGAINSTADD').hide();
                    $('#div_CA_AGAINST_POSTCD').hide();
                    $('#div_CA_AGAINST_STATECD').hide();
                    $('#div_CA_AGAINST_DISTCD').hide();
                }
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
//                $( "label[for='CA_AGAINST_PREMISE']" ).addClass( "required" );
//                $( "label[for='CA_AGAINSTADD']" ).addClass( "required" );
//                $( "label[for='CA_AGAINST_POSTCD']" ).addClass( "required" );
                $('#div_SERVICE_PROVIDER_INFO').hide();
                $('#div_ONLINECMPL').hide();
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
//                    d.state_cd = $('#state_cd_user').val();
                    d.state_cd = '{{ Auth::User()->state_cd }}';
//                    d.brn_cd = $('#brn_cd').val();
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

//        $('#btn-reset').on('click', function (e) {
//            document.getElementById("user-search-form").reset();
//            oTable.draw();
//            e.preventDefault();
//        });
//
//        $('#user-search-form').on('submit', function (e) {
//            oTable.draw();
//            e.preventDefault();
//        });
        
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

    function selectCategoryOrPremise(category, premise) {
        switch (category) {
            case 'BPGK 19':
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
                $('#div_ONLINECMPL').show();
                if (document.getElementById('CA_ONLINEADD_IND').checked) {
                    $('#div_CA_AGAINSTADD').show();
                    $('#div_CA_AGAINST_POSTCD').show();
                    $('#div_CA_AGAINST_STATECD').show();
                    $('#div_CA_AGAINST_DISTCD').show();
                } else {
                    $('#div_CA_AGAINSTADD').hide();
                    $('#div_CA_AGAINST_POSTCD').hide();
                    $('#div_CA_AGAINST_STATECD').hide();
                    $('#div_CA_AGAINST_DISTCD').hide();
                }
                break;
            default:
                switch (premise) {
                    case 'P25':
                        onlinecmplind();
                        $( "#checkpernahadu" ).show();
                        $( "#checkinsertadd" ).show();
                        $('#div_CA_ONLINECMPL_PROVIDER').show();
                        $('#div_CA_ONLINECMPL_URL').show();
                        $('#div_CA_ONLINECMPL_AMOUNT').show();
                        $('#div_CA_ONLINECMPL_BANKCD').hide();
                        $('#div_CA_ONLINECMPL_PYMNTTYP').hide();
                        $('#div_CA_ONLINECMPL_ACCNO').hide();
                        $('#div_CA_AGAINST_PREMISE').show();
                        $('#div_CA_AGAINSTADD').hide();
                        $('#div_CA_AGAINST_POSTCD').hide();
                        $('#div_CA_AGAINST_STATECD').hide();
                        $('#div_CA_AGAINST_DISTCD').hide();
                        $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
                        $('#div_SERVICE_PROVIDER_INFO').show();
                        $('#div_ONLINECMPL').show();
                        onlineaddind();
                        break;
                    default:
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
                        $('#div_ONLINECMPL').hide();
                        break;
                }
                break;
        }
    }
</script>
@stop
