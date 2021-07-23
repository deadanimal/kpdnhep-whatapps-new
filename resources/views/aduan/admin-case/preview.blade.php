@extends('layouts.main')
<?php
//    use App\Aduan\AdminCase;
//    use App\Aduan\AdminCaseDoc;
?>
@section('content')
    <style>
        textarea {
            resize: vertical;
        }

        .label-black {
            color: black;
        }
    </style>
    <h2>Semakan Aduan</h2>
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="">
                <a href='{{ $model->CA_INVSTS == "10" ? route("admin-case.edit", $model->id) : "" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                    MAKLUMAT ADUAN
                </a>
            </li>
            <li class="">
                <a href='{{ $model->CA_INVSTS == "10" ? route("admin-case.attachment", $model->id) : "" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                    LAMPIRAN
                </a>
            </li>
            <li class="active">
                <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                    SEMAKAN ADUAN
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="caseinfo" class="tab-pane active">
                <div class="panel-body">
                <!--{{-- Form::open(['url' => ['admin-case', $model->id], 'class' => 'form-horizontal']) --}}-->
                    {!! Form::open(['route' => ['admin-case.submit', $model->id], 'class' => 'form-horizontal', 'id' => 'submit-form']) !!}
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <h4>Cara Terima</h4>
                    <!--<div class="hr-line-solid"></div>-->
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_RCVDT', 'Tarikh', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{-- Form::text('CA_RCVDT', date('d-m-Y h:i A', strtotime($model->CA_RCVDT)), ['class' => 'form-control input-sm', 'readonly' => 'true']) --}}
                                    <p class="form-control-static">{{ date('d-m-Y h:i A', strtotime($model->CA_RCVDT)) }}</p>
                                    @if ($errors->has('CA_RCVDT'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVDT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVBY', 'Penerima', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    <!--<div class="input-group">-->
                                {{ Form::hidden('CA_RCVBY', $model->CA_RCVBY, ['class' => 'form-control input-sm', 'id' => 'RCVBY_id']) }}
                                {{-- Form::text('', $RcvBy, ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'RCVBY_name']) --}}
                                <p class="form-control-static">{{ $RcvBy }}</p>
                                <!--<span class="input-group-btn">-->
                                    <!--<a data-toggle="modal" class="btn btn-primary btn-sm" href="#carian-penerima">Carian</a>-->
                                    <!--</span>-->
                                    @if ($errors->has('CA_RCVBY'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVBY') }}</strong></span>
                                @endif
                                <!--</div>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                {{-- Form::text('CA_RCVTYP', $model->CA_RCVTYP != ''? Ref::GetDescr('259', $model->CA_RCVTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                <!--{{-- Form::select('CA_RCVTYP', AdminCase::getRefList('259', true), old('CA_RCVTYP', $model->CA_RCVTYP), ['class' => 'form-control input-sm']) --}}-->
                                <p class="form-control-static">{{ $model->receivedType ? $model->receivedType->descr : '' }}</p>
                                    @if ($errors->has('CA_RCVTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group"
                                 style="display: {{ in_array($model->CA_RCVTYP,['S14', 'S34']) ? 'block':'none' }};">
                                {{ Form::label('CA_BPANO', 'No. Aduan BPA', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('CA_BPANO', $model->CA_BPANO, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_BPANO }}</p>
                                </div>
                            </div>
                            <div class="form-group"
                                 style="display: {{ in_array($model->CA_RCVTYP,['S33', 'S34'])? 'block':'none' }};">
                                {{ Form::label('CA_SERVICENO', 'No. Tali Khidmat', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('CA_SERVICENO', $model->CA_SERVICENO, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_SERVICENO }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Maklumat Pengadu</h4>
                    <!--<div class="hr-line-solid"></div>-->
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
                                    {{-- Form::text('CA_DOCNO', old('CA_DOCNO', $model->CA_DOCNO), ['class' => 'form-control input-sm', 'disabled' => 'true']) --}}
                                    <p class="form-control-static">{{ $model->CA_DOCNO }}</p>
                                    @if ($errors->has('CA_DOCNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_EMAIL', 'Emel', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
                                    {{-- Form::text('CA_EMAIL', old('CA_EMAIL', $model->CA_EMAIL), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_EMAIL }}</p>
                                    @if ($errors->has('CA_EMAIL'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
                                    {{-- Form::text('CA_MOBILENO', old('CA_MOBILENO', $model->CA_MOBILENO), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_MOBILENO }}</p>
                                    @if ($errors->has('CA_MOBILENO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
                                    {{-- Form::text('CA_TELNO', old('CA_TELNO', $model->CA_TELNO), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_TELNO }}</p>
                                    @if ($errors->has('CA_TELNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_FAXNO', 'No. Faks', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
                                    {{-- Form::text('CA_FAXNO', old('CA_FAXNO', $model->CA_FAXNO), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_FAXNO }}</p>
                                    @if ($errors->has('CA_FAXNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ADDR', 'Alamat', ['class' => 'col-sm-6 control-label']) }}
                                <div class="col-sm-6">
                                    {{-- Form::textarea('CA_ADDR', old('CA_ADDR', $model->CA_ADDR), ['class' => 'form-control input-sm', 'rows'=>'4', 'readonly' => true]) --}}
                                    <p class="form-control-static">{!! nl2br(htmlspecialchars($model->CA_ADDR)) !!}</p>
                                    @if ($errors->has('CA_ADDR'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('CA_NAME') ? ' has-error' : '' }}">
                                {{ Form::label('CA_NAME', 'Nama', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{-- Form::text('CA_NAME', old('CA_NAME', $model->CA_NAME), ['class' => 'form-control input-sm', 'disabled' => 'true']) --}}
                                    <p class="form-control-static">{{ $model->CA_NAME }}</p>
                                    @if ($errors->has('CA_NAME'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGE', 'Umur', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{-- Form::text('CA_AGE', $model->CA_AGE, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_AGE }}</p>
                                    @if ($errors->has('CA_AGE'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_SEXCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                <!--{{-- Form::select('CA_SEXCD', Ref::GetList('202', true, 'ms'), old('CA_SEXCD', $model->CA_SEXCD), ['class' => 'form-control input-sm', 'id' => 'CA_SEXCD', 'disabled' => 'true']) --}}-->
                                    {{-- Form::text('CA_SEXCD', $model->CA_SEXCD != ''? Ref::GetDescr('202', $model->CA_SEXCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->sex ? $model->sex->descr : '' }}</p>
                                    @if ($errors->has('CA_SEXCD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_RACECD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_RACECD', 'Bangsa', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{-- Form::text('CA_RACECD', $model->CA_RACECD != ''? Ref::GetDescr('580', $model->CA_RACECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->race ? $model->race->descr : '' }}</p>
                                    @if ($errors->has('CA_RACECD'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_RACECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{-- Form::text('CA_NATCD', ($model->CA_NATCD != '' ? Ref::GetDescr('947', $model->CA_NATCD, 'ms') : ''), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->nationality ? $model->nationality->descr : '' }}</p>
                                </div>
                            </div>
                        <!--<div id="warganegara" style="display: {{ $model->CA_NATCD == '1' ? 'block' : 'none' }}">-->
                            <div id="warganegara" style="display:block">
                                <div class="form-group {{ $errors->has('CA_POSCD') ? ' has-error' : 'CA_POSCD' }}">
                                    {{ Form::label('CA_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{-- Form::text('CA_POSCD', old('CA_POSCD', $model->CA_POSCD), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                        <p class="form-control-static">{{ $model->CA_POSCD }}</p>
                                        @if ($errors->has('CA_POSCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_POSCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_STATECD') ? ' has-error' : 'CA_STATECD' }}">
                                    {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{-- Form::text('CA_STATECD', $model->CA_STATECD != ''? Ref::GetDescr('17', $model->CA_STATECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                        <p class="form-control-static">{{ $model->negeripengadu ? $model->negeripengadu->descr : '' }}</p>
                                        @if ($errors->has('CA_STATECD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('CA_DISTCD') ? ' has-error' : 'CA_DISTCD' }}">
                                    {{ Form::label('CA_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{-- Form::text('CA_DISTCD', ($model->CA_DISTCD != '' ? Ref::GetDescr('18', $model->CA_DISTCD, 'ms') : ''), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                        <p class="form-control-static">{{ $model->daerahpengadu ? $model->daerahpengadu->descr : '' }}</p>
                                        @if ($errors->has('CA_DISTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="bknwarganegara" style="display: {{ $model->CA_NATCD == '0' ? 'block' : 'none' }}">
                                <div class="form-group {{ $errors->has('CA_COUNTRYCD') ? ' has-error' : 'CA_COUNTRYCD' }}">
                                    {{ Form::label('CA_COUNTRYCD', 'Negara', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                    <!--{{-- Form::select('CA_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('CA_COUNTRYCD', $model->CA_COUNTRYCD), ['class' => 'form-control input-sm']) --}}-->
                                        {{-- Form::text('CA_COUNTRYCD', $model->CA_COUNTRYCD != ''? Ref::GetDescr('334', $model->CA_COUNTRYCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                        <p class="form-control-static">{{ $model->country ? $model->country->descr : '' }}</p>
                                        @if ($errors->has('CA_COUNTRYCD'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_COUNTRYCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Maklumat Aduan</h4>
                    <!--<div class="hr-line-solid"></div>-->
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCAT', 'Kategori', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_CMPLCAT', $model->CA_CMPLCAT != ''? Ref::GetDescr('244', $model->CA_CMPLCAT, 'ms') : '', array('class' => 'form-control input-sm', 'readonly' => true)) --}}
                                    <p class="form-control-static">{{ $model->category ? $model->category->descr : '' }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_CMPLCD', 'Subkategori', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_CMPLCD', $model->CA_CMPLCD != ''? Ref::GetDescr('634', $model->CA_CMPLCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->subCategory ? $model->subCategory->descr : '' }}</p>
                                </div>
                            </div>
                            <div id="CA_TTPMTYP"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};"
                                 class="form-group">
                                {{ Form::label('CA_TTPMTYP', 'Penuntut/Penentang', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_TTPMTYP', $model->CA_TTPMTYP != ''? Ref::GetDescr('1108', $model->CA_TTPMTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->ttpmType ? $model->ttpmType->descr : '' }}</p>
                                </div>
                            </div>
                            <div id="CA_TTPMNO"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 08'? 'block':'none')) }};"
                                 class="form-group">
                                {{ Form::label('CA_TTPMNO', 'No. TTPM', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_TTPMNO', old('CA_TTPMNO', $model->CA_TTPMNO), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_TTPMNO }}</p>
                                </div>
                            </div>
                        <!--<div id="div_CA_ONLINECMPL_AMOUNT" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">-->
                            <div class="form-group">
                                {{ Form::label('CA_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_ONLINECMPL_AMOUNT', $model->CA_ONLINECMPL_AMOUNT, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_ONLINECMPL_AMOUNT }}</p>
                                </div>
                            </div>
                            <div class="form-group" id="CA_CMPLKEYWORD"
                                 style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($model->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none')))  }};">
                                {{ Form::label('CA_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_CMPLKEYWORD', $model->CA_CMPLKEYWORD != ''? Ref::GetDescr('1051', $model->CA_CMPLKEYWORD, 'ms'):'', ['class' => 'form-control  input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->productType ? $model->productType->descr : '' }}</p>
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_PREMISE"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($model->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};"
                                 class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_PREMISE', 'Jenis Premis', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_AGAINST_PREMISE', $model->CA_AGAINST_PREMISE != ''? Ref::GetDescr('221', $model->CA_AGAINST_PREMISE) : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->premiseType ? $model->premiseType->descr : '' }}</p>
                                </div>
                            </div>
                            <div style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                <h4>Maklumat Penjual / Pihak Yang Diadu</h4>
                                <hr style="background-color: #ccc; height: 1px; width: 206%; border: 0;">
                            </div>
                            <div id="div_CA_ONLINECMPL_PROVIDER"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};"
                                 class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_ONLINECMPL_PROVIDER', $model->CA_ONLINECMPL_PROVIDER != ''? Ref::GetDescr('1091', $model->CA_ONLINECMPL_PROVIDER, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->serviceProvider ? $model->serviceProvider->descr : '' }}</p>
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_URL"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};"
                                 class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_URL', 'Laman Web / URL / ID', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_ONLINECMPL_URL', $model->CA_ONLINECMPL_URL, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_ONLINECMPL_URL }}</p>
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_PYMNTTYP"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};"
                                 class="form-group{{ $errors->has('CA_ONLINECMPL_PYMNTTYP') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_ONLINECMPL_PYMNTTYP', $model->CA_ONLINECMPL_PYMNTTYP != ''? Ref::GetDescr('1207', $model->CA_ONLINECMPL_PYMNTTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->paymentType ? $model->paymentType->descr : '' }}</p>
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_BANKCD"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};"
                                 class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_BANKCD', 'Nama Bank', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_ONLINECMPL_BANKCD', $model->CA_ONLINECMPL_BANKCD != ''? Ref::GetDescr('1106', $model->CA_ONLINECMPL_BANKCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->bankName ? $model->bankName->descr : '' }}</p>
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_ACCNO"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};"
                                 class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_ACCNO', 'No. Akaun Bank / No. Transaksi FPX', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_ONLINECMPL_ACCNO', $model->CA_ONLINECMPL_ACCNO, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_ONLINECMPL_ACCNO }}</p>
                                </div>
                            </div>
                            <div class="form-group"
                                 style="display: {{ $model->CA_CMPLCAT == 'BPGK 19'? 'block':'none' }}">
                                {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <div class="checkbox checkbox-success">
                                        <input name="CA_ONLINECMPL_IND" disabled="disabled" id="CA_ONLINECMPL_IND"
                                               type="checkbox"
                                                {{ (old('CA_ONLINECMPL_IND') == 'on' || $model->CA_ONLINECMPL_IND == '1') ? 'checked' : '' }}>
                                        <label for="CA_ONLINECMPL_IND" class="label-black">
                                            Pernah membuat aduan secara rasmi kepada Pembekal Perkhidmatan?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="div_CA_ONLINECMPL_CASENO"
                                 style="display:
                                 {{ old('CA_CMPLCAT') == 'BPGK 19'
                                    ? (
                                        old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'
                                        ? 'block'
                                        : (
                                            $model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINECMPL_IND == '1'
                                            ? 'block'
                                            :'none'
                                        )
                                    )
                                    : (
                                        old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'
                                        ? (
                                            old('CA_ONLINECMPL_IND') == '' && $model->CA_ONLINECMPL_IND == '1'
                                            ? 'block'
                                            : (
                                                old('CA_ONLINECMPL_IND') == 'on'
                                                ? 'block'
                                                : 'none'
                                            )
                                        )
                                        : 'none'
                                    ) }}"
                                 class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_ONLINECMPL_CASENO',$model->CA_ONLINECMPL_CASENO, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_ONLINECMPL_CASENO }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div style="height: 190px; display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }}"></div>
                            <div class="form-group">
                                {{ Form::label('CA_AGAINSTNM', 'Nama (Syarikat/Premis/Penjual)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_AGAINSTNM', $model->CA_AGAINSTNM, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_AGAINSTNM }}</p>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_TELNO', 'No. Telefon (Pejabat)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_AGAINST_TELNO', $model->CA_AGAINST_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_AGAINST_TELNO }}</p>
                                    @if ($errors->has('CA_AGAINST_TELNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_AGAINST_MOBILENO', $model->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_AGAINST_MOBILENO }}</p>
                                    @if ($errors->has('CA_AGAINST_MOBILENO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::email('CA_AGAINST_EMAIL', $model->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_AGAINST_EMAIL }}</p>
                                    @if ($errors->has('CA_AGAINST_EMAIL'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_AGAINST_FAXNO', $model->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_AGAINST_FAXNO }}</p>
                                    @if ($errors->has('CA_AGAINST_FAXNO'))
                                        <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="checkinsertadd"
                                 style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};"
                                 class="form-group">
                                {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <div class="checkbox checkbox-success">
                                        <input name="CA_ONLINEADD_IND" disabled="disabled" id="CA_ONLINEADD_IND"
                                               type="checkbox"
                                               onclick="onlineaddind()" {{ (old('CA_ONLINEADD_IND') == 'on' || $model->CA_ONLINEADD_IND == '1') ? 'checked' : '' }}>
                                        <label for="CA_ONLINEADD_IND" class="label-black">
                                            Mempunyai alamat penuh penjual / pihak yang diadu?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="div_CA_AGAINSTADD"
                                 style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'
                                                    ? (
                                                        old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'
                                                        ? 'block'
                                                        : (
                                                            $model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'
                                                            ? 'block'
                                                            : 'none'
                                                        )
                                                    )
                                                    : (
                                                        old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'
                                                        ? (
                                                            old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'
                                                            ? 'block'
                                                            : (
                                                                old('CA_ONLINEADD_IND') == 'on'
                                                                ? 'block'
                                                                : 'none'
                                                            )
                                                        )
                                                        :'block'
                                                    )}};"
                                 class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINSTADD', 'Alamat', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::textarea('CA_AGAINSTADD', $model->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows'=> '4', 'readonly' => true]) --}}
                                    <p class="form-control-static">{!! nl2br(htmlspecialchars($model->CA_AGAINSTADD)) !!}</p>
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_POSTCD"
                                 style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'
                                                    ? (
                                                        old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'
                                                        ? 'block'
                                                        : (
                                                            $model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'
                                                            ? 'block'
                                                            : 'none'
                                                        )
                                                    )
                                                    : (
                                                        old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'
                                                        ? (
                                                            old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'
                                                            ? 'block'
                                                            : (
                                                                old('CA_ONLINEADD_IND') == 'on'
                                                                ? 'block'
                                                                : 'none'
                                                            )
                                                        )
                                                        : 'block'
                                                    ) }} ;"
                                 class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_POSTCD', 'Poskod', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_AGAINST_POSTCD', $model->CA_AGAINST_POSTCD, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->CA_AGAINST_POSTCD }}</p>
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_STATECD"
                                 style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'
                                                    ? (
                                                        old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'
                                                        ? 'block'
                                                        : (
                                                            $model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'
                                                            ? 'block'
                                                            : 'none'
                                                        )
                                                    )
                                                    : (
                                                        old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'
                                                        ? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'
                                                        ? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;"
                                 class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_STATECD', 'Negeri', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_AGAINST_STATECD', $model->CA_AGAINST_STATECD != ''? Ref::GetDescr('17', $model->CA_AGAINST_STATECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->againstState ? $model->againstState->descr : '' }}</p>
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_DISTCD"
                                 style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'
? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none'))
: (old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'
? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'
? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;"
                                 class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                                {{ Form::label('CA_AGAINST_DISTCD', 'Daerah', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{-- Form::text('CA_AGAINST_DISTCD', ($model->CA_AGAINST_DISTCD != '' ? Ref::GetDescr('18', $model->CA_AGAINST_DISTCD, 'ms') : ''), ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    <p class="form-control-static">{{ $model->againstDistrict ? $model->againstDistrict->descr : '' }}</p>
                                </div>
                            </div>
                        <!--                            <div class="form-group {{ $errors->has('CA_ROUTETOHQIND') ? ' has-error' : '' }}">
                                {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    <div class="checkbox checkbox-primary">
                                        <input id="CA_ROUTETOHQIND" disabled="disabled" type="checkbox" name="CA_ROUTETOHQIND" {{ $model->CA_ROUTETOHQIND == '1'? 'checked':'' }}>
                                        <label for="CA_ROUTETOHQIND" class="label-black">
                                            Hantar aduan ke Ibu Pejabat Penguatkuasa
                                        </label>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('CA_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-1 control-label']) }}
                                <div class="col-sm-11">
                                    {{-- Form::textarea('CA_SUMMARY', $model->CA_SUMMARY, ['class' => 'form-control input-sm', 'rows'=> '5', 'readonly' => true]) --}}
                                    <p class="form-control-static">{!! nl2br(htmlspecialchars($model->CA_SUMMARY)) !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h4>Lampiran Aduan</h4>
                            <!--<div class="hr-line-solid"></div>-->
                            <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                            <table>
                                <tr>
                                    @foreach($mAdminCaseDoc as $AdminCaseDoc)
                                        <?php $ExtFile = substr($AdminCaseDoc->CC_IMG_NAME, -3); ?>
                                        @if($ExtFile == 'pdf' || $ExtFile == 'PDF')
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($AdminCaseDoc->CC_PATH.$AdminCaseDoc->CC_IMG) }}"
                                                       target="_blank">
                                                        <img src="{{ url('img/PDF.png') }}"
                                                             class="img-lg img-thumbnail"/>
                                                        <br/>
                                                        {{ $AdminCaseDoc->CC_IMG_NAME }}
                                                    </a>
                                                </div>
                                            </td>
                                        @else
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($AdminCaseDoc->CC_PATH.$AdminCaseDoc->CC_IMG) }}"
                                                       target="_blank">
                                                        <img src="{{ Storage::disk('bahanpath')->url($AdminCaseDoc->CC_PATH.$AdminCaseDoc->CC_IMG) }}"
                                                             class="img-lg img-thumbnail"/>
                                                        <br/>
                                                        {{ $AdminCaseDoc->CC_IMG_NAME }}
                                                    </a>
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            <a class="btn btn-success btn-sm" href="{{ route('admin-case.attachment',$model->id) }}"><i
                                        class="fa fa-chevron-left"></i> Sebelum</a>
                            <a class="btn btn-warning btn-sm" href="{{ url('admin-case') }}">Kembali</a>
                        <!--<a id="SubmitBtn" class="btn btn-success btn-sm" href="{{-- route('admin-case.submit',$model->id) --}}">Hantar <i class="fa fa-chevron-right"></i></a>-->
                            {{ Form::button('Hantar'.' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'id' => 'SubmitBtn']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script_datatable')
    <script type="text/javascript">
      //    $('#SubmitBtn').click(function(e) {
      //        e.preventDefault();
      //        var Confirm = confirm('Anda pasti untuk hantar aduan ini?');
      //        if(Confirm) {
      //            return true;
      //        }else{
      //            return false;
      //        }
      //    });
      $(document).ready(function () {
        $('.form-control').attr('disabled', true)
      })
      $('#submit-form').submit(
        function () {
          var Confirm = confirm('Anda pasti untuk hantar aduan ini?')
          if (Confirm) {
            return true
          } else {
            return false
          }
        }
      )
    </script>
@stop
