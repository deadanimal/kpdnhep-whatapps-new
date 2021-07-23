@extends('layouts.main_public')
<?php

use App\Ref;
use App\Aduan\PublicCase;
use App\Aduan\PublicCaseDoc;
?>
@section('content')
<style>
    .form-control[disabled] {
        background-color: #ffffff;
    }
    textarea {
        resize: vertical;
    }
</style>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#caseinfo">@lang('public-case.case.complaintinfo')</a></li>
        <!--<li class=""><a data-toggle="tab" href="#attachment">@lang('public-case.case.attachment')</a></li>-->
        <li class=""><a data-toggle="tab" href="#transaction">@lang('public-case.case.transaction')</a></li>
    </ul>
    <div class="tab-content">
        <div id="caseinfo" class="tab-pane active">
            <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                <div class="col-lg-12">
                    <div class="panel panel-success" style="border: 1px solid #115272;">
                        <div class="panel-heading" style="background: #115272;">
                            <i class="fa fa fa-tag"></i> @lang('button.info')
                        </div>
                        <div class="panel-body" style="color: black;">
                            {!! Form::open(['url' => ['public-case', $model->CA_CASEID], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                            {{ csrf_field() }}{{ method_field('PATCH') }}

                            <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="CA_CMPLCAT" class="col-sm-4 control-label required">@lang('public-case.case.CA_CMPLCAT')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CMPLCAT', $model->CA_CMPLCAT != ''? Ref::GetDescr('244', $model->CA_CMPLCAT, Auth::user()->lang) : '', array('class' => 'form-control input-sm', 'readonly' => true)) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="CA_CMPLCD" class="col-sm-4 control-label required">@lang('public-case.case.CA_CMPLCD')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CMPLCD', $model->CA_CMPLCD != ''? Ref::GetDescr('634', $model->CA_CMPLCD, Auth::user()->lang) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_CMPLCD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_CMPLCD')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT') ? (old('CA_CMPLCAT') == 'BPGK 08' ? 'block' : 'none') : ($model->CA_CMPLCAT == 'BPGK 08' ? 'block' : 'none')) }}">
                                <label for="CA_TTPMTYP" class="col-sm-4 control-label">@lang('public-case.case.CA_TTPMTYP')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_TTPMTYP', $model->CA_TTPMTYP != ''? Ref::GetDescr('1108', $model->CA_TTPMTYP, Auth::user()->lang) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('CA_CMPLCAT') ? (old('CA_CMPLCAT') == 'BPGK 08' ? 'block' : 'none') : ($model->CA_CMPLCAT == 'BPGK 08' ? 'block' : 'none')) }}">
                                <label for="CA_TTPMNO" class="col-sm-4 control-label">@lang('public-case.case.CA_TTPMNO')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_TTPMNO', $model->CA_TTPMNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <!--<div id="div_CA_ONLINECMPL_AMOUNT" style="display: {{-- (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) --}};" class="form-group{{-- $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' --}}">-->
                            <div id="div_CA_ONLINECMPL_AMOUNT" class="form-group{{ $errors->has('CA_ONLINECMPL_AMOUNT') ? ' has-error' : '' }}">
                                <label for="CA_ONLINECMPL_AMOUNT" class="col-sm-4 control-label">@lang('public-case.case.CA_ONLINECMPL_AMOUNT')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_ONLINECMPL_AMOUNT', number_format($model->CA_ONLINECMPL_AMOUNT, 2), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_ONLINECMPL_AMOUNT'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_AMOUNT')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group" id="CA_CMPLKEYWORD" style="display: {{ (old('CA_CMPLCAT')? (in_array(old('CA_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($model->CA_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none')))  }};">
                                <label for="CA_CMPLKEYWORD" class="col-sm-4 control-label required">@lang('public-case.case.CA_CMPLKEYWORD')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CMPLKEYWORD', $model->CA_CMPLKEYWORD != ''? Ref::GetDescr('1051', $model->CA_CMPLKEYWORD, Auth::user()->lang):'', ['class' => 'form-control  input-sm', 'readonly' => true])}}
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_PREMISE" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($model->CA_CMPLCAT == 'BPGK 19'? 'none':'block')) }};" class="form-group{{ $errors->has('CA_AGAINST_PREMISE') ? ' has-error' : '' }}">
                                <label for="CA_AGAINST_PREMISE" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_PREMISE')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_PREMISE', $model->CA_AGAINST_PREMISE != ''? Ref::GetDescr('221', $model->CA_AGAINST_PREMISE) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINST_PREMISE'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_PREMISE')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <br />
                            <div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                
                                <h3><strong>@lang('public-case.case.lblservicepro')</strong></h3>
                                <hr style="background-color: #ccc; height: 1px; width: 200%; border: 0;">
                                <!--<div id="div_CA_ONLINECMPL_PROVIDER" style="display: {{-- (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) --}};" class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">-->
                                <div class="form-group{{ $errors->has('CA_ONLINECMPL_PROVIDER') ? ' has-error' : '' }}">
                                    <label for="CA_ONLINECMPL_PROVIDER" class="col-sm-4 control-label required">@lang('public-case.case.CA_ONLINECMPL_PROVIDER')</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_PROVIDER', $model->CA_ONLINECMPL_PROVIDER != ''? Ref::GetDescr('1091', $model->CA_ONLINECMPL_PROVIDER, Auth::user()->lang) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <!--<div id="div_CA_ONLINECMPL_URL" style="display: {{-- (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) --}};" class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">-->
                                <div class="form-group{{ $errors->has('CA_ONLINECMPL_URL') ? ' has-error' : '' }}">
                                    <label for="CA_ONLINECMPL_URL" class="col-sm-4 control-label">@lang('public-case.case.CA_ONLINECMPL_URL')</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_URL',$model->CA_ONLINECMPL_URL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        @if ($errors->has('CA_ONLINECMPL_URL'))
                                        <span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_URL')</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="CA_ONLINECMPL_PYMNTTYP" class="col-sm-4 control-label">@lang('public-case.case.CA_ONLINECMPL_PYMNTTYP')</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_PYMNTTYP', $model->CA_ONLINECMPL_PYMNTTYP != '' ? Ref::GetDescr('1207', $model->CA_ONLINECMPL_PYMNTTYP, Auth::user()->lang) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="CA_ONLINECMPL_BANKCD" class="col-sm-4 control-label">@lang('public-case.case.CA_ONLINECMPL_BANKCD')</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_BANKCD', $model->CA_ONLINECMPL_BANKCD != '' ? Ref::GetDescr('1106', $model->CA_ONLINECMPL_BANKCD, Auth::user()->lang) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <!--<div id="div_CA_ONLINECMPL_ACCNO" style="display: {{-- (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) --}};" class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">-->
                                <div class="form-group{{ $errors->has('CA_ONLINECMPL_ACCNO') ? ' has-error' : '' }}">
                                    <label for="CA_ONLINECMPL_ACCNO" class="col-sm-4 control-label required">@lang('public-case.case.CA_ONLINECMPL_ACCNO')</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_ACCNO', $model->CA_ONLINECMPL_ACCNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        @if ($errors->has('CA_ONLINECMPL_ACCNO'))
                                        <span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_ACCNO')</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <!--<div class="form-group" style="display: {{-- (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) --}};" id="checkpernahadu">-->
                                <div class="form-group" id="checkpernahadu">
                                    {{ Form::label(old('CA_ONLINECMPL_IND'), null, ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-sm-6">
                                        <div class="checkbox checkbox-success">
                                            <input name="CA_ONLINECMPL_IND" disabled="disabled" id="CA_ONLINECMPL_IND" type="checkbox" onclick="onlinecmplind()" {{ old('CA_ONLINECMPL_IND') == 'on'? 'checked':'' || $model->CA_ONLINECMPL_IND == '1'? 'checked':'' }}>
                                            <label for="CA_ONLINECMPL_IND">
                                                @lang('public-case.case.CA_ONLINECMPL_IND')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!--<div id="div_CA_ONLINECMPL_CASENO" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINECMPL_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINECMPL_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINECMPL_IND') == '' && $model->CA_ONLINECMPL_IND == '1'? 'block':(old('CA_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">-->
                                <!--<div id="div_CA_ONLINECMPL_CASENO" style="display: {{ $model->CA_CMPLCAT == 'BPGK 19'? 'block':'none' }} ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}" ;" class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">-->
                                <div class="form-group{{ $errors->has('CA_ONLINECMPL_CASENO') ? ' has-error' : '' }}">
                                    <label for="CA_ONLINECMPL_CASENO" class="col-sm-4 control-label required">@lang('public-case.case.CA_ONLINECMPL_CASENO')</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('CA_ONLINECMPL_CASENO',$model->CA_ONLINECMPL_CASENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        @if ($errors->has('CA_ONLINECMPL_CASENO'))
                                        <span class="help-block"><strong>@lang('public-case.validation.CA_ONLINECMPL_CASENO')</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="div_CA_ONLINECMPL_URL" style="height: 210px; display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }}"></div>
                            <div class="form-group">
                                <label for="CA_AGAINSTNM" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINSTNM')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINSTNM', $model->CA_AGAINSTNM, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_TELNO') ? ' has-error' : '' }}">
                                <label for="CA_AGAINST_TELNO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_TELNO')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_TELNO', $model->CA_AGAINST_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINST_TELNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_MOBILENO') ? ' has-error' : '' }}">
                                <label for="CA_AGAINST_MOBILENO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_MOBILENO')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_MOBILENO', $model->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINST_MOBILENO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_EMAIL') ? ' has-error' : '' }}">
                                <label for="CA_AGAINST_EMAIL" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_EMAIL')</label>
                                <div class="col-sm-8">
                                    {{ Form::email('CA_AGAINST_EMAIL', $model->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINST_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('CA_AGAINST_FAXNO') ? ' has-error' : '' }}">
                                <label for="CA_AGAINST_FAXNO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_FAXNO')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_FAXNO', $model->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINST_FAXNO'))
                                    <span class="help-block"><strong>{{ $errors->first('CA_AGAINST_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="checkinsertadd" style="display: {{ (old('CA_CMPLCAT')? (old('CA_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($model->CA_CMPLCAT == 'BPGK 19'? 'block':'none')) }};" class="form-group">
                                {{ Form::label(old('CA_ONLINEADD_IND'), null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    <div class="checkbox checkbox-success">
                                        <input name="CA_ONLINEADD_IND" disabled="disabled" id="CA_ONLINEADD_IND" type="checkbox" onclick="onlineaddind()" {{ old('CA_ONLINEADD_IND') == 'on'? 'checked':'' || $model->CA_ONLINEADD_IND == '1'? 'checked':'' }}>
                                        <label for="CA_ONLINEADD_IND">
                                            @lang('public-case.case.CA_ONLINEADD_IND')
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="div_CA_AGAINSTADD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINSTADD') ? ' has-error' : '' }}">
                                <label for="CA_AGAINSTADD" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINSTADD')</label>
                                <div class="col-sm-8">
                                    {{ Form::textarea('CA_AGAINSTADD', $model->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows'=> '3', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINSTADD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINSTADD')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_POSTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_POSTCD') ? ' has-error' : '' }}">
                                <label for="CA_AGAINST_POSTCD" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_POSTCD')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_POSTCD', $model->CA_AGAINST_POSTCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINST_POSTCD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_POSTCD')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_STATECD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_STATECD') ? ' has-error' : '' }}">
                                <label class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_STATECD')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_STATECD', $model->CA_AGAINST_STATECD != ''? Ref::GetDescr('17', $model->CA_AGAINST_STATECD) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINST_STATECD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_STATECD')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_CA_AGAINST_DISTCD" style="display: {{ (old('CA_CMPLCAT') == 'BPGK 19'? (old('CA_CMPLCAT') == 'BPGK 19' && old('CA_ONLINEADD_IND') == 'on'? 'block':($model->CA_CMPLCAT == 'BPGK 19' && $model->CA_ONLINEADD_IND == '1'? 'block':'none')): old('CA_CMPLCAT') == '' && $model->CA_CMPLCAT == 'BPGK 19'? (old('CA_ONLINEADD_IND') == '' && $model->CA_ONLINEADD_IND == '1'? 'block':(old('CA_ONLINEADD_IND') == 'on'? 'block':'none')):'block' ) }} ;" class="form-group{{ $errors->has('CA_AGAINST_DISTCD') ? ' has-error' : '' }}">
                                <label class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_DISTCD')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_AGAINST_DISTCD', ($model->CA_AGAINST_DISTCD != '' ? Ref::GetDescr('18', $model->CA_AGAINST_DISTCD, Auth::user()->lang) : ''), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('CA_AGAINST_DISTCD'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_AGAINST_DISTCD')</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('CA_SUMMARY') ? ' has-error' : '' }}">
                                <label for="CA_SUMMARY" class="col-sm-2 control-label required">@lang('public-case.case.CA_SUMMARY')</label>
                                <div class="col-sm-10">
                                    {{ Form::textarea('CA_SUMMARY', $model->CA_SUMMARY, ['class' => 'form-control input-sm', 'rows'=> '5', 'readonly' => true]) }}
                                    @if ($errors->has('CA_SUMMARY'))
                                    <span class="help-block"><strong>@lang('public-case.validation.CA_SUMMARY')</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h4>@lang('public-case.attachment.btn')</h4>
                        <hr style="background-color: #ccc; height: 1px; border: 0;">
                        <table>
                            <tr>
                                @foreach($mPublicCaseDoc as $PublicCaseDoc)
                                <?php $ExtFile = substr($PublicCaseDoc->CC_IMG_NAME, -3); ?>
                                @if($ExtFile == 'pdf' || $ExtFile == 'PDF')
                                <td style="max-width: 10%; min-width: 10%; ">
                                    <div class="p-sm text-center">
                                        <a href="{{ Storage::disk('bahanpath')->url($PublicCaseDoc->CC_PATH.$PublicCaseDoc->CC_IMG) }}" target="_blank">
                                            <img src="{{ url('img/PDF.png') }}" class="img-lg img-thumbnail"/>
                                            <br />
                                            {{ $PublicCaseDoc->CC_IMG_NAME }}
                                        </a>
                                    </div>
                                </td>
                                @else
                                <td style="max-width: 10%; min-width: 10%; ">
                                    <div class="p-sm text-center">
                                        <a href="{{ Storage::disk('bahanpath')->url($PublicCaseDoc->CC_PATH.$PublicCaseDoc->CC_IMG) }}" target="_blank">
                                            <img src="{{ Storage::disk('bahanpath')->url($PublicCaseDoc->CC_PATH.$PublicCaseDoc->CC_IMG) }}" class="img-lg img-thumbnail"/>
                                            <br />
                                            {{ $PublicCaseDoc->CC_IMG_NAME }}
                                        </a>
                                    </div>
                                </td>
                                @endif
<!--                                <td style="max-width: 10%; min-width: 10%; ">
                                    <br />
                                    {{-- $PublicCaseDoc->CC_IMG_NAME --}}
                                </td>-->
                                @endforeach
                            </tr>
                        </table>
                    </div>
                    </div>

                            <div class="form-group col-sm-12" align="center">
                                <a class="btn btn-info btn-sm" href="{{ route('dashboard').'#complain' }}">@lang('button.back')</a>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="transaction" class="tab-pane">
             <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                <div class="col-lg-12">
            <div class="panel panel-success" style="border: 1px solid #115272;">
                <div class="panel-heading" style="background: #115272;">
                    <i class="fa fa-calendar-plus-o"></i> @lang('button.transaksi')
                </div>
                <div class="panel-body" style="color: black;">
                    <div class="table-responsive">
                        <table style="width: 100%" id="public-case-transaction-table" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>@lang('public-case.case.CD_INVSTS')</th>
                                    <!--<th>@lang('public-case.case.CD_ACTFROM')</th>-->
                                    <!--<th>@lang('public-case.case.CD_ACTTO')</th>-->
                                    <!--<th>@lang('public-case.case.CD_DESC')</th>-->

<!--<th>@lang('public-case.case.CD_CREDT')</th>-->
                                    <th>@lang('public-case.case.CD_DOCATTCHID_PUBLIC')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="form-group col-sm-12" align="center">
                        <a class="btn btn-info btn-sm" href="{{ route('dashboard').'#complain' }}">@lang('button.back')</a>
                    </div>
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
<!-- Modal Edit Attachment Start -->
<div class="modal fade" id="modal-edit-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalEditContent'></div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">

    $(document).ready(function () {
        var status = <?php echo $model->CA_INVSTS; ?>;
        if (status != '10') {
            $('.form-control').attr("disabled", true);
        }
    });

    function onlinecmplind() {
        if (document.getElementById('CA_ONLINECMPL_IND').checked)
        {
            $('#div_CA_ONLINECMPL_CASENO').show();
        } else
        {
            $('#div_CA_ONLINECMPL_CASENO').hide();
        }
    }
    ;

    function ModalAttachmentCreate(CASEID) {
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('public-case-doc.create','') }}" + "/" + CASEID);
        return false;
    }

    function ModalAttachmentEdit(ID) {
        ;
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('public-case-doc.edit','') }}" + "/" + ID);
        return false;
    }

    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href=' + hash + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    var CA_INVSTS = $('#CA_INVSTS').val();
    if (CA_INVSTS === 1) {
        $('#deletebutton').hide();
    }

    function onlineaddind() {
        if (document.getElementById('CA_ONLINEADD_IND').checked) {
            $('#div_CA_AGAINSTADD').show();
            $('#div_CA_AGAINST_POSTCD').show();
            $('#div_CA_AGAINST_STATECD').show();
            $('#div_CA_AGAINST_DISTCD').show();
        } else {
            $('#div_CA_AGAINSTADD').hide();
            $('#div_CA_AGAINST_POSTCD').hide();
            $('#div_CA_AGAINST_STATECD').hide();
            $('#div_CA_AGAINST_DISTCD').hide();
        }
    }
    ;

    $(function () {

        $('#CA_AGAINST_STATECD').on('change', function (e) {
            var CA_AGAINST_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('sas-case/getdistrictlist') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function (key, value) {
                        if (value == '0')
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="">' + key + '</option>');
                        else
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
        });

        $('#CA_ONLINECMPL_PROVIDER').on('change', function (e) {
            var CA_ONLINECMPL_PROVIDER = $(this).val();
            if (CA_ONLINECMPL_PROVIDER === '999')
                $("label[for='CA_ONLINECMPL_URL']").addClass("required");
            else
                $("label[for='CA_ONLINECMPL_URL']").removeClass("required");
        });

        $('#CA_CMPLCAT').on('change', function (e) {
            var CA_CMPLCAT = $(this).val();

            if (CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
                $("#CA_CMPLKEYWORD").show();
            } else {
                $("#CA_CMPLKEYWORD").hide();
            }

            if (CA_CMPLCAT === 'BPGK 19') {
                if (document.getElementById('CA_ONLINECMPL_IND').checked)
                    $('#div_CA_ONLINECMPL_CASENO').show();
                else
                    $('#div_CA_ONLINECMPL_CASENO').hide();

                $("#checkpernahadu").show();
                $("#checkinsertadd").show();
                $('#div_CA_ONLINECMPL_PROVIDER').show();
                $('#div_CA_ONLINECMPL_URL').show();
                $('#div_CA_ONLINECMPL_AMOUNT').show();
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
                $("label[for='CA_ONLINECMPL_URL']").removeClass("required");
//                $( "label[for='CA_AGAINST_PREMISE']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINSTADD']" ).removeClass( "required" );
//                $( "label[for='CA_AGAINST_POSTCD']" ).removeClass( "required" );
            } else {
                $("#checkpernahadu").hide();
                $("#checkinsertadd").hide();
                $('#div_CA_ONLINECMPL_CASENO').hide();
                $('#div_CA_ONLINECMPL_PROVIDER').hide();
                $('#div_CA_ONLINECMPL_URL').hide();
                $('#div_CA_ONLINECMPL_AMOUNT').hide();
                $('#div_CA_ONLINECMPL_ACCNO').hide();
                $('#div_CA_AGAINST_PREMISE').show();
                $('#div_CA_AGAINSTADD').show();
                $('#div_CA_AGAINST_POSTCD').show();
                $('#div_CA_AGAINST_STATECD').show();
                $('#div_CA_AGAINST_DISTCD').show();
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
                        if (value == '0')
                            $('select[name="CA_CMPLCD"]').append('<option value="">' + key + '</option>');
                        else
                            $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
        });

        $('#public-case-attachmnt-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bPaginate: false,
            bInfo: false,
            language: {
                zeroRecords: "@lang('datatable.infoEmpty')",
                infoEmpty: "@lang('datatable.infoEmpty')"
            },
            ajax: {
                url: "{{ url('public-case-doc/getDatatable',$model->CA_CASEID)}}"
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
                {data: 'CC_REMARKS', name: 'CC_REMARKS'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });

        $('#public-case-transaction-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bPaginate: false,
            bInfo: false,
            language: {
                zeroRecords: 'Tiada rekod ditemui',
                infoEmpty: 'Tiada rekod ditemui'
            },
            ajax: {
                url: "{{ url('public-case/getDatatableTransaction',$model->CA_CASEID)}}"
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CD_INVSTS', name: 'CD_INVSTS'},
//                {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
//                {data: 'CD_ACTTO', name: 'CD_ACTTO'},
//                {data: 'CD_DESC', name: 'CD_DESC'},
//                {data: 'CD_CREDT', name: 'CD_CREDT'},
                {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'},
            ]
        });
    });

</script>
@stop
