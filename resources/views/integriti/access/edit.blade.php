@extends('layouts.main')
<?php
    use App\Ref;
    use App\Penugasan;
    use Carbon\Carbon;
    use App\Aduan\Penyiasatan;
    use App\Integriti\IntegritiAdmin;
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
<h2>Akses Maklumat Pengadu (Integriti)</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#penugasan">
                    Akses Maklumat Pengadu
                </a>
            </li>
            <!-- <li class=""><a data-toggle="tab" href="#case-info"> Maklumat Aduan</a></li> -->
            <!-- <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li> -->
            <!-- <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li> -->
            <!--<li class=""><a data-toggle="tab" href="#letter">Surat</a></li>-->
            <!-- <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li> -->
        </ul>
        <div class="tab-content">
            <div id="penugasan" class="tab-pane active">
                <div class="panel-body">
                    <!-- {{-- Form::open(['url' => '/tugas/tugas-kepada/'.$mPenugasan->IN_CASEID,  'class'=>'form-horizontal', 'method' => 'POST']) --}} -->
                    {{ Form::open(['route' => ['integritiaccess.update', $mPenugasan->id], 'class' => 'form-horizontal']) }}
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <!-- {{-- Form::text('IN_CASEID', $mPenugasan->IN_CASEID, ['class' => 'form-control input-sm','readonly' => true]) --}} -->
                                    <p class="form-control-static"><strong>{{ $mPenugasan->IN_CASEID }}</strong></p>
                                </div>
                            </div>
                            <!-- <div class="form-group"> -->
                                <!-- {{-- Form::label('IN_FILEREF', 'No. Rujukan Fail', ['class' => 'col-md-4 control-label']) --}} -->
                                <!-- <div class="col-sm-8"> -->
                                    <!-- {{-- Form::text('IN_FILEREF', '', ['class' => 'form-control input-sm']) --}} -->
                                <!-- </div> -->
                            <!-- </div> -->
                            <div class="form-group">
                                {{ Form::label('IN_ASGBY', 'Penugasan Aduan Oleh', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <!-- {{-- Form::text('IN_ASGBY', $mUser->name, ['class' => 'form-control input-sm','readonly' => true]) --}} -->
                                    <p class="form-control-static">{{ $mUser->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            @if($mBukaSemula)
                                <div class="form-group">
                                    {{ Form::label('','No. Aduan Sebelum Dibuka Semula', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        @if(!empty($mBukaSemulaOld))
                                            <!-- <a onclick="ShowSummary('{{-- $mBukaSemula->IF_CASEID --}}')"> -->
                                            <a onclick="showsummaryintegriti('{{ $mBukaSemulaOld->id }}')">
                                            {{ $mBukaSemula->IF_CASEID }}
                                        @endif
                                        @if(!empty($mBukaSemulaOld))
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            
                            <div id="div_BLOCK" style="display: {{ old('IN_INVSTS') != '' ? 'block' : 'none' }}">
                                <!-- <div id="div_IN_CMPLCAT" class="form-group{{ $errors->has('IN_CMPLCAT') ? ' has-error' : '' }}" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '02' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '01' || $mPenugasan->IN_INVSTS == '02' ? 'block' : 'none')) }}"> -->
                                    <!-- {{ Form::label('IN_CMPLCAT', 'Kategori', ['class' => 'col-lg-5 control-label required']) }} -->
                                    <!-- <div class="col-lg-7"> -->
                                        <!-- {{ Form::select('IN_CMPLCAT', Ref::GetList('1344', true, 'ms', 'descr'), old('IN_CMPLCAT', $mPenugasan->IN_CMPLCAT), ['class' => 'form-control required', 'id' => 'IN_CMPLCAT']) }} -->
                                        <!--{{-- Form::select('IN_CMPLCAT', Ref::GetList('244', true, 'ms', 'descr'), old('IN_CMPLCAT') == ''? $mPenugasan->IN_CMPLCAT : old('IN_CMPLCAT'), ['class' => 'form-control input-sm required', 'id' => 'IN_CMPLCAT']) --}}-->
                                        <!-- @if ($errors->has('IN_CMPLCAT')) -->
                                        <!-- <span class="help-block"><strong>{{ $errors->first('IN_CMPLCAT') }}</strong></span> -->
                                        <!-- @endif -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <!-- <div id="div_IN_CMPLCD" class="form-group{{ $errors->has('IN_CMPLCD') ? ' has-error' : '' }}" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}"> -->
                                    <!-- {{ Form::label('IN_CMPLCD', 'Subkategori', ['class' => 'col-sm-5 control-label required']) }} -->
                                    <!-- <div class="col-sm-7"> -->
                                        <!-- {{ Form::select('IN_CMPLCD', $mPenugasan->IN_CMPLCAT == '' ? ['-- SILA PILIH --'] : Penugasan::getcmplcdlist($mPenugasan->IN_CMPLCAT), old('IN_CMPLCD', $mPenugasan->IN_CMPLCD), ['class' => 'form-control input-sm']) }} -->
                                        <!-- {{-- Form::select('IN_CMPLCD', Ref::GetList('634', true, 'ms'), $mPenugasan->IN_CMPLCD, ['class' => 'form-control input-sm']) --}} -->
                                        <!-- @if ($errors->has('IN_CMPLCD')) -->
                                        <!-- <span class="help-block"><strong>{{ $errors->first('IN_CMPLCD') }}</strong></span> -->
                                        <!-- @endif -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <!--<div id="div_IN_INVBY" style="display: {{-- old('IN_INVSTS') == '2' || $mPenugasan->IN_INVSTS == '1' ? 'block':'none' --}}" class="form-group{{ $errors->has('IN_INVBY') ? ' has-error' : '' }}">-->
                                <!-- <div id="div_IN_INVBY" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}" class="form-group{{ $errors->has('IN_INVBY') ? ' has-error' : '' }}"> -->
                                    <!-- {{ Form::label('IN_INVBY','Pegawai Penyiasat/Serbuan', ['class' => 'col-lg-5 control-label required']) }} -->
                                    <!-- <div class="col-lg-7"> -->
                                        <!-- <div class="input-group"> -->
                                            <!-- {{ Form::text('IN_INVBY_NAME', $mPenugasan->namapenyiasat ? $mPenugasan->namapenyiasat->name : $mPenugasan->IN_INVBY, ['class' => 'form-control', 'readonly' => 'true', 'id' => 'InvByName'])}} -->
                                            <!-- {{ Form::hidden('IN_INVBY', $mPenugasan->IN_INVBY, ['class' => 'form-control', 'id' => 'InvById']) }} -->
                                            <!-- <span class="input-group-btn"> -->
                                                <!-- <button type="button" class="btn btn-primary" id="UserSearchModal">Carian</button> -->
                                            <!-- </span> -->
                                        <!-- </div> -->
                                        <!-- @if ($errors->has('IN_INVBY')) -->
                                            <!-- <span class="help-block"><strong>{{ $errors->first('IN_INVBY') }}</strong></span> -->
                                        <!-- @endif -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <!-- <div id="div_IN_INVBYOTH" class="form-group" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}"> -->
                                    <!-- {{ Form::label('', 'Pegawai-pegawai Lain', ['class' => 'col-sm-5 control-label']) }} -->
                                    <!-- <div class="col-sm-7"> -->
                                        <!-- <button type="button" class="btn btn-primary btn-sm" id="MultiUserSearchModal">Carian</button> -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <!-- <div class="form-group" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}"> -->
                                    <!-- {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }} -->
                                    <!-- <div class="col-sm-7"> -->
                                        <!-- <div id="recipient1"></div> -->
                                        <!-- <div id="recipient2"></div> -->
                                    <!-- </div> -->
                                <!-- </div> -->
                            </div>
                            <!-- <div id="div_IN_SSP" style="display: {{ old('IN_INVSTS') ? (old('IN_INVSTS') == '03' ? 'block':'none') : 'none' }} "> -->
                                <div class="form-group{{ $errors->has('IN_ACCESSIND') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_ACCESSIND', 'Akses Maklumat Pengadu Kepada Pegawai Penyiasat', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        <div class="radio radio-success radio-inline">
                                            <input id="yes" type="radio" value="1" name="IN_ACCESSIND" {{ old('IN_ACCESSIND') == '1'||$mPenugasan->IN_ACCESSIND == '1'? 'checked':'' }}>
                                            <label for="yes">Ya</label>
                                        </div>
                                        <div class="radio radio-success radio-inline">
                                            <input id="no" type="radio" value="0" name="IN_ACCESSIND" {{ old('IN_ACCESSIND') == '0'||$mPenugasan->IN_ACCESSIND == '0'? 'checked':'' }}>
                                            <label for="no">Tidak</label>
                                        </div>
                                        @if ($errors->has('IN_ACCESSIND'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_ACCESSIND') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            <!-- </div> -->
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IN_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SUMMARY', 'Aduan', ['class' => 'col-lg-2 control-label required']) }}
                                <div class="col-lg-10">
                                    {{ Form::textarea('IN_SUMMARY', old('IN_SUMMARY', $mPenugasan->IN_SUMMARY), ['class' => 'form-control input-sm', 'rows' => 5, 'readonly' => true]) }}
                                    <span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>
                                    @if ($errors->has('IN_SUMMARY'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_SUMMARY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="form-group{{ $errors->has('CD_DESC') ? ' has-error' : '' }}" id="div_CD_DESC" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : 'none') }}"> -->
                                <!-- {{ Form::label('CD_DESC', 'Saranan', ['class' => 'col-lg-2 control-label required']) }} -->
                                <!-- <div class="col-lg-10"> -->
                                    <!-- {{ Form::textarea('CD_DESC', (!empty($mPenugasanDetail)) ? $mPenugasanDetail->CD_DESC : '', ['class' => 'form-control input-sm', 'rows' => 5]) }} -->
                                    <!-- @if ($errors->has('CD_DESC')) -->
                                        <!-- <span class="help-block"><strong>{{ $errors->first('CD_DESC') }}</strong></span> -->
                                    <!-- @endif -->
                                <!-- </div> -->
                            <!-- </div> -->
                            <!-- <div class="form-group{{ $errors->has('IN_ANSWER') ? ' has-error' : '' }}" id="div_IN_ANSWER" style="display: {{ old('IN_INVSTS') ? (old('IN_INVSTS') != '2' ? 'block':'none') : 'none' }}"> -->
                                <!-- {{ Form::label('IN_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-lg-2 control-label required']) }} -->
                                <!-- <div class="col-lg-10"> -->
                                    <!-- {{ Form::textarea('IN_ANSWER', '', ['class' => 'form-control input-sm', 'rows' => 5]) }} -->
                                    <!-- @if ($errors->has('IN_ANSWER')) -->
                                        <!-- <span class="help-block"><strong>{{ $errors->first('IN_ANSWER') }}</strong></span> -->
                                    <!-- @endif -->
                                <!-- </div> -->
                            <!-- </div> -->
                            <div class="form-group col-sm-12" align="center">
                                {{ Form::submit('Hantar', ['class' => 'btn btn-primary btn-sm']) }}
                                <!-- {{-- Form::submit('Cetak Surat', ['class' => 'btn btn-success btn-sm']) --}} -->
                                <a href="{{ url('integritiaccess') }}" type="button" class="btn btn-default btn-sm">Kembali</a>
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
                                {{ Form::label('IN_CASEID','No. Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CASEID', $mPenugasan->IN_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_BRNCD','Nama Cawangan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_BRNCD', '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CASEID','No Rujukan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CASEID', '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_RCVDT','Tarikh Penerimaan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_RCVDT', $mPenugasan->IN_RCVDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->IN_RCVDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CREDT','Tarikh Cipta ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CREDT', $mPenugasan->IN_CREDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->IN_CREDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>MAKLUMAT ADUAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::select('IN_RCVTYP', Ref::GetList('259', true), $mPenugasan->IN_RCVTYP, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    {{ Form::text('IN_RCVTYP', $mPenugasan->IN_RCVTYP != ''? Ref::GetDescr('259', $mPenugasan->IN_RCVTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('IN_RCVTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('IN_RCVBY', $mPenugasan->IN_RCVBY,  ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    {{ Form::text('IN_RCVBY', count($mPenugasan->namapenerima) == '1'? $mPenugasan->namapenerima->name : $mPenugasan->IN_RCVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('IN_RCVTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_SUMMARY','Aduan ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_SUMMARY', $mPenugasan->IN_SUMMARY, ['class' => 'form-control input-sm','readonly' => true,'rows' => 5]) }}
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
                                {{ Form::label('IN_ASGBY','Penugasan Aduan Oleh  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_ASGBY', $mPenugasan->IN_ASGBY != '' ? $mPenugasan->IN_ASGBY : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_INVBY','Pegawai Penyiasat/Serbuan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_INVBY', $mPenugasan->namapenyiasat ? $mPenugasan->namapenyiasat->name : $mPenugasan->IN_INVBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_ASGDT','Tarikh Penugasan  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_ASGDT', $mPenugasan->IN_ASGDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->IN_ASGDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEDT','Tarikh Selesai ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEDT', $mPenugasan->IN_CLOSEDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->IN_CLOSEDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
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
                                {{ Form::label('IN_INVSTS','Status Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_INVSTS', Ref::GetList('292', true, 'ms'), old('IN_INVSTS', $mPenugasan->IN_INVSTS), ['class' => 'form-control input-sm', 'id' => 'IN_INVSTS','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_PREMISE','Kod Premis ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), old('IN_AGAINST_PREMISE', $mPenugasan->IN_AGAINST_PREMISE), ['class' => 'form-control input-sm','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEBY','Ditutup Oleh ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEBY', $mPenugasan->IN_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CMPLCAT','Kategori Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_CMPLCAT', Ref::GetList('244', true, 'ms'), old('IN_CMPLCAT', $mPenugasan->IN_CMPLCAT), ['class' => 'form-control input-sm required', 'id' => 'IN_CMPLCAT','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CMPLCD','SubKategori ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_CMPLCD', $mPenugasan->IN_CMPLCD == ''? ['-- SILA PILIH --'] : Ref::GetList('634', true, 'ms'), old('IN_CMPLCD', $mPenugasan->IN_CMPLCD), ['class' => 'form-control input-sm','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEBY','Tarikh Ditutup ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEBY', $mPenugasan->IN_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_RESULT','Hasil Siasatan ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_RESULT', $mPenugasan->IN_RESULT, ['class' => 'form-control input-sm','readonly' => true,'rows' => 5]) }}
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
                                {{ Form::label('IN_NAME','Nama Pengadu ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_NAME', $mPenugasan->IN_NAME, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_SEXCD','Jantina ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_SEXCD', Ref::GetList('202 ', true), $mPenugasan->IN_SEXCD, ['class' => 'form-control input-sm', 'id' => 'IN_SEXCD', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ADDR','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::textarea('IN_ADDR', $mPenugasan->IN_ADDR, ['class' => 'form-control input-sm','rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_DOCNO','No. KP atau Passport ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_DOCNO', $mPenugasan->IN_DOCNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_NATCD','Warganegara ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_NATCD', Ref::GetList('199 ', true), $mPenugasan->IN_NATCD, ['class' => 'form-control input-sm', 'id' => 'IN_NATCD', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_TELNO', $mPenugasan->IN_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_MOBILENO', 'No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_MOBILENO', $mPenugasan->IN_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_EMAIL', $mPenugasan->IN_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
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
                                {{ Form::label('IN_AGAINSTNM','Nama ', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_AGAINSTNM', $mPenugasan->IN_AGAINSTNM, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINSTADD','Alamat ', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('IN_AGAINSTADD', $mPenugasan->IN_AGAINSTADD, ['class' => 'form-control input-sm', 'rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 08'? 'block':'none')) }};">
                                {{ Form::label('IN_TTPMTYP', 'Penuntut/Penentang', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_TTPMTYP', $mPenugasan->IN_TTPMTYP != ''? Ref::GetDescr('1108', $mPenugasan->IN_TTPMTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 08'? 'block':'none')) }};">
                                {{ Form::label('IN_TTPMNO', 'No. TTPM', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_TTPMNO', old('IN_TTPMNO', $mPenugasan->IN_TTPMNO), ['class' => 'form-control input-sm', 'readonly' => true])}}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_AMOUNT', $mPenugasan->IN_ONLINECMPL_AMOUNT, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (in_array(old('IN_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($mPenugasan->IN_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none'))) }};">
                                {{ Form::label('IN_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_CMPLKEYWORD', $mPenugasan->IN_CMPLKEYWORD != ''? Ref::GetDescr('1051', $mPenugasan->IN_CMPLKEYWORD, 'ms'):'', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'none':'block')) }};">
                                {{ Form::label('IN_AGAINST_PREMISE', 'Jenis Premis', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_AGAINST_PREMISE', $mPenugasan->IN_AGAINST_PREMISE != ''? Ref::GetDescr('221', $mPenugasan->IN_AGAINST_PREMISE, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_PROVIDER', $mPenugasan->IN_ONLINECMPL_PROVIDER != ''? Ref::GetDescr('1091', $mPenugasan->IN_ONLINECMPL_PROVIDER, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_URL', 'Laman Web / URL / ID', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_URL', $mPenugasan->IN_ONLINECMPL_URL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_PYMNTTYP', $mPenugasan->IN_ONLINECMPL_PYMNTTYP != ''? Ref::GetDescr('1207', $mPenugasan->IN_ONLINECMPL_PYMNTTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_BANKCD', 'Nama Bank', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_BANKCD', $mPenugasan->IN_ONLINECMPL_BANKCD != ''? Ref::GetDescr('1106', $mPenugasan->IN_ONLINECMPL_BANKCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_ACCNO', 'No. Akaun Bank / No. Transaksi FPX', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_ACCNO', $mPenugasan->IN_ONLINECMPL_ACCNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT') == 'BPGK 19'? (old('IN_CMPLCAT') == 'BPGK 19' && old('IN_ONLINECMPL_IND') == 'on'? 'block':($mPenugasan->IN_CMPLCAT == 'BPGK 19' && $mPenugasan->IN_ONLINECMPL_IND == '1'? 'block':'none')): old('IN_CMPLCAT') == '' && $mPenugasan->IN_CMPLCAT == 'BPGK 19'? (old('IN_ONLINECMPL_IND') == '' && $mPenugasan->IN_ONLINECMPL_IND == '1'? 'block':(old('IN_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;">
                                {{ Form::label('IN_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_CASENO', $mPenugasan->IN_ONLINECMPL_CASENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_TELNO', $mPenugasan->IN_AGAINST_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_MOBILENO', 'No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_MOBILENO', $mPenugasan->IN_AGAINST_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_FAXNO','No. Faks ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_FAXNO', $mPenugasan->IN_AGAINST_FAXNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_EMAIL', $mPenugasan->IN_AGAINST_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
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
<!-- {{-- @include('aduan.tugas.usersearchmodal') --}} -->
<!-- {{-- @include('aduan.tugas.multiusersearchmodal') --}} -->
<!-- Modal End -->
<!-- Modal Start -->
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
<div id="modal-show-summary-integriti" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummaryIntegriti'></div>
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

    function showsummaryintegriti(id)
    {
        $('#modal-show-summary-integriti')
            .modal("show")
            .find("#ModalShowSummaryIntegriti")
            .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
    }

    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href=' + hash + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    $('#IN_INVSTS').on('change', function (e) {
        var IN_INVSTS = $(this).val();
        if(IN_INVSTS === ''){
            $('#div_BLOCK').hide();
            $('#magncd').hide();
            $('#div_CD_DESC').hide();
            $('#div_IN_ANSWER').hide();
        }
        // else
        //     $('#div_BLOCK').show();
        else if(IN_INVSTS === '04'){
            $('#div_BLOCK').hide();
            $('#magncd').show();
            $('#div_CD_DESC').hide();
            $('#div_IN_ANSWER').show();
        }
        // else
        //     $('#magncd').hide();
        else if(IN_INVSTS === '02')
        {
            // $('#div_IN_CMPLCAT').show();
            // $('#div_IN_CMPLCD').show();
            // $('#div_IN_INVBY').show();
            // $('#div_IN_INVBYOTH').show();
            $('#div_CD_DESC').show();
            $('#div_IN_ANSWER').hide();
            $('#div_BLOCK').show();
            $('#magncd').hide();
        }
        else
        {
            // $('#div_IN_CMPLCAT').hide();
            // $('#div_IN_CMPLCD').hide();
            // $('#div_IN_INVBY').hide();
            // $('#div_IN_INVBYOTH').hide();
            $('#div_CD_DESC').hide();
            $('#div_IN_ANSWER').show();
            $('#div_BLOCK').hide();
            $('#magncd').hide();
        }
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
                // url: "{{-- url('tugas/getdatatableuser') --}}",
                url: "{{ url('integrititugas/getdatatableuser') }}",
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
                // {data: 'state_cd', name: 'state_cd'},
                // {data: 'brn_cd', name: 'brn_cd'},
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });
        
        // var oTableMulti = $('#users-multi-table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     bFilter: false,
        //     aaSorting: [],
        //     pagingType: "full_numbers",
        //     dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
        //         "<'row'<'col-sm-12'tr>>" +
        //         "<'row'<'col-sm-12'p>>",
        //     language: {
        //         lengthMenu: 'Memaparkan _MENU_ rekod',
        //         zeroRecords: 'Tiada rekod ditemui',
        //         info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
        //         infoEmpty: 'Tiada rekod ditemui',
        //         infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
        //         paginate: {
        //             previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
        //             next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
        //             first: 'Pertama',
        //             last: 'Terakhir',
        //         },
        //     },
        //     ajax: {
        //         url: "{{ url('tugas/getdatatablemultiuser') }}",
        //         data: function (d) {
        //             d.name = $('#name_multi').val();
        //             d.icnew = $('#icnew_multi').val();
        //         }
        //     },
        //     columns: [
        //         {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
        //         {data: 'username', name: 'username'},
        //         {data: 'name', name: 'name'},
        //         {data: 'state_cd', name: 'state_cd'},
        //         {data: 'brn_cd', name: 'brn_cd'},
        //         {data: 'count_case', name: 'count_case'},
        //         {data: 'role.role_code', name: 'role.role_code'},
        //         {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
        //     ],
        // });

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

    });
</script>
@stop