@extends('layouts.main')
<?php
    use App\Branch;
    use App\Ref;
    use App\Aduan\AdminCase;
    use App\Integriti\IntegritiAdmin;
    use App\Integriti\IntegritiPublic;
?>
@section('content')
    <style>
        textarea {
            resize: vertical;
        }

        span.select2 {
            width: 100% !important;
        }

        .select2-dropdown {
            z-index: 3000 !important;
        }

        .help-block-red {
            color: red;
        }
    </style>
    <!--<div class="row">-->
    <!--<div class="col-lg-12">-->
    <!--<div class="ibox float-e-margins">-->
    <h2>Aduan Baru (Integriti)</h2>
    <!--<div class="ibox-content">-->
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                    MAKLUMAT ADUAN
                </a>
            </li>
            <li class="">
                <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                    LAMPIRAN
                </a>
            </li>
            <li class="">
                <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                    SEMAKAN ADUAN
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => 'integritiadmin.store', 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }}
                    <h4>Cara Terima</h4>
                    <!--<div class="hr-line-solid"></div>-->
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_RCVDT') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVDT', 'Tarikh', ['class' => 'col-lg-2 control-label']) }}
                                <div class="col-lg-10">
                                    {{ Form::text('IN_RCVDT', date('d-m-Y h:i A'), ['class' => 'form-control input-sm', 'readonly' => 'true']) }}
                                    @if ($errors->has('IN_RCVDT'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RCVDT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVBY', 'Penerima', ['class' => 'col-lg-2 control-label']) }}
                                <div class="col-lg-10">
                                    <div class="input-group">
                                        {{ Form::text('', Auth::user()->name, ['class' => 'form-control input-sm', 'readonly' => 'true', 'id' => 'RcvByName']) }}
                                        {{ Form::hidden('IN_RCVBY', Auth::user()->id, ['class' => 'form-control input-sm', 'id' => 'RcvById']) }}
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-sm" id="UserSearchModal">
                                                Carian
                                            </button>
                                        </span>
                                    </div>
                                    @if ($errors->has('IN_RCVBY'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RCVBY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVTYP', 'Cara Penerimaan', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::select('IN_RCVTYP', AdminCase::getRefList('1353', true), null, ['class' => 'form-control input-sm']) --}} -->
                                    {{ Form::select('IN_RCVTYP', Ref::GetList('1353', true, 'ms', 'sort'), null, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_RCVTYP'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_CHANNEL') ? ' has-error' : '' }}">
                                {{ Form::label('IN_CHANNEL', 'Saluran', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- 
                                        Form::select(
                                            'IN_CHANNEL', 
                                            [
                                                'SPRM' => 'Suruhanjaya Pencegahan Rasuah Malaysia (SPRM)', 
                                                'BPA' => 'Biro Pengaduan Awam (BPA)', 
                                                'PDRM' => 'Polis Diraja Malaysia (PDRM)', 
                                            ], 
                                            null, 
                                            [
                                                'class' => 'form-control input-sm select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        )
                                    --}} -->
                                    {{ Form::select('IN_CHANNEL', 
                                    Ref::GetList('1400', true, 'ms', 'sort'), 
                                    null, 
                                    ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_CHANNEL'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_CHANNEL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_SECTOR') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SECTOR', 'Sektor', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ Form::select('IN_SECTOR', 
                                    Ref::GetList('1412', true, 'ms', 'sort'), 
                                    null, 
                                    ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_SECTOR'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_SECTOR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Maklumat Pengadu</h4>
                    <!--<div class="hr-line-solid"></div>-->
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-lg-6 control-label required']) }}
                                <div class="col-lg-6">
                                    <!-- <div class="input-group"> -->
                                    {{ Form::text('IN_DOCNO', '', ['class' => 'form-control input-sm', 'id' => 'IN_DOCNO', 'maxlength' => 15 ]) }}
                                    <!-- <span class="input-group-btn"> -->
                                    <!-- <button class="btn btn-primary btn-sm" type="button" id="CheckJpn">Semak JPN</button> -->
                                    <!-- <button class="ladda-button ladda-button-demo btn btn-primary btn-sm" type="button" data-style="expand-right" id="CheckJpn">Semak JPN</button> -->
                                    <!-- </span> -->
                                    <!-- </div> -->
                                    @if ($errors->has('IN_DOCNO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6" style="color: red"><strong>** Diperlukan salah satu</strong></div>
                            <div class="form-group{{ $errors->has('IN_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('IN_EMAIL', 'Emel', ['class' => 'col-lg-6 control-label required1']) }}
                                <div class="col-lg-6">
                                    {{ Form::email('IN_EMAIL', '', ['class' => 'form-control input-sm']) }}
                                    <style scoped>
                                        input:invalid, textarea:invalid {
                                            color: red;
                                        }
                                    </style>
                                <!--{{-- Form::text('IN_EMAIL', '', ['class' => 'form-control input-sm']) --}}-->
                                    @if ($errors->has('IN_EMAIL'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-lg-6 control-label required1']) }}
                                <div class="col-lg-6">
                                    {{ Form::text('IN_MOBILENO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)", 'maxlength' => 12 ]) }}
                                    @if ($errors->has('IN_MOBILENO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-lg-6 control-label required1']) }}
                                <div class="col-lg-6">
                                    {{ Form::text('IN_TELNO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)", 'maxlength' => 10 ]) }}
                                    @if ($errors->has('IN_TELNO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_FAXNO', 'No. Faks', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    {{ Form::text('IN_FAXNO', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)", 'maxlength' => 10 ]) }}
                                    @if ($errors->has('IN_FAXNO'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('IN_ADDR', 'Alamat', ['class' => 'col-lg-6 control-label']) }}
                                <div class="col-lg-6">
                                    {{ Form::textarea('IN_ADDR', '', ['class' => 'form-control input-sm', 'rows' => '4']) }}
                                    {{ Form::hidden('IN_MYIDENTITY_ADDR', '', ['class' => 'form-control input-sm', 'id' => 'IN_MYIDENTITY_ADDR']) }}
                                    @if ($errors->has('IN_ADDR'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_NAME') ? ' has-error' : '' }}">
                                {{ Form::label('IN_NAME', 'Nama', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ Form::text('IN_NAME', '', ['class' => 'form-control input-sm', 'id' => 'IN_NAME']) }}
                                    @if ($errors->has('IN_NAME'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGE', 'Umur', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                {{ Form::text('IN_AGE', '', ['class' => 'form-control input-sm', 'id' => 'IN_AGE','onkeypress' => "return isNumberKey(event)", 'maxlength' => 3 ]) }}
                                <!--{{-- Form::select('IN_AGE', Ref::GetList('309', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'IN_AGE']) --}}-->
                                    @if ($errors->has('IN_AGE'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_SEXCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SEXCD', 'Jantina', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    {{ Form::select('IN_SEXCD', Ref::GetList('202', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'IN_SEXCD']) }}
                                    @if ($errors->has('IN_SEXCD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RACECD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RACECD', 'Bangsa', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    {{ Form::select('IN_RACECD', Ref::GetList('580', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'IN_RACECD']) }}
                                    @if ($errors->has('IN_RACECD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_RACECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_NATCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_NATCD', 'Warganegara', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                <!-- {{-- @foreach($mRefWarganegara as $RefWarganegara) --}} -->
                                <!--<div class="radio"><label><input type="radio" value="{{-- $RefWarganegara->code --}}" name="IN_NATCD" id="{{-- $RefWarganegara->code --}}" {{-- old('IN_NATCD') == $RefWarganegara->code ? 'checked' : '' --}}><i></i>{{-- $RefWarganegara->descr --}}</label></div>-->
                                    <!-- {{-- @endforeach --}} -->
                                    <div class="radio radio-success">
                                        <input id="IN_NATCD1" type="radio" name="IN_NATCD" value="1"
                                               onclick="check(this.value)" {{ (old('IN_NATCD') != ''? (old('IN_NATCD') == '1'? 'checked':''):'checked') }} >
                                        <label for="IN_NATCD1"> Warganegara </label>
                                    </div>
                                    <div class="radio radio-success">
                                        <input id="IN_NATCD2" type="radio" name="IN_NATCD" value="0"
                                               onclick="check(this.value)" {{ (old('IN_NATCD') != ''? (old('IN_NATCD') == '0'? 'checked':''):'') }} >
                                        <label for="IN_NATCD2"> Bukan Warganegara </label>
                                    </div>
                                <!--<div class="radio"><label><input type="radio" value="oth" name="IN_NATCD" id="national2" {{ old('IN_NATCD') == 'oth' ? 'checked' : '' }}><i></i>Bukan Warganegara</label></div>-->
                                    @if ($errors->has('IN_NATCD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_NATCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="warganegara" style="display:block">
                            <!--<div id="warganegara" style="display: {{-- (old('IN_NATCD') != ''? (old('IN_NATCD') == '1'? 'block':'none'):'block') --}}">-->
                                <div class="form-group {{ $errors->has('IN_POSTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_POSTCD', 'Poskod', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        {{ Form::text('IN_POSTCD', '', ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                        {{ Form::hidden('IN_MYIDENTITY_POSTCD', '', ['class' => 'form-control input-sm', 'id' => 'IN_MYIDENTITY_POSCD']) }}
                                        @if ($errors->has('IN_POSTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_POSTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('IN_STATECD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_STATECD', 'Negeri', ['class' => 'col-lg-3 control-label required']) }}
                                    <div class="col-lg-9">
                                        {{ Form::select('IN_STATECD', Ref::GetList('17', true, 'ms'), null, ['class' => 'form-control input-sm required', 'id' => 'IN_STATECD']) }}
                                        {{ Form::hidden('IN_MYIDENTITY_STATECD', '', ['class' => 'form-control input-sm', 'id' => 'IN_MYIDENTITY_STATECD']) }}
                                        @if ($errors->has('IN_STATECD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('IN_DISTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_DISTCD', 'Daerah', ['class' => 'col-lg-3 control-label required']) }}
                                    <div class="col-lg-9">
                                    @if (old('IN_STATECD'))
                                        {{ Form::select('IN_DISTCD', Ref::GetListDist(old('IN_STATECD')), null, ['class' => 'form-control input-sm', 'id' => 'IN_DISTCD']) }}
                                    @else
                                        {{ Form::select('IN_DISTCD', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'IN_DISTCD']) }}
                                    @endif
                                    {{ Form::hidden('IN_MYIDENTITY_DISTCD', '', ['class' => 'form-control input-sm', 'id' => 'IN_MYIDENTITY_DISTCD']) }}
                                    <!--{{-- Form::select('IN_DISTCD', ['' => '-- SILA PILIH --'], old('IN_DISTCD'), ['class' => 'form-control input-sm', 'id' => 'IN_DISTCD']) --}}-->
                                        @if ($errors->has('IN_DISTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_DISTCD') }}</strong></span>
                                        @endif
                                        <span class="help-block m-b-none">
                                            <em>
                                                <a
                                                    href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf"
                                                    target="_blank">@lang('button.statedistpdf')
                                                </a>
                                            </em>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--<div id="bknwarganegara" style="display:none">-->
                            <div id="bknwarganegara"
                                 style="display: {{ (old('IN_NATCD') != ''? (old('IN_NATCD') == '0'? 'block':'none'):'none') }}">
                                <div class="form-group {{ $errors->has('IN_COUNTRYCD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_COUNTRYCD', 'Negara Asal', ['class' => 'col-lg-3 control-label required']) }}
                                    <div class="col-lg-9">
                                        {{ Form::select('IN_COUNTRYCD', Ref::GetList('334', true, 'ms'), null, ['class' => 'form-control input-sm']) }}
                                        {{ Form::hidden('IN_STATUSPENGADU', '', ['class' => 'form-control input-sm', 'id' => 'IN_STATUSPENGADU']) }}
                                        @if ($errors->has('IN_COUNTRYCD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_COUNTRYCD') }}</strong></span>
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
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_AGAINSTNM') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGAINSTNM', 'Nama Pegawai Yang Diadu (PYDA)', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ Form::text('IN_AGAINSTNM','', ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_AGAINSTNM'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_AGAINSTNM') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_REFTYPE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_REFTYPE', 'Jenis Rujukan Berkaitan', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::select(
                                            'IN_REFTYPE', 
                                            [
                                                'OTHER' => 'Salah Laku (Umum)', 
                                                'BGK' => 'Salah Laku (Aduan Kepenggunaan)', 
                                                'TTPM' => 'Salah Laku (TTPM)', 
                                            ], 
                                            null, 
                                            [
                                                'class' => 'form-control input-sm select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        )
                                    }}
                                    @if ($errors->has('IN_REFTYPE'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_REFTYPE') }}</strong>
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
                            style="display: {{ old('IN_REFTYPE') == 'BGK' ? 'block' : 'none' }};">
                                {{ Form::label('IN_BGK_CASEID', 'Aduan Kepenggunaan', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    <!-- IntegritiPublic::getpublicusercomplaintlist(),  -->
                                    {{
                                        Form::select('IN_BGK_CASEID', 
                                            [], 
                                            '', 
                                            [
                                                'class' => 'form-control input-sm select2', 
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    <!-- {{-- Form::text('IN_BGK_CASEID','', ['class' => 'form-control input-sm']) --}} -->
                                    @if ($errors->has('IN_BGK_CASEID'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_BGK_CASEID') }}</strong>
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
                            style="display: {{ old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none' }};">
                                {{ Form::label('IN_TTPMNO', 'No. TTPM', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ Form::text('IN_TTPMNO','', ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_TTPMNO'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_TTPMNO') }}</strong>
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
                                style="display: {{ old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none' }};">
                                {{ Form::label('IN_TTPMFORM', 'Jenis Borang TTPM', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    <!-- {{-- Form::text('IN_TTPMFORM','', ['class' => 'form-control input-sm']) --}} -->
                                    {{ 
                                        Form::select(
                                            'IN_TTPMFORM', 
                                            [
                                                '8' => 'Borang 8 - Award bagi pihak yang menuntut jika penentang tidak hadir', 
                                                '9' => 'Borang 9 - Award dengan persetujuan', 
                                                '10' => 'Borang 10 - Award selepas pendengaran', 
                                            ], 
                                            null, 
                                            [
                                                'class' => 'form-control input-sm select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        )
                                    }}
                                    @if ($errors->has('IN_TTPMFORM'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_TTPMFORM') }}</strong>
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
                                style="display: {{ old('IN_REFTYPE') == 'OTHER' ? 'block' : 'none' }};">
                                {{ 
                                    Form::label('IN_REFOTHER', 
                                    'Lain-lain', 
                                    ['class' => 'col-lg-3 control-label']) 
                                }}
                                <div class="col-lg-9">
                                    {{ Form::text('IN_REFOTHER','', ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_REFOTHER'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_REFOTHER') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_AGAINSTLOCATION') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGAINSTLOCATION', 'Lokasi PYDA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    <!-- {{-- Form::text('IN_SUMMARY_TITLE','', ['class' => 'form-control input-sm']) --}} -->
                                    <div class="radio radio-primary radio-inline">
                                        <input type="radio" id="IN_AGAINSTLOCATION1" value="BRN" name="IN_AGAINSTLOCATION" onclick="againstlocation(this.value)" 
                                        {{ old('IN_AGAINSTLOCATION') == 'BRN'? 'checked':'' }}
                                        >
                                        <label for="IN_AGAINSTLOCATION1">
                                            KPDNHEP
                                        </label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="IN_AGAINSTLOCATION2" value="AGN" name="IN_AGAINSTLOCATION" onclick="againstlocation(this.value)" 
                                        {{-- old('IN_AGAINSTLOCATION') == 'AGN'? 'checked':'' --}}
                                        @if(old('IN_AGAINSTLOCATION') == 'AGN' && old('IN_REFTYPE') != 'TTPM')
                                            checked
                                        @elseif(old('IN_REFTYPE') == 'TTPM')
                                            disabled
                                        @endif
                                        >
                                        <label for="IN_AGAINSTLOCATION2">
                                            Agensi KPDNHEP
                                        </label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                        <input type="radio" id="IN_AGAINSTLOCATIONOTHER" value="OTHER" name="IN_AGAINSTLOCATION" onclick="againstlocation(this.value)"
                                        {{ old('IN_AGAINSTLOCATION') == 'OTHER'? 'checked':'' }}
                                        >
                                        <label for="IN_AGAINSTLOCATIONOTHER">
                                            Lain - lain
                                        </label>
                                    </div>
                                    @if ($errors->has('IN_AGAINSTLOCATION'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_AGAINSTLOCATION') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- <div class="form-group{{ $errors->has('IN_BRNCD') ? ' has-error' : '' }}"> -->
                            <div id="div_IN_AGAINST_BRSTATECD" 
                                class="form-group {{ $errors->has('IN_AGAINST_BRSTATECD') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_AGAINSTLOCATION') == 'BRN' ? 'block' : 'none' }};">
<!--                                        <label id="" for="" class="col-lg-3 control-label">
                                    Bahagian / Cawangan
                                </label>-->
                                <!-- {{-- Form::label('', trans('public-case.case.IN_BRNCD'), ['class' => 'col-lg-3 control-label required']) --}} -->
                                {{ Form::label('IN_AGAINST_BRSTATECD', 
                                    'Negeri', 
                                    ['class' => 'col-lg-3 control-label required']) 
                                }}
                                <div class="col-lg-9">
                                    <!--{{-- Form::text('','', ['class' => 'form-control input-sm']) --}}-->
                                    <!-- {{-- 
                                        Form::select('', Branch::GetListBranch(), null, [
                                            'class' => 'form-control input-sm select2', 
                                        ]) 
                                    --}} -->
                                    {{ 
                                        Form::select(
                                            'IN_AGAINST_BRSTATECD', 
                                            Ref::GetList('17', false, Auth::user()->lang), 
                                            old('IN_AGAINST_BRSTATECD'), 
                                            [
                                                'class' => 'form-control input-sm select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_AGAINST_BRSTATECD'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_AGAINST_BRSTATECD') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- <div class="form-group{{ $errors->has('IN_BRNCD') ? ' has-error' : '' }}"> -->
                            <div id="div_IN_BRNCD" 
                                class="form-group {{ $errors->has('IN_BRNCD') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_AGAINSTLOCATION') == 'BRN' ? 'block' : 'none' }};">
                                {{ Form::label('IN_BRNCD', 'Bahagian / Cawangan', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    <!--{{-- Form::text('','', ['class' => 'form-control input-sm']) --}}-->
                                    <!-- {{-- 
                                        Form::select('IN_BRNCD', Branch::GetListBranch(), null, [
                                            'class' => 'form-control input-sm select2', 
                                            'id' => 'IN_BRNCD', 
                                            'style' => 'width:100%'
                                        ]) 
                                    --}} -->
                                    <!-- {{-- 
                                        Form::select('IN_BRNCD', [], null, [
                                            'class' => 'form-control input-sm select2', 
                                            'id' => 'IN_BRNCD', 
                                            'placeholder' => '-- SILA PILIH --'
                                        ]) 
                                    --}} -->
                                    {{ 
                                        Form::select(
                                            'IN_BRNCD', 
                                            old('IN_AGAINST_BRSTATECD')
                                            ?
                                            Branch::GetListByState(
                                                old('IN_AGAINST_BRSTATECD') ? old('IN_AGAINST_BRSTATECD') : '', 
                                                false
                                            )
                                            : []
                                            , 
                                            null, 
                                            [
                                                'class' => 'form-control input-sm select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_BRNCD'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_BRNCD') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- <div class="form-group{{ $errors->has('') ? ' has-error' : '' }}"> -->
                            <div id="div_IN_AGENCYCD" 
                                class="form-group {{ $errors->has('IN_AGENCYCD') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_AGAINSTLOCATION') == 'AGN' ? 'block' : 'none' }};">
                                {{ 
                                    Form::label('IN_AGENCYCD', 
                                    'Agensi KPDNHEP', 
                                    ['class' => 'col-lg-3 control-label required']) 
                                }}
                                <div class="col-lg-9">
                                    {{ 
                                        Form::select(
                                            'IN_AGENCYCD', 
                                            IntegritiAdmin::GetMagncdList(), 
                                            '', 
                                            [
                                                'class' => 'form-control input-sm select2'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_AGENCYCD'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_AGENCYCD') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('IN_SUMMARY_TITLE') ? ' has-error' : '' }}">
<!--                                        <label id="" for="" class="col-lg-3 control-label required">
                                    Tajuk Aduan
                                </label>-->
                                {{ Form::label('IN_SUMMARY_TITLE', 'Tajuk Aduan', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ Form::text('IN_SUMMARY_TITLE','', ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('IN_SUMMARY_TITLE'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_SUMMARY_TITLE') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IN_SUMMARY') ? ' has-error' : '' }}">
                                <!--<label for="" class="col-lg-3 control-label required">@lang('public-case.case.CA_SUMMARY')</label>-->
                                {{ Form::label('IN_SUMMARY', trans('public-case.case.CA_SUMMARY'), ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-9">
                                    {{ Form::textarea('IN_SUMMARY','', ['class' => 'form-control input-sm', 'rows'=> '5']) }}
                                    <span class="help-block m-b-none" style="font-weight:bold;color:black">
                                    </span>
                                    @if ($errors->has('IN_SUMMARY'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_SUMMARY') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            <a class="btn btn-warning btn-sm" href="{{ url('integritiadmin') }}">Kembali</a>
                            {{ Form::button('Simpan & Seterusnya'.' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Start -->
    <!-- {{-- @include('aduan.admin-case.usersearchmodal') --}} -->
    <div id="carian-penerima" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carian Penerima</h4>
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
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('name', 'Nama Pengguna', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <!-- <div class="form-group"> -->
                                <!-- {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) }} -->
                                <!-- <div class="col-sm-9"> -->
                                    <!-- {{-- Form::select('state_cd', Ref::GetList('17', true), Auth::User()->state_cd, ['class' => 'form-control input-sm', 'id' => 'state_cd_user']) --}} -->
                                    <!-- {{ Form::text('state_cd_text', Ref::GetDescr('17', Auth::User()->state_cd), ['class' => 'form-control input-sm', 'id' => 'state_cd_user', 'readonly' => true]) }} -->
                                <!-- </div> -->
                            <!-- </div> -->
                            <!-- <div class="form-group"> -->
                                <!-- {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label']) }} -->
                                <!-- <div class="col-sm-9"> -->
                                    <!-- {{-- Form::select('brn_cd', ['' => '-- SILA PILIH --'], Auth::User()->brn_cd, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) --}} -->
                                    <!-- {{ Form::text('brn_cd_text', Branch::GetBranchName(Auth::User()->brn_cd), ['class' => 'form-control input-sm', 'id' => 'brn_cd', 'readonly' => true]) }} -->
                                <!-- </div> -->
                            <!-- </div> -->
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
                                    <th>No.</th>
                                    <th>No. Kad Pengenalan</th>
                                    <th>Nama</th>
                                    <!-- <th>Negeri</th> -->
                                    <!-- <th>Cawangan</th> -->
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
    <!-- Modal End -->
@stop

@section('script_datatable')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#IN_ONLINECMPL_AMOUNT').blur(function () {
                $(this).val(amountchange($(this).val()))
            })

            function amountchange(amount) {
                var delimiter = ',' // replace comma if desired
                var a = amount.split('.', 2)
                var d = a[ 1 ]
                if (d) {
                    if (d.length === 1) {
                    d = d + '0'
                    } else if (d.length === 2) {
                    d = d
                    } else {
                    d = d
                    }
                } else {
                    d = '00'
                }
                var i = parseInt(a[ 0 ])
                if (isNaN(i)) {
                    return ''
                }
                var minus = ''
                if (i < 0) {
                    minus = '-'
                }
                i = Math.abs(i)
                var n = new String(i)
                var a = []
                while (n.length > 3) {
                    var nn = n.substr(n.length - 3)
                    a.unshift(nn)
                    n = n.substr(0, n.length - 3)
                }
                if (n.length > 0) {
                    a.unshift(n)
                }
                n = a.join(delimiter)
                if (d.length < 1) {
                    amount = n
                }
                else {
                    amount = n + '.' + d
                }
                amount = minus + amount
                return amount
            }

            $('select').select2()

        })

        function getDistListFromJpn(StateCd, DistCd) {
            if (StateCd != '' && DistCd != '') {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('admin-case/getdistlist') }}" + '/' + StateCd,
                    dataType: 'json',
                    success: function (data) {
                        $('select[name="IN_DISTCD"]').empty()
                        $.each(data, function (key, value) {
                            if (DistCd === value)
                            $('select[name="IN_DISTCD"]').append('<option value="' + value + '" selected="selected">' + key + '</option>')
                            else
                            $('select[name="IN_DISTCD"]').append('<option value="' + value + '">' + key + '</option>')
                        })
                    },
                    complete: function (data) {
                        $('#IN_DISTCD').val(DistCd).trigger('change')
                    }
                })
            } else {
                $('select[name="IN_DISTCD"]').empty()
                $('select[name="IN_DISTCD"]').append('<option value="">-- SILA PILIH --</option>')
            }
        }

        function checkJpn() {
            var DOCNO = $('#IN_DOCNO').val();
            var l = $('.ladda-button-demo').ladda();
            if(DOCNO){
                $.ajax({
                    type: 'GET',
                    url: "{{ url('admin-case/tojpn') }}" + '/' + DOCNO,
                    dataType: 'json',
                    beforeSend: function () {
                        l.ladda('start');
                    },
                    success: function (data) {
                        if (data.Message) {
                            alert(data.Message);
                        }
                        // if (!feedback) {
                        //   $('#IN_EMAIL').val(data.EmailAddress) // Email
                        //   $('#IN_MOBILENO').val(data.MobilePhoneNumber) // Telefon Bimbit
                        // }
                        $('#IN_EMAIL').val(data.EmailAddress); // Email
                        $('#IN_MOBILENO').val(data.MobilePhoneNumber); // Telefon Bimbit
                        $('#IN_STATUSPENGADU').val(data.StatusPengadu); // Status Pengadu
                        $('#IN_NAME').val(data.Name); // Nama
                        $('#IN_AGE').val(data.Age); // Umur
                        $('#IN_SEXCD').val(data.Gender).trigger('change'); // Jantina
                        $('#IN_RACECD').val(data.RaceCd).trigger('change'); // Bangsa
                        if (data.Warganegara != '') {
                            if (data.Warganegara === '1') { // Warganegara
                                document.getElementById('IN_NATCD1').checked = true;
                                $('#warganegara').show();
                                $('#bknwarganegara').hide();
                            } else {
                                document.getElementById('IN_NATCD2').checked = true;
                                $('#warganegara').show();
                                $('#bknwarganegara').show();
                            }
                        }
                        // Standard Field
                        $('#IN_ADDR').val(data.CorrespondenceAddress1 + '\n' + data.CorrespondenceAddress2); // Alamat
                        $('#IN_POSTCD').val(data.CorrespondenceAddressPostcode); // Poskod
                        $('#IN_STATECD').val(data.CorrespondenceAddressStateCode).trigger('change'); // Negeri
                        getDistListFromJpn(data.CorrespondenceAddressStateCode, data.KodDaerah); // Daerah
                        // MyIdentity Field
                        $('#IN_MYIDENTITY_ADDR').val(data.CorrespondenceAddress1 + '\n' + data.CorrespondenceAddress2); // Alamat MyIdentity
                        $('#IN_MYIDENTITY_POSCD').val(data.CorrespondenceAddressPostcode); // Poskod MyIdentity
                        $('#IN_MYIDENTITY_STATECD').val(data.CorrespondenceAddressStateCode); // Negeri MyIdentity
                        $('#IN_MYIDENTITY_DISTCD').val(data.KodDaerah); // Daerah MyIdentity
                        l.ladda('stop');
                    },
                    error: function (data) {
                        console.log(data);
                        if (data.status == '500') {
                            alert(data.statusText);
                        }
                        l.ladda('stop')
                    },
                    complete: function (data) {
                        console.log(data);
                        l.ladda('stop');
                    }
                })
            }
        }

        // $('#CheckJpn').on('click', function (e) {
        $('#IN_DOCNO').blur(function () {
            checkJpn()
            var IN_DOCNO = $(this).val();
            if(IN_DOCNO){
                $.ajax({
                    type: 'GET',
                    url: "{{ url('integritiadmin/getpublicusercomplaintlist') }}" + '/' + IN_DOCNO,
                    dataType: 'json',
                    success: function (data) {
                        $('select[name="IN_BGK_CASEID"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="IN_BGK_CASEID"]').append('<option value="' + key + '">' + value + '</option>');
                        })
                    },
                    complete: function (data) {
                        $('#IN_BGK_CASEID').trigger('change');
                    }
                });
            } else {
                $('select[id="IN_BGK_CASEID"]').empty();
                // $('select[id="IN_BGK_CASEID"]').append('<option>-- SILA PILIH --</option>');
                $('select[id="IN_BGK_CASEID"]').append('<option value="">-- SILA PILIH --</option>');
                $('select[id="IN_BGK_CASEID"]').trigger('change');
            }
        })

        function check(value) {
            if (value === '1') {
                $('#warganegara').show()
                $('#bknwarganegara').hide()
            } else {
                $('#warganegara').show()
                $('#bknwarganegara').show()
            }
        }

        function onlinecmplind() {
            if (document.getElementById('IN_ONLINECMPL_IND').checked)
                $('#div_IN_ONLINECMPL_CASENO').show()
            else
                $('#div_IN_ONLINECMPL_CASENO').hide()
        }

        function onlineaddind() {
            if (document.getElementById('IN_ONLINEADD_IND').checked) {
                $('#div_IN_AGAINSTADD').show()
                $('#div_IN_AGAINST_POSTCD').show()
                $('#div_IN_AGAINST_STATECD').show()
                $('#div_IN_AGAINST_DISTCD').show()
            } else {
                $('#div_IN_AGAINSTADD').hide()
                $('#div_IN_AGAINST_POSTCD').hide()
                $('#div_IN_AGAINST_STATECD').hide()
                $('#div_IN_AGAINST_DISTCD').hide()
            }
        }


        $(function () {
            $('#national1').on('click', function (e) {
                $('#warganegara').show()
                $('#bknwarganegara').hide()
            })
            $('#national2').on('click', function (e) {
                $('#warganegara').hide()
                $('#bknwarganegara').show()
            })

            $('#IN_ONLINECMPL_PROVIDER').on('change', function (e) {
                var IN_ONLINECMPL_PROVIDER = $(this).val()
                if (IN_ONLINECMPL_PROVIDER === '999')
                    $('label[for=\'IN_ONLINECMPL_URL\']').addClass('required')
                else
                    $('label[for=\'IN_ONLINECMPL_URL\']').removeClass('required')
            })

            $('#IN_ONLINECMPL_PYMNTTYP').on('change', function (e) {
                var IN_ONLINECMPL_PYMNTTYP = $(this).val()
                if (IN_ONLINECMPL_PYMNTTYP === 'COD' || IN_ONLINECMPL_PYMNTTYP === '') {
                    $('label[for=\'IN_ONLINECMPL_BANKCD\']').removeClass('required')
                    $('label[for=\'IN_ONLINECMPL_ACCNO\']').removeClass('required')
                } else {
                    $('label[for=\'IN_ONLINECMPL_BANKCD\']').addClass('required')
                    $('label[for=\'IN_ONLINECMPL_ACCNO\']').addClass('required')
                }
            })

            $('#IN_RCVTYP').on('change', function (e) {
                var IN_RCVTYP = $(this).val()

                if (IN_RCVTYP === 'S14') {
                    $('#div_IN_BPANO').show()
                } else {
                    $('#div_IN_BPANO').hide()
                }

                if (IN_RCVTYP === 'S33') {
                    $('#div_IN_SERVICENO').show()
                } else {
                    $('#div_IN_SERVICENO').hide()
                }
            })

            $('#IN_CMPLCAT').on('change', function (e) {
                var IN_CMPLCAT = $(this).val()
                if (IN_CMPLCAT === 'BPGK 01' || IN_CMPLCAT === 'BPGK 03') {
                    $('#div_IN_CMPLKEYWORD').show()
                } else {
                    $('#div_IN_CMPLKEYWORD').hide()
                }
                if (IN_CMPLCAT === 'BPGK 08') {
                    $('#IN_TTPMTYP').show()
                    $('#IN_TTPMNO').show()
                } else {
                    $('#IN_TTPMTYP').hide()
                    $('#IN_TTPMNO').hide()
                }
                if (IN_CMPLCAT === 'BPGK 19') {
                    if (document.getElementById('IN_ONLINECMPL_IND').checked)
                    $('#div_IN_ONLINECMPL_CASENO').show()
                    else
                    $('#div_IN_ONLINECMPL_CASENO').hide()
                    $('#checkpernahadu').show()
                    $('#checkinsertadd').show()
                    $('#div_IN_ONLINECMPL_PROVIDER').show()
                    $('#div_IN_ONLINECMPL_URL').show()
        //                $('#div_IN_ONLINECMPL_AMOUNT').show();
                    $('#div_IN_ONLINECMPL_BANKCD').show()
                    $('#div_IN_ONLINECMPL_PYMNTTYP').show()
                    $('#div_IN_ONLINECMPL_ACCNO').show()
                    $('#div_IN_AGAINST_PREMISE').hide()
        //                $('#div_IN_AGAINSTADD').hide();
        //                $('#div_IN_AGAINST_POSTCD').hide();
        //                $('#div_IN_AGAINST_STATECD').hide();
        //                $('#div_IN_AGAINST_DISTCD').hide();
                    $('#div_SERVICE_PROVIDER_INFO').show()
                    $('#div_ONLINECMPL').show()
                    if (document.getElementById('IN_ONLINEADD_IND').checked) {
                        $('#div_IN_AGAINSTADD').show()
                        $('#div_IN_AGAINST_POSTCD').show()
                        $('#div_IN_AGAINST_STATECD').show()
                        $('#div_IN_AGAINST_DISTCD').show()
                    } else {
                        $('#div_IN_AGAINSTADD').hide()
                        $('#div_IN_AGAINST_POSTCD').hide()
                        $('#div_IN_AGAINST_STATECD').hide()
                        $('#div_IN_AGAINST_DISTCD').hide()
                    }
                } else {
                    $('#checkpernahadu').hide()
                    $('#checkinsertadd').hide()
                    $('#div_IN_ONLINECMPL_CASENO').hide()
                    $('#div_IN_ONLINECMPL_PROVIDER').hide()
                    $('#div_IN_ONLINECMPL_URL').hide()
        //                $('#div_IN_ONLINECMPL_AMOUNT').hide();
                    $('#div_IN_ONLINECMPL_BANKCD').hide()
                    $('#div_IN_ONLINECMPL_PYMNTTYP').hide()
                    $('#div_IN_ONLINECMPL_ACCNO').hide()
                    $('#div_IN_AGAINST_PREMISE').show()
                    $('#div_IN_AGAINSTADD').show()
                    $('#div_IN_AGAINST_POSTCD').show()
                    $('#div_IN_AGAINST_STATECD').show()
                    $('#div_IN_AGAINST_DISTCD').show()
                    $('#div_SERVICE_PROVIDER_INFO').hide()
                    $('#div_ONLINECMPL').hide()
                }
                $.ajax({
                    type: 'GET',
                    url: "{{ url('admin-case/getcmpllist') }}" + '/' + IN_CMPLCAT,
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)
                        $('select[name="IN_CMPLCD"]').empty()
                        $.each(data, function (key, value) {
                            if (value == '0') {
                                $('select[name="IN_CMPLCD"]').append('<option value="">' + key + '</option>')
                                $('select[name="IN_CMPLCD"]').trigger('change')
                            } else {
                                $('select[name="IN_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>')
                                $('select[name="IN_CMPLCD"]').trigger('change')
                            }
                        })
                    }
                })
            })
            $('#IN_REFTYPE').on('change', function () {
                var IN_REFTYPE = $(this).val();
                var IN_DOCNO = $('#IN_DOCNO').val();
                if(IN_REFTYPE === 'BGK') {
                    $("#div_bgk").show();
                    $("#div_other").hide();
                    $("#div_ttpm").hide();
                    $("#div_ttpmform").hide();
                    $('#IN_AGAINSTLOCATION1').trigger('click');
                    // $('#IN_AGAINSTLOCATION2').attr('disabled', false);
                    $('#IN_AGAINSTLOCATION2').attr('disabled', true);
                    if(!IN_DOCNO){
                        alert('Sila isikan No. Kad Pengenalan Sebelum Memilih Rujukan No. Aduan Kepenggunaan.');
                    }
                } else if(IN_REFTYPE === 'TTPM') {
                    $("#div_bgk").hide();
                    $("#div_ttpm").show();
                    $("#div_ttpmform").show();
                    $("#div_other").hide();
                    $('#IN_AGAINSTLOCATION1').trigger('click');
                    $('#IN_AGAINSTLOCATION2').attr('disabled', true);
                } else if(IN_REFTYPE === 'OTHER') {
                    $("#div_bgk").hide();
                    $("#div_ttpm").hide();
                    $("#div_ttpmform").hide();
                    $("#div_other").show();
                    $('#IN_AGAINSTLOCATION2').attr('disabled', false);
                } else {
                    $("#div_bgk").hide();
                    $("#div_ttpm").hide();
                    $("#div_ttpmform").hide();
                    $("#div_other").hide();
                    $('#IN_AGAINSTLOCATION2').attr('disabled', false);
                }
            });
        })

        function myFunction(id) {
            $.ajax({
                url: "{{ url('admin-case/getuserdetail') }}" + '/' + id,
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (key, value) {
                        document.getElementById('RcvByName').value = key
                        document.getElementById('RcvById').value = value
                    })
                    $('#carian-penerima').modal('hide')
                }
            })
        }

        $(document).ready(function () {

            $('#UserSearchModal').on('click', function (e) {
                $('#carian-penerima').modal()
            })

            $('#rcvdt .input-daterange').datepicker({
                format: 'dd-mm-yyyy',
        //            format: 'yyyy-mm-dd',
                todayHighlight: true,
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            })

            $('#IN_STATECD').on('change', function (e) {
                var IN_STATECD = $(this).val();
                if(IN_STATECD){
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('admin-case/getdistlist') }}" + '/' + IN_STATECD,
                        dataType: 'json',
                        success: function (data) {
                            $('select[name="IN_DISTCD"]').empty()
                            $.each(data, function (key, value) {
                                $('select[name="IN_DISTCD"]').append('<option value="' + value + '">' + key + '</option>')
                            })
                        },
                        complete: function (data) {
                            $('#IN_DISTCD').trigger('change')
                        }
                    })
                } else {
                    $('select[name="IN_DISTCD"]').empty();
                    $('select[name="IN_DISTCD"]').append('<option value="">-- SILA PILIH --</option>');
                    $('select[name="IN_DISTCD"]').trigger('change');
                }
            })

            $('#IN_AGAINST_STATECD').on('change', function (e) {
                var IN_AGAINST_STATECD = $(this).val()
                $.ajax({
                    type: 'GET',
                    url: "{{ url('admin-case/getdistlist') }}" + '/' + IN_AGAINST_STATECD,
                    dataType: 'json',
                    success: function (data) {
                        $('select[name="IN_AGAINST_DISTCD"]').empty()
                        $.each(data, function (key, value) {
                            $('select[name="IN_AGAINST_DISTCD"]').append('<option value="' + value + '">' + key + '</option>')
                        })
                    },
                    complete: function (data) {
                        $('#IN_AGAINST_DISTCD').trigger('change')
                    }
                })
            })

            var oTable = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
                pagingType: 'full_numbers',
                dom: '<\'row\'<\'col-sm-6\'i><\'col-sm-6\'<\'pull-right\'l>>>' +
                    '<\'row\'<\'col-sm-12\'tr>>' +
                    '<\'row\'<\'col-sm-12\'p>>',
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
                        last: 'Terakhir'
                    }
                },
                ajax: {
                    // url: "{{-- url('admin-case/getdatatableuser') --}}",
                    url: "{{ url('integrititugas/getdatatableuser') }}",
                    data: function (d) {
                        d.name = $('#name').val()
                        d.icnew = $('#icnew').val()
                        // d.state_cd = $('#state_cd_user').val();
                        // d.state_cd = '{{-- Auth::User()->state_cd --}}'
                        // d.brn_cd = $('#brn_cd').val();
                        // d.brn_cd = '{{-- Auth::User()->brn_cd --}}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'id', width: '5%', searchable: false, orderable: false },
                    { data: 'username', name: 'username' },
                    { data: 'name', name: 'name' },
                    // { data: 'state_cd', name: 'state_cd' },
                    // { data: 'brn_cd', name: 'brn_cd' },
                    { data: 'action', name: 'action', searchable: false, orderable: false, width: '8%' }
                ]
            })

            $('#state_cd_user').on('change', function (e) {
                var state_cd = $(this).val()
                $.ajax({
                    type: 'GET',
                    url: "{{ url('user/getbrnlist') }}" + '/' + state_cd,
                    dataType: 'json',
                    success: function (data) {
                        $('select[name="brn_cd"]').empty()
                        $.each(data, function (key, value) {
                            $('select[name="brn_cd"]').append('<option value="' + key + '">' + value + '</option>')
                        })
                    }
                })
            })

            $('#resetbtn').on('click', function (e) {
                document.getElementById('search-form').reset()
                oTable.draw()
                e.preventDefault()
            })

            $('#search-form').on('submit', function (e) {
                oTable.draw()
                e.preventDefault()
            })

            $('#IN_AGAINST_BRSTATECD').on('change', function (e) {
                var IN_AGAINST_BRSTATECD = $(this).val();
                if(IN_AGAINST_BRSTATECD){
                    $.ajax({
                        type: 'GET',
                        // url: "{{-- url('user/getbrnlist') --}}" + '/' + IN_AGAINST_BRSTATECD,
                        url: "{{ url('integritibase/getbrnlist') }}" + '/' + IN_AGAINST_BRSTATECD,
                        dataType: 'json',
                        success: function (data) {
                            $('select[name="IN_BRNCD"]').empty();
                            // $('select[name="IN_BRNCD"]').append('<option>-- SILA PILIH --</option>');
                            $('select[name="IN_BRNCD"]').append('<option value="">-- SILA PILIH --</option>');
                            $.each(data, function (key, value) {
                                $('select[name="IN_BRNCD"]').append('<option value="' + key + '">' + value + '</option>');
                            })
                        },
                        complete: function (data) {
                            $('#IN_BRNCD').trigger('change');
                        }
                    });
                } else {
                    $('select[id="IN_BRNCD"]').empty();
                    // $('select[id="IN_BRNCD"]').append('<option>-- SILA PILIH --</option>');
                    $('select[id="IN_BRNCD"]').append('<option value="">-- SILA PILIH --</option>');
                    $('select[id="IN_BRNCD"]').trigger('change');
                }
            });
        })

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false
            }
            return true
        }

        function isNumberKey1(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
                return false
            }
            return true
        }

        function againstlocation(value) {
            if (value === 'BRN') {
                $('#div_IN_AGAINST_BRSTATECD').show();
                $('#div_IN_BRNCD').show();
                $('#div_IN_AGENCYCD').hide();
            } else if (value === 'AGN') {
                $('#div_IN_AGAINST_BRSTATECD').hide();
                $('#div_IN_BRNCD').hide();
                $('#div_IN_AGENCYCD').show();
            } else {
                $('#div_IN_AGAINST_BRSTATECD').hide();
                $('#div_IN_BRNCD').hide();
                $('#div_IN_AGENCYCD').hide();
            }
        }
    </script>
@stop