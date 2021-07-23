@extends('layouts.main')
<?php
    use App\Ref;
    use Carbon\Carbon;
    use App\Branch;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
</style>
<h2>Pengesahan Penutupan</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#penutupan"> Penutupan</a></li>
                <li class=""><a data-toggle="tab" href="#case-info"> Maklumat Aduan</a></li>
                <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li>
                <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li>
                <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li>
        </ul>
        <div class="tab-content">
            <div id="penutupan" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['url' => '/tutup/tutup-aduan/'.$mPenutupan->CA_CASEID,  'class'=>'form-horizontal', 'method' => 'POST']) !!}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_CASEID','No. Aduan ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CASEID', $mPenutupan->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_ASGBY','Penugasan Aduan Oleh ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <!-- {{-- Form::text('CA_ASGBY', $mPenutupan->namaPemberiTugas->name, ['class' => 'form-control input-sm','readonly' => true]) --}} -->
                                        {{ Form::text('CA_ASGBY', count($mPenutupan->namaPemberiTugas) == '1'? $mPenutupan->namaPemberiTugas->name : $mPenutupan->CA_ASGBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('CA_CLOSEBY') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_CLOSEBY', 'Penutupan Aduan Oleh', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            {{ Form::hidden('CA_CLOSEBY', Auth::user()->id, ['class' => 'form-control input-sm', 'id' => 'CloseById']) }}
                                            {{ Form::text('', $mUser->name, ['class' => 'form-control input-sm', 'readonly' => true, 'id' => 'CloseByName']) }}
                                            {{-- Form::text('', Auth::user()->name, ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'CloseByName']) --}}
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-sm" id="UserSearchModal">Carian</button>
                                            </span>
                                        </div>
                                        @if ($errors->has('CA_CLOSEBY'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_CLOSEBY') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
    <!--                            <div class="form-group">
                                    {{ Form::label('CA_CLOSEBY','Penutupan Aduan Oleh ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CLOSEBY', $mUser->name, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>-->
                                <div class="form-group{{ $errors->has('CA_INVSTS') ? ' has-error' : '' }}">
                                    {{ Form::label('CA_INVSTS', 'Status', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('state_cd', Ref::GetDescr('292', $mPenutupan->CA_INVSTS, 'ms'), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        {{-- Form::select('CA_INVSTS', Ref::GetList('292', true, 'ms'), $mPenutupan->CA_INVSTS, ['class' => 'form-control input-sm', 'disabled' => true]) --}}
                                        @if ($errors->has('CA_INVSTS'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_INVSTS') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
<!--                                <div class="form-group">
                                    {{-- Form::label('CA_FILEREF','No. Rujukan ', ['class' => 'col-md-6 control-label']) --}}
                                    <div class="col-sm-6">
                                        {{-- Form::text('CA_FILEREF', $mPenutupan->CA_FILEREF, ['class' => 'form-control input-sm']) --}}
                                    </div>
                                </div>-->
                                @if($mGabungAll)
                                    <div class="form-group">
                                        {{ Form::label('', 'Gabung Aduan', ['class' => 'col-lg-6 control-label']) }}
                                        <div class="col-lg-6">
                                        @foreach ($mGabungAll as $gabung)
                                            <span class="help-block m-b-none">{{ $gabung->CR_CASEID }}</span>
                                        @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    {{ Form::label('CA_INVBY','Pegawai Penyiasat/Serbuan', ['class' => 'col-md-6 control-label']) }}
                                    <div class="col-sm-6">
                                        <!-- {{-- Form::text('', $mPenutupan->CA_INVBY != '' ? $mPenutupan->namapenyiasat->name : '', ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'InvByName']) --}} -->
                                        {{ Form::text('', count($mPenutupan->namapenyiasat) == '1'? $mPenutupan->namapenyiasat->name : $mPenutupan->CA_INVBY, ['class' => 'form-control input-sm', 'readonly' => true, 'id' => 'InvByName']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('sarananterdahulu', 'Saranan Terdahulu', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('sarananterdahulu', $mPenutupanDetailOld != '' ? $mPenutupanDetailOld->CD_DESC : '', ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('CA_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('CA_ANSWER', $mPenutupan->CA_ANSWER, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('CA_RESULT', 'Hasil Siasatan', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('CA_RESULT', $mPenutupan->CA_RESULT, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('CD_DESC','Saranan ', ['class' => 'col-md-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('CD_DESC', (!empty($mPenutupanDetail)) ? $mPenutupanDetail->CD_DESC : '', ['class' => 'form-control input-sm', 'rows' => 5]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12" align="center">
                                 @if($mPenutupan->CA_INVSTS != 9)
                                {{ Form::submit('Hantar', ['class' => 'btn btn-primary btn-sm']) }}
                                @endif
                                <a href="{{ url('tutup')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
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
                                        {{ Form::text('CA_CASEID', $mPenutupan->CA_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_BRNCD','Nama Cawangan', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <!--{{-- Form::text('CA_BRNCD', $mPenutupan->CA_BRNCD, ['class' => 'form-control input-sm','readonly' => true]) --}}-->
                                        {{ Form::text('CA_BRNCD', $mPenutupan->CA_BRNCD ? Branch::GetBranchName($mPenutupan->CA_BRNCD) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_FILEREF','No Rujukan ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_FILEREF', $mPenutupan->CA_FILEREF, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_RCVDT','Tarikh Penerimaan ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_RCVDT', $mPenutupan->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($mPenutupan->CA_RCVDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_CREDT','Tarikh Cipta ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CREDT', $mPenutupan->CA_CREDT != '' ? date('d-m-Y h:i A', strtotime($mPenutupan->CA_CREDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
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
                                        <!--{{-- Form::select('CA_RCVTYP', Ref::GetList('259', true), $mPenutupan->CA_RCVTYP, ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                        {{ Form::text('CA_RCVTYP', $mPenutupan->CA_RCVTYP != ''? Ref::GetDescr('259', $mPenutupan->CA_RCVTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                                        {{ Form::text('CA_RCVBY', $mPenutupan->CA_RCVBY,  ['class' => 'form-control input-sm', 'readonly' => true]) }}
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
                                    {{ Form::label('CA_SUMMARY','Aduan ', ['class' => 'col-md-1 control-label']) }}
                                    <div class="col-sm-11">
                                        {{ Form::textarea('CA_SUMMARY', $mPenutupan->CA_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 5, 'readonly' => true]) }}
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
                                        <!--{{-- Form::text('CA_ASGBY', !empty($mPenutupan->CA_ASGBY) ? $mPenutupan->namaPemberiTugas->name : '', ['class' => 'form-control input-sm','readonly' => true]) --}}-->
                                        {{ Form::text('CA_ASGBY', count($mPenutupan->namaPemberiTugas) == '1' ? $mPenutupan->namaPemberiTugas->name : $mPenutupan->CA_ASGBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_INVBY','Pegawai Penyiasat/Serbuan ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <!--{{-- Form::text('CA_INVBY', !empty($mPenutupan->CA_INVBY) ? $mPenutupan->namapenyiasat->name : '', ['class' => 'form-control input-sm','readonly' => true]) --}}-->
                                        {{ Form::text('CA_INVBY', count($mPenutupan->namapenyiasat) == '1' ? $mPenutupan->namapenyiasat->name : $mPenutupan->CA_INVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_ASGDT','Tarikh Penugasan  ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ASGDT', $mPenutupan->CA_ASGDT != '' ? date('d-m-Y h:i A', strtotime($mPenutupan->CA_ASGDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_CLOSEDT','Tarikh Selesai ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CLOSEDT', $mPenutupan->CA_COMPLETEDT != '' ? date('d-m-Y h:i A', strtotime($mPenutupan->CA_COMPLETEDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
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
                                        {{ Form::select('CA_INVSTS', Ref::GetList('292', true, 'ms'), old('CA_INVSTS', $mPenutupan->CA_INVSTS), ['class' => 'form-control input-sm', 'id' => 'CA_INVSTS','disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_PREMISE','Kod Premis ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), old('CA_AGAINST_PREMISE', $mPenutupan->CA_AGAINST_PREMISE), ['class' => 'form-control input-sm','disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_CLOSEBY','Ditutup Oleh ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CLOSEBY', $mPenutupan->CA_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_CMPLCAT','Kategori Aduan ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, 'ms'), old('CA_CMPLCAT', $mPenutupan->CA_CMPLCAT), ['class' => 'form-control input-sm required', 'id' => 'CA_CMPLCAT','disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_CMPLCD','SubKategori ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_CMPLCD', $mPenutupan->CA_CMPLCD == ''? ['-- SILA PILIH --'] : Ref::GetList('634', true, 'ms'), old('CA_CMPLCD', $mPenutupan->CA_CMPLCD), ['class' => 'form-control input-sm','disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_CLOSEBY','Tarikh Ditutup ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CLOSEBY', $mPenutupan->CA_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('CA_RESULT','Hasil Siasatan ', ['class' => 'col-md-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::textarea('CA_RESULT', $mPenutupan->CA_RESULT, ['class' => 'form-control input-sm','readonly' => true,'rows' => 5]) }}
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
                        <div class="hr-line-solid"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_NAME','Nama Pengadu ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_NAME', $mPenutupan->CA_NAME, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_CASEID','Bangsa ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_SEXCD','Jantina ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{-- Form::text('CA_SEXCD', $mPenutupan->CA_SEXCD, ['class' => 'form-control input-sm','readonly' => true]) --}}
                                         {{ Form::select('CA_SEXCD', Ref::GetList('202 ', true), $mPenutupan->CA_SEXCD, ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_ADDR','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::textarea('CA_ADDR', $mPenutupan->CA_ADDR, ['class' => 'form-control input-sm','rows' => 3,'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_DOCNO','No. KP atau Passport ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_DOCNO', $mPenutupan->CA_DOCNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_NATCD','Warganegara ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{-- Form::text('CA_NATCD', $mPenutupan->CA_NATCD, ['class' => 'form-control input-sm','readonly' => true]) --}}
                                         {{ Form::select('CA_NATCD', Ref::GetList('199 ', true), $mPenutupan->CA_NATCD, ['class' => 'form-control input-sm', 'id' => 'CA_NATCD', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_TELNO', $mPenutupan->CA_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_MOBILENO','No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_MOBILENO', $mPenutupan->CA_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_EMAIL', $mPenutupan->CA_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
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
                                        {{ Form::text('CA_AGAINSTNM', $mPenutupan->CA_AGAINSTNM, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINSTADD','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::textarea('CA_AGAINSTADD', $mPenutupan->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows' => 3,'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_AGAINST_TELNO', $mPenutupan->CA_AGAINST_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_MOBILENO','No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_AGAINST_MOBILENO', $mPenutupan->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_FAXNO','No. Faks ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_AGAINST_FAXNO', $mPenutupan->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_AGAINST_EMAIL', $mPenutupan->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
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
                        <table id="penutupan-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                    <h4>GABUNGAN ADUAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="table-responsive">
                        <table id="penutupan-gabung-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                                    <th>Surat Awam</th>
                                    <th>Surat Admin</th>
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

<div id="carian-penutupan" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title">Carian Pegawai Pengesahan Penutupan</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('icnew', 'No. Kad Pengenalan', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('icnew', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('name', 'Nama Pegawai', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{-- Form::select('state_cd', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_cd_user']) --}}
                                {{ Form::text('state_cd', Ref::GetDescr('17', $mUser->state_cd), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('brn_cd') ? ' has-error' : '' }}">
                            {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{-- Form::select('brn_cd', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm']) --}}
                                {{ Form::text('brn_cd', Branch::GetBranchName($mUser->brn_cd), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('role_code', 'Peranan', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::select('role_code', Ref::GetList('152', true), null, ['class' => 'form-control input-sm', 'id' => 'role_code']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" align="center">
                        {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                        {{ Form::reset('Semula', ['class' => 'btn btn-default btn-sm', 'id' => 'resetbtn']) }}
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="table-responsive">
                    <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>No. Kad Pengenalan</th>
                                <th>Nama Pegawai</th>
                                <th>Negeri</th>
                                <th>Cawangan</th>
                                <th>Peranan</th>
                                <th>Tindakan</th>
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

<!-- Modal Start -->
<!--{{-- @include('aduan.tugas.usersearchmodal') --}}-->
<!-- Modal End -->

@stop

@section('script_datatable')
<script>
    
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
            url: "{{ url('tutup/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("CloseByName").value = key;
                    document.getElementById("CloseById").value = value;
                });
                $('#carian-penutupan').modal('hide');
            }
        });
    };
    
    $(document).ready(function(){
        
        var SorotanTable = $('#sorotan-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bInfo: false,
            bPaginate: false,
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
                url: "{{ url('/tutup/gettransaction', $mPenutupan->CA_CASEID)}}",
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
        
        var GabungTable = $('#penutupan-gabung-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bInfo: false,
            bPaginate: false,
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
//                url: "{{ url('/tutup/getgabungkes', $mPenutupan->CA_CASEID) }}",
//                siasat/getgabung/{CASEID}
                url: "{{ url('/tutup/getgabung', $mPenutupan->CA_CASEID) }}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CR_CASEID', name: 'CR_CASEID'},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY', orderable: false},
                {data: 'CA_RCVDT', name: 'CA_RCVDT', orderable: false},
            ]
        });
        
         var AttachmentTable = $('#penutupan-attachment-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            rowId: 'id',
            bInfo: false,
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
                url: "{{ url('/tutup/getattachment', $mPenutupan->CA_CASEID) }}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
                {data: 'CC_REMARKS', name: 'CC_REMARKS', orderable: false},
//                {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
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
                url: "{{ url('siasat/getAttachmentSiasat', $mPenutupan->CA_CASEID) }}"
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
                {data: 'CC_REMARKS', name: 'CC_REMARKS', orderable: false}
            ]
        });
        
        $('#UserSearchModal').on('click', function (e) {
            $("#carian-penutupan").modal();
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
//                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                infoFiltered: '',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('tutup/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
//                    d.state_cd = $('#state_cd_user').val();
                    d.state_cd = '{{ Auth::User()->state_cd }}';
//                    d.brn_cd = $('#brn_cd').val();
                    d.brn_cd = '{{ Auth::User()->brn_cd }}';
                    d.role_code = $('#role_code').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '5%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'role.role_code', name: 'role.role_code'},
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
</script>
@stop