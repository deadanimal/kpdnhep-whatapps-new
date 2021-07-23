@extends('layouts.main_public')
<?php

use App\Ref;
use App\Aduan\PublicCase;
?>
@section('content')
<style>
    textarea {
        resize: vertical;
    }
    select {
        width: 100%;
    }
    span.select2 {
        width: 100% !important;
    }
    .help-block-red {
        color: red;
    }
</style>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active">
            <!--<a style="color: black; background-color: #e7eaec">-->
            <a>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">1</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                @lang('public-case.case.complaintinfo')
            </a>
        </li>
        <li class="">
            <a>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">2</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                @lang('public-case.case.attachment')
            </a>
        </li>
        <li class="">
            <a>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">3</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                @lang('public-case.case.complaintreview')
            </a>
        </li>
        <li class="">
            <a>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">4</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">4</span>
                </span>
                @lang('public-case.case.acceptancedeclaration')
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                <div class="col-lg-12">
                    <div class="panel panel-success" style="border: 1px solid #115272;">
                        <div class="panel-heading" style="background: #115272;">
                            <i class="fa fa fa-tag"></i> @lang('button.info')
                        </div>
                        <!--<div class="panel-body" style="color: black; background-color: #e7eaec">-->
                        <div class="panel-body" style="color: black;">
                            {!! Form::open(['url' => 'public-case', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group{{ $errors->has('CA_CMPLCAT') ? ' has-error' : '' }}">
                                        <label for="CA_CMPLCAT" class="col-sm-4 control-label required">@lang('public-case.case.CA_CMPLCAT')</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_CMPLCAT', Ref::GetList('244', true, Auth::user()->lang, Auth::user()->lang == 'ms' ? 'descr' : 'descr_en'), null, ['class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT', 'style' => 'width:100%']) }}
                                            @if ($errors->has('CA_CMPLCAT'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_CMPLCAT')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_CMPLCD') ? ' has-error' : '' }}">
                                        <label for="CA_CMPLCD" class="col-sm-4 control-label required">@lang('public-case.case.CA_CMPLCD')</label>
                                        <div class="col-sm-8">
                                            @if(old('CA_CMPLCAT'))
                                            {{ Form::select('CA_CMPLCD',PublicCase::GetCmplCd(old('CA_CMPLCAT'), Auth::user()->lang, Auth::user()->lang == 'ms' ? 'descr' : 'descr_en'), old('CA_CMPLCD'),['class' => 'form-control input-sm', 'style' => 'width:100%'])}}
                                            @else
                                            {{ Form::select('CA_CMPLCD',Auth::user()->lang == 'ms'? ['' => '-- SILA PILIH --']:['' => '-- PLEASE SELECT --'], '',['class' => 'form-control input-sm', 'style' => 'width:100%'])}}
                                            @endif
                                            @if ($errors->has('CA_CMPLCD'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_CMPLCD')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="CA_TTPMTYP" style="display: {{ in_array(old('CA_CMPLCAT'),['BPGK 08'])? 'block':'none' }};" class="form-group{{ $errors->has('CA_TTPMTYP') ? ' has-error' : '' }}">
                                        <label for="CA_TTPMTYP" class="col-sm-4 control-label required">@lang('public-case.case.CA_TTPMTYP')</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_TTPMTYP',Ref::GetList('1108',true, Auth::user()->lang), old('CA_TTPMTYP'),['class' => 'form-control input-sm', 'style' => 'width:100%'])}}
                                            @if ($errors->has('CA_TTPMTYP'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_TTPMTYP')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="CA_TTPMNO" style="display: {{ in_array(old('CA_CMPLCAT'),['BPGK 08'])? 'block':'none' }};" class="form-group{{ $errors->has('CA_TTPMNO') ? ' has-error' : '' }}">
                                        <label for="CA_TTPMNO" class="col-sm-4 control-label required">@lang('public-case.case.CA_TTPMNO')</label>
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_TTPMNO','',['class' => 'form-control  input-sm'])}}
                                            @if ($errors->has('CA_TTPMNO'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_TTPMNO')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <!--<div id="div_CA_ONLINECMPL_AMOUNT" style="display: {{-- old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' --}};" class="form-group{{-- $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' --}}">-->
                                    <div class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                                        <label for="CA_ONLINECMPL_AMOUNT" class="col-sm-4 control-label required">@lang('public-case.case.CA_ONLINECMPL_AMOUNT')</label>
                                        <div class="col-sm-8">
                                            <!--{{-- Form::text('CA_ONLINECMPL_AMOUNT','0.00', ['class' => 'form-control input-sm', 'id'=>'CA_ONLINECMPL_AMOUNT', 'onkeypress' => "return isNumberKey1(event)"]) --}}-->
                                            {{ Form::text('CA_ONLINECMPL_AMOUNT', old('CA_ONLINECMPL_AMOUNT', '0.00'), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey1(event)", 'id'=>'CA_ONLINECMPL_AMOUNT']) }}
                                            @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                            <!--<span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_AMOUNT')</strong></span>-->
                                            <span class="help-block"><strong>{{ $errors->first('CA_ONLINECMPL_AMOUNT') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_CMPLKEYWORD') ? ' has-error' : '' }}" id="CA_CMPLKEYWORD" style="display: {{ in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none' }};">
                                        <label for="CA_CMPLKEYWORD" class="col-sm-4 control-label required">@lang('public-case.case.CA_CMPLKEYWORD')</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_CMPLKEYWORD',Ref::GetList('1051',true, Auth::user()->lang), old('CA_CMPLKEYWORD'),['class' => 'form-control input-sm', 'style' => 'width:100%'])}}
                                            @if ($errors->has('CA_CMPLKEYWORD'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_CMPLKEYWORD')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINST_PREMISE" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block' }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                        <label for="CA_AGAINST_PREMISE" class="col-sm-4 control-label {{ old('CA_CMPLCAT') == 'BPGK 19'? '':'required' }}">@lang('public-case.case.CA_AGAINST_PREMISE')</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_AGAINST_PREMISE', Auth::user()->lang == 'ms' ? Ref::GetList('221', true) : Ref::GetList('221', true, 'en'), null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_PREMISE', 'style' => 'width:100%']) }}
                                            @if ($errors->has('CA_AGAINST_PREMISE'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_PREMISE')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Maklumat Pembekal Perkhidmatan -->
                                    <br />

                                    <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};">
                                        <h3><strong>@lang('public-case.case.lblservicepro')</strong></h3>
                                        <hr style="background-color: #ccc; height: 1px; width: 200%; border: 0;">
                                        <div class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                            <label for="CA_ONLINECMPL_PROVIDER" class="col-sm-4 control-label required">@lang('public-case.case.CA_ONLINECMPL_PROVIDER')</label>
                                            <div class="col-sm-8">
                                                {{ Form::select('CA_ONLINECMPL_PROVIDER',Ref::GetList('1091',true, Auth::user()->lang, 'descr'), null, ['class' => 'form-control input-sm', 'id' => 'CA_ONLINECMPL_PROVIDER', 'style' => 'width:100%']) }}
                                                @if ($errors->has('CA_ONLINECMPL_PROVIDER'))
                                                <span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_PROVIDER')</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <!--<div id="div_CA_ONLINECMPL_URL" style="display: {{-- old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' --}};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">-->
                                        <div class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                            <label for="CA_ONLINECMPL_URL" class="col-sm-4 control-label">@lang('public-case.case.CA_ONLINECMPL_URL')</label>
                                            <div class="col-sm-8">
                                                {{ Form::text('CA_ONLINECMPL_URL','', ['class' => 'form-control input-sm', 'placeholder' => trans('public-case.case.CA_ONLINECMPL_URL_PLACEHOLDER')]) }}
                                                @if ($errors->has('CA_ONLINECMPL_URL'))
                                                    <span class="help-block">
                                                        <strong>
                                                            {{-- @lang('public-case.validation.CA_ONLINECMPL_URL') --}}
                                                            {{ $errors->first('CA_ONLINECMPL_URL') }}
                                                        </strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!--<div id="div_CA_ONLINECMPL_PYMNTTYP" style="display: {{-- old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' --}};" class="form-group{{ $errors->has('CA_ONLINECMPL_PYMNTTYP') ? ' has-error' : '' }}">-->
                                        <div class="form-group{{ $errors->has('CA_ONLINECMPL_PYMNTTYP') ? ' has-error' : '' }}">
                                            <label for="CA_ONLINECMPL_PYMNTTYP" class="col-sm-4 control-label required">@lang('public-case.case.CA_ONLINECMPL_PYMNTTYP')</label>
                                            <div class="col-sm-8">

                                                {{ Form::select('CA_ONLINECMPL_PYMNTTYP',Auth::user()->lang == 'ms' ? Ref::GetList('1207', true) : Ref::GetList('1207', true,'en') ,'', ['class' => 'form-control input-sm', 'style' => 'width:100%', 'id' => 'CA_ONLINECMPL_PYMNTTYP']) }}
                                                @if ($errors->has('CA_ONLINECMPL_PYMNTTYP'))
                                                <span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_PYMNTTYP')</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <!--<div id="div_CA_ONLINECMPL_BANKCD" style="display: {{-- old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' --}};" class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">-->
                                        <div class="form-group{{ $errors->has('CA_ONLINECMPL_BANKCD') ? ' has-error' : '' }}">
                                            <!--<label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label {{-- old('CA_ONLINECMPL_PYMNTTYP') == 'COD'? '':'required' --}}">@lang('public-case.case.CA_ONLINECMPL_BANKCD')</label>-->
                                            <label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label {{ in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '':'required' }}">@lang('public-case.case.CA_ONLINECMPL_BANKCD')</label>
                                            <div class="col-sm-8">
                                                {{ Form::select('CA_ONLINECMPL_BANKCD', Ref::GetList('1106', true, Auth::user()->lang), '', ['class' => 'form-control input-sm']) }}
                                                @if ($errors->has('CA_ONLINECMPL_BANKCD'))
                                                <span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_BANKCD')</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <!--<div id="div_CA_ONLINECMPL_ACCNO" style="display: {{-- old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' --}};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">-->
                                        <div class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                            <!--<label for="CA_ONLINECMPL_ACCNO" class="col-sm-4 control-label {{-- old('CA_ONLINECMPL_PYMNTTYP') == 'COD'? '':'required' --}}">@lang('public-case.case.CA_ONLINECMPL_ACCNO')</label>-->
                                            <label for="CA_ONLINECMPL_ACCNO" class="col-sm-4 control-label {{ in_array(old('CA_ONLINECMPL_PYMNTTYP'),['','COD','TB']) ? '':'required' }}">@lang('public-case.case.CA_ONLINECMPL_ACCNO')</label>
                                            <div class="col-sm-8">
                                                {{ Form::text('CA_ONLINECMPL_ACCNO','', ['class' => 'form-control input-sm']) }}
                                                @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                                    <span class="help-block">
                                                        <strong>
                                                            @lang('public-case.validation.CA_ONLINECMPL_ACCNO')
                                                            {{-- $errors->first('CA_ONLINECMPL_ACCNO') --}}
                                                        </strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!--<div class="form-group" style="display: {{-- old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' --}};" id="checkpernahadu">-->
                                        <div class="form-group" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" id="checkpernahadu">
                                            {{ Form::label('', null, ['class' => 'col-md-4 control-label']) }}
                                            <div class="col-sm-6">
                                                <div class="checkbox checkbox-success">
                                                    <input name="CA_ONLINECMPL_IND" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' }}>
                                                           <label for="CA_ONLINECMPL_IND">
                                                        @lang('public-case.case.CA_ONLINECMPL_IND')
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div id="div_CA_ONLINECMPL_CASENO" style="display: {{-- old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' --}};" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">-->
                                        <!--<div class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">-->
                                        <div id="div_CA_ONLINECMPL_CASENO" style="display: {{ old('CA_ONLINECMPL_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">
                                            <label for="CA_ONLINECMPL_CASENO" class="col-sm-4 control-label">@lang('public-case.case.CA_ONLINECMPL_CASENO')</label>
                                            <div class="col-sm-8">
                                                {{ Form::text('CA_ONLINECMPL_CASENO','', ['class' => 'form-control input-sm']) }}
                                                @if ($errors->has('CA_ONLINECMPL_CASENO'))
                                                <span class="help-block">
                                                    <strong>
                                                        {{-- @lang('public-case.validation.CA_ONLINECMPL_CASENO') --}}
                                                        {{ $errors->first('CA_ONLINECMPL_CASENO') }}
                                                    </strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div id="div_CA_ONLINECMPL_URL" style="height: 210px; display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};"></div>
                                    <div class="form-group{{ $errors->has('CA_AGAINSTNM') ? ' has-error' : '' }}">
                                        <label id="lbl_CA_AGAINSTNM" for="CA_AGAINSTNM" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINSTNM')</label>
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_AGAINSTNM','', ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_AGAINSTNM'))
                                                <span class="help-block">
                                                    <strong>
                                                    {{-- @lang('public-case.validation.CA_AGAINSTNM') --}}
                                                    {{ $errors->first('CA_AGAINSTNM') }}
                                                    </strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                        <label id="lbl_CA_AGAINST_TELNO" for="CA_AGAINST_TELNO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_TELNO')</label>
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_AGAINST_TELNO','', ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)"]) }}
                                            @if ($errors->has('CA_AGAINST_TELNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                                        <label id="lbl_CA_AGAINST_MOBILENO" for="CA_AGAINST_MOBILENO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_MOBILENO')</label>
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_AGAINST_MOBILENO','', ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)"]) }}
                                            @if ($errors->has('CA_AGAINST_MOBILENO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                                        <label id="lbl_CA_AGAINST_EMAIL" for="CA_AGAINST_EMAIL" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_EMAIL')</label>
                                        <div class="col-sm-8">
                                            {{ Form::email('CA_AGAINST_EMAIL','', ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('CA_AGAINST_EMAIL'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                                        <label id="lbl_CA_AGAINST_FAXNO" for="CA_AGAINST_FAXNO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_FAXNO')</label>
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_AGAINST_FAXNO','', ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)"]) }}
                                            @if ($errors->has('CA_AGAINST_FAXNO'))
                                            <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_FAXNO') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="checkinsertadd" style="display: {{ old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none' }};" class="form-group">
                                        {{ Form::label('', null, ['class' => 'col-md-4 control-label']) }}
                                        <div class="col-sm-6">
                                            <div class="checkbox checkbox-success">
                                                <input name="CA_ONLINEADD_IND" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' }}>
                                                       <label for="CA_ONLINEADD_IND">
                                                    @lang('public-case.case.CA_ONLINEADD_IND')
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINSTADD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                                        <!--<label id="lbl_CA_AGAINSTADD" for="CA_AGAINSTADD" class="col-sm-4 control-label {{-- old('CA_CMPLCAT') == 'BPGK 19'? '':'required' --}}">@lang('public-case.case.CA_AGAINSTADD')</label>-->
                                        <label id="lbl_CA_AGAINSTADD" for="CA_AGAINSTADD" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINSTADD')</label>
                                        <div class="col-sm-8">
                                            {{ Form::textarea('CA_AGAINSTADD','', ['class' => 'form-control input-sm', 'rows'=> '3']) }}
                                            @if ($errors->has('CA_AGAINSTADD'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINSTADD')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINST_POSTCD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                                        <!--<label id="lbl_CA_AGAINST_POSTCD" for="CA_AGAINST_POSTCD" class="col-sm-4 control-label {{-- old('CA_CMPLCAT') == 'BPGK 19'? '':'required' --}}">@lang('public-case.case.CA_AGAINST_POSTCD')</label>-->
                                        <label id="lbl_CA_AGAINST_POSTCD" for="CA_AGAINST_POSTCD" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_POSTCD')</label>
                                        <div class="col-sm-8">
                                            {{ Form::text('CA_AGAINST_POSTCD','', ['class' => 'form-control input-sm', 'maxlength' => 5, 'onkeypress' => "return isNumberKey(event)"]) }}
                                            @if ($errors->has('CA_AGAINST_POSTCD'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_POSTCD')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINST_STATECD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                                        <label id="lbl_CA_AGAINST_STATECD" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_STATECD')</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('CA_AGAINST_STATECD', Auth::user()->lang == 'ms' ? Ref::GetList('17', true) : Ref::GetList('17', true, 'en'), null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_STATECD', 'style' => 'width:100%']) }}
                                            @if ($errors->has('CA_AGAINST_STATECD'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_STATECD')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_CA_AGAINST_DISTCD" style="display: {{ old('CA_ONLINEADD_IND') == 'on' && old('CA_CMPLCAT') == 'BPGK 19'? 'block':(old('CA_CMPLCAT')? (old('CA_CMPLCAT') != 'BPGK 19'? 'block':'none'):'block') }};" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                                        <label id="lbl_CA_AGAINST_DISTCD" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_DISTCD')</label>
                                        <div class="col-sm-8">
                                            @if (old('CA_AGAINST_STATECD'))
                                            {{ Form::select('CA_AGAINST_DISTCD', Ref::GetListDist(old('CA_AGAINST_STATECD'), '18', true, Auth::user()->lang), null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD', 'style' => 'width:100%']) }}
                                            @else
                                            {{ Form::select('CA_AGAINST_DISTCD', Auth::user()->lang == 'ms' ? ['' => '-- SILA PILIH --'] : ['' => '-- PLEASE SELECT --'], null, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD', 'style' => 'width:100%']) }}
                                            @endif
                                            <span class="help-block m-b-none"><em><a href="/storage/SENARAI KOD DAERAH DAN MUKIM 02012018.pdf" target="_blank">@lang('button.statedistpdf')</a></em></span>
                                            @if ($errors->has('CA_AGAINST_DISTCD'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_DISTCD')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                                        <label for="CA_SUMMARY" class="col-sm-2 control-label required">@lang('public-case.case.CA_SUMMARY')</label>
                                        <div class="col-sm-10">
                                            {{ Form::textarea('CA_SUMMARY','', ['class' => 'form-control input-sm', 'rows'=> '5']) }}
                                            <span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span>
                                            @if ($errors->has('CA_SUMMARY'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_SUMMARY')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12" align="center">
                                    <!--<a class="btn btn-warning btn-sm" href="{{-- route('dashboard') --}}">@lang('button.back')</a>-->
                                    {{ Form::button(trans('button.continue').' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm'] )  }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $('#CA_ONLINECMPL_AMOUNT').blur(function(){
            $(this).val(amountchange($(this).val()));

//            alert(amount);
//            alert('Berjaya');
//            alert((123456789.12345).formatMoney(2, '.', ','));
//        var num = $(this).val();
//        alert(parseFloat((Math.round(num*100)/100)).toFixed(2).toLocaleString());
//        alert(num.toLocaleString('en'));
//        var formatNumber = function(num) {

//         };
//        var num = parseFloat($(this).val());
//        var cleanNum = num.toFixed(2);
//        $(this).val(cleanNum);
//            if(num/cleanNum < 1){
//                $('#error').text('Please enter only 2 decimal places, we have truncated extra points');
//                }
        });

        function amountchange(amount) {
            var delimiter = ","; // replace comma if desired
            var a = amount.split('.',2);
            var d = a[1];
            if(d){
                if(d.length === 1){
//                    alert('1');
                    d = d + '0';
                }else if(d.length === 2){
//                    alert('2');
                    d = d;
                }else{
                    d = d;
                }
            }else{
                d = '00';
            }
            var i = parseInt(a[0]);
            if(isNaN(i)) { return ''; }
            var minus = '';
            if(i < 0) { minus = '-'; }
            i = Math.abs(i);
            var n = new String(i);
            var a = [];
            while(n.length > 3) {
                    var nn = n.substr(n.length-3);
                a.unshift(nn);
                    n = n.substr(0,n.length-3);
            }
            if(n.length > 0) { a.unshift(n); }
            n = a.join(delimiter);
            if(d.length < 1) { amount = n; }
            else { amount = n + '.' + d; }
            amount = minus + amount;
            return amount;
//            $(this).val(amount);
        }
        $("select").select2();
    });
//    $(document).ready(function(){
//        document.getElementById('btnsave').style.visibility = 'hidden';
//    )};
    function btncontinueclick() {
        document.getElementById('btnsave').style.visibility = 'visible';
        document.getElementById('btnupdate').style.visibility = 'visible';
        document.getElementById('btncontinue').style.visibility = 'hidden';
        $('.form-control').attr("readonly", true);
    }
    function btnupdateclick() {
        document.getElementById('btnsave').style.visibility = 'hidden';
        document.getElementById('btnupdate').style.visibility = 'hidden';
        document.getElementById('btncontinue').style.visibility = 'visible';
        $('.form-control').attr("readonly", false);
    }

    function onlinecmplind() {
        if (document.getElementById('CA_ONLINECMPL_IND').checked)
            $('#div_CA_ONLINECMPL_CASENO').show();
        else
            $('#div_CA_ONLINECMPL_CASENO').hide();
    };

    function onlineaddind() {
        if (document.getElementById('CA_ONLINEADD_IND').checked) {
            $('#div_CA_AGAINSTADD').show();
            $('#div_CA_AGAINST_POSTCD').show();
            $('#div_CA_AGAINST_STATECD').show();
            $('#div_CA_AGAINST_DISTCD').show();
        }else{
            $('#div_CA_AGAINSTADD').hide();
            $('#div_CA_AGAINST_POSTCD').hide();
            $('#div_CA_AGAINST_STATECD').hide();
            $('#div_CA_AGAINST_DISTCD').hide();
        }
    };

    $(function () {

        $('#CA_ONLINECMPL_PROVIDER').on('change', function (e) {
            var CA_ONLINECMPL_PROVIDER = $(this).val();
            if(CA_ONLINECMPL_PROVIDER === '999')
                $( "label[for='CA_ONLINECMPL_URL']" ).addClass( "required" );
            else
                $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
        });

        $('#CA_ONLINECMPL_PYMNTTYP').on('change', function (e) {
            var CA_ONLINECMPL_PYMNTTYP = $(this).val();
            var requiredPaymentTypes = ['CRC', 'OTF', 'CDM'];
            var isInclude = requiredPaymentTypes.includes(CA_ONLINECMPL_PYMNTTYP);
            // if (CA_ONLINECMPL_PYMNTTYP === 'COD' || CA_ONLINECMPL_PYMNTTYP === '') {
            //     $("label[for='CA_ONLINECMPL_BANKCD']").removeClass("required");
            //     $("label[for='CA_ONLINECMPL_ACCNO']").removeClass("required");
            // } else {
            //     $("label[for='CA_ONLINECMPL_BANKCD']").addClass("required");
            //     $("label[for='CA_ONLINECMPL_ACCNO']").addClass("required");
            // }
            if (isInclude) {
                $('label[for="CA_ONLINECMPL_BANKCD"]').addClass('required');
                $('label[for="CA_ONLINECMPL_ACCNO"]').addClass('required');
            } else {
                $('label[for="CA_ONLINECMPL_BANKCD"]').removeClass('required');
                $('label[for="CA_ONLINECMPL_ACCNO"]').removeClass('required');
            }
        });

        $('#CA_CMPLCAT').on('change', function (e) {
            var CA_CMPLCAT = $(this).val();

            if(CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
                $( "#CA_CMPLKEYWORD" ).show();
            }else{
                $( "#CA_CMPLKEYWORD" ).hide();
            }

            if(CA_CMPLCAT === 'BPGK 08') {
                $( "#CA_TTPMTYP" ).show();
                $( "#CA_TTPMNO" ).show();
            }else{
                $( "#CA_TTPMTYP" ).hide();
                $( "#CA_TTPMNO" ).hide();
            }

            if(CA_CMPLCAT === 'BPGK 19') {
                if (document.getElementById('CA_ONLINECMPL_IND').checked)
                    $('#div_CA_ONLINECMPL_CASENO').show();
                else
                    $('#div_CA_ONLINECMPL_CASENO').hide();

                $( "#checkpernahadu" ).show();
                $( "#checkinsertadd" ).show();
                $('#div_CA_ONLINECMPL_PROVIDER').show();
                $('#div_CA_ONLINECMPL_URL').show();
                $('#div_CA_ONLINECMPL_AMOUNT').show();
                $('#div_CA_ONLINECMPL_BANKCD').show();
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_ONLINECMPL_PYMNTTYP').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
//                document.getElementById('lbl_CA_AGAINSTNM').innerHTML = "{{ trans('public-case.case.CA_AGAINSTNM_SP') }}";
//                document.getElementById('lbl_CA_AGAINST_TELNO').innerHTML = "{{ trans('public-case.case.CA_AGAINST_TELNO_SP') }}";
//                document.getElementById('lbl_CA_AGAINST_MOBILENO').innerHTML = "{{ trans('public-case.case.CA_AGAINST_MOBILENO_SP') }}";
//                document.getElementById('lbl_CA_AGAINST_EMAIL').innerHTML = "{{ trans('public-case.case.CA_AGAINST_EMAIL_SP') }}";
//                document.getElementById('lbl_CA_AGAINST_FAXNO').innerHTML = "{{ trans('public-case.case.CA_AGAINST_FAXNO_SP') }}";
//                document.getElementById('lbl_CA_AGAINSTADD').innerHTML = "{{ trans('public-case.case.CA_AGAINSTADD_SP') }}";
//                document.getElementById('lbl_CA_AGAINST_POSTCD').innerHTML = "{{ trans('public-case.case.CA_AGAINST_POSTCD_SP') }}";
//                document.getElementById('lbl_CA_AGAINST_STATECD').innerHTML = "{{ trans('public-case.case.CA_AGAINST_STATECD_SP') }}";
//                document.getElementById('lbl_CA_AGAINST_DISTCD').innerHTML = "{{ trans('public-case.case.CA_AGAINST_DISTCD_SP') }}";
//                $( "label[for='CA_AGAINST_PREMISE']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINSTADD']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINST_POSTCD']" ).removeClass( "required" );
            }else{
                $( "#checkpernahadu" ).hide();
                $( "#checkinsertadd" ).hide();
                $('#div_CA_ONLINECMPL_CASENO').hide();
                $('#div_CA_ONLINECMPL_PROVIDER').hide();
                $('#div_CA_ONLINECMPL_URL').hide();
                $('#div_CA_ONLINECMPL_AMOUNT').hide();
                $('#div_CA_ONLINECMPL_BANKCD').hide();
                $('#div_CA_ONLINECMPL_ACCNO').hide();
                $('#div_CA_ONLINECMPL_PYMNTTYP').hide();
                $('#div_CA_AGAINST_PREMISE').show();
                $('#div_CA_AGAINSTADD').show();
                $('#div_CA_AGAINST_POSTCD').show();
                $('#div_CA_AGAINST_STATECD').show();
                $('#div_CA_AGAINST_DISTCD').show();
//                document.getElementById('lbl_CA_AGAINSTNM').innerHTML = "{{ trans('public-case.case.CA_AGAINSTNM') }}";
//                document.getElementById('lbl_CA_AGAINST_TELNO').innerHTML = "{{ trans('public-case.case.CA_AGAINST_TELNO') }}";
//                document.getElementById('lbl_CA_AGAINST_MOBILENO').innerHTML = "{{ trans('public-case.case.CA_AGAINST_MOBILENO') }}";
//                document.getElementById('lbl_CA_AGAINST_EMAIL').innerHTML = "{{ trans('public-case.case.CA_AGAINST_EMAIL') }}";
//                document.getElementById('lbl_CA_AGAINST_FAXNO').innerHTML = "{{ trans('public-case.case.CA_AGAINST_FAXNO') }}";
//                document.getElementById('lbl_CA_AGAINSTADD').innerHTML = "{{ trans('public-case.case.CA_AGAINSTADD') }}";
//                document.getElementById('lbl_CA_AGAINST_POSTCD').innerHTML = "{{ trans('public-case.case.CA_AGAINST_POSTCD') }}";
//                document.getElementById('lbl_CA_AGAINST_STATECD').innerHTML = "{{ trans('public-case.case.CA_AGAINST_STATECD') }}";
//                document.getElementById('lbl_CA_AGAINST_DISTCD').innerHTML = "{{ trans('public-case.case.CA_AGAINST_DISTCD') }}";
//                $( "label[for='CA_AGAINST_PREMISE']" ).addClass( "required" );
//                $( "label[for='CA_AGAINSTADD']" ).addClass( "required" );
//                $( "label[for='CA_AGAINST_POSTCD']" ).addClass( "required" );
            }

            $.ajax({
                type: 'GET',
                url: "{{ url('public-case/getCmplCdList') }}" + "/" + CA_CMPLCAT,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('select[name="CA_CMPLCD"]').empty();
                    $.each(data, function (key, value) {
                        if (value == '0'){
                            $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                            $('select[name="CA_CMPLCD"]').trigger('change');
                        } else {
                            $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                            $('select[name="CA_CMPLCD"]').trigger('change');
                        }
                    });
//                $.each(data, function(key, value) {
//                    $('select[name="CA_AGAINST_DISTCD"]').append('<option value="'+ key +'">'+ value +'</option>');
//                });
                }
            });
        });

//        $('#CA_ONLINECMPL_IND').on('click', function() {
//            var Ind = $(this).val();
//            alert(Ind)
//        });

        $('#CA_AGAINST_STATECD').on('change', function (e) {
            var CA_AGAINST_STATECD = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('public-case/getdaerahlist') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success:function(data){
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="CA_AGAINST_DISTCD"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });
                },
                complete: function (data) {
                    $('#CA_AGAINST_DISTCD').trigger('change');
                }
            });
        });
    });

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function isNumberKey1(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46)
            return false;
        return true;
    }

</script>
@stop
