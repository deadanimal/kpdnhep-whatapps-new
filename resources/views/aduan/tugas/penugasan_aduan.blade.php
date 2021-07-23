@extends('layouts.main')
<?php
    use App\Ref;
    use App\Penugasan;
    use Carbon\Carbon;
    use App\Aduan\Penyiasatan;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
    .help-block-red {
        color: red;
    }
</style>
<h2>Penugasan Aduan</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#penugasan"> Penugasan</a></li>
            <li class=""><a data-toggle="tab" href="#case-info"> Maklumat Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li>
            <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li>
            <!--<li class=""><a data-toggle="tab" href="#letter">Surat</a></li>-->
            <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li>
        </ul>
        <div class="tab-content">
            <div id="penugasan" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['url' => '/tugas/tugas-kepada/'.$mPenugasan->CA_CASEID,  'class'=>'form-horizontal', 'method' => 'POST']) !!}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{-- Form::text('CA_CASEID', $mPenugasan->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) --}}
                                    <p class="form-control-static">{{ $mPenugasan->CA_CASEID }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_FILEREF', 'No. Rujukan Fail', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{ Form::text('CA_FILEREF', $mPenugasan->CA_FILEREF, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_ASGBY', 'Penugasan Aduan Oleh', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{-- Form::text('CA_ASGBY', $mUser->name, ['class' => 'form-control input-sm','readonly' => true]) --}}
                                    <p class="form-control-static">{{ $mUser->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            @if($mBukaSemula)
                                <div class="form-group">
                                    {{ Form::label('','No. Aduan Sebelum Dibuka Semula', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        <a onclick="ShowSummary('{{ $mBukaSemula->CF_CASEID }}')">{{ $mBukaSemula->CF_CASEID }}</a>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group{{ $errors->has('CA_INVSTS') ? ' has-error' : '' }}">
                                {{ Form::label('CA_INVSTS', 'Status Aduan', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ Form::select('CA_INVSTS', Penugasan::GetStatusList(), $mPenugasan->CA_INVSTS, ['class' => 'form-control input-sm', 'id' => 'CA_INVSTS']) }}
                                    @if ($errors->has('CA_INVSTS'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_INVSTS') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="magncd" style="display: {{ $errors->has('CA_MAGNCD')||$mPenugasan->CA_INVSTS == '4' ? 'block' : 'none' }} ;">
                                <div class="form-group{{ $errors->has('CA_MAGNCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_MAGNCD', 'Agensi', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_MAGNCD', Penyiasatan::GetMagncdList(), $mPenugasan->CA_MAGNCD, ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_MAGNCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_MAGNCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="div_BLOCK" style="display: {{ old('CA_INVSTS') != '' ? 'block' : 'none' }}">
                                <div id="div_CA_CMPLCAT" class="form-group{{ $errors->has('CA_CMPLCAT') ? ' has-error' : '' }}" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->CA_INVSTS == '1' || $mPenugasan->CA_INVSTS == '0' ? 'block' : 'none')) }}">
                                    {{ Form::label('CA_CMPLCAT', 'Kategori', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms', 'descr'), old('CA_CMPLCAT', $mPenugasan->CA_CMPLCAT), ['class' => 'form-control input-sm required', 'id' => 'CA_CMPLCAT']) }}
                                        <!--{{-- Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms', 'descr'), old('CA_CMPLCAT') == ''? $mPenugasan->CA_CMPLCAT : old('CA_CMPLCAT'), ['class' => 'form-control input-sm required', 'id' => 'CA_CMPLCAT']) --}}-->
                                        @if ($errors->has('CA_CMPLCAT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_CMPLCD" class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->CA_INVSTS == '1' || $mPenugasan->CA_INVSTS == '0' ? 'block' : 'none')) }}">
                                    {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('CA_CMPLCD', $mPenugasan->CA_CMPLCAT == '' ? ['-- SILA PILIH --'] : Penugasan::getcmplcdlist($mPenugasan->CA_CMPLCAT), old('CA_CMPLCD', $mPenugasan->CA_CMPLCD), ['class' => 'form-control input-sm']) }}
                                        {{-- Form::select('CA_CMPLCD', Ref::GetList('634', true, 'ms'), $mPenugasan->CA_CMPLCD, ['class' => 'form-control input-sm']) --}}
                                        @if ($errors->has('CA_CMPLCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <!--<div id="div_CA_INVBY" style="display: {{-- old('CA_INVSTS') == '2' || $mPenugasan->CA_INVSTS == '1' ? 'block':'none' --}}" class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">-->
                                <div id="div_CA_INVBY" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->CA_INVSTS == '1' || $mPenugasan->CA_INVSTS == '0' ? 'block' : 'none')) }}" class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_INVBY','Pegawai Penyiasat/Serbuan', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        <div class="input-group">
                                            {{ Form::text('CA_INVBY_NAME', $mPenugasan->CA_INVBY != '' ? $mPenugasan->namapenyiasat->name : '', ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'InvByName'])}}
                                            {{ Form::hidden('CA_INVBY', $mPenugasan->CA_INVBY, ['class' => 'form-control input-sm', 'id' => 'InvById']) }}
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-sm" id="UserSearchModal">Carian</button>
                                            </span>
                                        </div>
                                        @if ($errors->has('CA_INVBY'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_INVBY') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div id="div_CA_INVBYOTH" class="form-group" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->CA_INVSTS == '1' || $mPenugasan->CA_INVSTS == '0' ? 'block' : 'none')) }}">
                                    {{ Form::label('', 'Pegawai-pegawai Lain', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        <button type="button" class="btn btn-primary btn-sm" id="MultiUserSearchModal">Carian</button>
                                    </div>
                                </div>
                                <div class="form-group" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->CA_INVSTS == '1' || $mPenugasan->CA_INVSTS == '0' ? 'block' : 'none')) }}">
                                    {{ Form::label('', '', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        <div id="recipient1"></div>
                                        <div id="recipient2"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="countduration"
                                class="form-group"
                                style="display: {{ !empty(old('CA_INVSTS')) ? 'block' : 'none' }} ;">
                                {{ Form::label('', 'Hari', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <p class="form-control-static">{{ $countDuration }}</p>
                                    {{ Form::hidden('CD_REASON_DURATION', $countDuration, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            @if($countDuration >= 4)
                            <div id="cd_reason"
                                class="form-group {{ $errors->has('CD_REASON') ? ' has-error' : '' }}"
                                style="display: {{ $errors->has('CD_REASON') || !empty(old('CA_INVSTS')) ? 'block' : 'none' }} ;">
                                {{ Form::label('CD_REASON', 'Alasan', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ Form::select('CD_REASON', $caseReasonTemplates, null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                                    @if ($errors->has('CD_REASON'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_REASON') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="data_5"
                                class="form-group {{ $errors->has('CD_REASON_DATE_FROM') || $errors->has('CD_REASON_DATE_TO') ? ' has-error' : '' }}"
                                style="display: {{
                                    ($errors->has('CD_REASON_DATE_FROM') || $errors->has('CD_REASON_DATE_TO'))
                                    || !empty(old('CA_INVSTS')) && old('CD_REASON') == 'P2'
                                    ? 'block' : 'none'
                                }} ;">
                                {{ Form::label('CD_REASON_DATE_FROM', 'Tarikh', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
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
                            <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SUMMARY', 'Aduan', ['class' => 'col-lg-2 control-label required']) }}
                                <div class="col-lg-10">
                                    {{ Form::textarea('CA_SUMMARY', old('CA_SUMMARY', $mPenugasan->CA_SUMMARY), ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                    <span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>
                                    @if ($errors->has('CA_SUMMARY'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_SUMMARY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CD_DESC') ? ' has-error' : '' }}" id="div_CD_DESC" style="display: {{ (old('CA_INVSTS') ? (old('CA_INVSTS') == '2' ? 'block':'none') : 'none') }}">
                                {{ Form::label('CD_DESC', 'Saranan', ['class' => 'col-lg-2 control-label required']) }}
                                <div class="col-lg-10">
                                    {{ Form::textarea('CD_DESC', (!empty($mPenugasanDetail)) ? $mPenugasanDetail->CD_DESC : '', ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                    @if ($errors->has('CD_DESC'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_DESC') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_ANSWER') ? ' has-error' : '' }}" id="div_CA_ANSWER" style="display: {{ old('CA_INVSTS') ? (old('CA_INVSTS') != '2' ? 'block':'none') : 'none' }}">
                                {{ Form::label('CA_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-lg-2 control-label required']) }}
                                <div class="col-lg-10">
                                    {{ Form::textarea('CA_ANSWER', '', ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                    @if ($errors->has('CA_ANSWER'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ANSWER') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-sm-12" align="center">
                                {{ Form::submit('Hantar', ['class' => 'btn btn-primary btn-sm']) }}
                                {{-- Form::submit('Cetak Surat', ['class' => 'btn btn-success btn-sm']) --}}
                                <a href="{{ url('tugas')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
            <div id="case-info" class="tab-pane">
                <div class="panel-body">
                    {!! Form::open(['id' => 'case-info-form', 'class' => 'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CASEID','No. Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CASEID', $mPenugasan->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_BRNCD','Nama Cawangan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_BRNCD', $mPenugasan->namaCawangan->BR_BRNNM, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CASEID','No Rujukan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVDT','Tarikh Penerimaan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_RCVDT', $mPenugasan->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->CA_RCVDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CREDT','Tarikh Cipta ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CREDT', $mPenugasan->CA_CREDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->CA_CREDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>MAKLUMAT ADUAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::select('CA_RCVTYP', Ref::GetList('259', true), $mPenugasan->CA_RCVTYP, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    {{ Form::text('CA_RCVTYP', $mPenugasan->CA_RCVTYP != ''? Ref::GetDescr('259', $mPenugasan->CA_RCVTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_RCVTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('CA_RCVBY', $mPenugasan->CA_RCVBY,  ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    {{ Form::text('CA_RCVBY', count($mPenugasan->namapenerima) == '1'? $mPenugasan->namapenerima->name : $mPenugasan->CA_RCVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_RCVTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_SUMMARY','Aduan ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_SUMMARY', $mPenugasan->CA_SUMMARY, ['class' => 'form-control input-sm','readonly' => true,'rows' => 5]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>PENUGASAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGBY','Penugasan Aduan Oleh  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ASGBY', $mPenugasan->CA_ASGBY != '' ? $mPenugasan->CA_ASGBY : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_INVBY','Pegawai Penyiasat/Serbuan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_INVBY', $mPenugasan->CA_INVBY != '' ? $mPenugasan->namapenyiasat->name : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGDT','Tarikh Penugasan  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ASGDT', $mPenugasan->CA_ASGDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->CA_ASGDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEDT','Tarikh Selesai ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEDT', $mPenugasan->CA_CLOSEDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->CA_CLOSEDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>SIASATAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_INVSTS','Status Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_INVSTS', Ref::GetList('292', true, 'ms'), old('CA_INVSTS', $mPenugasan->CA_INVSTS), ['class' => 'form-control input-sm', 'id' => 'CA_INVSTS','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_PREMISE','Kod Premis ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), old('CA_AGAINST_PREMISE', $mPenugasan->CA_AGAINST_PREMISE), ['class' => 'form-control input-sm','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEBY','Ditutup Oleh ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEBY', $mPenugasan->CA_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCAT','Kategori Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms'), old('CA_CMPLCAT', $mPenugasan->CA_CMPLCAT), ['class' => 'form-control input-sm required', 'id' => 'CA_CMPLCAT','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCD','SubKategori ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCD', $mPenugasan->CA_CMPLCD == ''? ['-- SILA PILIH --'] : Ref::GetList('634', true, 'ms'), old('CA_CMPLCD', $mPenugasan->CA_CMPLCD), ['class' => 'form-control input-sm','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEBY','Tarikh Ditutup ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEBY', $mPenugasan->CA_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_RESULT','Hasil Siasatan ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_RESULT', $mPenugasan->CA_RESULT, ['class' => 'form-control input-sm','readonly' => true,'rows' => 5]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div id="adu-diadu" class="tab-pane">
                <div class="panel-body">
                    {!! Form::open(['id' => 'case-info-form', 'class' => 'form-horizontal']) !!}
                    <h4>MAKLUMAT PENGADU</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_NAME','Nama Pengadu ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_NAME', $mPenugasan->CA_NAME, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_SEXCD','Jantina ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_SEXCD', Ref::GetList('202 ', true), $mPenugasan->CA_SEXCD, ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_ADDR','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::textarea('CA_ADDR', $mPenugasan->CA_ADDR, ['class' => 'form-control input-sm','rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_DOCNO','No. KP atau Passport ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_DOCNO', $mPenugasan->CA_DOCNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_NATCD','Warganegara ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_NATCD', Ref::GetList('199 ', true), $mPenugasan->CA_NATCD, ['class' => 'form-control input-sm', 'id' => 'CA_NATCD', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_TELNO', $mPenugasan->CA_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_MOBILENO', 'No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_MOBILENO', $mPenugasan->CA_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_EMAIL', $mPenugasan->CA_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>MAKLUMAT YANG DIADU</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_AGAINSTNM','Nama ', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINSTNM', $mPenugasan->CA_AGAINSTNM, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINSTADD','Alamat ', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('CA_AGAINSTADD', $mPenugasan->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($mPenugasan->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};">
                                {{ Form::label('CA_TTPMTYP', 'Penuntut/Penentang', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_TTPMTYP', $mPenugasan->CA_TTPMTYP != ''? Ref::GetDescr('1108', $mPenugasan->CA_TTPMTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($mPenugasan->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};">
                                {{ Form::label('CA_TTPMNO', 'No. TTPM', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_TTPMNO', old('CA_TTPMNO', $mPenugasan->CA_TTPMNO), ['class' => 'form-control input-sm', 'readonly' => true])}}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_AMOUNT', $mPenugasan->CA_ONLINECMPL_AMOUNT, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($mPenugasan->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none'))) }};">
                                {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_CMPLKEYWORD', $mPenugasan->CA_CMPLKEYWORD != ''? Ref::GetDescr('1051', $mPenugasan->CA_CMPLKEYWORD, 'ms'):'', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($mPenugasan->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};">
                                {{ Form::label('CA_AGAINST_PREMISE', 'Jenis Premis', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_AGAINST_PREMISE', $mPenugasan->CA_AGAINST_PREMISE != ''? Ref::GetDescr('221', $mPenugasan->CA_AGAINST_PREMISE, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_PROVIDER', $mPenugasan->CA_ONLINECMPL_PROVIDER != ''? Ref::GetDescr('1091', $mPenugasan->CA_ONLINECMPL_PROVIDER, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('CA_ONLINECMPL_URL', 'Laman Web / URL / ID', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_URL', $mPenugasan->CA_ONLINECMPL_URL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('CA_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_PYMNTTYP', $mPenugasan->CA_ONLINECMPL_PYMNTTYP != ''? Ref::GetDescr('1207', $mPenugasan->CA_ONLINECMPL_PYMNTTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('CA_ONLINECMPL_BANKCD', 'Nama Bank', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_BANKCD', $mPenugasan->CA_ONLINECMPL_BANKCD != ''? Ref::GetDescr('1106', $mPenugasan->CA_ONLINECMPL_BANKCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun Bank / No. Transaksi FPX', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_ACCNO', $mPenugasan->CA_ONLINECMPL_ACCNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'? 'block':($mPenugasan->CA_CMPLCAT == 'BPGK 19' && $mPenugasan->CA_ONLINECMPL_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $mPenugasan->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINECMPL_IND') == '' && $mPenugasan->CA_ONLINECMPL_IND == '1'? 'block':(old('CA_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;">
                                {{ Form::label('CA_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_ONLINECMPL_CASENO', $mPenugasan->CA_ONLINECMPL_CASENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_TELNO', $mPenugasan->CA_AGAINST_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_MOBILENO', 'No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_MOBILENO', $mPenugasan->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_FAXNO','No. Faks ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_FAXNO', $mPenugasan->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_EMAIL', $mPenugasan->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
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
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="table-responsive">
                        <table id="penugasan-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Nama Fail</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <h4>GABUNGAN ADUAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="table-responsive">
                        <table id="penugasan-gabung-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                        <table style="width: 100%" id="sorotan-table" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Status</th>
                                    <th>Daripada</th>
                                    <th>Kepada</th>
                                    <th>Saranan</th>
                                    <th>Tarikh Transaksi</th>
                                    <th>Surat Pengadu</th>
                                    <th>Surat Pegawai</th>
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
@include('aduan.tugas.usersearchmodal')
@include('aduan.tugas.multiusersearchmodal')
<!-- Modal End -->
<!-- Modal Start -->
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
<!-- Modal End -->
@stop

@section('script_datatable')
<script type="text/javascript">
    function do_remove(user_idx) {
        var oRep = oMembers;
        
        var arr_persons = oRep.arr_persons;
        for(var i=0; i < arr_persons.length; i++) {
            if (user_idx == arr_persons[i].user_id) {
                console.log(i);
                arr_persons.splice(i, 1);
            }
        }
        oRep.arr_persons = arr_persons;
        
        
        var arr_persons = oMembers.arr_persons;
        //alert('koko'+arr_persons.length);
        var obj1 = $('#recipient1').html(''); // 
        var obj2 = $('#recipient2').html(''); //

        for(var i=0; i < arr_persons.length; i++) {
            str = "<input type='hidden' name='recipient[]' value='"+arr_persons[i].user_id+"' />";
            obj1.append(str);
            str = arr_persons[i].name + " <a href='#' onclick='do_remove(\""+arr_persons[i].user_id+"\")'> </a> <br />";
            obj2.append(str);
        }
        
    }
    
    function ShowSummary(CASEID)
    {
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
    }

    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href=' + hash + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    $('#CA_INVSTS').on('change', function (e) {
        var CA_INVSTS = $(this).val();
        if(CA_INVSTS === ''){
            $('#div_BLOCK').hide();
            $('#countduration').hide();
            $('#cd_reason').hide();
            $('#data_5').hide();
        } else {
            $('#div_BLOCK').show();
            $('#countduration').show();
            $('#cd_reason').show();
            $('select[name="CD_REASON"] option:selected').trigger('change');
        }
        if(CA_INVSTS === '4')
            $('#magncd').show();
        else
            $('#magncd').hide();
        if(CA_INVSTS == 2)
        {
            $('#div_CA_CMPLCAT').show();
            $('#div_CA_CMPLCD').show();
            $('#div_CA_INVBY').show();
            $('#div_CA_INVBYOTH').show();
            $('#div_CD_DESC').show();
            $('#div_CA_ANSWER').hide();
        }
        else
        {
            $('#div_CA_CMPLCAT').hide();
            $('#div_CA_CMPLCD').hide();
            $('#div_CA_INVBY').hide();
            $('#div_CA_INVBYOTH').hide();
            $('#div_CD_DESC').hide();
            $('#div_CA_ANSWER').show();
        }
    });

    $('#CA_CMPLCAT').on('change', function (e) {
        var CA_CMPLCAT = $(this).val();
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
    });

    function myFunction(id) {
        $.ajax({
            url: "{{ url('tugas/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("InvByName").value = key;
                    document.getElementById("InvById").value = value;
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
        
        $('#MultiUserSearchModal').on('click', function (e) {
            $("#carian-lain2-penerima").modal();
        });

        $('#carian-penerima').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
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
                infoFiltered: '(filtered from _MAX_ total records)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('tugas/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd_user').val();
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
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });
        
        var oTableMulti = $('#users-multi-table').DataTable({
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
                url: "{{ url('tugas/getdatatablemultiuser') }}",
                data: function (d) {
                    d.name = $('#name_multi').val();
                    d.icnew = $('#icnew_multi').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
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
            oTable.columns.adjust();
            e.preventDefault();
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        
        $('#search-form-multi').on('submit', function (e) {
            oTableMulti.draw();
            e.preventDefault();
        });
        
        $('#resetbtnmulti').on('click', function(e) {
            document.getElementById("search-form-multi").reset();
            oTableMulti.draw();
            e.preventDefault();
        });

        var SorotanTable = $('#sorotan-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
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
                url: "{{ url('/tugas/gettransaction', $mPenugasan->CA_CASEID)}}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CD_INVSTS', name: 'CD_INVSTS'},
                {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
                {data: 'CD_ACTTO', name: 'CD_ACTTO'},
                {data: 'CD_DESC', name: 'CD_DESC'},
                {data: 'CD_CREDT', name: 'CD_CREDT'},
                {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'},
                {data: 'CD_DOCATTCHID_ADMIN', name: 'CD_DOCATTCHID_ADMIN'},
            ]
        });

        var GabungTable = $('#penugasan-gabung-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            info: false,
            paging: false,
            aaSorting: [],
            bLengthChange: false,
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
                url: "{{ url('/tugas/getgabungkes', $mPenugasan->CA_CASEID) }}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CA_CASEID', name: 'CA_CASEID'},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                {data: 'CA_RCVDT', name: 'CA_RCVDT'},
            ]
        });

        var AttachmentTable = $('#penugasan-attachment-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            info: false,
            paging: false,
            aaSorting: [],
            bLengthChange: false,
            rowId: 'id',
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
                url: "{{ url('/tugas/getattachment', $mPenugasan->CA_CASEID) }}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
                {data: 'CC_REMARKS', name: 'CC_REMARKS'}
//                {data: 'doc_title', name: 'doc_title', orderable: false},
//                {data: 'file_name_sys', name: 'file_name_sys', orderable: false},
//                {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
            ]
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
</script>
@stop