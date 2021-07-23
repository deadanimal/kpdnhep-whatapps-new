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
            <h2>Kemaskini Aduan</h2>
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#case-form">Maklumat Aduan</a></li>
                    <li class=""><a data-toggle="tab" href="#case-transaction">Sorotan Transaksi</a></li>
                </ul>
                <div class="tab-content">
                    <div id="case-form" class="tab-pane fade in active">
                        <div class="panel-body">
                            {!! Form::open(['route' => ['admin-case.update', $mAdminCase->CA_CASEID], 'class' => 'form-horizontal']) !!}
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <h4>Cara Terima</h4>
                            <div class="hr-line-solid"></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::text('CA_CASEID', $mAdminCase->CA_CASEID, ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                            @if ($errors->has('CA_CASEID'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CASEID') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_RCVBY') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                {{ Form::hidden('CA_RCVBY', $mAdminCase->CA_RCVBY, ['class' => 'form-control input-sm', 'id' => 'RCVBY_id']) }}
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
                                    <div class="form-group">
                                        {{ Form::label('CA_RCVDT', 'Tarikh', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_RCVDT', date('d-m-Y h:i A', strtotime($mAdminCase->CA_RCVDT)), ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                            @if ($errors->has('CA_RCVDT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label required']) }}
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_RCVTYP', AdminCase::getRefList('259', true), old('CA_RCVTYP', $mAdminCase->CA_RCVTYP), ['class' => 'form-control input-sm']) }}
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
                                        {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-6 control-label required']) }}
                                        <div class="col-sm-6">
                                            {{ Form::text('CA_DOCNO', old('CA_DOCNO', $mAdminCase->CA_DOCNO), ['class' => 'form-control input-sm', 'disabled' => 'true']) }}
                                            @if ($errors->has('CA_DOCNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_DOCNO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_EMAIL') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-sm-6 control-label']) }}
                                        <div class="col-sm-6">
                                            {{ Form::text('CA_EMAIL', old('CA_EMAIL', $mAdminCase->CA_EMAIL), ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_EMAIL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-6 control-label required']) }}
                                        <div class="col-sm-6">
                                            {{ Form::text('CA_MOBILENO', old('CA_MOBILENO', $mAdminCase->CA_MOBILENO), ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_MOBILENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-sm-6 control-label']) }}
                                        <div class="col-sm-6">
                                            {{ Form::text('CA_TELNO', old('CA_TELNO', $mAdminCase->CA_TELNO), ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_TELNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_FAXNO') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-sm-6 control-label']) }}
                                        <div class="col-sm-6">
                                            {{ Form::text('CA_FAXNO', old('CA_FAXNO', $mAdminCase->CA_FAXNO), ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_FAXNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_FAXNO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-6 control-label']) }}
                                        <div class="col-sm-6">
                                            {{ Form::textarea('CA_ADDR', old('CA_ADDR', $mAdminCase->CA_ADDR), ['class' => 'form-control input-sm', 'rows'=>'4']) }}
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
                                            {{ Form::text('CA_NAME', old('CA_NAME', $mAdminCase->CA_NAME), ['class' => 'form-control input-sm', 'disabled' => 'true']) }}
                                            @if ($errors->has('CA_NAME'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::select('CA_AGE', Ref::GetList('309', true, 'ms'), old('CA_AGE', $mAdminCase->CA_AGE), ['class' => 'form-control input-sm', 'disabled' => 'true']) }}
                                            @if ($errors->has('CA_AGE'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), old('CA_SEXCD', $mAdminCase->CA_SEXCD), ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD', 'disabled' => 'true']) }}
                                            @if ($errors->has('CA_SEXCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_RACECD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), old('CA_RACECD', $mAdminCase->CA_RACECD), ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_RACECD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_RACECD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_NATCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                        <div class="col-sm-9">
                                            <div class="radio radio-success">
                                                <input id="CA_NATCD1" type="radio" name="CA_NATCD" value="1" onclick="check(this.value)" {{ $mAdminCase->CA_NATCD == '1' ? 'checked' : '' }} >
                                                <label for="CA_NATCD1"> Warganegara </label>
                                            </div>
                                            <div class="radio radio-success">
                                                <input id="CA_NATCD2" type="radio" name="CA_NATCD" value="0" onclick="check(this.value)" {{ $mAdminCase->CA_NATCD == '0' ? 'checked' : '' }} >
                                                <label for="CA_NATCD2"> Bukan Warganegara </label>
                                            </div>
                                            @if ($errors->has('CA_NATCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_NATCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="warganegara" style="display: {{ $mAdminCase->CA_NATCD == '1' ? 'block' : 'none' }}">
                                        <div class="form-group {{ $errors->has('CA_POSCD') ? ' has-error' : 'CA_POSCD' }}">
                                            {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label']) }}
                                            <div class="col-sm-9">
                                                {{ Form::text('CA_POSCD', old('CA_POSCD', $mAdminCase->CA_POSCD), ['class' => 'form-control input-sm']) }}
                                                @if ($errors->has('CA_POSCD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('CA_STATECD') ? ' has-error' : 'CA_STATECD' }}">
                                            {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label required']) }}
                                            <div class="col-sm-9">
                                                {{ Form::select('CA_STATECD', Ref::GetList('17', true, 'ms'), old('CA_STATECD', $mAdminCase->CA_STATECD), ['class' => 'form-control input-sm required', 'id' => 'CA_STATECD']) }}
                                                @if ($errors->has('CA_STATECD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('CA_DISTCD') ? ' has-error' : 'CA_DISTCD' }}">
                                            {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label required']) }}
                                            <div class="col-sm-9">
                                                {{ Form::select('CA_DISTCD', $mAdminCase->CA_DISTCD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($mAdminCase->CA_STATECD, '18', true, 'ms'), old('CA_DISTCD', $mAdminCase->CA_DISTCD), ['class' => 'form-control input-sm', 'id' => 'CA_DISTCD']) }}
                                                @if ($errors->has('CA_DISTCD'))
                                                <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bknwarganegara" style="display: {{ $mAdminCase->CA_NATCD == '0' ? 'block' : 'none' }}">
                                        <div class="form-group {{ $errors->has('CA_COUNTRYCD') ? ' has-error' : 'CA_COUNTRYCD' }}">
                                            {{ Form::label('CA_COUNTRYCD', 'Negara', ['class' => 'col-sm-3 control-label required']) }}
                                            <div class="col-sm-9">
                                                {{ Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('CA_COUNTRYCD', $mAdminCase->CA_COUNTRYCD), ['class' => 'form-control input-sm']) }}
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
                                            {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms'), $mAdminCase->CA_CMPLCAT, array('class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT')) }}
                                            @if ($errors->has('CA_CMPLCAT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-4 control-label required']) }}
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_CMPLCD', AdminCase::getcmplcdlist(old('CA_CMPLCAT')? old('CA_CMPLCAT') : ($mAdminCase->CMPLCAT? $mAdminCase->CA_CMPLCAT:'')), (old('CA_CMPLCD')? old('CA_CMPLCD') : ($mAdminCase->CA_CMPLCD? $mAdminCase->CA_CMPLCD:'')), ['class' => 'form-control input-sm', 'id' => 'CA_CMPLCD']) }}
                                            @if ($errors->has('CA_CMPLCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="CA_CMPLKEYWORD" style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($mAdminCase->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none')))  }};">
                                        {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-4 control-label required']) }}
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_CMPLKEYWORD',Ref::GetList('1051',true, 'ms'), $mAdminCase->CA_CMPLKEYWORD,['class' => 'form-control  input-sm'])}}
                                            @if ($errors->has('CA_CMPLKEYWORD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CMPLKEYWORD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mAdminCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-4 control-label required']) }}
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_ONLINECMPL_PROVIDER', Ref::GetList('1091', true, 'ms'), $mAdminCase->CA_ONLINECMPL_PROVIDER, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PROVIDER']) }}
                                            @if ($errors->has('CA_ONLINECMPL_PROVIDER'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_PROVIDER') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_ONLINECMPL_URL" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mAdminCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_ONLINECMPL_URL', 'Laman Web / URL', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_ONLINECMPL_URL',$mAdminCase->CA_ONLINECMPL_URL, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_ONLINECMPL_URL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_URL') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_ONLINECMPL_AMOUNT" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mAdminCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-4 control-label required']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_ONLINECMPL_AMOUNT', $mAdminCase->CA_ONLINECMPL_AMOUNT, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>;
                                    <div id="div_CA_ONLINECMPL_BANKCD" style="display: {{ (old('CA_CMPLCAT') ? (old('CA_CMPLCAT') == 'BPGK 19' ? 'block' : 'none') :  ($mAdminCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }}" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                                        <label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Nama Bank</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, 'ms'), $mAdminCase->CA_ONLINECMPL_BANKCD, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_BANKCD']) }}
                                            @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_BANKCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_ONLINECMPL_ACCNO" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mAdminCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun Bank / No. Transaksi FPX', ['class' => 'col-sm-4 control-label required']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_ONLINECMPL_ACCNO', $mAdminCase->CA_ONLINECMPL_ACCNO, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_ACCNO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINST_PREMISE" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($mAdminCase->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                        <label for="CA_AGAINST_PREMISE" class="col-sm-4 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Jenis Premis</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), $mAdminCase->CA_AGAINST_PREMISE, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_PREMISE']) }}
                                            @if ($errors->has('CA_AGAINST_PREMISE'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_PREMISE') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mAdminCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" id="checkpernahadu">
                                        {{ Form::label('', '', ['class' => 'col-md-4 control-label']) }}
                                        <div class="col-sm-6">
                                            <div class="checkbox checkbox-success">
                                                <input name="CA_ONLINECMPL_IND" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' || $mAdminCase->CA_ONLINECMPL_IND == '1'? 'checked':'' }}>
                                                       <label for="CA_ONLINECMPL_IND">
                                                    Pernah membuat aduan di pihak diadu?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_CA_ONLINECMPL_CASENO" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'? 'block':($mAdminCase->CA_CMPLCAT == 'BPGK 19' && $mAdminCase->CA_ONLINECMPL_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mAdminCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINECMPL_IND') == '' && $mAdminCase->CA_ONLINECMPL_IND == '1'? 'block':(old('CA_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-4 control-label required']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_ONLINECMPL_CASENO',$mAdminCase->CA_ONLINECMPL_CASENO, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_ONLINECMPL_CASENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_CASENO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon (Pejabat)', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_AGAINST_TELNO', $mAdminCase->CA_AGAINST_TELNO, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_AGAINST_TELNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_AGAINST_MOBILENO', $mAdminCase->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_AGAINST_MOBILENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::email('CA_AGAINST_EMAIL', $mAdminCase->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_AGAINST_EMAIL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_AGAINST_FAXNO', $mAdminCase->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm']) }}
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
                                            {{ Form::text('CA_AGAINSTNM', $mAdminCase->CA_AGAINSTNM, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_AGAINSTNM'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTNM') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="checkinsertadd" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mAdminCase->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group">
                                        {{ Form::label('', '', ['class' => 'col-md-5 control-label']) }}
                                        <div class="col-sm-7">
                                            <div class="checkbox checkbox-success">
                                                <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' || $mAdminCase->CA_ONLINEADD_IND == '1'? 'checked':'' }}>
                                                       <label for="CA_ONLINEADD_IND">
                                                    Mempunyai alamat penuh pembekal perkhidmatan?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINSTADD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($mAdminCase->CA_CMPLCAT == 'BPGK 19' && $mAdminCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mAdminCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mAdminCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                                        <label for="CA_AGAINSTADD" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Alamat</label>
                                        <div class="col-sm-7">
                                            {{ Form::textarea('CA_AGAINSTADD', $mAdminCase->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows'=> '4']) }}
                                            @if ($errors->has('CA_AGAINSTADD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINSTADD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINST_POSTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($mAdminCase->CA_CMPLCAT == 'BPGK 19' && $mAdminCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mAdminCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mAdminCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                                        <label for="CA_AGAINST_POSTCD" class="col-sm-5 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">Poskod</label>
                                        <div class="col-sm-7">
                                            {{ Form::text('CA_AGAINST_POSTCD', $mAdminCase->CA_AGAINST_POSTCD, ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_AGAINST_POSTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_POSTCD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINST_STATECD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($mAdminCase->CA_CMPLCAT == 'BPGK 19' && $mAdminCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mAdminCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mAdminCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-5 control-label required']) }}
                                        <div class="col-sm-7">
                                            {{ Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true, 'ms'), $mAdminCase->CA_AGAINST_STATECD, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_STATECD']) }}
                                            @if ($errors->has('CA_AGAINST_STATECD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_STATECD') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINST_DISTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($mAdminCase->CA_CMPLCAT == 'BPGK 19' && $mAdminCase->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mAdminCase->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $mAdminCase->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                                        {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-5 control-label required']) }}
                                        <div class="col-sm-7">
                                            {{ Form::select('CA_AGAINST_DISTCD', ($mAdminCase->CA_AGAINST_STATECD == '' ? ['' => '-- SILA PILIH --'] : Ref::GetListDist($mAdminCase->CA_AGAINST_STATECD, '18', true, 'ms')), $mAdminCase->CA_AGAINST_DISTCD, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD']) }}
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
                                            {{ Form::textarea('CA_SUMMARY', old('CA_SUMMARY', $mAdminCase->CA_SUMMARY), ['class' => 'form-control input-sm', 'rows'=>'4']) }}
                                            @if ($errors->has('CA_SUMMARY'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                           
                                 <div class="row">
                                <div class="col-md-12">
                                    @if ( $count < 5)
                                    <a class="btn btn-success btn-sm" data-toggle="modal" href="#caseAttachment">
                                        <i class="fa fa-plus"></i> Bukti Aduan
                                    </a>
                                    @endif
                                    <div class="modal fade" id="caseAttachment" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-remove"></i></button>
                                                    <h4 class="modal-title">Tambah Bahan Bukti</h4>
                                                </div>
                                                <div class="modal-body">
                                                    {!! Form::open(['route' => 'admin-case-doc.store', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'form-create-attachment']) !!}
                                                    {{ csrf_field() }}
                                                    <div id="file_field" class="form-group">
                                                        {{ Form::label('file', 'Fail', ['class' => 'col-sm-2 control-label required']) }}
                                                        <div class="col-sm-10">
                                                            {{ Form::file('file') }}
                                                            <div>
                                                                <span style="color: red;">
                                                                    Format : pdf, jpeg, png, gif. Maksimum 2Mb bagi setiap fail.
                                                                </span>
                                                            </div>
                                                            <span id="file_block" style="display: none;" class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div id="CC_REMARKS_field" class="form-group">
                                                        {{ Form::label('CC_REMARKS', 'Catatan', ['class' => 'col-sm-2 control-label']) }}
                                                        <div class="col-sm-10">
                                                            {{ Form::textarea('CC_REMARKS', '', ['class' => 'form-control input-sm', 'rows' => 2]) }}
                                                            {{ Form::hidden('CC_CASEID', $mAdminCase->CA_CASEID, ['class' => 'form-control input-sm']) }}
                                                            <span id="CC_REMARKS_block" style="display: none;" class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group col-sm-12" align="center">
                                                            <input type="button" value="Tambah" id="btnsubmitcreate" class="btn btn-sm btn-success">
                                                        </div>
                                                    </div>
                                                     </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                   
                                    @if(!empty($mAdminCaseDoc))
                                    <div class="modal fade" id="modal-edit-attachment" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content" id='modalEditContent'>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                 </div>
                            <div class="table-responsive">
                                <table id="admin-case-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Fail</th>
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
                                      {{ Form::submit('Hantar', ['class' => 'btn btn-success btn-sm'])}}
                      
                        {{-- Form::submit('Hantar', array('class' => 'btn btn-success btn-sm')) --}}
                    
                        {{ link_to_route('admin-case.index', 'Kembali', [], ['class' => 'btn btn-default btn-sm']) }}
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>


                    <div id="case-transaction" class="tab-pane fade">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="admin-case-transaction-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Status</th>
                                            <th>Daripada</th>
                                            <th>Kepada</th>
                                            <th>Tarikh Transaksi</th>
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
    </div>
</div>

<div id="carian-penerima" class="modal fade" aria-hidden="true">
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
                            {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::select('state_cd', Ref::GetList('17', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'state_cd_user']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('name', 'Nama Pengguna', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
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
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href=' + hash + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    function check(value) {
        if (value === '1') {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        } else {
            $('#warganegara').hide();
            $('#bknwarganegara').show();
        }
    }

    $(function () {
        $('#national1').on('click', function (e) {
            $('#warganegara').show();
            $('#bknwarganegara').hide();
        });
        $('#national2').on('click', function (e) {
            $('#warganegara').hide();
            $('#bknwarganegara').show();
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
                $('#div_CA_ONLINECMPL_AMOUNT').show();
                $('#div_CA_ONLINECMPL_BANKCD').show();
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
                $("label[for='CA_ONLINECMPL_URL']").removeClass("required");
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
                        if (value === '0')
                            $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                        else
                            $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
        });
        $('#CA_ONLINECMPL_PROVIDER').on('change', function (e) {
            var CA_ONLINECMPL_PROVIDER = $(this).val();
            if (CA_ONLINECMPL_PROVIDER === '999')
                $("label[for='CA_ONLINECMPL_URL']").addClass("required");
            else
                $("label[for='CA_ONLINECMPL_URL']").removeClass("required");
        });
    });

    function onlinecmplind() {
        if (document.getElementById('CA_ONLINECMPL_IND').checked) {
            $('#div_CA_ONLINECMPL_CASENO').show();
        } else {
            $('#div_CA_ONLINECMPL_CASENO').hide();
        }
    }
    ;

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
    }
    ;


    function modalattachmenteditadmin(ID) {
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('admin-case-doc-edit','') }}" + "/" + ID);
        return false;
    }

    $(document).ready(function () {
        $('#rcvdt .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });

        $('#CA_CMPLCAT').on('change', function (e) {
            var CA_CMPLCAT = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('admin-case/getcmpllist') }}" + "/" + CA_CMPLCAT,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_CMPLCD"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
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
                    d.state_cd = $('#state_cd_user').val();
                    d.brn_cd = $('#brn_cd').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
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

        $('#btn-reset').on('click', function (e) {
            document.getElementById("user-search-form").reset();
            oTable.draw();
            e.preventDefault();
        });

        $('#user-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

        $('#admin-case-attachment-table').DataTable({
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
//                paginate: {
//                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
//                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
//                    first: 'Pertama',
//                    last: 'Terakhir'
//                }
            },
            ajax: {
                url: "{{ url('admin-case-doc/getdatatable', $mAdminCase->CA_CASEID) }}"
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
                {data: 'CC_REMARKS', name: 'CC_REMARKS'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
            ]
        });

        $('#admin-case-transaction-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
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
                url: "{{ url('admin-case/getdatatabletransaction', $mAdminCase->CA_CASEID)}}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CD_INVSTS', name: 'CD_INVSTS'},
                {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
                {data: 'CD_ACTTO', name: 'CD_ACTTO'},
                {data: 'CD_CREDT', name: 'CD_CREDT'},
                {data: 'CD_DESC', name: 'CD_DESC'}
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
                url: "{{ url('admin-case-doc/ajaxvalidatestoreattachment') }}",
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

        $('#btnsubmitupdate').click(function (e) {
            e.preventDefault();

            var $form = $('#form-edit-attachment');
            var formData = {};

            $form.find(':input').each(function () {
                formData[ $(this).attr('name') ] = $(this).val();
            });

            $.ajax({
                type: 'POST',
                url: "{{ url('admin-case-doc/ajaxvalidateupdateattachment') }}",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if (data['fails']) {
                        $('.form-group').removeClass('has-error');
                        $('.help-block').hide().text();
                        $.each(data['fails'], function (key, value) {
                            $("#form-edit-attachment div[id=" + key + "_field]").addClass('has-error');
                            $("#form-edit-attachment span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                            console.log(key);
                        });
                        console.log("fails");
                    } else {
                        $('#form-edit-attachment').submit();
                        console.log("success");
                    }
                }
            });
        });
    });
</script>
@stop