@extends('layouts.main')
<?php
use App\Ref;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDoc;
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
<h2>Penyiasatan Aduan (Integriti)</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#siasatan"> Penyiasatan </a></li>
                <!--<li class=""><a data-toggle="tab" href="#lampiran"> Lampiran </a></li>-->
                <!-- <li class=""><a data-toggle="tab" href="#mak_aduan"> Maklumat Aduan</a></li>
                <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li>
                <li class=""><a data-toggle="tab" href="#attachdoc">Bukti Aduan dan Gabungan Aduan</a></li>
                <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li> -->
        </ul>
        <div class="tab-content">
            <div id="siasatan" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => ['integritisiasat.update',$mSiasat->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                    {{ csrf_field() }}{{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CASEID','No. Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CASEID', $mSiasat->IN_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ASGBY','Penugasan Aduan Oleh ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_ASGBY_LABEL', count($mSiasat->asgby) == '1'? $mSiasat->asgby->name:$mSiasat->IN_ASGBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_IPSTS') ? ' has-error' : '' }}">
                                {{ Form::label('IN_IPSTS', 'Status Penyiasatan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_IPSTS_LABEL', Ref::GetDescr('1370', '03'), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('IN_IPSTS'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_IPSTS') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RECOMMENDTYP') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RECOMMENDTYP', 'Jenis Pengesyoran', ['class' => 'col-sm-4 control-label required']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_RECOMMENDTYP[]', IntegritiAdmin::GetStatusList('1376', ['1','2','3'], false), old('IN_RECOMMENDTYP', explode(",",$mSiasat->IN_RECOMMENDTYP)), ['class' => 'select2_demo_2 form-control', 'id' => 'IN_RECOMMENDTYP', 'multiple' => 'multiple']) }}
                                    @if ($errors->has('IN_RECOMMENDTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_RECOMMENDTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="magncd" style="display: {{ $errors->has('IN_MAGNCD')||$mSiasat->IN_IPSTS == '4' ? 'block' : 'none' }} ;">
                                <div class="form-group{{ $errors->has('IN_MAGNCD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_MAGNCD', 'Agensi', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('IN_MAGNCD', IntegritiAdmin::GetMagncdList(), $mSiasat->IN_MAGNCD, ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('IN_MAGNCD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_MAGNCD') }}</strong></span>
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
                            @endif
                            <div class="form-group">
                                {{ Form::label('IN_IPNO','No. IP', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_IPNO', $mSiasat->IN_IPNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_FILEREF','No. Rujukan Fail', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_FILEREF', $mSiasat->IN_FILEREF, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_INVBY','Pegawai Penyiasat/Serbuan', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_INVBY_LABEL', count($mSiasat->invby) == '1'? $mSiasat->invby->name:$mSiasat->IN_INVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('ResultJMA','Hasil Keputusan Mesyuarat JMA ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('ResultJMA', $mResultJMA != '' ? $mResultJMA->ID_DESC : '', ['class' => 'form-control input-sm', 'rows' => 3, 'readonly'=>true]) }}
                                </div>
                            </div>
                            @if (!empty($mAlasanTolak))
                            <div class="form-group">
                                {{ Form::label('AlasanTolak','Alasan Tolak ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('AlasanTolak', $mAlasanTolak != '' ? $mAlasanTolak->ID_DESC : '', ['class' => 'form-control input-sm', 'rows' => 3, 'readonly'=>true]) }}
                                </div>
                            </div>
                            @endif
                            <div id="div_IN_RESULT">
                                <div class="form-group{{ $errors->has('IN_RESULT') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_RESULT','Hasil Siasatan ', ['class' => 'col-md-2 control-label required']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('IN_RESULT', old('IN_RESULT', $mSiasat->IN_RESULT), ['class' => 'form-control input-sm','rows' => 3]) }}
                                        @if ($errors->has('IN_RESULT'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RESULT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="div_IN_RECOMMEND">
                                <div class="form-group{{ $errors->has('IN_RECOMMEND') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_RECOMMEND','Catatan Pengesyoran ', ['class' => 'col-md-2 control-label required']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('IN_RECOMMEND', old('IN_RECOMMEND', $mSiasat->IN_RECOMMEND), ['class' => 'form-control input-sm', 'rows' => 3]) }}
                                        @if ($errors->has('IN_RECOMMEND'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RECOMMEND') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_ANSWER') ? ' has-error' : '' }}">
                                {{ Form::label('IN_ANSWER','Jawapan Kepada Pengadu ', ['class' => 'col-md-2 control-label required']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_ANSWER', old('IN_ANSWER', $mSiasat->IN_ANSWER), ['class' => 'form-control input-sm', 'rows' => 3]) }}
                                    @if ($errors->has('IN_ANSWER'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_ANSWER') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-9">
                            @if($mAttachSiasatCount < 8)
                            <a onclick="ModalCreateAttachment('{{ $mSiasat->id }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Bukti Siasatan</a>
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
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            <a href="{{ url('integritisiasat')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
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
            <!-- <div id="lampiran" class="tab-pane">
                <div class="panel-body">
                    <div class="row"></div><br>
                    
                    <br/>
                    
                </div>
            </div> -->
            <!--<div id="mak_aduan" class="tab-pane">
            <div class="panel-body">
            {!! Form::open(['id' => 'mak-aduan-form', 'class' => 'form-horizontal']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {{ Form::label('IN_CASEID','No. Aduan ', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('IN_CASEID', $mSiasat->IN_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('IN_BRNCD', 'Nama Cawangan', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('IN_BRNCD', count($mSiasat->GetBrnCd) == '1'? $mSiasat->GetBrnCd->BR_BRNNM : $mSiasat->IN_BRNCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('IN_FILEREF','No Rujukan ', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('IN_FILEREF', $mSiasat->IN_FILEREF, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {{ Form::label('IN_RCVDT','Tarikh Penerimaan ', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('IN_RCVDT', empty($mSiasat->IN_RCVDT)? '':Carbon::parse($mSiasat->IN_RCVDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm','readonly' => true]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('IN_CREDT','Tarikh Cipta ', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-8">
                            {{ Form::text('IN_CREDT', empty($mSiasat->IN_CREDT)? '':Carbon::parse($mSiasat->IN_CREDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm','readonly' => true]) }}
                        </div>
                    </div>
                </div>
                    </div><br>
                    <h4>MAKLUMAT ADUAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_RCVTYP', Ref::GetList('259', true), $mSiasat->IN_RCVTYP, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
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
                                    {{ Form::text('IN_RCVBY', count($mSiasat->namapenerima) == '1' ? $mSiasat->namapenerima->name : $mSiasat->IN_RCVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                                    {{ Form::textarea('IN_SUMMARY', $mSiasat->IN_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>PENUGASAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_ASGBY','Penugasan Aduan Oleh  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_ASGBY', count($mSiasat->GetAsgByName) == '1' ? $mSiasat->GetAsgByName->name : $mSiasat->IN_ASGBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_INVBY','Pegawai Penyiasat/Serbuan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_INVBY', count($mSiasat->GetInvByName) == '1'? $mSiasat->GetInvByName->name : $mSiasat->IN_INVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_ASGDT','Tarikh Penugasan  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_ASGDT',empty($mSiasat->IN_ASGDT)? '':Carbon::parse($mSiasat->IN_ASGDT)->format('d-m-Y h:i A'), ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEDT','Tarikh Selesai ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEDT', $mSiasat->IN_CLOSEDT, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>SIASATAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_INVSTS','Status Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                   {{ Form::select('IN_INVSTS',Ref::GetList('292', true), $mSiasat->IN_INVSTS, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_PREMISE','Kod Premis ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_PREMISE',$mSiasat->IN_AGAINST_PREMISE != ''? $mSiasat->GetNamaPremis->descr:'', ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEBY','Ditutup Oleh ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEBY', count($mSiasat->closeby) == '1'? $mSiasat->closeby->name : $mSiasat->IN_CLOSEBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CMPLCAT','Kategori Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_CMPLCAT',Ref::GetList('244', true), $mSiasat->IN_CMPLCAT, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CMPLCD','SubKategori ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_CMPLCD',Ref::GetList('634', true),$mSiasat->IN_CMPLCD, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEDT','Tarikh Ditutup ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEDT',$mSiasat->IN_CLOSEDT, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_RESULT ','Hasil Siasatan ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_RESULT', $mSiasat->IN_RESULT, ['class' => 'form-control input-sm', 'rows' => 5, 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>-->
            
            <!--<div id="adu-diadu" class="tab-pane">
                <div class="panel-body">
                    {!! Form::open(['id' => 'adu-diadu-form', 'class' => 'form-horizontal']) !!}
                    <h4>MAKLUMAT PENGADU</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_NAME','Nama Pengadu ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_NAME', $mSiasat->IN_NAME, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_RACECD','Bangsa ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                     {{ Form::select('IN_RACECD', Ref::GetList('580', true, 'ms'), $mSiasat->IN_RACECD, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                            </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_SEXCD','Jantina ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                      {{ Form::select('IN_SEXCD',Ref::GetList('202', true),$mSiasat->IN_SEXCD, ['class' => 'form-control input-sm','disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ADDR','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::textarea('IN_ADDR', $mSiasat->IN_ADDR, ['class' => 'form-control input-sm','rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_DOCNO','No. KP atau Passport ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_DOCNO', $mSiasat->IN_DOCNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_NATCD','Warganegara ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('IN_NATCD', $mSiasat->IN_NATCD, ['class' => 'form-control input-sm','readonly' => true]) --}}
                                         {{ Form::select('IN_NATCD', Ref::GetList('199 ', true), $mSiasat->IN_NATCD, ['class' => 'form-control input-sm', 'id' => 'IN_NATCD', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_TELNO', $mSiasat->IN_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_MOBILENO','No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_MOBILENO', $mSiasat->IN_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_EMAIL', $mSiasat->IN_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>MAKLUMAT YANG DIADU</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_AGAINSTNM','Nama ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINSTNM', $mSiasat->IN_AGAINSTNM, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINSTADD','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::textarea('IN_AGAINSTADD',$mSiasat->IN_AGAINSTADD, ['class' => 'form-control input-sm', 'rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_TELNO',$mSiasat->IN_AGAINST_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_MOBILENO','No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_MOBILENO', $mSiasat->IN_AGAINST_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_FAXNO','No. Faks ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_FAXNO', $mSiasat->IN_AGAINST_FAXNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_EMAIL',$mSiasat->IN_AGAINST_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>-->
            
            <!--<div id="attachdoc" class="tab-pane">
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
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
            
            <!--<div id="transaction" class="tab-pane">
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
            </div>-->
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
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('integritisiasat.createattachsiasat','') }}" + "/" + CASEID);
        return false;
    }
    function ModalCreateKes(CASEID) {
        $('#modal-create-kes').modal("show").find("#modalCreateKes").load("{{ route('integritisiasat.create_kes_siasat','') }}" + "/" + CASEID);
        return false;
    }
    function ModalAttachmentEdit(id) {
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('integritisiasat.editattachsiasat','') }}" + "/" + id);
        return false;
    }
    function ModalKesEdit(id) {
        $('#modal-edit-kes').modal("show").find("#modalEditKes").load("{{ route('integritisiasat.edit_kes_siasat','') }}" + "/" + id);
        return false;
    }

    $(document).ready(function(){

        $(".select2_demo_2").select2();
        
        $('#IN_COMPLETEDT').datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            showMeridian: true,
            todayHighlight: true,
            // keyboardNavigation: false,
            // forceParse: false,
            // autoclose: true
        });

        var invsts = $('#IN_IPSTS').val();
        /* if (invsts == '03') {
            $('#div_IN_SSP').show();
            if ($('#yes').is(":checked")) {
                $('#ssp').show();        
            } else if ($('#no').is(":checked")) {
                $('#ssp').hide();
            }
        } else if (invsts == '') {
            $('#ssp').hide();
        } */
    });
    
    $('#IN_AKTA').on('change', function (e) {
        var akta = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('integritisiasat/getsubakta') }}" + "/" + akta,
            dataType: "json",
            success:function(data){
                console.log(data);
                $('select[name="IN_SUBAKTA"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="IN_SUBAKTA"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        });
    });
    
    $('#IN_IPSTS').on('change', function (e) {
        if($(this).val() === '04')
            $('#magncd').show();
        else
            $('#magncd').hide();
        /* if($(this).val() === '03'){
            $('#div_IN_SSP').show();
            check($('input[name=IN_SSP]:checked').val());
        }
        else {
            $('#div_IN_SSP').hide();
            check('NO');
        }
        if($(this).val() === '07') {
            $('#div_IN_RESULT').hide();
            $('#div_IN_RECOMMEND').hide();
        } else {
            $('#div_IN_RESULT').show();
            $('#div_IN_RECOMMEND').show();
        } */
    });
    
    function check(value) {
        if (value === 'YES') {
            $('#ssp').show();
        } else {
            $('#ssp').hide();
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
            url: "{{ url('integritisiasat/getAttachmentSiasat', $mSiasat->id) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'IC_DOCFULLNAME', name: 'IC_DOCFULLNAME'},
            {data: 'IC_REMARKS', name: 'IC_REMARKS'},
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
            url: "{{ url('siasat/getattachment', $mSiasat->IN_CASEID) }}"
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
            url: "{{ url('integritisiasat/getAttachmentSiasat', $mSiasat->id) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'IC_DOCFULLNAME', name: 'IC_DOCFULLNAME', orderable: false},
            {data: 'IC_REMARKS', name: 'IC_REMARKS', orderable: false}
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
            url: "{{ url('integritisiasat/getKesSiasat', $mSiasat->id) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'IT_IPNO', name: 'IT_IPNO'},
            {data: 'IT_EPNO', name: 'IT_EPNO'},
            {data: 'IT_AKTA', name: 'IT_AKTA'},
            {data: 'IT_SUBAKTA', name: 'IT_SUBAKTA'},
            {data: 'action', name: 'aItion', searchable: false, orderable: false, width: '5%'}
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
            url: "{{ url('siasat/getgabung', $mSiasat->IN_CASEID) }}"
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
            url: "{{ url('siasat/gettransaction', $mSiasat->IN_CASEID)}}",
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'IN_INVSTS', name: 'IN_INVSTS'},
            {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
            {data: 'CD_ACTTO', name: 'CD_ACTTO'},
            {data: 'CD_DESC', name: 'CD_DESC'},
            {data: 'CD_CREDT', name: 'CD_CREDT'},
            {data: 'SURAT', name: 'SURAT'}
        ]
    });
</script>
@stop
