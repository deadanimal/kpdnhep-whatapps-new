@extends('layouts.main')
<?php

use App\Ref;
use App\Aduan\CallCenterCase;
use App\CallCenterCaseDoc;
use App\CallCenCaseDetail;
use Illuminate\Database\Query\Builder;
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
<h2>Kemaskini  Maklumat Call Center</h2>
<div class="row">
    <div class="tabs-container">
<!--        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#case-info">Maklumat Aduan</a></li>
                        <li><a data-toggle="tab" href="#case-attachment">Bukti Aduan</a></li>
            <li><a data-toggle="tab" href="#case-transaction">Sorotan Transaksi</a></li>
            <li class="active"><a>MAKLUMAT ADUAN</a></li>
            <li class=""><a>BAHAN BUKTI</a></li>
            <li class=""><a>SEMAKAN ADUAN</a></li>
        </ul>-->
        <div class="tab-content">
            <div id="case-info" class="tab-pane fade in active">
                <div class="panel-body">
                    {!! Form::open(['route' => ['call-center-case.update',$mCallCase->id], 'class'=>'form-horizontal']) !!}
                    {{ csrf_field() }}{{ method_field('PUT') }}
                    <h3>Cara Terima</h3>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_RCVDT') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVDT', 'Tarikh Aduan', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('CA_RCVDT', date('d-m-Y h:i A ', strtotime($mCallCase->CA_RCVDT)), ['class' => 'form-control input-sm','readonly' => true]) }}
                                    @if ($errors->has('CA_RCVDT'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_CASEID') ? ' has-error' : '' }}">
                                {{ Form::label('CA_CASEID','No. Aduan ', ['class' => 'col-md-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('CA_CASEID', $mCallCase->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    @if ($errors->has('CA_CASEID'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CASEID') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_RCVTYP', Ref::GetList('259', true),$mCallCase->CA_RCVTYP, ['class' => 'form-control input-sm','i$mCallCased' => 'CA_RCVTYP','disabled'=>'disabled']) }}
                                    @if ($errors->has('CA_RCVTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_RCVBY_NAME',$RcvBy, ['class' => 'form-control input-sm','readonly' => 'true']) }}
                                    {{ Form::hidden('CA_RCVBY',$mCallCase->CA_RCVBY, ['class' => 'form-control input-sm','readonly' => 'true']) }}
                                    @if ($errors->has('CA_RCVBY'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_RCVBY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>Maklumat Pengadu</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    <!--<div class="input-group">-->
                                    {{ Form::text('CA_DOCNO',$mCallCase->CA_DOCNO, ['class' => 'form-control input-sm', 'id' => 'DOCNO']) }}
                                    <!--<span class="input-group-btn" >-->
                                        <!--<button <?php // echo ($mCallCase->CA_DOCNO != '') ? 'disabled' : '' ?> class="ladda-button ladda-button-demo btn btn-primary btn-sm" type="button" data-style="expand-right" id="CheckJpn">Semak JPN</button>-->
                                    <!--</span>-->
                                    <!--</div>-->
                                    @if ($errors->has('CA_DOCNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_EMAIL', 'Email', ['class' => 'col-sm-5 control-label required1']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_EMAIL', old('CA_EMAIL', $mCallCase->CA_EMAIL),['class' => 'form-control input-sm', 'id' => 'CA_EMAIL']) }}
                                    @if ($errors->has('CA_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_MOBILENO', 'No.Telefon Bimbit', ['class' => 'col-sm-5 control-label required1']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_MOBILENO', old('CA_MOBILENO', $mCallCase->CA_MOBILENO), ['class' => 'form-control input-sm', 'id' => 'CA_MOBILENO', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12]) }}
                                    @if ($errors->has('CA_MOBILENO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_TELNO', 'No.Telefon', ['class' => 'col-sm-5 control-label required1']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_TELNO', old('CA_TELNO', $mCallCase->CA_TELNO), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_TELNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('CA_ADDR', old('CA_ADDR', $mCallCase->CA_ADDR), ['class' => 'form-control input-sm', 'rows'=>'4', 'id' => 'CA_ADDR']) }}
                                    {{ Form::hidden('CA_MYIDENTITY_ADDR', old('CA_MYIDENTITY_ADDR', $mCallCase->CA_MYIDENTITY_ADDR), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_ADDR']) }}
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
                                    {{ Form::text('CA_NAME', old('CA_NAME', $mCallCase->CA_NAME), ['class' => 'form-control input-sm required', 'id' => 'CA_NAME']) }}
                                    @if ($errors->has('CA_NAME'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{-- Form::select('CA_AGE', Ref::GetList('309', true), $mCallCase->CA_AGE, ['class' => 'form-control input-sm']) --}}
                                    {{ Form::text('CA_AGE',old('CA_AGE', $mCallCase->CA_AGE) , ['class' => 'form-control input-sm', 'id' => 'CA_AGE', 'onkeypress' => "return isNumberKey(event)"]) }}
                                    @if ($errors->has('CA_AGE'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('CA_SEXCD', Ref::GetList('202', true), old('CA_SEXCD', $mCallCase->CA_SEXCD), ['class' => 'form-control input-sm required', 'id' => 'CA_SEXCD']) }}
                                    @if ($errors->has('CA_SEXCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!--                            <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                                            {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                                            <div class="col-sm-9">
                                                                <div>
                                                                    <div class="radio"><label> <input type="radio" value="1" name="CA_NATCD" {{ $mCallCase->CA_NATCD == 1? 'checked':'' }} onclick="natcd(this.value)"> <i></i> Warganegara </label></div>
                                                                    <div class="radio"><label> <input type="radio" value="0" name="CA_NATCD" {{ $mCallCase->CA_NATCD == 0? 'checked':'' }} onclick="natcd(this.value)"> <i></i> Bukan Warganegara</label></div>
                                                                </div>
                                                                @if ($errors->has('CA_NATCD'))
                                                                <span class="help-block"><strong>{{ $errors->first('CA_NATCD') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>-->
                            <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    <div class="radio radio-success">
                                        <input id="CA_NATCD1" type="radio" name="CA_NATCD" value="1" onclick="check1(this.value)" {{ $mCallCase->CA_NATCD == '1' ? 'checked' : '' }} >
                                        <label for="CA_NATCD1"> Warganegara </label>
                                    </div>
                                    <div class="radio radio-success">
                                        <input id="CA_NATCD2" type="radio" name="CA_NATCD" value="0" onclick="check1(this.value)" {{ $mCallCase->CA_NATCD == '0' ? 'checked' : '' }} >
                                        <label for="CA_NATCD2"> Bukan Warganegara </label>
                                    </div>
                                    @if ($errors->has('CA_NATCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_NATCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!--<div id="warganegara" style="display: {--{ $mCallCase->CA_NATCD == '1' ? 'block' : 'none' --}}">-->
                            <div>
                                <div class="form-group {{ $errors->has('CA_POSCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_POSCD', old('CA_POSCD', $mCallCase->CA_POSCD), ['class' => 'form-control input-sm', 'id' => 'CA_POSCD', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                        {{ Form::hidden('CA_MYIDENTITY_POSCD', old('CA_MYIDENTITY_POSCD', $mCallCase->CA_MYIDENTITY_POSCD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_POSCD']) }}
                                        @if ($errors->has('CA_POSCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_STATECD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_STATECD', Ref::GetList('17', true), old('CA_STATECD', $mCallCase->CA_STATECD), ['class' => 'form-control input-sm required', 'id' => 'CA_STATECD']) }}
                                        {{ Form::hidden('CA_MYIDENTITY_STATECD', old('CA_MYIDENTITY_STATECD', $mCallCase->CA_MYIDENTITY_STATECD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_STATECD']) }}
                                        @if ($errors->has('CA_STATECD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_DISTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        @if(old('CA_STATECD'))
                                        {{ Form::select('CA_DISTCD', CallCenterCase::GetDstrtList(old('CA_STATECD')), old('CA_DISTCD', $mCallCase->CA_DISTCD ), ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) }}
                                        @else
                                        {{-- Form::select('CA_DISTCD', ['' => '-- SILA PILIH --'], '', ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) --}}
                                        {{ Form::select('CA_DISTCD', ($mCallCase->CA_STATECD == '' ? ['' => '-- SILA PILIH --'] : CallCenterCase::GetDstrtList($mCallCase->CA_STATECD)), '', ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) }}
                                        {{-- Form::select('CA_DISTCD', old('CA_STATECD') ? Ref::GetListDist(old('CA_STATECD')) : Ref::GetListDist($mCallCase->CA_STATECD, '18', true, 'ms'), $mCallCase->CA_DISTCD, ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) --}}
                                        @endif
                                        {{ Form::hidden('CA_MYIDENTITY_DISTCD', old('CA_MYIDENTITY_DISTCD', $mCallCase->CA_MYIDENTITY_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_DISTCD']) }}
                                        <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                        @if ($errors->has('CA_DISTCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="bknwarganegara" style="display: {{ $mCallCase->CA_NATCD == '0' ? 'block' : 'none' }}">
                                <div class="form-group {{ $errors->has('CA_COUNTRYCD') ? ' has-error' : 'CA_COUNTRYCD' }}">
                                    {{ Form::label('CA_COUNTRYCD', 'Negara', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('CA_COUNTRYCD', $mCallCase->CA_COUNTRYCD), ['class' => 'form-control input-sm']) }}
                                        {{ Form::hidden('CA_STATUSPENGADU', old('CA_STATUSPENGADU', $mCallCase->CA_STATUSPENGADU), ['class' => 'form-control input-sm', 'id' => 'CA_STATUSPENGADU']) }}
                                        @if ($errors->has('CA_COUNTRYCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_COUNTRYCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>Maklumat Aduan</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_CMPLCAT') ? ' has-error' : '' }}">
                                {{ Form::label('CA_CMPLCAT', 'Kategori', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms','descr'), old('CA_CMPLCAT', $mCallCase->CA_CMPLCAT), array('class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT')) }}
                                    @if ($errors->has('CA_CMPLCAT'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{-- Form::select('CA_CMPLCD', $mCallCase->CA_CMPLCAT == '' ? [''=>'-- SILA PILIH --'] : CallCenterCase::GetCmplCd($mCallCase->CA_CMPLCAT), old('CA_CMPLCD', $mCallCase->CA_CMPLCD), ['class' => 'form-control input-sm']) --}}
                                    @if(old('CA_CMPLCAT'))
                                    {{ Form::select('CA_CMPLCD',CallCenterCase::GetCmplCd((old('CA_CMPLCAT')? old('CA_CMPLCAT') : $mCallCase->CA_CMPLCAT)), (old('CA_CMPLCD')? old('CA_CMPLCD') : ($mCallCase->CA_CMPLCD? $mCallCase->CA_CMPLCD:'')),['class' => 'form-control  input-sm'])}}
                                    @else
                                    {{ Form::select('CA_CMPLCD', ($mCallCase->CA_CMPLCAT == '' ? ['' => '-- SILA PILIH --'] : CallCenterCase::GetCmplCd($mCallCase->CA_CMPLCAT)), old('CA_CMPLCD', $mCallCase->CA_CMPLCD), ['class' => 'form-control input-sm', 'id' => 'CA_CMPLCD']) }}
                                    @endif
                                    @if ($errors->has('CA_CMPLCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="CA_CMPLKEYWORD" style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($mCallCase->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none')))  }};">
                                {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLKEYWORD',Ref::GetList('1051',true, 'ms'), $mCallCase->CA_CMPLKEYWORD,['class' => 'form-control  input-sm'])}}
                                    @if ($errors->has('CA_CMPLKEYWORD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CMPLKEYWORD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_ONLINECMPL_PROVIDER', Ref::GetList('1091', true, 'ms', 'descr'), $mCallCase->CA_ONLINECMPL_PROVIDER, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PROVIDER']) }}
                                    @if ($errors->has('CA_ONLINECMPL_PROVIDER'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PROVIDER') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_URL" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_URL', 'Laman Web / URL', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ONLINECMPL_URL', old('CA_ONLINECMPL_URL', $mCallCase->CA_ONLINECMPL_URL), ['class' => 'form-control input-sm', 'placeholder' => '(Contoh: www.google.com)']) }}
                                    @if ($errors->has('CA_ONLINECMPL_URL'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_URL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!--<div id="div_CA_ONLINECMPL_AMOUNT" style="display: {{-- (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) --}};" class="form-group{{-- $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' --}}">-->
                            <div class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ONLINECMPL_AMOUNT', $mCallCase->CA_ONLINECMPL_AMOUNT, ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey1(event)"]) }}
                                    @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_PYMNTTYP" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PYMNTTYP') ? ' has-error' : '' }}">
                                {{-- <label for="CA_ONLINECMPL_PYMNTTYP" class="col-sm-4 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Cara Pembayaran</label> --}}
                                {{ Form::label('CA_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_ONLINECMPL_PYMNTTYP', Ref::GetList('1207', true, 'ms'), old('CA_ONLINECMPL_PYMNTTYP', $mCallCase->CA_ONLINECMPL_PYMNTTYP), ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PYMNTTYP']) }}
                                    @if ($errors->has('CA_ONLINECMPL_PYMNTTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PYMNTTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_BANKCD" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                                <!--<label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label {{-- old('CA_CMPLCAT') == 'BPGK 19'? '':'required' --}}">Nama Bank</label>-->
                                <label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label {{ old('CA_ONLINECMPL_PYMNTTYP') ? (in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '':'required') : (in_array($mCallCase->CA_ONLINECMPL_PYMNTTYP,['','COD','TB']) ? '' : 'required') }}">Nama Bank</label>
                                <div class="col-sm-8">
                                    {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, 'ms'), old('CA_ONLINECMPL_BANKCD', $mCallCase->CA_ONLINECMPL_BANKCD), ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_BANKCD']) }}
                                    @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_BANKCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_ACCNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                <!--{{-- Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun', ['class' => 'col-sm-4 control-label required']) --}}-->
                                <label for="CA_ONLINECMPL_ACCNO" class="col-sm-4 control-label {{ old('CA_ONLINECMPL_PYMNTTYP') ? (in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '':'required') : (in_array($mCallCase->CA_ONLINECMPL_PYMNTTYP,['','COD','TB']) ? '' : 'required') }}">No. Akaun Bank / No. Transaksi FPX</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ONLINECMPL_ACCNO', old('CA_ONLINECMPL_ACCNO', $mCallCase->CA_ONLINECMPL_ACCNO), ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                    @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_ACCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_PREMISE" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                <label for="CA_AGAINST_PREMISE" class="col-sm-4 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Jenis Premis</label>
                                <div class="col-sm-8">
                                    {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), $mCallCase->CA_AGAINST_PREMISE, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_PREMISE']) }}
                                    @if ($errors->has('CA_AGAINST_PREMISE'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_PREMISE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" id="checkpernahadu">
                                {{ Form::label('', '', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    <div class="checkbox checkbox-success">
                                        <input name="CA_ONLINECMPL_IND" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' || $mCallCase->CA_ONLINECMPL_IND == '1'? 'checked':'' }}>
                                        <label for="CA_ONLINECMPL_IND">
                                            Pernah membuat aduan secara rasmi kepada Pembekal Perkhidmatan?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_CASENO" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'? 'block':($mCallCase->CA_CMPLCAT == 'BPGK 19' && $mCallCase->CA_ONLINECMPL_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mCallCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINECMPL_IND') == '' && $mCallCase->CA_ONLINECMPL_IND == '1'? 'block':(old('CA_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ONLINECMPL_CASENO',$mCallCase->CA_ONLINECMPL_CASENO, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_ONLINECMPL_CASENO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_CASENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon (Pejabat)', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_TELNO', $mCallCase->CA_AGAINST_TELNO, ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                    @if ($errors->has('CA_AGAINST_TELNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_MOBILENO', $mCallCase->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                    @if ($errors->has('CA_AGAINST_MOBILENO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_AGAINSTNM') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINSTNM', 'Nama (Syarikat/Premis)', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINSTNM', $mCallCase->CA_AGAINSTNM, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_AGAINSTNM'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTNM') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            
                            <div id="checkinsertadd" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mCallCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group">
                                {{ Form::label('', null, ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <div class="checkbox checkbox-success">
                                        <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' || $mCallCase->CA_ONLINEADD_IND == '1'? 'checked':'' }}>
                                        <label for="CA_ONLINEADD_IND">
                                            Mempunyai alamat penuh penjual / pihak yang diadu?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="div_CA_AGAINSTADD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($mCallCase->CA_CMPLCAT == 'BPGK 19' && $mCallCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mCallCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mCallCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('CA_AGAINSTADD', $mCallCase->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows'=> '4']) }}
                                    @if ($errors->has('CA_AGAINSTADD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINSTADD')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_POSTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($mCallCase->CA_CMPLCAT == 'BPGK 19' && $mCallCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mCallCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mCallCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_POSTCD', 'Poskod', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINST_POSTCD', $mCallCase->CA_AGAINST_POSTCD, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_AGAINST_POSTCD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_POSTCD')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_STATECD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($mCallCase->CA_CMPLCAT == 'BPGK 19' && $mCallCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mCallCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mCallCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true), old('CA_AGAINST_STATECD', $mCallCase->CA_AGAINST_STATECD), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_STATECD']) }}
                                    @if ($errors->has('CA_AGAINST_STATECD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_STATECD')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_DISTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($mCallCase->CA_CMPLCAT == 'BPGK 19' && $mCallCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mCallCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mCallCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    @if(old('CA_AGAINST_STATECD'))
                                    {{ Form::select('CA_AGAINST_DISTCD', CallCenterCase::GetDstrtList(old('CA_AGAINST_STATECD')), old('CA_AGAINST_DISTCD', $mCallCase->CA_AGAINST_DISTCD ), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) }}
                                    @else
                                    {{ Form::select('CA_AGAINST_DISTCD', ($mCallCase->CA_AGAINST_STATECD == '' ? ['' => '-- SILA PILIH --'] : CallCenterCase::GetDstrtList($mCallCase->CA_AGAINST_STATECD)), old('CA_AGAINST_DISTCD', $mCallCase->CA_AGAINST_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) }}
                                    {{-- Form::select('CA_AGAINST_DISTCD', ['' => '-- SILA PILIH --'], '', ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) --}}
                                    @endif
                                    {{-- Form::select('CA_AGAINST_DISTCD', ($mCallCase->CA_AGAINST_STATECD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($model->CA_AGAINST_STATECD, '18', true, 'ms')), $mCallCase->CA_AGAINST_DISTCD, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) --}}
                                    <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                    @if ($errors->has('CA_AGAINST_DISTCD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_DISTCD')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_EMAIL', 'Email', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINST_EMAIL', $mCallCase->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_AGAINST_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINST_FAXNO', $mCallCase->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_AGAINST_FAXNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
<!--                            <div class="form-group" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" id="checkpernahadu">
                                {{ Form::label(old('CA_ONLINECMPL_IND'), null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    <div class="checkbox checkbox-success">
                                        <input name="CA_ONLINECMPL_IND" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' }}>
                                               <label for="CA_ONLINECMPL_IND">
                                            Pernah membuat aduan secara rasmi kepada Pembekal Perkhidmatan?
                                        </label>
                                    </div>
                                </div>
                            </div>-->
<!--                            <div class="form-group {{ $errors->has('CA_ROUTETOHQIND') ? ' has-error' : '' }}">
                                {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <div class="checkbox checkbox-primary">
                                        <input id="CA_ROUTETOHQIND" type="checkbox" name="CA_ROUTETOHQIND" {{ $mCallCase->CA_ROUTETOHQIND == '1'? 'checked':'' }}>
                                        <label for="CA_ROUTETOHQIND">
                                            Hantar aduan ke Ibu Pejabat Penguatkuasa 
                                        </label>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SUMMARY', 'Aduan', ['class' => 'col-sm-2 control-label required']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_SUMMARY', old('CA_SUMMARY', $mCallCase->CA_SUMMARY), ['class' => 'form-control input-sm','rows'=>'4']) }}
                                    <span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>
                                    @if ($errors->has('CA_SUMMARY'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="case-attachment">
                        <!--<div class="panel-body">-->
<!--                            <div class="row">
                                <div class="col-md-12">
                                    @if($CountDoc < 5)
                                    <a onclick="ModalCreateAttachment()" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Bukti Aduan</a>
                                    @else
                                    <a class="btn btn-success btn-sm disabled"><i class="fa fa-plus"></i> Bukti Aduan</a>
                                    @endif
                                </div>
                            </div>-->
<!--                            <div class="table-responsive">
                                <table id="attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Fail</th>
                                            <th>Fail</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>-->
                        <!--</div>-->
                    </div>
                    <div class="form-group col-sm-12" align="center">
                        {{-- Form::submit('Hantar', ['class' => 'btn btn-success btn-sm']) --}}
                        @if($mCallCase->CA_INVSTS == '10')
                        {{-- Form::submit('Hantar', array('class' => 'btn btn-success btn-sm')) --}}
                        @endif
                        {{ link_to_route('call-center-case.index', 'Kembali', [], ['class' => 'btn btn-default btn-sm']) }}
                        
                        {{ Form::button(trans('button.continue').' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm'] )  }}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            <div id="case-transaction" class="tab-pane fade">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tarikh Transaksi</th>
                                    <th>Status</th>
                                    <th>Daripada</th>
                                    <th>Kepada</th>
                                    <th>Saranan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Attachment Start -->
@include('aduan.call-center-case.AttachmentCreate')
<!-- Modal Create Attachment End -->

<!-- Modal Edit Attachment Start -->
<div class="modal fade" id="modal-edit-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalEditContent'></div>
    </div>
</div>
<!-- Modal Edit Attachment End -->

@stop

@section('script_datatable')
<script type="text/javascript">
//    $('#CheckJpn').on('click', function(e) {
    $('#DOCNO').blur(function(){
//        alert('sdfghjkdsdg');
        var DOCNO = $('#DOCNO').val();
        var l = $( '.ladda-button-demo' ).ladda();
        $.ajax({
            type:'GET',
//            url:"{{ url('call-center-case/tojpn') }}" + "/" + DOCNO,
            url: "{{ url('admin-case/tojpn') }}" + "/" + DOCNO,
            dataType: "json",
            beforeSend: function () {
                l.ladda( 'start' );
            },
            success:function(data){
//                console.log(data);
//                if(data.error){
//                    alert(data.error);
//                }
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
                if(data.Warganegara != ''){
//                    if(data.Warganegara === 'malaysia') { // Warganegara
                    if (data.Warganegara === '1') { // Warganegara
                        document.getElementById('CA_NATCD1').checked = true;
                        $('#warganegara').show();
                        $('#bknwarganegara').hide();
                    }else{
                        document.getElementById('CA_NATCD2').checked = true;
                        $('#warganegara').hide();
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
                l.ladda( 'stop' );
            },
            error: function (data) {
                console.log(data);
                if(data.status == '500'){
                    alert(data.statusText);
                };
                l.ladda( 'stop' );
            },
            complete: function (data) {
                console.log(data);
                l.ladda( 'stop' );
            }
        });
    });
    
    function getDistListFromJpn(StateCd, DistCd) {
        if(StateCd != '' && DistCd != '') {
            $.ajax({
                type: 'GET',
                url: "{{ url('call-center-case/getdistlist') }}" + "/" + StateCd,
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
    
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href=' + hash + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    function ModalCreateAttachment() {
        $('#modal-create-attachment').modal("show");
    }
    ;
    function onlinecmplind() {
        if (document.getElementById('CA_ONLINECMPL_IND').checked) {
            $('#div_CA_ONLINECMPL_CASENO').show();
        } else {
            $('#div_CA_ONLINECMPL_CASENO').hide();
        }
    }
    ;

    function onlineaddind() {
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
    }
    ;
    function ModalEditAttachment(DOCATTCHID) {
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('call-center-case.AttachmentEdit','') }}" + "/" + DOCATTCHID);
        return false;
    }
    function check1(value) {
        if (value === '1') {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        } else {
            $('#warganegara').hide();
            $('#bknwarganegara').show();
        }
    }

    $(function () {
        $('#resident1').on('click', function (e) {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        });
        $('#resident2').on('click', function (e) {
            $('#warganegara').hide();
            $('#bknwarganegara').show();
        });
    });

    $(document).ready(function () {
    $('#btnsubmitcreate').click(function (e) {
    e.preventDefault();
            var $form = $('#form-create-attachment');
            var formData = {};
            $form.find(':input').each(function () {
    formData[ $(this).attr('name') ] = $(this).val();
    });
            $.ajax({
            type: 'POST',
                    url: "{{ url('call-center-case/AjaxValidateCreateAttachment') }}",
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
            $('#rcvdt .input-daterange').datepicker({
    format: 'dd-mm-yyyy',
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
    });
//        $('#CA_CMPLCAT').on('change', function (e) {
//            var CA_CMPLCAT = $(this).val();
//            if(CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
//                $( "#CA_CMPLKEYWORD" ).show();
//            }else{
//                $( "#CA_CMPLKEYWORD" ).hide();
//            }
//            if(CA_CMPLCAT === 'BPGK 19') {
//                if (document.getElementById('CA_ONLINECMPL_IND').checked)
//                    $('#div_CA_ONLINECMPL_CASENO').show();
//                else
//                    $('#div_CA_ONLINECMPL_CASENO').hide();
//                $( "#checkpernahadu" ).show();
//                $( "#checkinsertadd" ).show();
//                $('#div_CA_ONLINECMPL_PROVIDER').show();
//                $('#div_CA_ONLINECMPL_URL').show();
//                $('#div_CA_ONLINECMPL_AMOUNT').show();
//                $('#div_CA_ONLINECMPL_BANKCD').show();
//                $('#div_CA_ONLINECMPL_PYMNTTYP').show();
//                $('#div_CA_ONLINECMPL_ACCNO').show();
//                $('#div_CA_AGAINST_PREMISE').hide();
//                $('#div_CA_AGAINSTADD').hide();
//                $('#div_CA_AGAINST_POSTCD').hide();
//                $('#div_CA_AGAINST_STATECD').hide();
//                $('#div_CA_AGAINST_DISTCD').hide();
//                $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
//            }else{
//                $( "#checkpernahadu" ).hide();
//                $( "#checkinsertadd" ).hide();
//                $('#div_CA_ONLINECMPL_CASENO').hide();
//                $('#div_CA_ONLINECMPL_PROVIDER').hide();
//                $('#div_CA_ONLINECMPL_URL').hide();
//                $('#div_CA_ONLINECMPL_AMOUNT').hide();
//                $('#div_CA_ONLINECMPL_BANKCD').hide();
//                $('#div_CA_ONLINECMPL_PYMNTTYP').hide();
//                $('#div_CA_ONLINECMPL_ACCNO').hide();
//                $('#div_CA_AGAINST_PREMISE').show();
//                $('#div_CA_AGAINSTADD').show();
//                $('#div_CA_AGAINST_POSTCD').show();
//                $('#div_CA_AGAINST_STATECD').show();
//                $('#div_CA_AGAINST_DISTCD').show();
//            }
//          $('#CA_CMPLCAT').on('change', function (e) {
//            var CA_CMPLCAT = $(this).val();
//            $.ajax({
//                type:'GET',
//                url:"{{ url('call-center-case/getCmplCdList') }}" + "/" + CA_CMPLCAT,
//                dataType: "json",
//                success:function(data){
//                    $('select[name="CA_CMPLCD"]').empty();
//                    $.each(data, function(key, value) {
//                        $('select[name="CA_CMPLCD"]').append('<option value="'+ value +'">'+ key +'</option>');
//                    });
//                }
//            });
//        });
//        $('#CA_ONLINECMPL_PROVIDER').on('change', function (e) {
//            var CA_ONLINECMPL_PROVIDER = $(this).val();
//            if(CA_ONLINECMPL_PROVIDER === '999')
//                $( "label[for='CA_ONLINECMPL_URL']" ).addClass( "required" );
//            else
//                $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
//        });
//    });
//
//    $('#CA_STATECD').on('change', function (e) {
//    var CA_STATECD = $(this).val();
//            $.ajax({
//            type: 'GET',
//                    url: "{{ url('call-center-case/getdistlist') }}" + "/" + CA_STATECD,
//                    dataType: "json",
//                    success: function (data) {
//                    $('select[name="CA_DISTCD"]').empty();
//                            $.each(data, function (key, value) {
//                            if (value == '0')
//                                    $('select[name="CA_DISTCD"]').append('<option value="">' + key + '</option>');
//                                    else
//                                    $('select[name="CA_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
//                            });
//                    }
//            });
//    });
//            $('#CA_AGAINST_STATECD').on('change', function (e) {
//    var CA_AGAINST_STATECD = $(this).val();
//            $.ajax({
//            type: 'GET',
//                    url: "{{ url('call-center-case/getdistlist') }}" + "/" + CA_AGAINST_STATECD,
//                    dataType: "json",
//                    success: function (data) {
//                    $('select[name="CA_AGAINST_DISTCD"]').empty();
//                            $.each(data, function (key, value) {
//                            if (value == '0')
//                                    $('select[name="CA_AGAINST_DISTCD"]').append('<option value="">' + key + '</option>');
//                                    else
//                                    $('select[name="CA_AGAINST_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
//                            });
//                    }
//            });
//    });
            $('#attachment-table').DataTable({
    processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bPaginate: false,
            bInfo: false,
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
            url: "{{ url('call-center-case/GetDataTableAttachment', $mCallCase->CA_CASEID) }}"
            },
            columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'},
            {data: 'action', name: 'action', searchable: false, orderable: false, 'width': '5%'}
            ]
    });
            $('#transaction-table').DataTable({
    processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bPaginate: false,
            bInfo: false,
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
            url: "{{ url('call-center-case/GetDataTableTransaction', $mCallCase->CA_CASEID) }}"
            },
            columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CD_CREDT', name: 'CD_CREDT'},
            {data: 'CD_INVSTS', name: 'CD_INVSTS'},
            {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
            {data: 'CD_ACTTO', name: 'CD_ACTTO'},
            {data: 'CD_DESC', name: 'CD_DESC'}
            ]
    });
    });
    function isNumberKey(event)
        {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        } 
    function isNumberKey1(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46)
            return false;
        return true;
    }
    
    $(document).ready(function(){
        $('#CA_ONLINECMPL_AMOUNT').blur(function(){
            $(this).val(amountchange($(this).val()));
        });
            function amountchange(amount) {
                var delimiter = ","; // replace comma if desired
                var a = amount.split('.',2);
                var d = a[1];
                if(d){
                    if(d.length === 1){
    //                    alert('1');
                        d = d + '0';
                    }else if(d.length === 2){
    //                    alert('2');
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
    //            $(this).val(amount);
            }
        $("select").select2();
    });
    
    $(function () {
        
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
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_ONLINECMPL_PYMNTTYP').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
//                $( "label[for='CA_AGAINST_PREMISE']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINSTADD']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINST_POSTCD']" ).removeClass( "required" );
            }else{
                $( "#checkpernahadu" ).hide();
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
            }
            
            $.ajax({
                type: 'GET',
                url: "{{ url('call-center-case/getCmplCdList') }}" + "/" + CA_CMPLCAT,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('select[name="CA_CMPLCD"]').empty();
                    $.each(data, function (key, value) {
                        if (value == '0')
                            $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                        else
                            $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                            $('select[name="CA_CMPLCD"]').trigger('change');
                    });
//                $.each(data, function(key, value) {
//                    $('select[name="CA_AGAINST_DISTCD"]').append('<option value="'+ key +'">'+ value +'</option>');
//                });
                }
            });
        });

//        $('#CA_ONLINECMPL_IND').on('click', function() {
//            var Ind = $(this).val();
//            alert(Ind)
//        });

        $('#CA_AGAINST_STATECD').on('change', function (e) {
            var CA_AGAINST_STATECD = $(this).val();
            $.ajax({
                type:'GET',
                url: "{{ url('call-center-case/getdistlist') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success:function(data){
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="CA_AGAINST_DISTCD"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });
                },
                complete: function (data) {
                    $('#CA_AGAINST_DISTCD').trigger('change');
                }
            });
        });
        $('#CA_STATECD').on('change', function (e) {
            var CA_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('call-center-case/getdistlist') }}" + "/" + CA_STATECD,
                dataType: "json",
                success:function(data){
                    $('select[name="CA_DISTCD"]').empty();
                    $.each(data, function(key, value) {
                        if(value == '0')
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
    });
</script>
@stop
