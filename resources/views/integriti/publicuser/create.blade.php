@extends('layouts.app')
<?php
    use App\Branch;
    use App\Ref;
    use App\User;
    use App\Integriti\IntegritiAdmin;
?>
@section('content')
<style>
    textarea {
        resize: vertical;
    }    
</style>
<div class="ibox floating-container">
    <div class="ibox-content" style="padding: 0px;">
        <nav class="navbar navbar-static-top" role="navigation" style="margin:0;background: #115272 !important;border-bottom: 3px solid #fac626; ">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" data-target="#navbarmenu" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbarmenu" style="padding-left:15px;padding-right:15px;">
                <ul class="nav navbar-top-links navbar-left">
                    <li>

                    </li>
                    <li style="background-color: #115272 !important;"><a style="background-color: #115272; color: white; " href="{{ url('/') }}">{{ app()->getLocale() == 'en' ? 'HOME' : 'HALAMAN UTAMA' }}</a></li>
                </ul>
            </div>
        </nav>
        <div class="loginColumns" style="padding : 20px 20px 20px">
            <div class="row">
                <div class="col-lg-12">
                    {{ Form::open(['route' => 'integritipublicuser.store', 'class' => 'form-horizontal', 'id' => 'formpublicuser']) }}
                    {{-- csrf_field() --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-size: 14px;" class="label label-success">1</span>
                            Daftar Aduan Integriti
                        </div>
                        <div class="panel-body">
                            <h3 class="text-danger">
                                Peringatan
                            </h3>
                            <b style="font-size: 14px;">
                                "Kementerian Perdagangan Dalam Negeri Dan Hal Ehwal Pengguna (KPDNHEP) boleh untuk tidak melayan sebarang aduan yang menggunakan bahasa kesat, lucah, konfrontasi, penghinaan dan/atau tuduhan tanpa asas. KPDNHEP juga tidak akan campur tangan dalam kes-kes aduan yang melibatkan keputusan mana-mana mahkamah/badan arbitrari/seumpamanya serta mana-mana pertikaian yang melibatkan masalah peribadi antara syarikat/individu yang tiada kaitan dengan peranan/fungsi agensi KPDNHEP."
                            </b>
                            <div class="bg-muted b-r-xl p-xs"><strong>Maklumat Pengadu</strong></div>
                            <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                            <div class="form-group{{ $errors->has('IN_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_DOCNO', null, ['class' => 'form-control']) }}
                                    {{-- @if ($errors->has('IN_DOCNO')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_DOCNO') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_NAME') ? ' has-error' : '' }}">
                                {{ Form::label('IN_NAME', 'Nama', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_NAME', null, ['class' => 'form-control', 'placeholder' => __('public-case.case.IN_NAME_PLACEHOLDER')]) }}
                                    {{-- @if ($errors->has('IN_NAME')) --}}
                                        <!--span class="help-block">
                                            <strong>{{ $errors->first('IN_NAME') }}</strong>
                                        </span-->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('IN_EMAIL', 'Emel', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::email('IN_EMAIL', null, ['class' => 'form-control']) }}
                                    <style scoped>
                                        input:invalid, textarea:invalid {
                                            color: red;
                                        }
                                    </style>
                                    @if ($errors->has('IN_EMAIL'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_EMAIL') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_MOBILENO', null, ['class' => 'form-control', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 12 ]) }}
                                    @if ($errors->has('IN_MOBILENO'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_MOBILENO') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_TELNO', 'No. Telefon (Rumah)', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_TELNO', null, ['class' => 'form-control', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10 ]) }}
                                    @if ($errors->has('IN_TELNO'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_TELNO') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('IN_FAXNO', 'No. Faks', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_FAXNO', null, ['class' => 'form-control', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 10 ]) }}
                                    @if ($errors->has('IN_FAXNO'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_FAXNO') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGE', 'Umur', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_AGE', null, ['class' => 'form-control', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 3 ]) }}
                                    <!--{{-- Form::select('IN_AGE', Ref::GetList('309', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'IN_AGE']) --}}-->
                                    @if ($errors->has('IN_AGE'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_AGE') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_SEXCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SEXCD', 'Jantina', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::select('IN_SEXCD', Ref::GetList('202', true, 'ms'), null, ['class' => 'form-control']) }}
                                    @if ($errors->has('IN_SEXCD'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_SEXCD') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_RACECD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RACECD', 'Bangsa', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::select('IN_RACECD', Ref::GetList('580', true, 'ms'), null, ['class' => 'form-control']) }}
                                    @if ($errors->has('IN_RACECD'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_RACECD') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_NATCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_NATCD', 'Warganegara', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                <!-- {{-- @foreach($mRefWarganegara as $RefWarganegara) --}} -->
                                <!--<div class="radio"><label><input type="radio" value="{{-- $RefWarganegara->code --}}" name="IN_NATCD" id="{{-- $RefWarganegara->code --}}" {{-- old('IN_NATCD') == $RefWarganegara->code ? 'checked' : '' --}}><i></i>{{-- $RefWarganegara->descr --}}</label></div>-->
                                    <!-- {{-- @endforeach --}} -->
                                    <div class="radio radio-success">
                                        <input id="IN_NATCD1" type="radio" name="IN_NATCD" 
                                            value="1" onclick="check(this.value)" 
                                            {{ (old('IN_NATCD') != ''? (old('IN_NATCD') == '1'? 'checked':''):'checked') }}>
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
                            <div class="form-group{{ $errors->has('IN_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('IN_ADDR', 'Alamat', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::textarea('IN_ADDR', null, ['class' => 'form-control', 'rows' => '4']) }}
                                    @if ($errors->has('IN_ADDR'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('IN_POSTCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_POSTCD', 'Poskod', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_POSTCD', null, ['class' => 'form-control', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 5]) }}
                                    @if ($errors->has('IN_POSTCD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_POSTCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('IN_STATECD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_STATECD', 'Negeri', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    {{ Form::select('IN_STATECD', Ref::GetList('17', true, 'ms'), null, ['class' => 'form-control required', 'id' => 'IN_STATECD']) }}
                                    @if ($errors->has('IN_STATECD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_STATECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('IN_DISTCD') ? ' has-error' : '' }}">
                                {{ Form::label('IN_DISTCD', 'Daerah', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                @if (old('IN_STATECD'))
                                    {{ Form::select('IN_DISTCD', Ref::GetListDist(old('IN_STATECD')), null, ['class' => 'form-control', 'id' => 'IN_DISTCD']) }}
                                @else
                                    {{ Form::select('IN_DISTCD', [], null, ['class' => 'form-control', 'id' => 'IN_DISTCD', 'placeholder' => '-- SILA PILIH --']) }}
                                @endif
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
                            <div id="bknwarganegara"
                                style="display: {{ (old('IN_NATCD') != ''? (old('IN_NATCD') == '0'? 'block':'none'):'none') }}">
                                <div class="form-group {{ $errors->has('IN_COUNTRYCD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_COUNTRYCD', 'Negara Asal', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('IN_COUNTRYCD', Ref::GetList('334', true, 'ms'), null, ['class' => 'form-control']) }}
                                        @if ($errors->has('IN_COUNTRYCD'))
                                            <span class="help-block"><strong>{{ $errors->first('IN_COUNTRYCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="bg-muted b-r-xl p-xs"><strong>Maklumat Aduan</strong></div>
                            <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                            <div class="form-group{{ $errors->has('IN_AGAINSTNM') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGAINSTNM', 'Nama Pegawai Yang Diadu (PYDA)', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_AGAINSTNM', null, ['class' => 'form-control']) }}
                                    {{-- @if ($errors->has('IN_AGAINSTNM')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_AGAINSTNM') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_REFTYPE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_REFTYPE', 'Jenis Rujukan Berkaitan', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
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
                                                'class' => 'form-control',
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
                            <div id="div_bgk" 
                            class="form-group {{ $errors->has('IN_BGK_CASEID') ? ' has-error' : '' }}"
                            style="display: {{ old('IN_REFTYPE') == 'BGK' ? 'block' : 'none' }};">
                                {{ Form::label('IN_BGK_CASEID', 'No. Aduan Kepenggunaan', ['class' => 'col-lg-5 control-label']) }}
                                <div class="col-lg-7">
                                    <!-- IntegritiPublic::getpublicusercomplaintlist(),  -->
                                    {{--
                                        Form::select('IN_BGK_CASEID', 
                                            [], 
                                            null, 
                                            [
                                                'class' => 'form-control', 
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    --}}
                                    {{ Form::text('IN_BGK_CASEID', null, ['class' => 'form-control']) }}
                                    {{-- @if ($errors->has('IN_BGK_CASEID')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_BGK_CASEID') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div id="div_ttpm" 
                            class="form-group {{ $errors->has('IN_TTPMNO') ? ' has-error' : '' }}"
                            style="display: {{ old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none' }};">
                                {{ Form::label('IN_TTPMNO', 'No. TTPM', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_TTPMNO', null, ['class' => 'form-control']) }}
                                    {{-- @if ($errors->has('IN_TTPMNO')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_TTPMNO') }}</strong>
                                        </span-->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div id="div_ttpmform" 
                                class="form-group {{ $errors->has('IN_TTPMFORM') ? ' has-error' : '' }}"
                                style="display: {{ old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none' }};">
                                {{ Form::label('IN_TTPMFORM', 'Jenis Borang TTPM', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
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
                                                'class' => 'form-control',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        )
                                    }}
                                    {{-- @if ($errors->has('IN_TTPMFORM')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_TTPMFORM') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div id="div_other" 
                                class="form-group {{ $errors->has('IN_REFOTHER') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_REFTYPE') == 'OTHER' ? 'block' : 'none' }};">
                                {{ 
                                    Form::label('IN_REFOTHER', 
                                    'Lain-lain', 
                                    ['class' => 'col-lg-5 control-label']) 
                                }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_REFOTHER','', ['class' => 'form-control']) }}
                                    {{-- @if ($errors->has('IN_REFOTHER')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_REFOTHER') }}</strong>
                                        </span-->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_AGAINSTLOCATION') ? ' has-error' : '' }}">
                                {{ Form::label('IN_AGAINSTLOCATION', 'Lokasi PYDA', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
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
                                    {{-- @if ($errors->has('IN_AGAINSTLOCATION')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_AGAINSTLOCATION') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div id="div_IN_AGAINST_BRSTATECD" 
                                class="form-group {{ $errors->has('IN_AGAINST_BRSTATECD') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_AGAINSTLOCATION') == 'BRN' ? 'block' : 'none' }};">
                                {{ Form::label('IN_AGAINST_BRSTATECD', 
                                    'Negeri', 
                                    ['class' => 'col-lg-5 control-label required']) 
                                }}
                                <div class="col-lg-7">
                                    {{ 
                                        Form::select(
                                            'IN_AGAINST_BRSTATECD', 
                                            Ref::GetList('17', false, 'ms'), 
                                            old('IN_AGAINST_BRSTATECD'), 
                                            [
                                                'class' => 'form-control',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    {{-- @if ($errors->has('IN_AGAINST_BRSTATECD')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_AGAINST_BRSTATECD') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div id="div_IN_BRNCD" 
                                class="form-group {{ $errors->has('IN_BRNCD') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_AGAINSTLOCATION') == 'BRN' ? 'block' : 'none' }};">
                                {{ Form::label('IN_BRNCD', 'Bahagian / Cawangan', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
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
                                                'class' => 'form-control',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    {{-- @if ($errors->has('IN_BRNCD')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_BRNCD') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div id="div_IN_AGENCYCD" 
                                class="form-group {{ $errors->has('IN_AGENCYCD') ? ' has-error' : '' }}" 
                                style="display: {{ old('IN_AGAINSTLOCATION') == 'AGN' ? 'block' : 'none' }};">
                                {{ 
                                    Form::label('IN_AGENCYCD', 
                                    'Agensi KPDNHEP', 
                                    ['class' => 'col-lg-5 control-label required']) 
                                }}
                                <div class="col-lg-7">
                                    {{ 
                                        Form::select(
                                            'IN_AGENCYCD', 
                                            IntegritiAdmin::GetMagncdList(), 
                                            null, 
                                            [
                                                'class' => 'form-control'
                                            ]
                                        ) 
                                    }}
                                    {{-- @if ($errors->has('IN_AGENCYCD')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_AGENCYCD') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_SUMMARY_TITLE') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SUMMARY_TITLE', 'Tajuk Aduan', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_SUMMARY_TITLE', null, ['class' => 'form-control']) }}
                                    {{-- @if ($errors->has('IN_SUMMARY_TITLE')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_SUMMARY_TITLE') }}</strong>
                                        </span-->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-lg-5 control-label required']) }}
                                <!-- trans('public-case.case.CA_SUMMARY') -->
                                <div class="col-lg-7">
                                    {{ Form::textarea('IN_SUMMARY', null, ['class' => 'form-control', 'rows'=> '5']) }}
                                    {{-- @if ($errors->has('IN_SUMMARY')) --}}
                                        <!-- span class="help-block">
                                            <strong>{{ $errors->first('IN_SUMMARY') }}</strong>
                                        </span -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div class="bg-muted b-r-xl p-xs"><strong>Pengakuan</strong></div>
                            <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                            <p>
                                Saya mengaku bahawa saya telah membaca dan memahami takrif aduan dan prosedur pengurusan aduan oleh pihak kerajaan Malaysia. Segala maklumat diri dan maklumat perkara yang dikemukakan oleh saya adalah benar dan saya bertanggungjawab ke atasnya.
                            </p>
                            <p>
                                Kerajaan Malaysia tidak bertanggungjawab terhadap sebarang kehilangan atau kerosakan yang dialami kerana menggunakan perkhidmatan ini di dalam sistem ini.
                            </p>
                            <p>
                                Semua maklumat akan dirahsiakan dan hanya digunakan oleh Kerajaan Malaysia.
                            </p>
                            <div class="form-group {{ $errors->has('agreeTnc') ? ' has-error' : '' }}">
                                    <!-- {{-- Form::label('', '', ['class' => 'col-lg-3 control-label']) --}} -->
                                <div class="col-lg-12">
                                    <div class="checkbox checkbox-primary">
                                        <input id="agreeTnc" type="checkbox" name="agreeTnc" {{ old('agreeTnc') == 'on'? 'checked':'' }}>
                                        <label for="agreeTnc">
                                            Saya telah membaca dan setuju dengan Terma dan Syarat yang telah ditetapkan.
                                        </label>
                                        <div id="notis">
                                            <b>
                                                <font color="red">
                                                &nbsp;
                                                (Sila sahkan pengakuan anda sebelum meneruskan membuat aduan)
                                                </font>
                                            </b>
                                        </div>
                                    </div>
                                    @if ($errors->has('agreeTnc'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('agreeTnc') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="text-center">
                                <a href="{{ url('integriti')}}" type="button" class="btn btn-primary">@lang('button.back')</a>
                                {{ Form::button( trans('button.continue'), ['type' => 'submit', 'class' => 'btn btn-success']) }}
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">

    function check(value) {
        if (value === '1') {
            // $('#warganegara').show()
            $('#bknwarganegara').hide()
        } else {
            // $('#warganegara').show()
            $('#bknwarganegara').show()
        }
    }
    
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
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

    $('#IN_STATECD').on('change', function (e) {
        var IN_STATECD = $(this).val();
        if(IN_STATECD){
            $.ajax({
                type: 'GET',
                url: "{{ url('integritipublicuser/getdistlist') }}" + '/' + IN_STATECD,
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

    $('#IN_AGAINST_BRSTATECD').on('change', function (e) {
        var IN_AGAINST_BRSTATECD = $(this).val();
        if(IN_AGAINST_BRSTATECD){
            $.ajax({
                type: 'GET',
                // url: "{{-- url('user/getbrnlist') --}}" + '/' + IN_AGAINST_BRSTATECD,
                url: "{{ url('integritipublicuser/getbrnlist') }}" + '/' + IN_AGAINST_BRSTATECD,
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

    $('#IN_REFTYPE').on('change', function () {
        var IN_REFTYPE = $(this).val();
        // var IN_DOCNO = $('#IN_DOCNO').val();
        if(IN_REFTYPE === 'BGK') {
            $("#div_bgk").show();
            $("#div_other").hide();
            $("#div_ttpm").hide();
            $("#div_ttpmform").hide();
            $('#IN_AGAINSTLOCATION1').trigger('click');
            // $('#IN_AGAINSTLOCATION2').attr('disabled', false);
            $('#IN_AGAINSTLOCATION2').attr('disabled', true);
            // if(!IN_DOCNO){
            //     alert('Sila isikan No. Kad Pengenalan Sebelum Memilih Rujukan No. Aduan Kepenggunaan.');
            // }
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

    // $('#IN_DOCNO').blur(function () {
    //     // checkJpn()
    //     var IN_DOCNO = $(this).val();
    //     if(IN_DOCNO){
    //         $.ajax({
    //             type: 'GET',
    //             url: "{{-- url('integritipublicuser/getpublicusercomplaintlist') --}}" + '/' + IN_DOCNO,
    //             dataType: 'json',
    //             success: function (data) {
    //                 $('select[name="IN_BGK_CASEID"]').empty();
    //                 $.each(data, function (key, value) {
    //                     $('select[name="IN_BGK_CASEID"]').append('<option value="' + key + '">' + value + '</option>');
    //                 })
    //             },
    //             complete: function (data) {
    //                 // $('#IN_BGK_CASEID')
    //                 $('select[name="IN_BGK_CASEID"]').trigger('change');
    //             }
    //         });
    //     } else {
    //         $('select[name="IN_BGK_CASEID"]').empty();
    //         // $('select[id="IN_BGK_CASEID"]').append('<option>-- SILA PILIH --</option>');
    //         $('select[name="IN_BGK_CASEID"]').append('<option value="">-- SILA PILIH --</option>');
    //         $('select[name="IN_BGK_CASEID"]').trigger('change');
    //     }
    // })

    $('#formpublicuser').on('submit', function (e) {
        if (!$('#agreeTnc').is(':checked')) {
            e.preventDefault();
        }
    });
    $('#agreeTnc').click(function () {
        if ($(this).is(':checked')) {
            $('#notis').hide();
        } else {
            $('#notis').show();
        }
    });
    if ($('#agreeTnc').is(':checked')) {
        $('#notis').hide();
    }
</script>

@endsection
