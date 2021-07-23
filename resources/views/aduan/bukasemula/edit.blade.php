@extends('layouts.main')
<?php
    use App\Ref;
?>
@section('content')
<h2>Buka Semula Aduan</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#buka-semula">Buka Semula</a></li>
            <li class=""><a data-toggle="tab" href="#case-info"> Maklumat Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li>
            <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li>
        </ul>
        <div class="tab-content">
            <div id="buka-semula" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => ['bukasemula.update', $mBukaSemula->CA_CASEID], 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }} {{ method_field('PATCH') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CASEID', $mBukaSemula->CA_CASEID, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
<!--                                <div class="col-sm-4">
                                    @if ( $countBukaSemulaForward == 0 )
                                        {{-- Form::text('', '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    @elseif ($countBukaSemulaForward > 0)
                                        {{-- Form::text('', $mBukaSemulaForward->CF_CASEID != '' ? $mBukaSemulaForward->CF_CASEID : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    @endif
                                </div>-->
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_ASGBY', 'Penugasan Aduan Oleh', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ASGBY', $mBukaSemula->CA_ASGBY != '' ? $mBukaSemula->pindaholeh->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCAT', 'Kategori Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms'), $mBukaSemula->CA_CMPLCAT, ['class' => 'form-control input-sm', 'disabled' => true]) --}}
                                    {{ Form::text('CA_CMPLCAT', Ref::GetDescr('244', $mBukaSemula->CA_CMPLCAT), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCD', 'Subkategori Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- $mBukaSemula->CA_CMPLCD --}}
                                    {{-- Form::select('CA_CMPLCD', Ref::GetList('634', true, 'ms'), $mBukaSemula->CA_CMPLCD, ['class' => 'form-control input-sm', 'disabled' => true]) --}}
                                    {{ Form::text('CA_CMPLCD', $mBukaSemula->CA_CMPLCD == '0'||$mBukaSemula->CA_CMPLCD == ''? '':Ref::GetDescr('634', $mBukaSemula->CA_CMPLCD), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
<!--                            <div class="form-group">
                                {{-- Form::label('CA_FILEREF', 'No. Rujukan', ['class' => 'col-sm-5 control-label']) --}}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_FILEREF', $mBukaSemula->CA_FILEREF, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                </div>
                            </div>-->
                            <div class="form-group{{ $errors->has('CA_INVBY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('CA_INVBY_NAME', '', ['class' => 'form-control input-sm', 'readonly' => true, 'id' => 'CA_INVBY_name']) }}
                                    {{ Form::hidden('CA_INVBY', '', ['class' => 'form-control input-sm', 'id' => 'CA_INVBY_id']) }}
                                    @if ($errors->has('CA_INVBY'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_INVBY') }}</strong></span>
                                    @endif
                                </div>
                                <a data-toggle="modal" class="btn btn-sm btn-primary col-sm-1" href="#carian-penyiasat" title="Carian Pegawai"><i class="fa fa-search"></i></a>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_INVSTS', 'Status', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::select('CA_INVSTS', Ref::GetList('292', true, 'ms'), $mBukaSemula->CA_INVSTS, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_COMPLETEDT', 'Tarikh Selesai', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_COMPLETEDT', $mBukaSemula->CA_COMPLETEDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->CA_COMPLETEDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 align="center">HASIL SIASATAN</h4>
                            <div class="hr-line-solid"></div>
                            <div class="form-group{{ $errors->has('CA_RESULT') ? ' has-error' : '' }}">
                                <div class="col-sm-12">
                                    {{ Form::textarea('CA_RESULT', '', ['class' => 'form-control input-sm', 'rows' => 3]) }}
                                    @if ($errors->has('CA_RESULT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RESULT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4 align="center">HASIL SIASATAN TERDAHULU</h4>
                            <div class="hr-line-solid"></div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    {{ Form::textarea('', $mBukaSemula->CA_RESULT != '' ? $mBukaSemula->CA_RESULT : '', ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 align="center">TINDAKAN KES</h4>
                            <div class="hr-line-solid"></div>
                            <div class="form-group{{ $errors->has('CA_SSP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SSP', 'Kes SSP', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-9">
                                    <div class="radio radio-success radio-inline"><input type="radio" id="Radio1" value="YES" name="CA_SSP" onclick="check(this.value)" {{ old('CA_SSP') == 'YES' ? 'checked' : '' }}><label for="Radio1">Ya</label></div>
                                    <div class="radio radio-success radio-inline"><input type="radio" id="Radio2" value="NO" name="CA_SSP" onclick="check(this.value)" {{ old('CA_SSP') == 'NO' ? 'checked' : '' }}><label for="Radio2">Tidak</label></div>
                                    @if ($errors->has('CA_SSP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_SSP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="ya" style="display: {{ old('CA_SSP') == 'YES' ? 'block' : 'none' }}">
                                <div class="form-group {{ $errors->has('CA_IPNO') ? ' has-error' : 'CA_IPNO' }}">
                                    {{ Form::label('CA_IPNO', 'No. IP', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('CA_IPNO', '', ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('CA_IPNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_IPNO') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_AKTA') ? ' has-error' : 'CA_AKTA' }}">
                                    {{ Form::label('CA_AKTA', 'Akta', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_AKTA', Ref::GetList('713', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'CA_AKTA']) }}
                                        @if ($errors->has('CA_AKTA'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AKTA') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_SUBAKTA') ? ' has-error' : 'CA_SUBAKTA' }}">
                                    {{ Form::label('CA_SUBAKTA', 'Kod Akta', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_SUBAKTA', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'CA_SUBAKTA']) }}
                                        @if ($errors->has('CA_SUBAKTA'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_SUBAKTA') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4 align="center">TINDAKAN KES TERDAHULU</h4>
                            <div class="hr-line-solid"></div>
                            <div class="form-group">
                                {{ Form::label('', 'Kes SSP', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    <div class="radio inline"><label><input type="radio" value="YES" disabled {{ $mBukaSemula->CA_SSP == 'YES' ? 'checked':'' }} onclick="checkold(this.value)"><i></i>Ya</label></div>
                                    <div class="radio inline"><label><input type="radio" value="NO" disabled {{ $mBukaSemula->CA_SSP == 'NO' ? 'checked':'' }} onclick="checkold(this.value)"><i></i>Tidak</label></div>
                                </div>
                            </div>
                            <div id="ya-old" style="display: {{ $mBukaSemula->CA_SSP == 'YES' ? 'block':'none' }}">
                                <div class="form-group">
                                    {{ Form::label('', 'No. IP', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::text('', $mBukaSemula->CA_IPNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('', 'Akta', ['class' => 'col-sm-2 control-label ']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('', Ref::GetList('713', true, 'ms'), $mBukaSemula->CA_AKTA, ['class' => 'form-control input-sm', 'disabled' => true, 'id' => 'CA_AKTA_OLD']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('', 'Kod Akta', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('', Ref::GetList('714', true, 'ms'), $mBukaSemula->CA_SUBAKTA, ['class' => 'form-control input-sm', 'disabled' => true, 'id' => 'CA_SUBAKTA_OLD']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 align="center">SARANAN</h4>
                            <div class="hr-line-solid"></div>
                            <div class="form-group {{ $errors->has('CD_DESC') ? ' has-error' : '' }}">
                                <div class="col-sm-12">
                                    {{ Form::textarea('CD_DESC', '', ['class' => 'form-control input-sm', 'rows' => 3]) }}
                                    @if ($errors->has('CD_DESC'))
                                        <span class="help-block"><strong>{{ $errors->first('CD_DESC') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4 align="center">SARANAN TERDAHULU</h4>
                            <div class="hr-line-solid"></div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    @if ( $countCaseDetail == 0 )
                                        {{ Form::textarea('', '', ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    @elseif ($countCaseDetail > 0)
                                        {{ Form::textarea('', $mBukaSemula->casedetail->CD_DESC != '' ? $mBukaSemula->casedetail->CD_DESC : '', ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            {{ Form::submit('Hantar', ['class' => 'btn btn-success btn-sm']) }}
                            {{ link_to('bukasemula', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
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
                                    {{ Form::text('CA_CASEID', $mBukaSemula->CA_CASEID, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_BRNCD', 'Kod Cawangan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_BRNCD', $mBukaSemula->CA_BRNCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    <!--{{-- Form::select('CA_BRNCD', BukaSemula::getbrnlist(true), $mBukaSemula->CA_BRNCD, ['class' => 'form-control input-sm']) --}}-->
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_FILEREF', 'No. Rujukan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_FILEREF', $mBukaSemula->CA_FILEREF, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVDT', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_RCVDT', $mBukaSemula->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->CA_RCVDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CREDT', 'Tarikh Cipta', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CREDT', $mBukaSemula->CA_CREDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->CA_CREDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                                    {{ Form::select('CA_RCVTYP', Ref::GetList('259', true, 'ms'), $mBukaSemula->CA_RCVTYP, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('CA_RCVBY', $mBukaSemula->CA_RCVBY != '' ? $mBukaSemula->namapenerima->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_SUMMARY', 'Aduan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_SUMMARY', $mBukaSemula->CA_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
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
                                    {{ Form::text('CA_ASGBY', $mBukaSemula->CA_ASGBY != '' ? $mBukaSemula->pindaholeh->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('CA_INVBY', $mBukaSemula->CA_INVBY != '' ? $mBukaSemula->namapenyiasat->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_ASGDT', 'Tarikh Penugasan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ASGDT', $mBukaSemula->CA_ASGDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->CA_ASGDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('', 'Tarikh Selesai', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('', $mBukaSemula->CA_CLOSEDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->CA_CLOSEDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                                    {{ Form::select('CA_INVSTS', Ref::GetList('292', true, 'ms'), $mBukaSemula->CA_INVSTS, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINST_PREMISE', 'Kod Premis', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), $mBukaSemula->CA_AGAINST_PREMISE, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEBY', 'Ditutup Oleh', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEBY', $mBukaSemula->CA_CLOSEBY != '' ? $mBukaSemula->ditutupoleh->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCAT', 'Kategori Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms'), $mBukaSemula->CA_CMPLCAT, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CMPLCD', Ref::GetList('634', true, 'ms'), $mBukaSemula->CA_CMPLCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CLOSEDT', 'Tarikh Ditutup', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CLOSEDT', $mBukaSemula->CA_CLOSEDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->CA_CLOSEDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('CA_RESULT', 'Hasil Siasatan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_RESULT', $mBukaSemula->CA_RESULT, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
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
                                        {{ Form::text('CA_NAME', $mBukaSemula->CA_NAME, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_RACECD', Ref::GetList('580', true, 'ms'), $mBukaSemula->CA_RACECD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), $mBukaSemula->CA_SEXCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('CA_ADDR', $mBukaSemula->CA_ADDR, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_DOCNO', $mBukaSemula->CA_DOCNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_NATCD', $mBukaSemula->CA_NATCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_MOBILENO', $mBukaSemula->CA_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_TELNO', 'No. Telefon', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_TELNO', $mBukaSemula->CA_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_FAXNO', $mBukaSemula->CA_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_EMAIL', $mBukaSemula->CA_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                                        {{ Form::text('CA_AGAINSTNM', $mBukaSemula->CA_AGAINSTNM, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('CA_AGAINSTADD', $mBukaSemula->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_MOBILENO', $mBukaSemula->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_TELNO', $mBukaSemula->CA_AGAINST_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_FAXNO', $mBukaSemula->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('CA_AGAINST_EMAIL', $mBukaSemula->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                        <table id="buka-semula-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Nama Fail</th>
                                    <th>Fail</th>
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
                        <table id="buka-semula-gabung-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                        <table id="buka-semula-transaction-table" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Status</th>
                                    <th>Daripada</th>
                                    <th>Kepada</th>
                                    <th>Tarikh Transaksi</th>
                                    <th>Saranan</th>
                                    <th>Surat Kepada Pengadu</th>
                                    <th>Surat Kepada Pegawai/Agensi</th>
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
                            <div class="form-group">
                                {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('brn_cd', ['-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
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
                                <th>No.</th>
                                <th>Kod</th>
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
    function check(value) {
        if (value == 'YES') {
            $('#ya').show();
        } else {
            $('#ya').hide();
        }
    }
    
    function checkold(value) {
        if (value == 'YES') {
            $('#ya-old').show();
        } else {
            $('#ya-old').hide();
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
    
    $('#CA_AKTA').on('change', function (e) {
        var CA_AKTA = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('bukasemula/getsubaktalist') }}" + "/" + CA_AKTA,
            dataType: "json",
            success:function(data){
                $('select[name="CA_SUBAKTA"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="CA_SUBAKTA"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        }); 
    });
    
    $('#buka-semula-attachment-table').DataTable({
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
            url: "{{ url('bukasemula/getdatatableattachment', $mBukaSemula->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id_no', 'width': '5%', searchable: false, orderable: false},
            {data: 'doc_title', name: 'doc_title', orderable: false},
            {data: 'file_name_sys', name: 'file_name_sys', orderable: false},
            {data: 'updated_at', name: 'updated_at', orderable: false}
//            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
    
    $('#buka-semula-gabung-table').DataTable({
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
            url: "{{ url('bukasemula/getdatatablemergecase', $mBukaSemula->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CA_CASEID', name: 'CA_CASEID'},
            {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
            {data: 'CA_RCVDT', name: 'CA_RCVDT'}
        ]
    });
    
    $('#buka-semula-transaction-table').DataTable({
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
            url: "{{ url('bukasemula/getdatatabletransaction', $mBukaSemula->CA_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CD_INVSTS', name: 'CD_INVSTS'},
            {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
            {data: 'CD_ACTTO', name: 'CD_ACTTO'},
            {data: 'CD_CREDT', name: 'CD_CREDT'},
            {data: 'CD_DESC', name: 'CD_DESC'},
            {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'},
            {data: 'CD_DOCATTCHID_ADMIN', name: 'CD_DOCATTCHID_ADMIN'}
        ]
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
                    last: 'Terakhir'
                }
            },
            ajax: {
                url: "{{ url('bukasemula/getdatatableuser') }}",
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
            url: "{{ url('bukasemula/getuserdetail') }}" + "/" + id,
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
</script>
@stop