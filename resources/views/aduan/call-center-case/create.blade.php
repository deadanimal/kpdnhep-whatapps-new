@extends('layouts.main')
<?php

use App\Ref;
use App\User;
use App\Aduan\CallCenterCase;
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
<h2>Aduan Baru Call Center</h2>
<div class="ibox-content">
    {!! Form::open(['route' => 'call-center-case.store', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
    {{ csrf_field() }}
    <h3>Cara Terima</h3>
    <div class="hr-line-solid"></div>
    <div class="row">

        <div class="col-sm-6">
            <div class="form-group{{ $errors->has('CA_RCVDT') ? ' has-error' : '' }}">
                {{ Form::label('CA_RCVDT', 'Tarikh Aduan', ['class' => 'col-sm-4 control-label required']) }}
                <div class="col-sm-8">
                    {{-- date('d-m-Y') --}}
                    {{ Form::datetime('CA_RCVDT',date('d-m-Y  h:i A '),['class' => 'form-control input-sm','readonly' => true]) }}
                    @if ($errors->has('CA_RCVDT'))
                    <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="form-group" id="tidak" style="display:block">
                {{ Form::label('CA_CASEID','No. Aduan', ['class' => 'col-md-4 control-label required']) }}
                <div class="col-sm-5">
                    {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm', 'id' => 'CA_CASEID','readonly' => true]) }}
                </div>
                <div class="col-sm-3">
                    <a class="btn btn-success btn-sm" id = "GenNoAduan" data-confirm="Jana No. Aduan Baru?" href="{{ url('call-center-case/GenNoAduan') }}">Jana No. Aduan</a>
                    {{-- link_to('call-center-case/GenNoAduan', 'Jana No. Aduan', ['class' => 'btn btn-success btn-sm', 'data-confirm' => 'GenNoAduan?']) --}}
                </div>
            </div>
            <!--            <div class="form-group">
                            {{ Form::label('', 'No. Aduan (BPA)', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-6">
                                {{ Form::text('', '', ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                @if ($errors->has(''))
                                <span class="help-block"><strong>{{ $errors->first('') }}</strong></span>
                                @endif
                            </div>
                            {{ Form::label('', 'Jika Ada', ['class' => 'col-sm-2 control-label'])}}
                        </div>-->
        </div>

        <div class="col-sm-6">
            <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                {{ Form::label('CA_RCVDT', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label required']) }}
                <div class="col-sm-8">
                    {{ Form::select('CA_RCVTYP', Ref::GetList('259', true), 'S28', ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                    @if ($errors->has('CA_RCVTYP'))
                    <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label required']) }}
                <div class="col-sm-8">
                    {{ Form::text('', Auth::user()->name, ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                    {{ Form::hidden('CA_RCVBY', Auth::user()->id, ['class' => 'form-control input-sm', 'id' => 'RcvById']) }}
                    @if ($errors->has('CA_RCVBY'))
                    <span class="help-block"><strong>{{ $errors->first('CA_RCVBY') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6 bg-info p-xs b-r-xs">
            {{ Form::label('CA_ASGBY', 'Masukkan maklumat aduan?', ['class' => 'col-sm-7 control-label']) }}
            <div class="radio radio-info inline"><input type="radio" value="1" name="CA_ASGBY"  onclick="check(this.value)" <?php echo (old('CA_ASGBY') ? (old('CA_ASGBY') == '1') ? 'checked' : ''  : '')?>> <label>Ya</label></div>
            <div class="radio radio-info inline"><input type="radio" value="2" name="CA_ASGBY"  onclick="check(this.value)" <?php echo (old('CA_ASGBY') ? (old('CA_ASGBY') == '0') ? 'checked' : ''  : 'checked')?>> <label> Tidak</label></div>
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    <div id="ya" style="display:{{ (old('CA_ASGBY') ? (old('CA_ASGBY') == '1') ? 'block' : 'none'  : 'none') }}">
        <div class="tabs-container">
<!--            <ul class="nav nav-tabs">
                <li class="active"><a>MAKLUMAT ADUAN</a></li>
                <li class=""><a>BAHAN BUKTI</a></li>
                <li class=""><a>SEMAKAN ADUAN</a></li>
            </ul>-->
            <br>
                <h3> Borang Aduan Web - Maklumat Pengadu</h3>
                <div class="hr-line-solid"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('CA_DOCNO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                <!--<div class="input-group">-->
                                    {{ Form::text('CA_DOCNO', '', ['class' => 'form-control input-sm', 'id' => 'DOCNO', 'maxlength' => 12 ]) }}
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
                            {{ Form::label('CA_EMAIL', 'Email', ['class' => 'col-sm-5 control-label required1']) }}
                            <div class="col-sm-7">
                                {{ Form::text('CA_EMAIL', '',['class' => 'form-control input-sm', 'id' => 'CA_EMAIL']) }}
                                @if ($errors->has('CA_EMAIL'))
                                <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_MOBILENO', 'No.Telefon Bimbit', ['class' => 'col-sm-5 control-label required1']) }}
                            <div class="col-sm-7">
                                {{ Form::text('CA_MOBILENO','', ['class' => 'form-control input-sm', 'id' => 'CA_MOBILENO', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12]) }}
                                @if ($errors->has('CA_MOBILENO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_TELNO', 'No.Telefon', ['class' => 'col-sm-5 control-label required1']) }}
                            <div class="col-sm-7">
                                {{ Form::text('CA_TELNO','', ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_TELNO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                            {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::textarea('CA_ADDR','', ['class' => 'form-control input-sm','rows'=>'4', 'id' => 'CA_ADDR']) }}
                                {{ Form::hidden('CA_MYIDENTITY_ADDR', '', ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_ADDR']) }}
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
                                {{ Form::text('CA_NAME','', ['class' => 'form-control input-sm required', 'id' => 'CA_NAME']) }}
                                @if ($errors->has('CA_NAME'))
                                <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{-- Form::select('CA_AGE', Ref::GetList('309', true), '', ['class' => 'form-control input-sm']) --}}
                                {{ Form::text('CA_AGE', '', ['class' => 'form-control input-sm', 'id' => 'CA_AGE', 'onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_AGE'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::select('CA_SEXCD', Ref::GetList('202', true), '', ['class' => 'form-control input-sm required', 'id' => 'CA_SEXCD']) }}
                                @if ($errors->has('CA_SEXCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <!--                <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                            {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                            <div class="col-sm-9">
                                                <div>
                                                    <div class="radio"><label> <input type="radio" value="1" name="CA_NATCD" onclick="natcd(this.value)" checked=""> <i></i> Warganegara </label></div>
                                                    <div class="radio"><label> <input type="radio" value="0" name="CA_NATCD" onclick="natcd(this.value)"> <i></i> Bukan Warganegara</label></div>
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
                                    <input id="CA_NATCD1" type="radio" name="CA_NATCD" value="1" onclick="check1(this.value)" {{ (old('CA_NATCD') != ''? (old('CA_NATCD') == '1'? 'checked':''):'checked') }} >
                                    <label for="CA_NATCD1"> Warganegara </label>
                                </div>
                                <div class="radio radio-success">
                                    <input id="CA_NATCD2" type="radio" name="CA_NATCD" value="0" onclick="check1(this.value)" {{ (old('CA_NATCD') != ''? (old('CA_NATCD') == '0'? 'checked':''):'') }} >
                                    <label for="CA_NATCD2"> Bukan Warganegara </label>
                                </div>
                                <!--<div class="radio"><label><input type="radio" value="oth" name="CA_NATCD" id="national2" {{ old('CA_NATCD') == 'oth' ? 'checked' : '' }}><i></i>Bukan Warganegara</label></div>-->
                                @if ($errors->has('CA_NATCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_NATCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <!--<div id="warganegara" style="display:block">-->
                        <!--<div id="warganegara" style="display: {{-- (old('CA_NATCD') != ''? (old('CA_NATCD') == '1'? 'block':'none'):'block') --}}">-->
                            <div class="form-group {{ $errors->has('CA_POSCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('CA_POSCD', '', ['class' => 'form-control input-sm', 'id' => 'CA_POSCD', 'maxlength' => '5', 'onkeypress' => "return isNumberKey(event)"]) }}
                                    {{ Form::hidden('CA_MYIDENTITY_POSCD', '', ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_POSCD']) }}
                                    @if ($errors->has('CA_POSCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('CA_STATECD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('CA_STATECD', Ref::GetList('17', true), '', ['class' => 'form-control input-sm required', 'id' => 'CA_STATECD']) }}
                                    {{ Form::hidden('CA_MYIDENTITY_STATECD', '', ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_STATECD']) }}
                                    @if ($errors->has('CA_STATECD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('CA_DISTCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    <!--{{-- Form::select('CA_DISTCD', [''=>'-- SILA PILIH --'], '', ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) --}}-->
                                    @if (old('CA_STATECD'))
                                        {{ Form::select('CA_DISTCD', Ref::GetListDist(old('CA_STATECD')), null, ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) }}
                                    @else
                                        {{ Form::select('CA_DISTCD', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) }}
                                    @endif
                                    {{ Form::hidden('CA_MYIDENTITY_DISTCD', '', ['class' => 'form-control input-sm', 'id' => 'CA_MYIDENTITY_DISTCD']) }}
                                    @if ($errors->has('CA_DISTCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        <div id="bknwarganegara" style="display: {{ (old('CA_NATCD') != ''? (old('CA_NATCD') == '0'? 'block':'none'):'none') }}">
                            <div class="form-group {{ $errors->has('CA_COUNTRYCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_COUNTRYCD', 'Negara', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), null, ['class' => 'form-control input-sm']) }}
                                    {{ Form::hidden('CA_STATUSPENGADU', '', ['class' => 'form-control input-sm', 'id' => 'CA_STATUSPENGADU']) }}
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
                                        {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms', 'descr'), null, ['class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT']) }}
                                        @if ($errors->has('CA_CMPLCAT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        @if(old('CA_CMPLCAT'))
                                        {{ Form::select('CA_CMPLCD', CallCenterCase::GetCmplCd((old('CA_CMPLCAT'))), old('CA_CMPLCD') ? old('CA_CMPLCD') : '', ['class' => 'form-control input-sm'])}}
                                        @else
                                        {{ Form::select('CA_CMPLCD', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm']) }}
                                        @endif
                                        @if ($errors->has('CA_CMPLCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="CA_CMPLKEYWORD" style="display: {{ in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none' }};">
                                    {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_CMPLKEYWORD', Ref::GetList('1051', true, 'ms'), old('CA_CMPLKEYWORD'), ['class' => 'form-control input-sm'])}}
                                        @if ($errors->has('CA_CMPLKEYWORD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_CMPLKEYWORD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_ONLINECMPL_PROVIDER', Ref::GetList('1091', true, 'ms', 'descr'), null, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PROVIDER']) }}
                                        @if ($errors->has('CA_ONLINECMPL_PROVIDER'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PROVIDER') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_URL" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_URL', 'Laman Web / URL', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_URL', '' , ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_ONLINECMPL_URL'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_URL') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
<!--                                <div id="div_CA_ONLINECMPL_AMOUNT" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">-->
                                <div id="div_CA_ONLINECMPL_AMOUNT" class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_AMOUNT', '0.00', ['class' => 'form-control input-sm','placeholder'=>'0.00', 'id'=>'CA_ONLINECMPL_AMOUNT', 'onkeypress' => "return isNumberKey1(event)"]) }}
                                        @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_PYMNTTYP" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PYMNTTYP') ? ' has-error' : '' }}">
                                    <!--<label for="CA_ONLINECMPL_PYMNTTYP" class="col-sm-4 control-label {{-- old('CA_CMPLCAT') == 'BPGK 19'? '':'required' --}}">Cara Pembayaran</label>-->
                                    {{ Form::label('CA_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_ONLINECMPL_PYMNTTYP', Ref::GetList('1207', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PYMNTTYP']) }}
                                        @if ($errors->has('CA_ONLINECMPL_PYMNTTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PYMNTTYP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_BANKCD" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                                    <!--<label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label {{-- old('CA_CMPLCAT') == 'BPGK 19'? '':'required' --}}">Nama Bank</label>-->
                                    <label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label {{ in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '' : 'required' }}">Nama Bank</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_BANKCD']) }}
                                        @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_BANKCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_ACCNO" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                    <!--{{-- Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun', ['class' => 'col-sm-4 control-label required']) --}}-->
                                    <label for="CA_ONLINECMPL_ACCNO" class="col-sm-4 control-label {{ in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '' : 'required' }}">No. Akaun Bank / No. Transaksi FPX</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_ACCNO', '', ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)"]) }}
                                        @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_ACCNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_AGAINST_PREMISE" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block' }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                    <label for="CA_AGAINST_PREMISE" class="col-sm-4 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Jenis Premis</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_PREMISE']) }}
                                        @if ($errors->has('CA_AGAINST_PREMISE'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_PREMISE') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" id="checkpernahadu">
                                    {{ Form::label('', '', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <div class="checkbox checkbox-success">
                                            <input name="CA_ONLINECMPL_IND" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' }}>
                                                   <label for="CA_ONLINECMPL_IND">
                                                Pernah membuat aduan secara rasmi kepada Pembekal Perkhidmatan?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_CA_ONLINECMPL_CASENO" style="display: {{ old('CA_ONLINECMPL_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_CASENO', '', ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_ONLINECMPL_CASENO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_CASENO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon (Pejabat)', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_AGAINST_TELNO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                        @if ($errors->has('CA_AGAINST_TELNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                        <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_AGAINST_MOBILENO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_AGAINST_MOBILENO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_EMAIL', 'Email', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_AGAINST_EMAIL', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('CA_AGAINST_EMAIL'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_AGAINST_FAXNO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_AGAINST_FAXNO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_FAXNO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('CA_AGAINSTNM') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINSTNM', 'Nama (Syarikat/Premis)', ['class' => 'col-sm-3 control-label required']) }}
                            <div class="col-sm-9">
                                {{ Form::text('CA_AGAINSTNM', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('CA_AGAINSTNM'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTNM') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="checkinsertadd" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group">
                            {{ Form::label(old('CA_ONLINEADD_IND'), null, ['class' => 'col-md-3 control-label']) }}
                            <div class="col-sm-7">
                                <div class="checkbox checkbox-success">
                                    <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' }}>
                                           <label for="CA_ONLINEADD_IND">
                                        Mempunyai alamat penuh penjual / pihak yang diadu?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="div_CA_AGAINSTADD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                            <label for="CA_AGAINSTADD" class="col-sm-3 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Alamat</label>
                            <div class="col-sm-9">
                                {{ Form::textarea('CA_AGAINSTADD', '', ['class' => 'form-control input-sm', 'rows' => '4']) }}
                                @if ($errors->has('CA_AGAINSTADD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTADD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="div_CA_AGAINST_POSTCD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                            <!--<label for="CA_AGAINST_POSTCD" class="col-sm-3 control-label {{-- old('CA_CMPLCAT') == 'BPGK 19'? '':'required' --}}">Poskod</label>-->
                            <label for="CA_AGAINST_POSTCD" class="col-sm-3 control-label ">Poskod</label>
                            <div class="col-sm-9">
                                {{ Form::text('CA_AGAINST_POSTCD', '', ['class' => 'form-control input-sm', 'maxlength' => '5', 'onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_AGAINST_POSTCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_POSTCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="div_CA_AGAINST_STATECD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label required']) }}
                            <div class="col-sm-9">
                                {{ Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true), '', ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_STATECD']) }}
                                @if ($errors->has('CA_AGAINST_STATECD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_STATECD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="div_CA_AGAINST_DISTCD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label required']) }}
                            <div class="col-sm-9">
                                @if (old('CA_AGAINST_STATECD'))
                                {{ Form::select('CA_AGAINST_DISTCD', old('CA_AGAINST_STATECD') ? Ref::GetListDist(old('CA_AGAINST_STATECD')) : Ref::GetListDist(old('CA_AGAINST_STATECD')), null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) }}
                                @else
                                {{ Form::select('CA_AGAINST_DISTCD', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) }}
                                @endif
                                @if ($errors->has('CA_AGAINST_DISTCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_DISTCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
<!--                        <div class="form-group {{ $errors->has('CA_ROUTETOHQIND') ? ' has-error' : '' }}">
                            {{ Form::label('', '', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                <div class="checkbox checkbox-primary">
                                    <input id="CA_ROUTETOHQIND" type="checkbox" name="CA_ROUTETOHQIND">
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
                                {{ Form::textarea('CA_SUMMARY', '', ['class' => 'form-control input-sm','rows'=>'4']) }}
                                <span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>
                                @if ($errors->has('CA_SUMMARY'))
                                <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        <div class="row">
            <div class="form-group col-sm-12" align="center">
<!--                {{-- Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']) --}}
                <a href="{{-- url('call-center-case')--}}" type="button" class="btn btn-default btn-sm">Kembali</a>-->
                
                <!--<input style="visibility:visible" type="button" id="btncontinue" value="{{ trans('button.continue') }}" onClick="btncontinueclick();" class="btn btn-primary btn-sm" />-->
                <!--<input style="visibility:hidden" type="submit" id="btnupdate" value="{{ trans('button.send') }}" class="btn btn-primary btn-sm" />-->
                <!--<input style="visibility:hidden" type="button" id="btnsave" value="{{ trans('button.update') }}" onClick="btnupdateclick();" class="btn btn-primary btn-sm" />-->
                            <a class="btn btn-default btn-sm" href="{{ url('call-center-case') }}">Kembali</a>
                {{ Form::button('Simpan & Seterusnya'.' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm']) }}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    </div>
</div>
<!--<div style="display: block" class="form-group" align="center" id="btnback">
    {{-- link_to('call-center-case', 'Kembali', ['class' => 'btn btn-default btn-sm']) --}}
</div>-->


@stop

@section('script_datatable')
<script>
    $('#GenNoAduan').on('click', function (e) {
        return !!confirm($(this).data('confirm'));
    });
//    $('#CheckJpn').on('click', function(e) {
    $('#DOCNO').blur(function(){
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
//                url: "{{ url('call-center-case/getdistlist') }}" + "/" + StateCd,
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
    
        function btncontinueclick() {
        document.getElementById('btnsave').style.visibility = 'visible';
        document.getElementById('btnupdate').style.visibility = 'visible';
        document.getElementById('btncontinue').style.visibility = 'hidden';
        $('.form-control').attr("readonly", true);
    }
    function btnupdateclick() {
        document.getElementById('btnsave').style.visibility = 'hidden';
        document.getElementById('btnupdate').style.visibility = 'hidden';
        document.getElementById('btncontinue').style.visibility = 'visible';
        $('.form-control').attr("readonly", false);
    }
    function onlinecmplind() {
        if (document.getElementById('CA_ONLINECMPL_IND').checked)
            $('#div_CA_ONLINECMPL_CASENO').show();
        else
            $('#div_CA_ONLINECMPL_CASENO').hide();
    }
    ;
    function check(value) {
        if (value == '1') {
            $('#ya').show();
            $('#tidak').hide();
            $('#btnback').hide();
        } else {
            $('#ya').hide();
            $('#tidak').show();
            $('#btnback').show();
        }
    }
    function ModalCreateAttachment() {
        $('#modal-create-attachment').modal("show");
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
        $('#rcvdt .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
//            format: 'yyyy-mm-dd',
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
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

            if (CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
                $("#CA_CMPLKEYWORD").show();
            } else {
                $("#CA_CMPLKEYWORD").hide();
            }

            if (CA_CMPLCAT === 'BPGK 19') {
                if (document.getElementById('CA_ONLINECMPL_IND').checked)
                    $('#div_CA_ONLINECMPL_CASENO').show();
                else
                    $('#div_CA_ONLINECMPL_CASENO').hide();

                $("#checkpernahadu").show();
                $("#checkinsertadd").show();
                $('#div_CA_ONLINECMPL_PROVIDER').show();
                $('#div_CA_ONLINECMPL_URL').show();
//                $('#div_CA_ONLINECMPL_AMOUNT').show();
                $('#div_CA_ONLINECMPL_BANKCD').show();
                $('#div_CA_ONLINECMPL_PYMNTTYP').show();
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
//                $( "label[for='CA_AGAINST_PREMISE']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINSTADD']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINST_POSTCD']" ).removeClass( "required" );
            } else {
                $("#checkpernahadu").hide();
                $("#checkinsertadd").hide();
                $('#div_CA_ONLINECMPL_CASENO').hide();
                $('#div_CA_ONLINECMPL_PROVIDER').hide();
                $('#div_CA_ONLINECMPL_URL').hide();
//                $('#div_CA_ONLINECMPL_AMOUNT').hide();
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
                    $('select[name="CA_CMPLCD"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                        $('select[name="CA_CMPLCD"]').trigger('change');
                    });
                }
            });
        });

        $('#CA_STATECD').on('change', function (e) {
            var CA_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('call-center-case/getdistlist') }}" + "/" + CA_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_DISTCD"]').empty();
                    $.each(data, function (key, value) {
                        if (value == '0')
                            $('select[name="CA_DISTCD"]').append('<option value="">' + key + '</option>');
                        else
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
                url: "{{ url('call-center-case/getdistlist') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function (key, value) {
                        if (value == '0')
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="">' + key + '</option>');
                        else
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                },
                complete: function (data) {
                    $('#CA_AGAINST_DISTCD').trigger('change');
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
                url: "{{ url('CallCenterCase/getdatatableuser')}}",
                data: function (d) {
                    d.name = $('#name').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
//                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
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
</script>
@stop

@section('javascript')
<script type="text/javascript">

//    $(function () {
////        $('#GenNoAduan').on(click)
//        $('#Generate').on('click', function (e) {
//            alert('Generate');
//        });
//    });
    
//    function onclick(){
//         alert('Generate'); 
//    }
</script>
@stop