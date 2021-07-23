@extends('layouts.main')
<?php
use App\Ref;
use App\Aduan\Penyiasatan;
use App\Aduan\PenyiasatanDoc;
use Carbon\Carbon;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
    ol {
        margin-left: 15px;
        padding: 0;
    }
</style>
<h2>Penyiasatan Aduan</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#siasatan"> Penyiasatan</a></li>
            <!--<li class=""><a data-toggle="tab" href="#lampiran"> Lampiran </a></li>-->
            <li class=""><a data-toggle="tab" href="#mak_aduan"> Maklumat Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li>
            <li class=""><a data-toggle="tab" href="#attachdoc">Bukti Aduan dan Gabungan Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li>
        </ul>
        <div class="tab-content">
            <div id="siasatan" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => ['siasat.update',$mSiasat->CA_CASEID], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                    {{ csrf_field() }}{{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CASEID','No. Aduan ', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{-- Form::text('CA_CASEID', $mSiasat->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) --}}
                                    <p class="form-control-static">{{ $mSiasat->CA_CASEID }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_ASGBY','Penugasan Aduan Oleh ', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{-- Form::text('CA_ASGBY', count($mSiasat->GetAsgByName) == '1'? $mSiasat->GetAsgByName->name:$mSiasat->CA_ASGBY, ['class' => 'form-control input-sm','readonly' => true]) --}}
                                    <p class="form-control-static">
                                        {{ !empty($mSiasat->GetAsgByName) ? $mSiasat->GetAsgByName->name : $mSiasat->CA_ASGBY }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_COMPLETEDT') ? ' has-error' : '' }}">
                                {{ Form::label('CA_COMPLETEDT','Tarikh Selesai', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{ Form::text('CA_COMPLETEDT', empty($mSiasat->CA_COMPLETEDT)? Carbon::now()->format('d-m-Y h:i A'):Carbon::parse($mSiasat->CA_COMPLETEDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm', 'readonly' => true, 'id'=>'CA_COMPLETEDT']) }}
                                </div>
                                @if ($errors->has('CA_COMPLETEDT'))
                                <span class="help-block"><strong>{{ $errors->first('CA_COMPLETEDT') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('CA_AREACD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AREACD','Kawasan Kes', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('CA_AREACD', Ref::GetList('712', true), old('CA_AREACD', $mSiasat->CA_AREACD), ['class' => 'form-control input-sm required', 'id' => 'CA_AREACD']) }}
                                    @if ($errors->has('CA_AREACD'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AREACD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_INVSTS') ? ' has-error' : '' }}">
                                {{ Form::label('CA_INVSTS', 'Status Aduan', ['class' => 'col-lg-4 control-label required']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('CA_INVSTS', Penyiasatan::GetStatusList(), '', ['class' => 'form-control input-sm', 'id' => 'CA_INVSTS']) }}
                                    @if ($errors->has('CA_INVSTS'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_INVSTS') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="magncd" style="display: {{ $errors->has('CA_MAGNCD')||$mSiasat->CA_INVSTS == '4' ? 'block' : 'none' }} ;">
                                <div class="form-group{{ $errors->has('CA_MAGNCD') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_MAGNCD', 'Agensi', ['class' => 'col-lg-4 control-label required']) }}
                                    <div class="col-lg-8">
                                        {{ Form::select('CA_MAGNCD', Penyiasatan::GetMagncdList(), $mSiasat->CA_MAGNCD, ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_MAGNCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_MAGNCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            @if($mGabungAll)
                                <div class="form-group">
                                    {{ Form::label('', 'Gabung Aduan', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                    @foreach ($mGabungAll as $gabung)
                                        <span class="help-block m-b-none">{{ $gabung->CR_CASEID }}</span>
                                    @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    {{ Form::label('CA_FILEREF','No. Rujukan Fail', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        {{ Form::text('CA_FILEREF', $mSiasat->CA_FILEREF, ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                {{ Form::label('CA_INVBY','Pegawai Penyiasat/Serbuan', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{-- Form::text('CA_INVBY', count($mSiasat->GetInvByName) == '1'? $mSiasat->GetInvByName->name:$mSiasat->CA_INVBY, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">
                                        {{ !empty($mSiasat->GetInvByName) ? $mSiasat->GetInvByName->name : $mSiasat->CA_INVBY }}
                                    </p>
                                </div>
                            </div>
                            <!--<div id="div_CA_SSP" style="display: {{-- old('CA_INVSTS') ? (old('CA_INVSTS') == '3' ? 'block':'none') : ($mSiasat->CA_INVSTS == '2' ? 'block':'none') --}} ">-->
                            <div id="div_CA_SSP" style="display: {{ old('CA_INVSTS') ? (old('CA_INVSTS') == '3' ? 'block':'none') : 'none' }} ">
                                <div class="form-group{{ $errors->has('CA_SSP') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_SSP', 'Lanjutan Siasatan (IP / EP)', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        <div class="radio radio-success radio-inline">
                                            <input id="yes" type="radio" value="YES" name="CA_SSP"  onclick="check(this.value)" {{ old('CA_SSP') == 'YES'||$mSiasat->CA_SSP == 'YES'? 'checked':'' }}><label for="yes">Ya</label>
                                        </div>
                                        <div class="radio radio-success radio-inline">
                                            <input id="no" type="radio" value="NO" name="CA_SSP" onclick="check(this.value)" {{ old('CA_SSP') == 'NO'||$mSiasat->CA_SSP == 'NO'? 'checked':'' }}><label for="no">Tidak</label>
                                        </div>
                                        @if ($errors->has('CA_SSP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_SSP') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
<!--                            <div id="ssp" style="display: {{ $errors->has('CA_IPNO')||$errors->has('CA_AKTA')||$errors->has('CA_SUBAKTA')||$mSiasat->CA_SSP == 'YES'? 'block' : 'none' }}">-->
<!--                                <div class="form-group {{-- $errors->has('CA_IPNO') ? ' has-error' : 'CA_IPNO' --}}">
                                    {{-- Form::label('CA_IPNO', 'No.IP', ['class' => 'col-sm-5 control-label required']) --}}
                                    <div class="col-sm-7">
                                        {{-- Form::text('CA_IPNO', $mSiasat->CA_IPNO, ['class' => 'form-control input-sm']) --}}
                                        @if ($errors->has('CA_IPNO'))
                                        <span class="help-block"><strong>{{-- $errors->first('CA_IPNO') --}}</strong></span>
                                        @endif
                                    </div>
                                </div>-->
<!--                                <div class="form-group {{-- $errors->has('CA_AKTA') ? ' has-error' : 'CA_AKTA' --}}">
                                    {{-- Form::label('CA_AKTA', 'Akta', ['class' => 'col-sm-5 control-label required']) --}}
                                    <div class="col-sm-7">
                                        {{-- Form::select('CA_AKTA', Ref::GetList('713', true), $mSiasat->CA_AKTA, ['class' => 'form-control input-sm required', 'id' => 'CA_AKTA']) --}}
                                        @if ($errors->has('CA_AKTA'))
                                        <span class="help-block"><strong>{{-- $errors->first('CA_AKTA') --}}</strong></span>
                                        @endif
                                    </div>
                                </div>-->
<!--                                <div class="form-group {{-- $errors->has('CA_SUBAKTA') ? ' has-error' : 'CA_SUBAKTA' --}}">
                                    {{-- Form::label('CA_SUBAKTA', 'Kod Akta', ['class' => 'col-sm-5 control-label required']) --}}
                                    <div class="col-sm-7">
                                        {{-- Form::select('CA_SUBAKTA', $mSiasat->CA_SUBAKTA == ''? ['' => '-- SILA PILIH --']:Penyiasatan::GetSubAktaList($mSiasat->CA_AKTA), $mSiasat->CA_SUBAKTA, ['class' => 'form-control input-sm', 'id' => 'CA_SUBAKTA']) --}}
                                        @if ($errors->has('CA_SUBAKTA'))
                                        <span class="help-block"><strong>{{-- $errors->first('CA_SUBAKTA') --}}</strong></span>
                                        @endif
                                    </div>
                                </div>-->
                            <!--</div>-->
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('SarananPenugasan','Saranan Penugasan ', ['class' => 'col-lg-2 control-label']) }}
                                <div class="col-lg-10">
                                    <!--{{-- Form::textarea('SarananPenugasan', $mSiasatDetail->CD_DESC, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly'=>true]) --}}-->
                                    {{-- Form::textarea('SarananPenugasan', $mSiasatDetail != '' ? $mSiasatDetail->CD_DESC : '', ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) --}}
                                    <p class="form-control-static">
                                        {!! $mSiasatDetail != '' ? nl2br(htmlspecialchars($mSiasatDetail->CD_DESC)) : '' !!}
                                    </p>
                                </div>
                            </div>
                            <div id="div_CA_RESULT" style="display: {{ old('CA_INVSTS') == '7' || $mSiasat->CA_INVSTS == '7' ? 'none' : 'block' }} ;">
                                <div class="form-group{{ $errors->has('CA_RESULT') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RESULT','Hasil Siasatan ', ['class' => 'col-lg-2 control-label required']) }}
                                    <div class="col-lg-10">
                                        {{ Form::textarea('CA_RESULT', old('CA_RESULT', $mSiasat->CA_RESULT), ['class' => 'form-control input-sm','rows' => 3]) }}
                                        @if ($errors->has('CA_RESULT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RESULT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="div_CA_RECOMMEND" style="display: {{ old('CA_INVSTS') == '7' || $mSiasat->CA_INVSTS == '7' ? 'none' : 'block' }} ;">
                                <div class="form-group{{ $errors->has('CA_RECOMMEND') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_RECOMMEND','Saranan ', ['class' => 'col-lg-2 control-label required']) }}
                                    <div class="col-lg-10">
                                        {{ Form::textarea('CA_RECOMMEND', old('CA_RECOMMEND', $mSiasat->CA_RECOMMEND), ['class' => 'form-control input-sm', 'rows' => 3]) }}
                                        @if ($errors->has('CA_RECOMMEND'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RECOMMEND') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_ANSWER') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ANSWER','Jawapan Kepada Pengadu ', ['class' => 'col-lg-2 control-label required']) }}
                                <div class="col-lg-10">
                                    {{ Form::textarea('CA_ANSWER', old('CA_ANSWER', $mSiasat->CA_ANSWER), ['class' => 'form-control input-sm', 'rows' => 3]) }}
                                    @if ($errors->has('CA_ANSWER'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_ANSWER') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-9">
                            @if($mAttachSiasatCount < 8)
                            <a onclick="ModalCreateAttachment('{{ $mSiasat->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Bukti Siasatan</a>
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
                                    <!--<th>Tarikh Muatnaik</th>-->
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="ssp" style="display: {{ $errors->has('CA_IPNO')||$errors->has('CA_AKTA')||$errors->has('CA_SUBAKTA')||$mSiasat->CA_SSP == 'YES'? 'block' : 'none' }}">
                    <div class="row">
                        <div class="col-md-9">
                            @if($mKesSiasatCount < 8)
                                <a onclick="ModalCreateKes('{{ $mSiasat->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Akta</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="admin-case-kes-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>No. Kertas Siasatan</th>
                                    <th>No. EP</th>
                                    <th>Akta</th>
                                    <!--<th>Tarikh Muatnaik</th>-->
                                    <th>Jenis Kesalahan</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div id="countakta"
                        class="row {{ $errors->has('countakta') ? ' has-error' : '' }}"
                        style="display: {{ $errors->has('countakta') || (old('CA_INVSTS') == '3' && old('CA_SSP') == 'YES') ? 'block' : 'none' }} ">
                        <div class="col-lg-12">
                            {{ Form::hidden('countakta', $mKesSiasatCount, ['readonly' => true]) }}
                            @if ($errors->has('countakta'))
                                <span class="help-block">
                                    <strong>
                                        {{ $errors->first('countakta') }}
                                    </strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="col-lg-6">
                            @if($mGabungAll)
                                @foreach ($mGabungAll as $gabung)
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label">No. Aduan</label>
                                            <p class="form-control-static">{{ $gabung->CR_CASEID }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label">Hari</label>
                                            @if ($countDurationReceived[$gabung->CR_CASEID] >= 0 && $countDurationReceived[$gabung->CR_CASEID] <= 7)
                                                <p class="form-control-static"><span class="label" style="background-color:#3F6; color: white;">{{ $countDurationReceived[$gabung->CR_CASEID] }}</span></p>
                                            @elseif ($countDurationReceived[$gabung->CR_CASEID] > 7 && $countDurationReceived[$gabung->CR_CASEID] <= 14)
                                                <p class="form-control-static"><span class="label" style="background-color:#FF3;">{{ $countDurationReceived[$gabung->CR_CASEID] }}</span></p>
                                            @elseif ($countDurationReceived[$gabung->CR_CASEID] > 14 && $countDurationReceived[$gabung->CR_CASEID] <= 21)
                                                <p class="form-control-static"><span class="label" style="background-color:#F0F; color: white;">{{ $countDurationReceived[$gabung->CR_CASEID] }}</span></p>
                                            @elseif ($countDurationReceived[$gabung->CR_CASEID] > 21)
                                                <p class="form-control-static"><span class="label" style="background-color:#F00; color: white;">{{ $countDurationReceived[$gabung->CR_CASEID] }}</span></p>
                                            @endif
                                            <input type="hidden" name="countDurationReceived['{{ $gabung->CR_CASEID }}']" id="countDurationReceived['{{ $gabung->CR_CASEID }}']" value="{{ $countDurationReceived[$gabung->CR_CASEID] }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label">Tempoh Seliaan</label>
                                            <p class="form-control-static">{{ $countDuration[$gabung->CR_CASEID] }}</p>
                                            <input type="hidden" name="CD_REASON_DURATION['{{ $gabung->CR_CASEID }}']" id="CD_REASON_DURATION['{{ $gabung->CR_CASEID }}']" value="{{ $countDuration[$gabung->CR_CASEID] }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('', 'Hari', ['class' => 'control-label']) }}
                                        @if ($countDurationReceived >= 0 && $countDurationReceived <= 7)
                                            <p class="form-control-static"><span class="label" style="background-color:#3F6; color: white;">{{ $countDurationReceived }}</span></p>
                                        @elseif ($countDurationReceived > 7 && $countDurationReceived <= 14)
                                            <p class="form-control-static"><span class="label" style="background-color:#FF3;">{{ $countDurationReceived }}</span></p>
                                        @elseif ($countDurationReceived > 14 && $countDurationReceived <= 21)
                                            <p class="form-control-static"><span class="label" style="background-color:#F0F; color: white;">{{ $countDurationReceived }}</span></p>
                                        @elseif ($countDurationReceived > 21)
                                            <p class="form-control-static"><span class="label" style="background-color:#F00; color: white;">{{ $countDurationReceived }}</span></p>
                                        @endif
                                        {{ Form::hidden('countDurationReceived', $countDurationReceived, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('', 'Tempoh Seliaan', ['class' => 'control-label']) }}
                                        <p class="form-control-static">{{ $countDuration }}</p>
                                        {{ Form::hidden('CD_REASON_DURATION', $countDuration, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if ($countDurationReceivedIndicator)
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group {{ $errors->has('CD_REASON') ? ' has-error' : '' }}">
                                    {{ Form::label('CD_REASON', 'Alasan', ['class' => 'control-label required']) }}
                                    {{ Form::select('CD_REASON', $caseReasonTemplates, null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                                    @if ($errors->has('CD_REASON'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_REASON') }}</strong></span>
                                    @endif
                                </div>
                                <div id="data_5"
                                    class="form-group {{ $errors->has('CD_REASON_DATE_FROM') || $errors->has('CD_REASON_DATE_TO') ? ' has-error' : '' }}"
                                    style="display: {{
                                        ($errors->has('CD_REASON_DATE_FROM') || $errors->has('CD_REASON_DATE_TO'))
                                        || !empty(old('CA_INVSTS')) && old('CD_REASON') == 'S2'
                                        ? 'block' : 'none'
                                    }} ;">
                                    {{ Form::label('CD_REASON_DATE_FROM', 'Tarikh', ['class' => 'control-label required']) }}
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
                        </div>
                        @endif
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            <a href="{{ url('siasat')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
                            {{ Form::submit('Simpan', ['class' => 'btn btn-primary btn-sm','name'=>'simpan']) }}
                            {{ Form::submit('Hantar', ['class' => 'btn btn-info btn-sm','name'=>'hantar']) }}
                            {{-- Form::button('Seterusnya'.' <i class="fa fa-chevron-right"></i>', ['name'=>'hantar', 'type' => 'submit', 'class' => 'btn btn-success btn-sm']) --}}
                            {{-- Form::submit('Cetak', ['class' => 'btn btn-success btn-sm']) --}}
                            {{-- link_to_action('Aduan\SiasatController@pdf','Preview', $attributes = ['class' => 'btn btn-info btn-sm', 'target'=>'_blank']) --}}
                            <!--<a href="{{-- url('siasat\pdf') --}}" type="button" class="btn btn-info btn-sm">Preview</a>-->
                            
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div id="lampiran" class="tab-pane">
                <div class="panel-body">
                    <div class="row"></div><br>
                    
                    <br/>
                    
                </div>
            </div>
            <div id="mak_aduan" class="tab-pane">
            <div class="panel-body">
            {!! Form::open(['id' => 'mak-aduan-form', 'class' => 'form-horizontal']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {{ Form::label('CA_CASEID','No. Aduan ', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('CA_CASEID', $mSiasat->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('CA_BRNCD', 'Nama Cawangan', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            <!--{{-- Form::text('CA_BRNCD', $mSiasat->CA_BRNCD ? $mSiasat->GetBrnCd->BR_BRNNM : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                            {{ Form::text('CA_BRNCD', count($mSiasat->GetBrnCd) == '1'? $mSiasat->GetBrnCd->BR_BRNNM : $mSiasat->CA_BRNCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('CA_FILEREF','No Rujukan ', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('CA_FILEREF', $mSiasat->CA_FILEREF, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {{ Form::label('CA_RCVDT','Tarikh Penerimaan ', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('CA_RCVDT', empty($mSiasat->CA_RCVDT)? '':Carbon::parse($mSiasat->CA_RCVDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm','readonly' => true]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('CA_CREDT','Tarikh Cipta ', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('CA_CREDT', empty($mSiasat->CA_CREDT)? '':Carbon::parse($mSiasat->CA_CREDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm','readonly' => true]) }}
                        </div>
                    </div>
                </div>
                    </div><br>
                    <h4>MAKLUMAT ADUAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_RCVTYP', Ref::GetList('259', true), $mSiasat->CA_RCVTYP, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
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
                                    <!--{{-- Form::text('CA_RCVBY', $mSiasat->CA_RCVBY,  ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                    {{ Form::text('CA_RCVBY', count($mSiasat->namapenerima) == '1' ? $mSiasat->namapenerima->name : $mSiasat->CA_RCVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                                    {{ Form::textarea('CA_SUMMARY', $mSiasat->CA_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>PENUGASAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGBY','Penugasan Aduan Oleh  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ASGBY', count($mSiasat->GetAsgByName) == '1' ? $mSiasat->GetAsgByName->name : $mSiasat->CA_ASGBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_INVBY','Pegawai Penyiasat/Serbuan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    <!--{{-- Form::text('CA_INVBY', $mSiasat->GetInvByName->name, ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                    {{ Form::text('CA_INVBY', count($mSiasat->GetInvByName) == '1'? $mSiasat->GetInvByName->name : $mSiasat->CA_INVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGDT','Tarikh Penugasan  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ASGDT',empty($mSiasat->CA_ASGDT)? '':Carbon::parse($mSiasat->CA_ASGDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEDT','Tarikh Selesai ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEDT', $mSiasat->CA_CLOSEDT, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>SIASATAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_INVSTS','Status Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                   {{ Form::select('CA_INVSTS',Ref::GetList('292', true), $mSiasat->CA_INVSTS, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_PREMISE','Kod Premis ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_PREMISE',$mSiasat->CA_AGAINST_PREMISE != ''? $mSiasat->GetNamaPremis->descr:'', ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEBY','Ditutup Oleh ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    <!--{{-- Form::text('CA_CLOSEBY', $mSiasat->CA_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) --}}-->
                                    {{ Form::text('CA_CLOSEBY', count($mSiasat->closeby) == '1'? $mSiasat->closeby->name : $mSiasat->CA_CLOSEBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCAT','Kategori Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCAT',Ref::GetList('244', true), $mSiasat->CA_CMPLCAT, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCD','SubKategori ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCD',Ref::GetList('634', true),$mSiasat->CA_CMPLCD, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEDT','Tarikh Ditutup ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEDT',$mSiasat->CA_CLOSEDT, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_RESULT ','Hasil Siasatan ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_RESULT', $mSiasat->CA_RESULT, ['class' => 'form-control input-sm', 'rows' => 5, 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            
            <div id="adu-diadu" class="tab-pane">
                <div class="panel-body">
                    {!! Form::open(['id' => 'adu-diadu-form', 'class' => 'form-horizontal']) !!}
                    <h4>MAKLUMAT PENGADU</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_NAME','Nama Pengadu ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_NAME', $mSiasat->CA_NAME, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_RACECD','Bangsa ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                     {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), $mSiasat->CA_RACECD, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                            </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_SEXCD','Jantina ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                      {{ Form::select('CA_SEXCD',Ref::GetList('202', true),$mSiasat->CA_SEXCD, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_ADDR','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::textarea('CA_ADDR', $mSiasat->CA_ADDR, ['class' => 'form-control input-sm','rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_DOCNO','No. KP atau Passport ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_DOCNO', $mSiasat->CA_DOCNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_NATCD','Warganegara ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('CA_NATCD', $mSiasat->CA_NATCD, ['class' => 'form-control input-sm','readonly' => true]) --}}
                                         {{ Form::select('CA_NATCD', Ref::GetList('199 ', true), $mSiasat->CA_NATCD, ['class' => 'form-control input-sm', 'id' => 'CA_NATCD', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_TELNO', $mSiasat->CA_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_MOBILENO','No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_MOBILENO', $mSiasat->CA_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_EMAIL', $mSiasat->CA_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>MAKLUMAT YANG DIADU</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_AGAINSTNM','Nama ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINSTNM', $mSiasat->CA_AGAINSTNM, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINSTADD','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::textarea('CA_AGAINSTADD',$mSiasat->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_TELNO',$mSiasat->CA_AGAINST_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_MOBILENO','No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_MOBILENO', $mSiasat->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_FAXNO','No. Faks ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_FAXNO', $mSiasat->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_EMAIL',$mSiasat->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            
            <div id="attachdoc" class="tab-pane">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>BAHAN BUKTI</h4>
                            <div class="hr-line-solid"></div>
                            <div class="table-responsive">
                                <table id="attachdoc-siasat-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Fail</th>
                                            <th>Catatan</th>
                                            <!--<th>Tindakan</th>-->
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>BUKTI SIASATAN</h4>
                            <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                            <div class="table-responsive">
                                <table id="doc-siasat-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Nama Fail</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>GABUNG ADUAN</h4>
                            <div class="hr-line-solid"></div>
                            <div class="table-responsive">
                                <table style="width: 100%" id="gabung-table" class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>No. Aduan</th>
                                            <th>Aduan</th>
                                            <th></th>
                                            <!--<th>Tarikh Terima</th>-->
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="transaction" class="tab-pane">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table style="width: 100%" id="transac-siasat-table" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Status</th>
                                    <th>Daripada</th>
                                    <th>Kepada</th>
                                    <th>Saranan</th>
                                    <th>Tarikh Transaksi</th>
                                    <th>Surat</th>
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
<!-- Modal Create Attachment Start -->
<div class="modal fade" id="modal-create-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalCreateContent'></div>
    </div>
</div>
<!-- Modal Update Attachment Start -->
<div class="modal fade" id="modal-edit-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalEditContent'></div>
    </div>
</div>
<!-- Modal Create Kes Start -->
<div class="modal fade" id="modal-create-kes" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalCreateKes'></div>
    </div>
</div>
<!-- Modal Update Kes Start -->
<div class="modal fade" id="modal-edit-kes" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalEditKes'></div>
    </div>
</div>
@stop

@section('script_datatable')
<script>
    function ModalCreateAttachment(CASEID) {
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('siasat.createattachsiasat','') }}" + "/" + CASEID);
        return false;
    }
    function ModalCreateKes(CASEID) {
        $('#modal-create-kes').modal("show").find("#modalCreateKes").load("{{ route('siasat.create_kes_siasat','') }}" + "/" + CASEID);
        return false;
    }
    function ModalAttachmentEdit(id) {
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('siasat.editattachsiasat','') }}" + "/" + id);
        return false;
    }
    function ModalKesEdit(id) {
        $('#modal-edit-kes').modal("show").find("#modalEditKes").load("{{ route('siasat.edit_kes_siasat','') }}" + "/" + id);
        return false;
    }
//    $( document ).ready(function() {
//        $('#ssp').show();
//    });
    $(document).ready(function(){
//        $('.clockpicker').clockpicker({
//            twelvehour: true,
//            donetext: 'DONE'
//        });
        
        $('#CA_COMPLETEDT').datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            showMeridian: true,
            todayHighlight: true,
//            keyboardNavigation: false,
//            forceParse: false,
//            autoclose: true
        });

        $('.input-daterange').datepicker({
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
    
    $('#CA_AKTA').on('change', function (e) {
        var akta = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('siasat/getsubakta') }}" + "/" + akta,
            dataType: "json",
            success:function(data){
                console.log(data);
                $('select[name="CA_SUBAKTA"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="CA_SUBAKTA"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        });
    });
    
    $('#CA_INVSTS').on('change', function (e) {
        if($(this).val() === '4')
            $('#magncd').show();
        else
            $('#magncd').hide();
//        if($(this).val() === '6' || $(this).val() === '7' || $(this).val() === '8')
        if($(this).val() === '3'){
            $('#div_CA_SSP').show();
            check($('input[name=CA_SSP]:checked').val());
        }
        else {
            $('#div_CA_SSP').hide();
            check('NO');
        }
        if($(this).val() === '7') {
            $('#div_CA_RESULT').hide();
            $('#div_CA_RECOMMEND').hide();
        } else {
            $('#div_CA_RESULT').show();
            $('#div_CA_RECOMMEND').show();
        }
    });
    
    function check(value) {
        if (value === 'YES') {
            $('#ssp').show();
            $('#countakta').show();
        } else {
            $('#ssp').hide();
            $('#countakta').hide();
        }
    }
    
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href='+hash+']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
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
        },
        ajax: {
            url: "{{ url('siasat/getAttachmentSiasat', $mSiasat->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'},
//            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
   
    
    $('#attachdoc-siasat-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        bInfo: false,
        aaSorting: [],
        bLengthChange: false,
        rowId: 'id',
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
            url: "{{ url('siasat/getattachment', $mSiasat->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'}
        ]
    });
    
    $('#doc-siasat-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        bInfo: false,
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
            url: "{{ url('siasat/getAttachmentSiasat', $mSiasat->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
            {data: 'CC_REMARKS', name: 'CC_REMARKS', orderable: false}
        ]
    });
    
     $('#admin-case-kes-table').DataTable({
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
        },
        ajax: {
            url: "{{ url('siasat/getKesSiasat', $mSiasat->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CT_IPNO', name: 'CT_IPNO'},
            {data: 'CT_EPNO', name: 'CT_EPNO'},
            {data: 'CT_AKTA', name: 'CT_AKTA'},
            {data: 'CT_SUBAKTA', name: 'CT_SUBAKTA'},
            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
    $('#gabung-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        bInfo: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
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
                last: 'Terakhir'
            }
        },
        ajax: {
            url: "{{ url('siasat/getgabung', $mSiasat->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CR_CASEID', name: 'CR_CASEID', orderable: false},
            {data: 'SUMMARY', name: 'SUMMARY', orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false}
        ]
    });
        
    $('#transac-siasat-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        bInfo: false,
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
            url: "{{ url('siasat/gettransaction', $mSiasat->CA_CASEID)}}",
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CA_INVSTS', name: 'CA_INVSTS'},
            {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
            {data: 'CD_ACTTO', name: 'CD_ACTTO'},
            {data: 'CD_DESC', name: 'CD_DESC'},
            {data: 'CD_CREDT', name: 'CD_CREDT'},
            {data: 'SURAT', name: 'SURAT'}
        ]
    });

    $('input[name="hantar"]').click(function (e) {
        var ca_invsts = $('#CA_INVSTS').val();
        var ca_ssp = $('input[name="CA_SSP"]:checked').val();
        var countakta = parseInt($('input[name="countakta"]').val());
        if(ca_invsts == '3' && ca_ssp == 'YES' && countakta <= 0){
            swal({
                title: "Peringatan",
                text: "Jumlah Akta mesti sekurang-kurangnya 1.",
                type: "warning"
            });
            e.preventDefault();
        }
    });

    $('select[name="CD_REASON"]').on('change', function () {
        switch ($(this).val()) {
            case 'S2':
                $('#data_5').show();
                break;
            default:
                $('#data_5').hide();
                break;
        }
    });
</script>
@stop
    
