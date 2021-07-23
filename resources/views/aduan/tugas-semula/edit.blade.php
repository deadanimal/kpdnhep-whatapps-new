@extends('layouts.main')
<?php
use App\Ref;
use App\Aduan\PenugasanSemula;
use App\Branch;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
</style>
<h2>Penugasan Semula Aduan</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tugas-semula">Penugasan Semula</a></li>
            <li class=""><a data-toggle="tab" href="#case-info"> Maklumat Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li>
            <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li>
        </ul>
        <div class="tab-content">
            <div id="tugas-semula" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['url' => ['tugas-semula', $mPenugasanSemula->CA_CASEID], 'id' => 'tugas-semula-form', 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CASEID', $mPenugasanSemula->CA_CASEID, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_FILEREF', 'No. Rujukan Fail', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_FILEREF', $mPenugasanSemula->CA_FILEREF, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_ASGBY', 'Penugasan Aduan Oleh', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::hidden('CA_ASGBY', $mPenugasanSemula->CA_ASGBY) }}
                                    @if($mPenugasanSemula->CA_ASGBY != '')
                                        {{ Form::text('CA_ASGBY', $mPenugasanSemula->penugasansemulaoleh->name, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_ASGBY == '')
                                        {{ Form::text('CA_ASGBY', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            @if($mGabungAll)
                                <div class="form-group">
                                    {{ Form::label('', 'Gabung Aduan', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        @foreach ($mGabungAll as $gabung)
                                            <span class="help-block m-b-none">{{ $gabung->CR_CASEID }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                {{ Form::label('CA_INVSTS', 'Status', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_INVSTS', Ref::GetList('292', true, 'ms'), $mPenugasanSemula->CA_INVSTS, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_CMPLCAT') ? ' has-error' : '' }}">
                                {{ Form::label('CA_CMPLCAT', 'Kategori Aduan', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms'), old('CA_CMPLCAT', $mPenugasanSemula->CA_CMPLCAT), ['class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT']) }}
                                    @if ($errors->has('CA_CMPLCAT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_CMPLCAT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_CMPLCD', 'Subkategori Aduan', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_CMPLCD', $mPenugasanSemula->CA_CMPLCAT == '' ? ['-- SILA PILIH --'] : PenugasanSemula::getcmplcdlist($mPenugasanSemula->CA_CMPLCAT), old('CA_CMPLCD', $mPenugasanSemula->CA_CMPLCD), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('CA_CMPLCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_CMPLCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        {{ Form::hidden('CA_INVBY', old('CA_INVBY', $mPenugasanSemula->CA_INVBY), ['class' => 'form-control input-sm', 'id' => 'CA_INVBY_id']) }}
                                        @if($mPenugasanSemula->CA_INVBY != '')
                                            {{ Form::text('CA_INVBY_NAME', old('CA_INVBY_NAME', $mPenugasanSemula->namapenyiasat ? $mPenugasanSemula->namapenyiasat->name : $mPenugasanSemula->CA_INVBY), ['class' => 'form-control input-sm', 'readonly' => true, 'id' => 'CA_INVBY_name']) }}
                                        @elseif($mPenugasanSemula->CA_INVBY == '')
                                            {{ Form::text('CA_INVBY_NAME', old('CA_INVBY_NAME', ''), ['class' => 'form-control input-sm', 'readonly' => true, 'id' => 'CA_INVBY_name']) }}
                                        @endif
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
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_SUMMARY', 'Aduan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_SUMMARY', $mPenugasanSemula->CA_SUMMARY, ['class' => 'form-control input-sm', 'readonly' => true, 'rows'=>3]) }}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CD_DESC') ? ' has-error' : '' }}">
                                {{ Form::label('CD_DESC', 'Saranan', ['class' => 'col-sm-2 control-label required']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CD_DESC', '', ['class' => 'form-control input-sm', 'rows'=>3]) }}
                                    @if ($errors->has('CD_DESC'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_DESC') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group col-sm-12" align="center">
                                {{ Form::submit(trans('button.send'), ['class' => 'btn btn-success btn-sm']) }}
                                {{ link_to('tugas-semula', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                            </div>
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
                                    {{ Form::text('CA_CASEID', $mPenugasanSemula->CA_CASEID, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_BRNCD', 'Kod Cawangan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_BRNCD', $mPenugasanSemula->CA_BRNCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('', 'No Rujukan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVDT', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    @if($mPenugasanSemula->CA_RCVDT != '')
                                        {{ Form::text('CA_RCVDT', date('d-m-Y h:i A', strtotime($mPenugasanSemula->CA_RCVDT)), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_RCVDT == '')
                                        {{ Form::text('CA_RCVDT', '', ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CREDT', 'Tarikh Cipta', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    @if($mPenugasanSemula->CA_CREDT != '')
                                        {{ Form::text('CA_CREDT', date('d-m-Y h:i A', strtotime($mPenugasanSemula->CA_CREDT)), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_CREDT == '')
                                        {{ Form::text('CA_CREDT', '', ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                    @endif
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
                                    {{ Form::select('CA_RCVTYP', Ref::GetList('259', true, 'ms'), $mPenugasanSemula->CA_RCVTYP, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_RCVBY', $RcvBy, ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_SUMMARY', 'Aduan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_SUMMARY', $mPenugasanSemula->CA_SUMMARY, ['class' => 'form-control input-sm', 'readonly' => true, 'rows'=>3]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>PENUGASAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGBY', 'Penugasan Aduan Oleh', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    @if($mPenugasanSemula->CA_ASGBY != '')
                                        {{ Form::text('', $mPenugasanSemula->penugasansemulaoleh->name, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_ASGBY == '')
                                        {{ Form::text('', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    @if($mPenugasanSemula->CA_INVBY != '')
                                        {{ Form::text('', $mPenugasanSemula->namapenyiasat ? $mPenugasanSemula->namapenyiasat->name : $mPenugasanSemula->CA_INVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_INVBY == '')
                                        {{ Form::text('', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGDT', 'Tarikh Penugasan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    @if($mPenugasanSemula->CA_ASGDT != '')
                                        {{ Form::text('CA_ASGDT', date('d-m-Y h:i A', strtotime($mPenugasanSemula->CA_ASGDT)), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_ASGDT == '')
                                        {{ Form::text('CA_ASGDT', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEDT', 'Tarikh Selesai', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    @if($mPenugasanSemula->CA_CLOSEDT != '')
                                        {{ Form::text('CA_CLOSEDT', date('d-m-Y h:i A', strtotime($mPenugasanSemula->CA_CLOSEDT)), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_CLOSEDT == '')
                                        {{ Form::text('CA_CLOSEDT', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>SIASATAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_INVSTS', 'Status Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_INVSTS', Ref::GetList('292', true, 'ms'), $mPenugasanSemula->CA_INVSTS, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_PREMISE', 'Kod Premis', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), $mPenugasanSemula->CA_AGAINST_PREMISE, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEBY', 'Ditutup Oleh', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    @if($mPenugasanSemula->CA_CLOSEBY != '')
                                        {{ Form::text('CA_CLOSEBY', $mPenugasanSemula->ditutupoleh->name, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_CLOSEBY == '')
                                        {{ Form::text('CA_CLOSEBY', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('', 'Kategori Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('', Ref::GetList('244', true, 'ms'), $mPenugasanSemula->CA_CMPLCAT, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('', 'Subkategori', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('', $mPenugasanSemula->CA_CMPLCAT == '' ? ['-- SILA PILIH --'] : PenugasanSemula::getcmplcdlist($mPenugasanSemula->CA_CMPLCAT), $mPenugasanSemula->CA_CMPLCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEDT', 'Tarikh Ditutup', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    @if($mPenugasanSemula->CA_CLOSEDT != '')
                                        {{ Form::text('CA_CLOSEDT', date('d-m-Y h:i A', strtotime($mPenugasanSemula->CA_CLOSEDT)), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @elseif($mPenugasanSemula->CA_CLOSEDT == '')
                                        {{ Form::text('CA_CLOSEDT', '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('CA_RESULT') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RESULT', 'Hasil Siasatan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_RESULT', $mPenugasanSemula->CA_RESULT, ['class' => 'form-control input-sm', 'readonly' => true, 'rows'=>3]) }}
                                    @if ($errors->has('CA_RESULT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RESULT') }}</strong></span>
                                    @endif
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
                                        {{ Form::text('CA_NAME', $mPenugasanSemula->CA_NAME, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), $mPenugasanSemula->CA_RACECD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), $mPenugasanSemula->CA_SEXCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('CA_ADDR', $mPenugasanSemula->CA_ADDR, ['class' => 'form-control input-sm', 'readonly' => true, 'rows'=>4]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_DOCNO', $mPenugasanSemula->CA_DOCNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_NATCD', $mPenugasanSemula->CA_NATCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_MOBILENO', $mPenugasanSemula->CA_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_TELNO', 'No. Telefon', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_TELNO', $mPenugasanSemula->CA_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_FAXNO', $mPenugasanSemula->CA_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_EMAIL', $mPenugasanSemula->CA_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4>MAKLUMAT DIADU</h4>
                        <div class="hr-line-solid"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINSTNM', 'Nama Diadu', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_AGAINSTNM', $mPenugasanSemula->CA_AGAINSTNM, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('CA_AGAINSTADD', $mPenugasanSemula->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'readonly' => true, 'rows'=>4]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_MOBILENO', $mPenugasanSemula->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_TELNO', $mPenugasanSemula->CA_AGAINST_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_FAXNO', $mPenugasanSemula->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_EMAIL', $mPenugasanSemula->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                        <table id="penugasan-semula-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                        <table id="penugasan-semula-gabung-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                        <table id="penugasan-semula-transaction-table" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Status</th>
                                    <th>Daripada</th>
                                    <th>Kepada</th>
                                    <th>Surat Kepada Pengadu</th>
                                    <th>Tarikh Transaksi</th>
                                    <th>Saranan</th>
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

<div id="carian-penyiasat" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title">Carian Pegawai Penyiasat / Serbuan</h4>
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
                                {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{-- Form::select('state_cd', Ref::GetList('17', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'state_cd_user']) --}}
                                    {{ Form::text('state_cd_text', Ref::GetDescr('17', Auth::User()->state_cd), ['class' => 'form-control input-sm', 'id' => 'state_cd_user', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{-- Form::select('brn_cd', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) --}}
                                    {{ Form::text('brn_cd_text', Branch::GetBranchName(Auth::User()->brn_cd), ['class' => 'form-control input-sm', 'id' => 'brn_cd', 'readonly' => true]) }}
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
                                <th>Nama Pengguna</th>
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
        $('.nav-tabs a[href='+hash+']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
    
    $('#CA_CMPLCAT').on('change', function (e) {
        var CA_CMPLCAT = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('tugas-semula/getcmpllist') }}" + "/" + CA_CMPLCAT,
            dataType: "json",
            success:function(data){
                $('select[name="CA_CMPLCD"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="CA_CMPLCD"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        });
    });
    
    $(document).ready(function(){
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
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('tugas-semula/getdatatableuser') }}",
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

        $('#btn-reset').on('click', function(e) {
            document.getElementById("user-search-form").reset();
            oTable.draw();
            e.preventDefault();
        });

        $('#user-search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    
    function carianpenyiasat(id) {
        $.ajax({ 
            url: "{{ url('tugas-semula/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success:function(data){
                $.each(data, function(key, value) {
                    document.getElementById("CA_INVBY_name").value = key;
                    document.getElementById("CA_INVBY_id").value = value;
                });
                $('#carian-penyiasat').modal('hide');
            }
        });
    };
    
    $('#penugasan-semula-attachment-table').DataTable({
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
            paginate: {
                previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                first: 'Pertama',
                last: 'Terakhir'
            }
        },
        ajax: {
            url: "{{ url('tugas-semula/getdatatableattachment', $mPenugasanSemula->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id_no', 'width': '5%', searchable: false, orderable: false},
//            {data: 'doc_title', name: 'doc_title', orderable: false},
//            {data: 'file_name_sys', name: 'file_name_sys', orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'},
            {data: 'updated_at', name: 'updated_at', orderable: false},
//            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
    
    $('#penugasan-semula-gabung-table').DataTable({
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
                last: 'Terakhir',
            },
        },
        ajax: {
            url: "{{ url('tugas-semula/getdatatablemergecase', $mPenugasanSemula->CA_CASEID) }}",
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CA_CASEID', name: 'CA_CASEID'},
            {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
            {data: 'CA_RCVDT', name: 'CA_RCVDT'},
        ]
    });
    
    $('#penugasan-semula-transaction-table').DataTable({
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
            paginate: {
                previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                first: 'Pertama',
                last: 'Terakhir'
            }
        },
        ajax: {
            url: "{{ url('tugas-semula/getdatatabletransaction', $mPenugasanSemula->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CD_INVSTS', name: 'CD_INVSTS'},
            {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
            {data: 'CD_ACTTO', name: 'CD_ACTTO'},
            {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'},
            {data: 'CD_CREDT', name: 'CD_CREDT'},
            {data: 'CD_DESC', name: 'CD_DESC'}
        ]
    });
</script>
@stop