@extends('layouts.main')
<?php
use App\Penugasan;
use App\Ref;
use App\Aduan\PindahAduan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
?>
@section('content')
<style>
    textarea {
        resize: vertical;
    }
</style>
<h2>Pindah Aduan</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#pindah">Pindah Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#case-info">Maklumat Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#adu-diadu">Maklumat Pengadu Dan Diadu</a></li>
            <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li>
        </ul>
        <div class="tab-content">
            <div id="pindah" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => ['pindah.update', $mPindah->CA_CASEID], 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }} {{ method_field('PATCH') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{-- Form::text('CA_CASEID', $mPindah->CA_CASEID, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $mPindah->CA_CASEID }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_FILEREF', 'No. Rujukan Fail', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{ Form::text('CA_FILEREF', $mPindah->CA_FILEREF, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <!-- <div id="div_CA_CMPLCAT" class="form-group{{ $errors->has('CA_CMPLCAT') ? ' has-error' : '' }}" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '1' ? 'block':'none') : ($mPindah->CA_INVSTS == '1' || $mPindah->CA_INVSTS == '0' ? 'block' : 'none')) }}"> -->
                            <div id="div_CA_CMPLCAT" class="form-group{{ $errors->has('CA_CMPLCAT') ? ' has-error' : '' }}" style="display: block">
                                {{ Form::label('CA_CMPLCAT', 'Kategori', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms', 'descr'), old('CA_CMPLCAT', $mPindah->CA_CMPLCAT), ['class' => 'form-control input-sm required', 'id' => 'CA_CMPLCAT']) }}
                                    @if ($errors->has('CA_CMPLCAT'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div id="div_CA_CMPLCD" class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '1' ? 'block':'none') : ($mPindah->CA_INVSTS == '1' || $mPindah->CA_INVSTS == '0' ? 'block' : 'none')) }}"> -->
                            <div id="div_CA_CMPLCD" class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}" style="display: block">
                                {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('CA_CMPLCD', $mPindah->CA_CMPLCAT == '' ? ['-- SILA PILIH --'] : Penugasan::getcmplcdlist(old('CA_CMPLCAT', $mPindah->CA_CMPLCAT)), old('CA_CMPLCD', $mPindah->CA_CMPLCD), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_CMPLCD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_INVSTS') ? ' has-error' : '' }}">
                                {{ Form::label('CA_INVSTS', 'Status', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    {{-- Form::select('CA_INVSTS', PindahAduan::GetListStatusAduan(), $mPindah->CA_INVSTS, ['class' => 'form-control input-sm', 'id'=>'CA_INVSTS', 'placeholder' => '-- SILA PILIH --']) --}}
                                    {{ Form::select('CA_INVSTS', PindahAduan::GetListStatusAduan(), null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                                    @if ($errors->has('CA_INVSTS'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_INVSTS') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="camagncd" style="display: {{ $errors->has('CA_MAGNCD')||old('CA_INVSTS') == '4'||$mPindah->CA_INVSTS == '4' ? 'block' : 'none' }} ;">
                                <div class="form-group{{ $errors->has('CA_MAGNCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_MAGNCD', 'Agensi', ['class' => 'col-lg-4 control-label required']) }}
                                    <div class="col-lg-8">
                                        {{ Form::select('CA_MAGNCD', PindahAduan::GetListMagn(), $mPindah->CA_MAGNCD, ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_MAGNCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_MAGNCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGBY', 'Dipindahkan Oleh', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{-- Form::text('CA_ASGBY_NAME', Auth::User()->name, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ Auth::User()->name }}</p>
                                    {{ Form::hidden('CA_ASGBY', Auth::User()->id, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    {{ Form::hidden('CA_ASGDT', Carbon::now(), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_INVBY_NAME') ? ' has-error' : '' }}"
                                style="display : none;">
                                {{ Form::label('CA_INVBY','Dipindahkan Kepada', ['class' => 'col-lg-4 control-label required', 'for' => 'CA_INVBY']) }}
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <!--{{-- Form::text('CA_INVBY_NAME', $mPindah->CA_INVBY != '' ? $mPindah->namapenyiasat->name : '', ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'InvByName']) --}}-->
                                        {{ Form::text('CA_INVBY_NAME', count($mPindah->namapenyiasat) == '1' ? $mPindah->namapenyiasat->name : $mPindah->CA_INVBY, ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'InvByName']) }}
                                        {{ Form::hidden('CA_INVBY', $mPindah->CA_INVBY, ['class' => 'form-control input-sm', 'id' => 'InvById']) }}
                                        {{ Form::hidden('CA_INVDT', Carbon::now(), ['class' => 'form-control input-sm', 'id' => 'InvById']) }}
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-sm" id="UserSearchModal">Carian</button>
                                        </span>
                                    </div>
                                    @if ($errors->has('CA_INVBY_NAME'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_INVBY_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="ca_br_statecd"
                                class="form-group {{ $errors->has('CA_BR_STATECD') ? ' has-error' : '' }}"
                                style="display: {{ $errors->has('CA_BR_STATECD') || old('CA_INVSTS') == '0' ? 'block' : 'none' }} ;">
                                {{ Form::label('CA_BR_STATECD', 'Negeri', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('CA_BR_STATECD', $mRefState, null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                                    @if ($errors->has('CA_BR_STATECD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_BR_STATECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="ca_brncd"
                                class="form-group {{ $errors->has('CA_BRNCD') ? ' has-error' : '' }}"
                                style="display: {{ $errors->has('CA_BRNCD') || old('CA_INVSTS') == '0' ? 'block' : 'none' }} ;">
                                {{ Form::label('CA_BRNCD', 'Cawangan', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('CA_BRNCD', [], null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                                    @if ($errors->has('CA_BRNCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_BRNCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="countduration"
                                class="form-group"
                                style="display: {{ old('CA_INVSTS') != '' ? 'block' : 'none' }} ;">
                                {{ Form::label('', 'Hari', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <p class="form-control-static">{{ $countDuration }}</p>
                                    {{ Form::hidden('CD_REASON_DURATION', $countDuration, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            @if ($countDuration >= 4)
                            <div id="cd_reason"
                                class="form-group {{ $errors->has('CD_REASON') ? ' has-error' : '' }}"
                                style="display: {{ $errors->has('CD_REASON') || (old('CA_INVSTS') != '') ? 'block' : 'none' }} ;">
                                {{ Form::label('CD_REASON', 'Alasan', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('CD_REASON', $mCaseReasonTemplate, null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                                    @if ($errors->has('CD_REASON'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_REASON') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="data_5"
                                class="form-group {{ $errors->has('CD_REASON_DATE_FROM') || $errors->has('CD_REASON_DATE_TO') ? ' has-error' : '' }}"
                                style="display: {{
                                    ($errors->has('CD_REASON_DATE_FROM') || $errors->has('CD_REASON_DATE_TO'))
                                    || old('CA_INVSTS') != '' && old('CD_REASON') == 'P2'
                                    ? 'block' : 'none'
                                }} ;">
                                {{ Form::label('CD_REASON_DATE_FROM', 'Tarikh', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('CD_REASON_DATE_FROM', null, ['class' => 'form-control input-sm', 'readonly' => 'true', 'placeholder' => 'HH-BB-TTTT']) }}
                                        <span class="input-group-addon">Hingga</span>
                                        {{ Form::text('CD_REASON_DATE_TO', null, ['class' => 'form-control input-sm', 'readonly' => 'true', 'placeholder' => 'HH-BB-TTTT']) }}
                                    </div>
                                    @if ($errors->has('CD_REASON_DATE_FROM'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_REASON_DATE_FROM') }}</strong></span>
                                    @endif
                                    @if ($errors->has('CD_REASON_DATE_TO'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_REASON_DATE_TO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('CD_DESC') ? ' has-error' : '' }}">
                                {{ Form::label('CD_DESC', 'Saranan', ['class' => 'col-sm-2 control-label required']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CD_DESC', '', ['class' => 'form-control input-sm', 'rows' => 3]) }}
                                    @if ($errors->has('CD_DESC'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_DESC') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_ANSWER') ? ' has-error' : '' }}" id="div_CA_ANSWER" style="display: {{ old('CA_INVSTS') ? ((old('CA_INVSTS') == '4' || (old('CA_INVSTS') == '5'))? 'block':'none') : (($mPindah->CA_INVSTS == '4' || $mPindah->CA_INVSTS == '5') ? 'block':'none') }}">
                                {{ Form::label('CA_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-sm-2 control-label required']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_ANSWER', old('CA_ANSWER'), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                    @if ($errors->has('CA_ANSWER'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ANSWER') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            {{ Form::submit('Hantar', ['class' => 'btn btn-success btn-sm']) }}
                            {{ link_to('pindah', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div id="case-info" class="tab-pane">
                <div class="panel-body">
                    {!! Form::open(['class' => 'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CASEID', $mPindah->CA_CASEID, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_BRNCD', 'Nama Cawangan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('CA_BRNCD', $mPindah->CA_BRNCD, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    {{ Form::text('CA_BRNCD', $mPindah->CA_BRNCD != '' ? $mPindah->namacawangan->BR_BRNNM : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('', 'No. Rujukan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVDT', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_RCVDT', $mPindah->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($mPindah->CA_RCVDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CREDT', 'Tarikh Cipta', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CREDT', $mPindah->CA_CREDT != '' ? date('d-m-Y h:i A', strtotime($mPindah->CA_CREDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>MAKLUMAT ADUAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_RCVTYP', Ref::GetList('259', true, 'ms'), $mPindah->CA_RCVTYP, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    <!--{{-- Form::text('CA_RCVBY', $mPindah->CA_RCVBY != '' ? $mPindah->namapenerima->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                    {{ Form::text('CA_RCVBY', count($mPindah->namaPenerima) == '1' ? $mPindah->namaPenerima->name : $mPindah->CA_RCVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_SUMMARY', 'Aduan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_SUMMARY', $mPindah->CA_SUMMARY, ['class' => 'form-control input-sm', 'readonly' => true,'rows'=>4]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>PENUGASAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('', 'Penugasan Aduan Oleh', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <!--{{-- Form::text('', $mPindah->CA_ASGBY != '' ? $mPindah->penugasanpindaholeh->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                    {{ Form::text('', count($mPindah->penugasanpindaholeh) == '1' ? $mPindah->penugasanpindaholeh->name : $mPindah->CA_ASGBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <!--{{-- Form::text('CA_INVBY', $mPindah->CA_INVBY != '' ? $mPindah->namapenyiasat->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                    {{ Form::text('CA_INVBY', count($mPindah->namapenyiasat) == '1' ? $mPindah->namapenyiasat->name : $mPindah->CA_INVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGDT', 'Tarikh Penugasan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ASGDT', $mPindah->CA_ASGDT != '' ? date('d-m-Y h:i A', strtotime($mPindah->CA_ASGDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEDT', 'Tarikh Selesai', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEDT', $mPindah->CA_CLOSEDT != '' ? date('d-m-Y h:i A', strtotime($mPindah->CA_CLOSEDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>SIASATAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('', 'Status Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('', Ref::GetList('292', true, 'ms'), $mPindah->CA_INVSTS, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_PREMISE', 'Jenis Premis', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), $mPindah->CA_AGAINST_PREMISE, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEBY', 'Ditutup Oleh', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    <!--{{-- Form::text('CA_CLOSEBY', $mPindah->CA_CLOSEBY != '' ? $mPindah->ditutupoleh->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                    {{ Form::text('CA_CLOSEBY', count($mPindah->CA_CLOSEBY) == '1' ? $mPindah->ditutupoleh->name : $mPindah->CA_CLOSEBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCAT', 'Kategori Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms'), $mPindah->CA_CMPLCAT, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCD', Ref::GetList('634', true, 'ms'), $mPindah->CA_CMPLCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEDT', 'Tarikh Ditutup', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEDT', $mPindah->CA_CLOSEDT != '' ? date('d-m-Y h:i A', strtotime($mPindah->CA_CLOSEDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_RESULT', 'Hasil Siasatan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_RESULT', old('CA_RESULT', $mPindah->CA_RESULT), ['class' => 'form-control input-sm', 'readonly' => true,'rows'=>4]) }}
                            </div>
                        </div>
                    </div>
                    </div>
                    
                    {!! Form::close() !!}
                </div>
            </div>
            <div id="adu-diadu" class="tab-pane">
                <div class="panel-body">
                    {!! Form::open(['class' => 'form-horizontal']) !!}
                        <h4>MAKLUMAT PENGADU</h4>
                        <div class="hr-line-solid"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_NAME', 'Nama Pengadu', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_NAME', $mPindah->CA_NAME, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), $mPindah->CA_SEXCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                  <div class="form-group">
                                    {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_AGE', Ref::GetList('309', true, 'ms'), $mPindah->CA_AGE, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_MOBILENO', $mPindah->CA_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_TELNO', 'No. Telefon', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_TELNO', $mPindah->CA_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_FAXNO', $mPindah->CA_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_EMAIL', $mPindah->CA_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_DOCNO', $mPindah->CA_DOCNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                     <div class="form-group">
                                    {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), $mPindah->CA_RACECD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        @if ($mPindah->CA_NATCD =='1')
                                               {{ Form::text('CA_NATCD', 'Warganegara', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                            @elseif ($mPindah->CA_NATCD =='0')
                                        {{ Form::text('CA_NATCD', ' Bukan Warganegara', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        @endif
                                    </div>
                                </div>
                                <!--<div class="row">-->
                                <div class="form-group">
                                     {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                       {{ Form::textarea('CA_ADDR', $mPindah->CA_ADDR, ['class' => 'form-control input-sm', 'readonly' => true, 'rows' => 3]) }}
                                    </div>
                                </div>
                                @if ($mPindah->CA_NATCD =='1')
                                    <div class="form-group">
                                        {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-sm-5 control-label']) }}
                                        <div class="col-sm-7">
                                            {{ Form::text('CA_POSCD', $mPindah->CA_POSCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-sm-5 control-label']) }}
                                        <div class="col-sm-7">
                                            {{ Form::select('CA_DISTCD',Ref::GetList('18', true, 'ms'), $mPindah->CA_DISTCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-5 control-label']) }}
                                        <div class="col-sm-7">
                                            {{ Form::select('CA_STATECD', Ref::GetList('17', true, 'ms'), $mPindah->CA_STATECD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                        </div>
                                    </div>
                                @elseif ($mPindah->CA_NATCD =='0')
                                    <div class="form-group">
                                        {{ Form::label('CA_COUNTRYCD', 'Negara', ['class' => 'col-sm-5 control-label']) }}
                                        <div class="col-sm-7">
                                            {{ Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), $mPindah->CA_COUNTRYCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                        </div>
                                    </div>
                                @endif
                                <!--</div>-->
                            </div>
                        </div>
                        <h4>MAKLUMAT DIADU</h4>
                        <div class="hr-line-solid"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINSTNM', 'Nama Diadu', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_AGAINSTNM', $mPindah->CA_AGAINSTNM, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                             <div class="form-group">
                                    {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_AGAINST_MOBILENO', $mPindah->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_AGAINST_TELNO', $mPindah->CA_AGAINST_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_AGAINST_FAXNO', $mPindah->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_AGAINST_EMAIL', $mPindah->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                    <div class="form-group">
                                    {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::textarea('CA_AGAINSTADD', $mPindah->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'readonly' => true,'rows'=>3]) }}
                                    </div>
                                </div>
                                 <div class="form-group">
                                    {{ Form::label('CA_AGAINST_POSTCD', 'Poskod', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_POSTCD', $mPindah->CA_AGAINST_POSTCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                 <div class="form-group">
                                    {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_AGAINST_DISTCD',  Ref::GetList('18', true, 'ms'),$mPindah->CA_AGAINST_DISTCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                 <div class="form-group">
                                    {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::select('CA_AGAINST_STATECD',  Ref::GetList('17', true, 'ms'),$mPindah->CA_AGAINST_STATECD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div id="attachment" class="tab-pane">
                <div class="panel-body">
                    <h4>BAHAN BUKTI</h4>
                    <div class="hr-line-solid"></div>
                    <div class="table-responsive">
                        <table id="pindah-aduan-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Nama Fail</th>
                                    <th>Catatan</th>
                                    <th>Tarikh Muatnaik</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <h4>GABUNGAN ADUAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="table-responsive">
                        <table id="pindah-aduan-gabung-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>No. Aduan</th>
                                    <th>Aduan</th>
                                    <th>Tarikh Terima</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="transaction" class="tab-pane">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="pindah-aduan-transaction-table" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Status</th>
                                    <th>Daripada</th>
                                    <th>Kepada</th>
                                    <th>Tarikh Transaksi</th>
                                    <th>Saranan</th>
                                    <th>Surat Admin</th>
                                    <th>Surat Pengadu</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Start -->
@include('aduan.pindah.usersearchmodal')
<!-- Modal End -->

@stop

@section('script_datatable')
<script type="text/javascript">
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href='+hash+']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
    
    function myFunction(id) {
        $.ajax({ 
            url: "{{ url('pindah/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success:function(data){
                console.log(data);
                document.getElementById("InvByName").value = data.name;
                document.getElementById("InvById").value = data.id;
                $('#carian-penerima').modal('hide');
            }
        });
    };
    
    $(document).ready(function(){
        
        $('#UserSearchModal').on('click', function (e) {
            $("#carian-penerima").modal();
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

        $('select[name="CA_BR_STATECD"]').on('change', function (e) {
            var CA_BR_STATECD = $(this).val();
            if(CA_BR_STATECD){
                $.ajax({
                    type:'GET',
                    url:"{{ url('user/getbrnlist') }}" + "/" + CA_BR_STATECD,
                    dataType: "json",
                    success:function(data){
                        $('select[name="CA_BRNCD"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="CA_BRNCD"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            } else {
                $('select[name="CA_BRNCD"]').empty();
                $('select[name="CA_BRNCD"]').append('<option>-- SILA PILIH --</option>');
                $('select[name="CA_BRNCD"]').trigger('change');
            }
        });
        
        $('#resetbtn').on('click', function(e) {
            document.getElementById("search-form").reset();
            oTable.draw();
            e.preventDefault();
        });

        $('#search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
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
                url: "{{ url('pindah/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd').val();
                    d.brn_cd = $('#brn_cd').val();
                    d.role_cd = $('#role_cd').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
//                {data: 'count_case', name: 'count_case'},
                {data: 'descr', name: 'descr'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });

        $('select[name="CD_REASON"]').on('change', function () {
            switch ($(this).val()) {
                case 'P2':
                    $('#data_5').show();
                    break;
                default:
                    $('#data_5').hide();
                    break;
            }
        });

        $('#data_5 .input-daterange').datepicker({
            autoclose: true,
            calendarWeeks: true,
            forceParse: false,
            format: 'dd-mm-yyyy',
            keyboardNavigation: false,
            todayBtn: "linked",
            todayHighlight: true,
            weekStart: 1,
        });
    });
    
    $('#CA_INVSTS').on('change', function (e) {
        if($(this).val() === '4'){
            $('#camagncd').show();
            $('#ca_br_statecd').hide();
            $('#ca_brncd').hide();
        } else if($(this).val() === '0'){
            $('#camagncd').hide();
            $('#ca_br_statecd').show();
            $('#ca_brncd').show();
        } else {
            $('#camagncd').hide();
            $('#ca_br_statecd').hide();
            $('#ca_brncd').hide();
        }

        // if($(this).val() === '4' || $(this).val() === '5')
        // {
        //     document.getElementById("UserSearchModal").disabled = true;
        //     $( "label[for='CA_INVBY']" ).removeClass( "required" );
        // }else{
        //     document.getElementById("UserSearchModal").disabled = false;
        //     $( "label[for='CA_INVBY']" ).addClass( "required" );
        // }
        if($(this).val() === '4' || $(this).val() === '5')
            $('#div_CA_ANSWER').show();
        else
            $('#div_CA_ANSWER').hide();

        switch ($(this).val()) {
            case '4':
            case '5':
            case '0':
                $('#countduration').show();
                $('#cd_reason').show();
                $('select[name="CD_REASON"] option:selected').trigger('change');
                break;
            default:
                $('#countduration').hide();
                $('#cd_reason').hide();
                $('#data_5').hide();
                break;
        }
    });

    $('#CA_CMPLCAT').on('change', function (e) {
        var CA_CMPLCAT = $(this).val();
        if(CA_CMPLCAT){
            $.ajax({
                type: 'GET',
                url: "{{ url('tugas/getcmpllist') }}" + "/" + CA_CMPLCAT,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_CMPLCD"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
        } else {
            $('select[name="CA_CMPLCD"]').empty();
            $('select[name="CA_CMPLCD"]').append('<option value="">-- SILA PILIH --</option>');
            $('select[name="CA_CMPLCD"]').trigger('change');
        }
    });
    
    $('#pindah-aduan-attachment-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        info: false,
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
            url: "{{ url('pindah/getdatatableattachment', $mPindah->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id_no', 'width': '5%', searchable: false, orderable: false},
//            {data: 'doc_title', name: 'doc_title', orderable: false},
//            {data: 'file_name_sys', name: 'file_name_sys', orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'},
            {data: 'updated_at', name: 'updated_at', orderable: false}
//            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
    
    $('#pindah-aduan-gabung-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        info: false,
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
                last: 'Terakhir',
            },
        },
        ajax: {
            url: "{{ url('pindah/getdatatablemergecase', $mPindah->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CA_CASEID', name: 'CA_CASEID'},
            {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
            {data: 'CA_RCVDT', name: 'CA_RCVDT'}
        ]
    });
    
    $('#pindah-aduan-transaction-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
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
            url: "{{ url('pindah/getdatatabletransaction', $mPindah->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CD_INVSTS', name: 'CD_INVSTS'},
            {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
            {data: 'CD_ACTTO', name: 'CD_ACTTO'},
            {data: 'CD_CREDT', name: 'CD_CREDT'},
            {data: 'CD_DESC', name: 'CD_DESC'},
            {data: 'CD_DOCATTCHID_ADMIN', name: 'CD_DOCATTCHID_ADMIN'},
            {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'}
        ]
    });
    
</script>
@stop