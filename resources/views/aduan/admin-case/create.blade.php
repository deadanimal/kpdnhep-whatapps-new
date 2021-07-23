@extends('layouts.main')
<?php

use App\Ref;
use App\User;
use App\Aduan\AdminCase;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Aduan Baru</h2>
            <div class="ibox-content">
                {!! Form::open(['route' => 'admin-case.store', 'class' => 'form-horizontal']) !!}
                {{ csrf_field() }}
                <h4>Cara Terima</h4>
                <div class="hr-line-solid"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT', 'Tarikh', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-10">
                                {{ Form::text('CA_RCVDT', date('d-m-Y h:i A'), ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                @if ($errors->has('CA_RCVDT'))
                                <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_RCVBY') ? ' has-error' : '' }}">
                            {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-10">
                                <div class="input-group">
                                    {{ Form::text('', Auth::user()->name, ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'RcvByName']) }}
                                    {{ Form::hidden('CA_RCVBY', Auth::user()->id, ['class' => 'form-control input-sm', 'id' => 'RcvById']) }}
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm" id="UserSearchModal">Carian</button>
                                        <!--<button class="btn btn-primary btn-sm" type="button" id="CheckJpn">Semak JPN</button>-->
                                    </span>
                                </div>
                                @if ($errors->has('CA_RCVBY'))
                                <span class="help-block"><strong>{{ $errors->first('CA_RCVBY') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                            {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                {{ Form::select('CA_RCVTYP', AdminCase::getRefList('259', true), null, ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('CA_RCVTYP'))
                                <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
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
                            {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan / Pasport', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                <div class="input-group">
                                    {{ Form::text('CA_DOCNO', '', ['class' => 'form-control input-sm', 'id' => 'DOCNO']) }}
                                    <span class="input-group-btn">
                                        <!--<button class="btn btn-primary btn-sm" type="button" id="CheckJpn">Semak JPN</button>-->
                                        <button class="ladda-button ladda-button-demo btn btn-primary btn-sm" type="button" data-style="expand-right" id="CheckJpn">Semak JPN</button>
                                    </span>
                                </div>
                                @if ($errors->has('CA_DOCNO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_DOCNO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_EMAIL') ? ' has-error' : '' }}">
                            {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_EMAIL', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('CA_EMAIL'))
                                <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_MOBILENO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_MOBILENO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_TELNO', 'No. Telefon', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_TELNO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_TELNO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_FAXNO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_FAXNO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_FAXNO'))
                                <span class="help-block"><strong>{{ $errors->first('CA_FAXNO') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                            {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::textarea('CA_ADDR', '', ['class' => 'form-control input-sm', 'rows'=>'4']) }}
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
                                {{ Form::text('CA_NAME', '', ['class' => 'form-control input-sm', 'id' => 'CA_NAME']) }}
                                @if ($errors->has('CA_NAME'))
                                <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::text('CA_AGE', '', ['class' => 'form-control input-sm', 'id' => 'CA_AGE','onkeypress' => "return isNumberKey(event)" ]) }}
                                {{-- Form::select('CA_AGE', Ref::GetList('309', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_AGE']) --}}
                                @if ($errors->has('CA_AGE'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::select('CA_SEXCD_DISABLED', Ref::GetList('202', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD', 'disabled'=>'disabled']) }}
                                {{ Form::hidden('CA_SEXCD', '', ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD']) }}
                                @if ($errors->has('CA_SEXCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_RACECD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_RACECD']) }}
                                @if ($errors->has('CA_RACECD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_RACECD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                <!--                                    @foreach($mRefWarganegara as $RefWarganegara)
                                                                    <div class="radio"><label><input type="radio" value="{{ $RefWarganegara->code }}" name="CA_NATCD" id="{{ $RefWarganegara->code }}" {{ old('CA_NATCD') == $RefWarganegara->code ? 'checked' : '' }}><i></i>{{ $RefWarganegara->descr }}</label></div>
                                                                    @endforeach-->
                                <div class="radio radio-success">
                                    <input id="CA_NATCD1" type="radio" name="CA_NATCD" value="1" onclick="check(this.value)" {{ (old('CA_NATCD') != ''? (old('CA_NATCD') == '1'? 'checked':''):'checked') }} >
                                    <label for="CA_NATCD1"> Warganegara </label>
                                </div>
                                <div class="radio radio-success">
                                    <input id="CA_NATCD2" type="radio" name="CA_NATCD" value="0" onclick="check(this.value)" {{ (old('CA_NATCD') != ''? (old('CA_NATCD') == '0'? 'checked':''):'') }} >
                                    <label for="CA_NATCD2"> Bukan Warganegara </label>
                                </div>
                                <!--<div class="radio"><label><input type="radio" value="oth" name="CA_NATCD" id="national2" {{ old('CA_NATCD') == 'oth' ? 'checked' : '' }}><i></i>Bukan Warganegara</label></div>-->
                                @if ($errors->has('CA_NATCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_NATCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <!--<div id="warganegara" style="display:block">-->
                        <div id="warganegara" style="display: {{ (old('CA_NATCD') != ''? (old('CA_NATCD') == '1'? 'block':'none'):'block') }}">
                            <div class="form-group {{ $errors->has('CA_POSCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('CA_POSCD', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                    @if ($errors->has('CA_POSCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('CA_STATECD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('CA_STATECD', Ref::GetList('17', true, 'ms'), null, ['class' => 'form-control input-sm required', 'id' => 'CA_STATECD']) }}
                                    @if ($errors->has('CA_STATECD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('CA_DISTCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('CA_DISTCD', ['' => '-- SILA PILIH --'], old('CA_DISTCD'), ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) }}
                                    @if ($errors->has('CA_DISTCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--<div id="bknwarganegara" style="display:none">-->
                        <div id="bknwarganegara" style="display: {{ (old('CA_NATCD') != ''? (old('CA_NATCD') == '0'? 'block':'none'):'none') }}">
                            <div class="form-group {{ $errors->has('CA_COUNTRYCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_COUNTRYCD', 'Negara', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), null, ['class' => 'form-control input-sm']) }}
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
                                {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT']) }}
                                @if ($errors->has('CA_CMPLCAT'))
                                <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                @if(old('CA_CMPLCAT'))
                                {{ Form::select('CA_CMPLCD', AdminCase::getcmplcdlist(old('CA_CMPLCAT')), old('CA_CMPLCD'), ['class' => 'form-control input-sm'])}}
                                @else
                                {{ Form::select('CA_CMPLCD', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm']) }}
                                @endif
                                @if ($errors->has('CA_CMPLCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="div_CA_CMPLKEYWORD" style="display: {{ in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none' }};">
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
                                {{ Form::select('CA_ONLINECMPL_PROVIDER', Ref::GetList('1091', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PROVIDER']) }}
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
                        <div id="div_CA_ONLINECMPL_AMOUNT" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                            {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_ONLINECMPL_AMOUNT', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)"]) }}
                                @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                @endif
                            </div>
                        </div>
                          <div id="div_CA_ONLINECMPL_BANKCD" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                            <label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Nama Bank</label>
                            <div class="col-sm-8">
                                {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_BANKCD']) }}
                                @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_BANKCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="div_CA_ONLINECMPL_ACCNO" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                            {{ Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun Bank / No. Transaksi FPX', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                {{ Form::text('CA_ONLINECMPL_ACCNO', '', ['class' => 'form-control input-sm']) }}
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
                            {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                <style scoped>input:invalid, textarea:invalid { color: red; }</style>
                                <input id="CA_AGAINST_EMAIL" type="email" class="form-control input-sm" name="CA_AGAINST_EMAIL" value="{{ old('CA_AGAINST_EMAIL') }}">
                                {{-- Form::email('CA_AGAINST_EMAIL', '', ['class' => 'form-control input-sm']) --}}
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
                            {{ Form::label('CA_AGAINSTNM', 'Nama (Syarikat/Premis)', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::text('CA_AGAINSTNM', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('CA_AGAINSTNM'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTNM') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="checkinsertadd" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group">
                            {{ Form::label('', '', ['class' => 'col-md-5 control-label']) }}
                            <div class="col-sm-7">
                                <div class="checkbox checkbox-success">
                                    <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' }}>
                                           <label for="CA_ONLINEADD_IND">
                                        Mempunyai alamat penuh pembekal perkhidmatan?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="div_CA_AGAINSTADD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                            <label for="CA_AGAINSTADD" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Alamat</label>
                            <div class="col-sm-7">
                                {{ Form::textarea('CA_AGAINSTADD', '', ['class' => 'form-control input-sm', 'rows'=> '4']) }}
                                @if ($errors->has('CA_AGAINSTADD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTADD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="div_CA_AGAINST_POSTCD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                            <label for="CA_AGAINST_POSTCD" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Poskod</label>
                            <div class="col-sm-7">
                                {{ Form::text('CA_AGAINST_POSTCD', '', ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)" ]) }}
                                @if ($errors->has('CA_AGAINST_POSTCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_POSTCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="div_CA_AGAINST_STATECD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_STATECD']) }}
                                @if ($errors->has('CA_AGAINST_STATECD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_STATECD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="div_CA_AGAINST_DISTCD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                            {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                @if (old('CA_AGAINST_STATECD'))
                                {{ Form::select('CA_AGAINST_DISTCD', Ref::GetListDist(old('CA_AGAINST_STATECD')), null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) }}
                                @else
                                {{ Form::select('CA_AGAINST_DISTCD', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) }}
                                @endif
                                @if ($errors->has('CA_AGAINST_DISTCD'))
                                <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_DISTCD') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                            {{ Form::label('CA_SUMMARY', 'Aduan', ['class' => 'col-sm-1 control-label required']) }}
                            <div class="col-sm-11">
                                {{ Form::textarea('CA_SUMMARY', '', ['class' => 'form-control input-sm', 'rows'=>'4']) }}
                                @if ($errors->has('CA_SUMMARY'))
                                <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12" align="center">
                        <!--                            {{-- Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']) --}}
                                                    {{-- link_to('admin-case', 'Kembali', ['class' => 'btn btn-default btn-sm']) --}}-->
                        <input style="visibility:visible" type="button" id="btncontinue" value="{{ trans('button.continue') }}" onClick="btncontinueclick();" class="btn btn-primary btn-sm" />
                        <input style="visibility:hidden" type="submit" id="btnupdate" value="{{ trans('button.send') }}" class="btn btn-primary btn-sm" />
                        <input style="visibility:hidden" type="button" id="btnsave" value="{{ trans('button.update') }}" onClick="btnupdateclick();" class="btn btn-primary btn-sm" />
                        <a class="btn btn-default btn-sm" href="{{ url()->previous() }}">Kembali</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <!--            <div class="ibox ">
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-3">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>-->
        </div>
    </div>
</div>

<!-- Modal Start -->
@include('aduan.admin-case.usersearchmodal')
<!-- Modal End -->

@stop

@section('script_datatable')
<script type="text/javascript">
    $('#CheckJpn').on('click', function (e) {
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
                console.log(data.CorrespondenceAddressStateCode);
                if (data.error) {
                    alert(data.error);
                }
                $('input[name="CA_NAME"]').val(data.Name); // Nama
                $('input[name="CA_EMAIL"]').val(data.EmailAddress); // Email
                document.getElementById('CA_AGE').value = data.Age; // Umur
                $('input[name="CA_MOBILENO"]').val(data.MobilePhoneNumber); // Telefon Bimbit
                document.getElementById('CA_SEXCD').value = data.Gdr; // Jantina
                if (data.Warganegara != '') {
                    if (data.Warganegara === 'malaysia') { // Warganegara
                        document.getElementById('CA_NATCD1').checked = true;
                        $('#warganegara').show();
                        $('#bknwarganegara').hide();
                    } else {
                        document.getElementById('CA_NATCD2').checked = true;
                        $('#warganegara').hide();
                        $('#bknwarganegara').show();
                    }
                }
			$('textarea[name="CA_ADDR"]').val(data.CorrespondenceAddress1 + '\n' + data.CorrespondenceAddress2); // Alamat
                $('input[name="CA_POSCD"]').val(data.CorrespondenceAddressPostcode); // Poskod
                document.getElementById('CA_STATECD').value = data.CorrespondenceAddressStateCode; // Negeri
                getDistListFromJpn(data.CorrespondenceAddressStateCode, data.Daerah); // Daerah
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
            }
        });
    }

    function check(value) {
        if (value === '1') {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        } else {
            $('#warganegara').hide();
            $('#bknwarganegara').show();
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

    $(function () {
        $('#national1').on('click', function (e) {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        });
        $('#national2').on('click', function (e) {
            $('#warganegara').hide();
            $('#bknwarganegara').show();
        });

        $('#CA_ONLINECMPL_PROVIDER').on('change', function (e) {
            var CA_ONLINECMPL_PROVIDER = $(this).val();
            if (CA_ONLINECMPL_PROVIDER === '999')
                $("label[for='CA_ONLINECMPL_URL']").addClass("required");
            else
                $("label[for='CA_ONLINECMPL_URL']").removeClass("required");
        });

        $('#CA_CMPLCAT').on('change', function (e) {
            var CA_CMPLCAT = $(this).val();
            if (CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
                $("#div_CA_CMPLKEYWORD").show();
            } else {
                $("#div_CA_CMPLKEYWORD").hide();
            }
            if (CA_CMPLCAT === 'BPGK 19') {
                if (document.getElementById('CA_ONLINECMPL_IND').checked)
                    $('#div_CA_ONLINECMPL_CASENO').show();
                else
                    $('#div_CA_ONLINECMPL_CASENO').hide();
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
                $("#checkpernahadu").show();
                $("#checkinsertadd").show();
                $('#div_CA_ONLINECMPL_PROVIDER').show();
                $('#div_CA_ONLINECMPL_URL').show();
                $('#div_CA_ONLINECMPL_AMOUNT').show();
                $('#div_CA_ONLINECMPL_BANKCD').show();
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
            } else {
                $("#checkpernahadu").hide();
                $("#checkinsertadd").hide();
                $('#div_CA_ONLINECMPL_CASENO').hide();
                $('#div_CA_ONLINECMPL_PROVIDER').hide();
                $('#div_CA_ONLINECMPL_URL').hide();
                $('#div_CA_ONLINECMPL_AMOUNT').hide();
                $('#div_CA_ONLINECMPL_BANKCD').hide();
                $('#div_CA_ONLINECMPL_ACCNO').hide();
                $('#div_CA_AGAINST_PREMISE').show();
                $('#div_CA_AGAINSTADD').show();
                $('#div_CA_AGAINST_POSTCD').show();
                $('#div_CA_AGAINST_STATECD').show();
                $('#div_CA_AGAINST_DISTCD').show();
            }
            $.ajax({
                type: 'GET',
                url: "{{ url('admin-case/getcmpllist') }}" + "/" + CA_CMPLCAT,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('select[name="CA_CMPLCD"]').empty();
                    $.each(data, function (key, value) {
                        if (value == '0')
                            $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                        else
                            $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
        });
    });

    function myFunction(id) {
        $.ajax({
            url: "{{ url('admin-case/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("RcvByName").value = key;
                    document.getElementById("RcvById").value = value;
                });
                $('#carian-penerima').modal('hide');
            }
        });
    }
    ;
    $(document).ready(function () {

        $('#UserSearchModal').on('click', function (e) {
            $("#carian-penerima").modal();
        });

        $('#rcvdt .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
//            format: 'yyyy-mm-dd',
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
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
                }
            });
        });

        $('#CA_AGAINST_STATECD').on('change', function (e) {
            var CA_AGAINST_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('admin-case/getdistlist') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_AGAINST_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
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
                url: "{{ url('admin-case/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd_user').val();
                    d.brn_cd = $('#brn_cd').val();
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
    });
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>
@stop