@extends('layouts.main')
<?php
    use App\Ref;
    use App\Aduan\AdminCase;
    use App\Aduan\AdminCaseDoc;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
</style>
<h2>Semakan Aduan Integriti (Maklumat Tidak Lengkap)</h2>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="">
            <!-- <a href='{{-- $model->CA_INVSTS == "7" ? route("kemaskini.edit", $model->CA_CASEID) : "" --}}'> -->
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                MAKLUMAT ADUAN
            </a>
        </li>
        <li class="">
            <!-- <a href='{{-- $model->CA_INVSTS == "7" ? route("kemaskini.docbuktiaduan", $model->CA_CASEID) : "" --}}'> -->
            <a>
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
    </ul>
    <div class="tab-content">
        <div id="caseinfo" class="tab-pane active">
            <div class="panel-body">
                {!! Form::open(['route' => ['integritikemaskini.submit', $model->id], 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <h4>Cara Terima</h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <strong>{{ $model->IN_CASEID }}</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_RCVDT', 'Tarikh', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        {{ $model->IN_RCVDT ? date('d-m-Y h:i A', strtotime($model->IN_RCVDT)) : '' }}
                                    </p>
                                    @if ($errors->has('IN_RCVDT'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RCVDT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVBY', 'Penerima', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        {{ $model->rcvby ? $model->rcvby->name : $model->IN_RCVBY }}
                                    </p>
                                    @if ($errors->has('IN_RCVBY'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RCVBY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVTYP', 'Cara Penerimaan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        {{ $model->rcvtyp ? $model->rcvtyp->descr : $model->IN_RCVTYP }}
                                    </p>
                                    @if ($errors->has('IN_RCVTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_CHANNEL') ? ' has-error' : '' }}">
                                {{ Form::label('IN_CHANNEL', 'Saluran', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        @if($model->IN_CHANNEL == 'SPRM')
                                            Suruhanjaya Pencegahan Rasuah Malaysia (SPRM)
                                        @elseif($model->IN_CHANNEL == 'BPA')
                                            Biro Pengaduan Awam (BPA)
                                        @elseif($model->IN_CHANNEL == 'PDRM')
                                            Polis Diraja Malaysia (PDRM)
                                        @endif
                                    </p>
                                    @if ($errors->has('IN_CHANNEL'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_CHANNEL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Maklumat Pengadu</h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    <p class="form-control-static">
                                        {{ $model->IN_DOCNO }}
                                    </p>
                                    @if ($errors->has('IN_DOCNO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('IN_EMAIL', 'Emel', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    <p class="form-control-static">
                                        {{ $model->IN_EMAIL }}
                                    </p>
                                    @if ($errors->has('IN_EMAIL'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    <p class="form-control-static">
                                        {{ $model->IN_MOBILENO }}
                                    </p>
                                    @if ($errors->has('IN_MOBILENO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    <p class="form-control-static">
                                        {{ $model->IN_TELNO }}
                                    </p>
                                    @if ($errors->has('IN_TELNO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_FAXNO', 'No. Faks', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    <p class="form-control-static">
                                        {{ $model->IN_FAXNO }}
                                    </p>
                                    @if ($errors->has('IN_FAXNO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('IN_ADDR', 'Alamat', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    <p class="form-control-static">
                                        {!! nl2br(htmlspecialchars($model->IN_ADDR)) !!}
                                    </p>
                                    @if ($errors->has('IN_ADDR'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_NAME') ? ' has-error' : '' }}">
                                {{ Form::label('IN_NAME', 'Nama', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->IN_NAME }}
                                    </p>
                                    @if ($errors->has('IN_NAME'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGE', 'Umur', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->IN_AGE }}
                                    </p>
                                    @if ($errors->has('IN_AGE'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_SEXCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SEXCD', 'Jantina', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->sexcd ? $model->sexcd->descr : '' }}
                                    </p>
                                    @if ($errors->has('IN_SEXCD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RACECD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RACECD', 'Bangsa', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->racecd ? $model->racecd->descr : '' }}
                                    </p>
                                    @if ($errors->has('IN_RACECD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RACECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_NATCD', 'Warganegara', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->natcd ? $model->natcd->descr : '' }}
                                    </p>
                                </div>
                            </div>
                            <div id="warganegara" style="display:block">
                                <div class="form-group {{ $errors->has('IN_POSTCD') ? ' has-error' : 'IN_POSTCD' }}">
                                    {{ Form::label('IN_POSTCD', 'Poskod', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <p class="form-control-static">
                                            {{ $model->IN_POSTCD }}
                                        </p>
                                        @if ($errors->has('IN_POSTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_POSTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('IN_STATECD') ? ' has-error' : 'IN_STATECD' }}">
                                    {{ Form::label('IN_STATECD', 'Negeri', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <p class="form-control-static">
                                            {{ $model->instatecd ? $model->instatecd->descr : '' }}
                                        </p>
                                        @if ($errors->has('IN_STATECD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('IN_DISTCD') ? ' has-error' : 'IN_DISTCD' }}">
                                    {{ Form::label('IN_DISTCD', 'Daerah', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <p class="form-control-static">
                                            {{ $model->indistcd ? $model->indistcd->descr : '' }}
                                        </p>
                                        @if ($errors->has('IN_DISTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="bknwarganegara" style="display: {{ $model->IN_NATCD == '0' ? 'block' : 'none' }}">
                                <div class="form-group {{ $errors->has('IN_COUNTRYCD') ? ' has-error' : 'IN_COUNTRYCD' }}">
                                    {{ Form::label('IN_COUNTRYCD', 'Negara', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        <p class="form-control-static">
                                            {{ $model->countrycd ? $model->countrycd->descr : '' }}
                                        </p>
                                        @if ($errors->has('IN_COUNTRYCD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_COUNTRYCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Maklumat Aduan</h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('IN_AGAINSTNM', 'Nama Pegawai Yang Diadu', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->IN_AGAINSTNM }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_REFTYPE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_REFTYPE', 'Jenis Rujukan Berkaitan', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        @if($model->IN_REFTYPE == 'BGK')
                                            Salah Laku (Aduan Kepenggunaan)
                                        @elseif($model->IN_REFTYPE == 'TTPM')
                                            Salah Laku (TTPM)
                                        @elseif($model->IN_REFTYPE == 'OTHER')
                                            Salah Laku (Umum)
                                        @endif
                                    </p>
                                    @if ($errors->has('IN_REFTYPE'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_REFTYPE') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_bgk" 
                            class="form-group{{ $errors->has('IN_BGK_CASEID') ? ' has-error' : '' }}"
                            style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'BGK' ? 'block' : 'none') : ($model->IN_REFTYPE == 'BGK' ? 'block' : 'none') }};">
                                {{ Form::label('IN_BGK_CASEID', 'Aduan Kepenggunaan', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->IN_BGK_CASEID }}
                                    </p>
                                    @if ($errors->has('IN_BGK_CASEID'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_BGK_CASEID') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_ttpm" 
                            class="form-group {{ $errors->has('IN_TTPMNO') ? ' has-error' : '' }}"
                            style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none') : ($model->IN_REFTYPE == 'TTPM' ? 'block' : 'none') }};">
                                {{ Form::label('IN_TTPMNO', 'No. TTPM', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->IN_TTPMNO }}
                                    </p>
                                    @if ($errors->has('IN_TTPMNO'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_TTPMNO') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_ttpmform" 
                                class="form-group {{ $errors->has('IN_TTPMFORM') ? ' has-error' : '' }}"
                                style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none') : ($model->IN_REFTYPE == 'TTPM' ? 'block' : 'none') }};">
                                {{ Form::label('IN_TTPMFORM', 'Jenis Borang TTPM', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        @if($model->IN_TTPMFORM == '8')
                                            Borang 8 - Award bagi pihak yang menuntut jika penentang tidak hadir
                                        @elseif($model->IN_TTPMFORM == '9')
                                            Borang 9 - Award dengan persetujuan
                                        @elseif($model->IN_TTPMFORM == '10')
                                            Borang 10 - Award selepas pendengaran
                                        @endif
                                    </p>
                                    @if ($errors->has('IN_TTPMFORM'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_TTPMNO') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_other" 
                                class="form-group {{ $errors->has('IN_REFOTHER') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'OTHER' ? 'block' : 'none') : ($model->IN_REFTYPE == 'OTHER' ? 'block' : 'none') }};">
                                {{ 
                                    Form::label('IN_REFOTHER', 
                                    'Lain-lain', 
                                    ['class' => 'col-lg-3 control-label']) 
                                }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->IN_REFOTHER }}
                                    </p>
                                    @if ($errors->has('IN_REFOTHER'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_REFOTHER') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_AGAINSTLOCATION') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGAINSTLOCATION', 'Lokasi PYDA', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        @if($model->IN_AGAINSTLOCATION == 'BRN')
                                            Bahagian / Cawangan KPDNHEP
                                        @elseif($model->IN_AGAINSTLOCATION == 'AGN')
                                            Agensi KPDNHEP
                                        @endif
                                    </p>
                                    @if ($errors->has('IN_AGAINSTLOCATION'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_AGAINSTLOCATION') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_IN_AGAINST_BRSTATECD" 
                                class="form-group {{ $errors->has('IN_AGAINST_BRSTATECD') ? ' has-error' : '' }}" 
                                style="display: {{ 
                                    old('IN_AGAINSTLOCATION') 
                                    ? (old('IN_AGAINSTLOCATION') == 'BRN'? 'block' : 'none') 
                                    : ($model->IN_AGAINSTLOCATION == 'BRN' ? 'block' : 'none')
                                }};">
                                {{ Form::label('IN_AGAINST_BRSTATECD', 
                                    'Negeri', 
                                    ['class' => 'col-lg-3 control-label']) 
                                }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->againstbrstatecd ? $model->againstbrstatecd->descr : $model->IN_AGAINST_BRSTATECD }}
                                    </p>
                                    @if ($errors->has('IN_AGAINST_BRSTATECD'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_AGAINST_BRSTATECD') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_IN_BRNCD" 
                                class="form-group {{ $errors->has('IN_BRNCD') ? ' has-error' : '' }}" 
                                style="display: {{ 
                                    old('IN_AGAINSTLOCATION') 
                                    ? (old('IN_AGAINSTLOCATION') == 'BRN'? 'block' : 'none') 
                                    : ($model->IN_AGAINSTLOCATION == 'BRN' ? 'block' : 'none')
                                }};">
                                {{ Form::label('IN_BRNCD', 'Bahagian / Cawangan', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->brncd ? $model->brncd->BR_BRNNM : $model->IN_BRNCD }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div_IN_AGENCYCD" 
                                class="form-group {{ $errors->has('IN_AGENCYCD') ? ' has-error' : '' }}" 
                                style="display: {{ 
                                    old('IN_AGAINSTLOCATION') 
                                    ? (old('IN_AGAINSTLOCATION') == 'AGN'? 'block' : 'none') 
                                    : ($model->IN_AGAINSTLOCATION == 'AGN' ? 'block' : 'none')
                                }};">
                                {{ 
                                    Form::label('IN_AGENCYCD', 
                                    'Agensi KPDNHEP', 
                                    ['class' => 'col-lg-3 control-label']) 
                                }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->agencycd ? $model->agencycd->MI_DESC : $model->IN_AGENCYCD }}
                                    </p>
                                    @if ($errors->has('IN_AGENCYCD'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IN_AGENCYCD') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('IN_SUMMARY_TITLE', 'Tajuk Aduan', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $model->IN_SUMMARY_TITLE }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IN_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {!! nl2br(htmlspecialchars($model->IN_SUMMARY)) !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Lampiran Aduan</h4>
                            <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                            <table>
                                <tr>
                                    @foreach($modelDoc as $doc)
                                        <?php $ExtFile = substr($doc->IC_DOCFULLNAME, -3); ?>
                                        @if($ExtFile == 'pdf' || $ExtFile == 'PDF')
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($doc->IC_PATH.$doc->IC_DOCNAME) }}" target="_blank">
                                                        <img src="{{ url('img/PDF.png') }}" class="img-lg img-thumbnail"/>
                                                        <br />
                                                        {{ $doc->IC_DOCFULLNAME }}
                                                    </a>
                                                </div>
                                            </td>
                                        @else
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($doc->IC_PATH.$doc->IC_DOCNAME) }}" target="_blank">
                                                        <img src="{{ Storage::disk('bahanpath')->url($doc->IC_PATH.$doc->IC_DOCNAME) }}" class="img-lg img-thumbnail"/>
                                                        <br />
                                                        {{ $doc->IC_DOCFULLNAME }}
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
                            <a class="btn btn-success btn-sm" href="{{ route('integritikemaskini.attachment',$model->id) }}">
                                <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Sebelum
                            </a>
                            <a class="btn btn-warning btn-sm" href="{{ url('integritikemaskini') }}">Kembali</a>
                            <a id="SubmitBtn" class="btn btn-success btn-sm" href="{{ route('integritikemaskini.submit',$model->id) }}">
                                Hantar&nbsp;&nbsp;<i class="fa fa-chevron-right"></i>
                            </a>
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
    $('#SubmitBtn').click(function(e) {
        var Confirm = confirm('Anda pasti untuk hantar aduan ini?');
        if(Confirm) {
            return true;
        }else{
            return false;
        }
    });
</script>
@stop
