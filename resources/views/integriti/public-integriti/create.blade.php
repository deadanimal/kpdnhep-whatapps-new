@extends('layouts.main_public')
<?php
    use App\Branch;
    use App\Ref;
    use App\Integriti\IntegritiAdmin;
    use App\Integriti\IntegritiPublic;
?>
@section('content')
<style>
    textarea {
        resize: vertical;
    }
    select {
        width: 100%;
    }
    .select2-dropdown{
        z-index:3000 !important;
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
                            <!--{{-- Form::open(['url' => 'public-case', 'class' => 'form-horizontal', 'method' => 'POST']) --}}-->
                            {{ Form::open(['route' => 'public-integriti.store', 'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'formpublic']) }}
                            {{ csrf_field() }}
                            <!-- <div class="row"> -->
                                <!-- <div class="col-sm-12"> -->
                                    <!-- <h4>Peringatan</h4> -->
                                    <!-- <hr style="background-color: #ccc; height: 1px; border: 0;"> -->
                                    <!-- <b> -->
                                        <!-- "KPDNKK boleh untuk tidak melayan sebarang aduan yang menggunakan bahasa kesat, lucah, konfrantasi, penghinaan dan/atau tuduhan tanpa asas. KPDNKK juga tidak akan campur tangan dalam kes-kes aduan yang melibatkan keputusan mana-mana mahkamah/badan arbitrary/seumpamanya serta mana-mana pertikaian yang melibatkan masalah peribadi antara syarikat/individu yang tiada kaitan dengan peranan/fungsi agensi KPDNKK"  -->
                                    <!-- </b> -->
                                <!-- </div> -->
                            <!-- </div> -->
                            <!-- <br /> -->
                            <!-- <div class="row"> -->
                                <!-- <div class="col-sm-12"> -->
                                    <!-- <h4>{{ Auth::user()->lang == 'ms' ? 'Maklumat Aduan' : 'Complaint Information' }}</h4> -->
                                    <!-- <hr style="background-color: #ccc; height: 1px; border: 0;"> -->
                                    <!-- <div class="form-group {{-- $errors->has('IN_SECRETFLAG') ? ' has-error' : '' --}}"> -->
                                    <!-- {{-- Form::label('', '', ['class' => 'col-lg-3 control-label']) --}} -->
                                        <!-- <div class="col-lg-9"> -->
                                            <!-- <div class="checkbox checkbox-primary"> -->
                                                <!-- <input id="IN_SECRETFLAG" type="checkbox" name="IN_SECRETFLAG" {{ old('IN_SECRETFLAG') == 'on'? 'checked':'' }}> -->
                                                <!-- <label for="IN_SECRETFLAG"> -->
                                                    <!-- <b> -->
                                                        <!-- Saya ingin merahsiakan maklumat sulit (maklumat, identiti, alamat, pekerjaan dan yang berkaitan dengan pemberi maklumat) -->
                                                    <!-- </b> -->
                                                <!-- </label> -->
                                            <!-- </div> -->
                                            <!-- {{-- @if ($errors->has('IN_SECRETFLAG')) --}} -->
                                                <!-- <span class="help-block"> -->
                                                    <!-- <strong>{{-- $errors->first('IN_SECRETFLAG') --}}</strong> -->
                                                <!-- </span> -->
                                            <!-- {{-- @endif --}} -->
                                        <!-- </div> -->
                                    <!-- </div> -->
                                <!-- </div> -->
                            <!-- </div> -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group{{ $errors->has('IN_AGAINSTNM') ? ' has-error' : '' }}">
                                        <!--<label id="" for="" class="col-lg-3 control-label">-->
                                            <!--Nama Pegawai Yang Diadu-->
                                        <!--</label>-->
                                        {{ Form::label('IN_AGAINSTNM', Auth::user()->lang == 'ms' ? 'Nama Pegawai Yang Diadu (PYDA)' : 'Name of Claimed Officer', ['class' => 'col-lg-3 control-label required']) }}
                                        <div class="col-lg-9">
                                            {{ Form::text('IN_AGAINSTNM','', ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('IN_AGAINSTNM'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- @lang('public-case.validation.CA_SUMMARY') -->
                                                        <!-- {{ $errors->first('IN_AGAINSTNM') }} -->
                                                    </strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group{{ $errors->has('IN_REFTYPE') ? ' has-error' : '' }}">
                                        <!-- {{-- Form::label('', trans('public-case.case.reference'), ['class' => 'col-lg-3 control-label']) --}} -->
                                        {{ Form::label('IN_REFTYPE', Auth::user()->lang == 'ms' ? 'Jenis Rujukan Berkaitan' : 'Type Of Related Reference', ['class' => 'col-lg-3 control-label']) }}
                                        <div class="col-lg-9">
                                            {{ 
                                                Form::select(
                                                    'IN_REFTYPE', 
                                                    [
                                                        'OTHER' => Auth::user()->lang == 'ms' ? 'Salah Laku (Umum)' : 'Others', 
                                                        'BGK' => Auth::user()->lang == 'ms' ? 'Salah Laku (Aduan Kepenggunaan)' : 'Misconduct (Consumer Complaint)', 
                                                        'TTPM' => Auth::user()->lang == 'ms' ? 'Salah Laku (TTPM)' : 'Misconduct (TTPM)', 
                                                    ], 
                                                    null, 
                                                    [
                                                        'class' => 'form-control input-sm select2',
                                                        'placeholder' => Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --'
                                                    ]
                                                )
                                            }}
                                            @if ($errors->has('IN_REFTYPE'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- {{ $errors->first('IN_REFTYPE') }} -->
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
                                    style="display: {{ old('IN_REFTYPE') == 'BGK' ? 'block' : 'none' }};">
                                        {{ Form::label('IN_BGK_CASEID', Auth::user()->lang == 'ms' ? 'Aduan Kepenggunaan' : 'Consumer Complaint', ['class' => 'col-lg-3 control-label required']) }}
                                        <div class="col-lg-9">
                                            {{ 
                                                Form::select('IN_BGK_CASEID', IntegritiPublic::getpublicusercomplaintlist(), '', 
                                                    [
                                                        'class' => 'form-control input-sm select2', 
                                                        'placeholder' => Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --'
                                                    ]
                                                ) 
                                            }}
                                            @if ($errors->has('IN_BGK_CASEID'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- {{ $errors->first('IN_BGK_CASEID') }} -->
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
                                    style="display: {{ old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none' }};">
                                        {{ Form::label('IN_TTPMNO', Auth::user()->lang == 'ms' ? 'No. TTPM' : 'TTPM No.', ['class' => 'col-lg-3 control-label required']) }}
                                        <div class="col-lg-9">
                                            {{ Form::text('IN_TTPMNO','', ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('IN_TTPMNO'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- @lang('public-case.validation.CA_SUMMARY') -->
                                                        <!-- {{ $errors->first('IN_TTPMNO') }} -->
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
                                        style="display: {{ old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none' }};">
                                        {{ Form::label('IN_TTPMFORM', Auth::user()->lang == 'ms' ? 'Jenis Borang TTPM' : 'TTPM Form Type', ['class' => 'col-lg-3 control-label required']) }}
                                        <div class="col-lg-9">
                                            <!-- {{-- Form::text('IN_TTPMFORM','', ['class' => 'form-control input-sm']) --}} -->
                                            {{ 
                                                Form::select(
                                                    'IN_TTPMFORM', 
                                                    [
                                                        '8' => Auth::user()->lang == 'ms' 
                                                            ? 'Borang 8 - Award bagi pihak yang menuntut jika penentang tidak hadir' 
                                                            : 'Form 8 - Award for the claimant if the opponent is absent', 
                                                        '9' => Auth::user()->lang == 'ms' 
                                                            ? 'Borang 9 - Award dengan persetujuan' : 'Form 9 - Award with approval', 
                                                        '10' => Auth::user()->lang == 'ms' 
                                                            ? 'Borang 10 - Award selepas pendengaran' : 'Form 10 - Award after hearing', 
                                                    ], 
                                                    null, 
                                                    [
                                                        'class' => 'form-control input-sm select2',
                                                        'placeholder' => Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --'
                                                    ]
                                                )
                                            }}
                                            @if ($errors->has('IN_TTPMFORM'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- @lang('public-case.validation.CA_SUMMARY') -->
                                                        <!-- {{ $errors->first('IN_TTPMNO') }} -->
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
                                        style="display: {{ old('IN_REFTYPE') == 'OTHER' ? 'block' : 'none' }};">
                                        {{ 
                                            Form::label('IN_REFOTHER', 
                                            Auth::user()->lang == 'ms' ? 'Rujukan Lain' : 'Other References', 
                                            ['class' => 'col-lg-3 control-label']) 
                                        }}
                                        <div class="col-lg-9">
                                            {{ Form::text('IN_REFOTHER','', ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('IN_REFOTHER'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- @lang('public-case.validation.CA_SUMMARY') -->
                                                        <!-- {{ $errors->first('IN_REFOTHER') }} -->
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
                                        {{ Form::label('IN_AGAINSTLOCATION', Auth::user()->lang == 'ms' ? 'Lokasi PYDA' : 'Officer Location', ['class' => 'col-lg-3 control-label required']) }}
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
                                                    {{ Auth::user()->lang == 'ms' ? 'Agensi KPDNHEP' : 'KPDNHEP Agencies' }}
                                                </label>
                                            </div>
                                            @if ($errors->has('IN_AGAINSTLOCATION'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- @lang('public-case.validation.CA_SUMMARY') -->
                                                        <!-- {{ $errors->first('IN_AGAINSTLOCATION') }} -->
                                                    </strong>
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
                                            Auth::user()->lang == 'ms' ? 'Negeri' : 'State', 
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
                                                        'placeholder' => Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --'
                                                    ]
                                                ) 
                                            }}
                                            @if ($errors->has('IN_AGAINST_BRSTATECD'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- {{ $errors->first('IN_AGAINST_BRSTATECD') }} -->
                                                    </strong>
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
<!--                                        <label id="" for="" class="col-lg-3 control-label">
                                            Bahagian / Cawangan
                                        </label>-->
                                        {{ Form::label('IN_BRNCD', trans('public-case.case.IN_BRNCD'), ['class' => 'col-lg-3 control-label required']) }}
                                        <div class="col-lg-9">
                                            <!--{{-- Form::text('','', ['class' => 'form-control input-sm']) --}}-->
                                            {{ 
                                                Form::select('IN_BRNCD', [], null, [
                                                    'class' => 'form-control input-sm select2', 
                                                    'id' => 'IN_BRNCD', 
                                                    'placeholder' => Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --'
                                                ]) 
                                            }}
                                            @if ($errors->has('IN_BRNCD'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- {{ $errors->first('IN_BRNCD') }} -->
                                                    </strong>
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
                                            Auth::user()->lang == 'ms' ? 'Agensi KPDNHEP' : 'KPDNHEP Agencies', 
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
                                                    <strong>
                                                        <!-- {{ $errors->first('IN_AGENCYCD') }} -->
                                                    </strong>
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
                                        {{ Form::label('IN_SUMMARY_TITLE', Auth::user()->lang == 'ms' ? 'Tajuk Aduan' : 'Complaint Title', ['class' => 'col-lg-3 control-label required']) }}
                                        <div class="col-lg-9">
                                            {{ Form::text('IN_SUMMARY_TITLE','', ['class' => 'form-control input-sm']) }}
                                            @if ($errors->has('IN_SUMMARY_TITLE'))
                                                <span class="help-block">
                                                    <strong>
                                                        <!-- @lang('public-case.validation.CA_SUMMARY') -->
                                                        {{ $errors->first('IN_SUMMARY_TITLE') }}
                                                    </strong>
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
                                                    <strong>
                                                        @lang('public-case.validation.CA_SUMMARY')
                                                    </strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>
                                        {{ Auth::user()->lang == 'ms' ? 'Pengakuan' : 'Acknowledgement' }}
                                    </h4>
                                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                                    <p>
                                    {{ 
                                        Auth::user()->lang == 'ms' 
                                        ? 'Saya mengaku bahawa saya telah membaca dan memahami takrif aduan dan prosedur pengurusan aduan oleh pihak kerajaan Malaysia. Segala maklumat diri dan maklumat perkara yang dikemukakan oleh saya adalah benar dan saya bertanggungjawab ke atasnya.' 
                                        : 'I declare that I have read and understood the definition of complaints and complaint management procedures by the Malaysian government. All personal information and information that is submitted by me is true and I am responsible for it.' 
                                    }}
                                    </p>
                                    <p>
                                        {{ 
                                            Auth::user()->lang == 'ms' 
                                            ? 'Kerajaan Malaysia tidak bertanggungjawab terhadap sebarang kehilangan atau kerosakan yang dialami kerana menggunakan perkhidmatan ini di dalam sistem ini.' 
                                            : 'The Government of Malaysia shall not be liable for any loss or damage caused by the use of this service in this system.' 
                                        }}
                                    </p>
                                    <p>
                                        {{ 
                                            Auth::user()->lang == 'ms' 
                                            ? 'Semua maklumat akan dirahsiakan dan hanya digunakan oleh Kerajaan Malaysia.'
                                            : 'All information will be kept confidential and is only used by the Government of Malaysia.'
                                        }}
                                    </p>
                                    <div class="form-group {{ $errors->has('agreeTnc') ? ' has-error' : '' }}">
                                    <!-- {{-- Form::label('', '', ['class' => 'col-lg-3 control-label']) --}} -->
                                        <div class="col-lg-12">
                                            <div class="checkbox checkbox-primary">
                                                <input id="agreeTnc" type="checkbox" name="agreeTnc" {{ old('agreeTnc') == 'on'? 'checked':'' }}>
                                                <label for="agreeTnc">
                                                    {{ 
                                                        Auth::user()->lang == 'ms' 
                                                        ? 'Saya telah membaca dan setuju dengan Terma dan Syarat yang telah ditetapkan !'
                                                        : 'I have read and agree with Terms and Conditions !'
                                                    }}
                                                </label>
                                                <div id="notis">
                                                    <b>
                                                        <font color="red">
                                                        &nbsp;
                                                        {{ 
                                                            Auth::user()->lang == 'ms' 
                                                            ? '(Sila sahkan pengakuan anda sebelum meneruskan membuat aduan)'
                                                            : 'Please confirm your acknowledgement before continuing to lodge a complaint'
                                                        }}
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
//        $("select").select2();
        $(".select2").select2();
        $('#formpublic').on('submit', function (e) {
            if (!$('#agreeTnc').is(':checked')) {
                e.preventDefault();
                // alert('Sila sahkan pengakuan anda sebelum meneruskan membuat aduan');
                // var currentbahasa = '<?php // echo Auth::user()->lang; ?>';
                // if (currentbahasa == 'en') {
                    // alert('Please confirm your acknowledgement before continuing to lodge a complaint');
                // } else if (currentbahasa == 'ms') {
                    // alert('Sila sahkan pengakuan anda sebelum meneruskan membuat aduan');
                // }
            }
        });
        // $('#IN_AGAINST_BRSTATECD').on('change', function (e) {
        //     var state_cd = $(this).val();
        //     $.ajax({
        //         type: 'GET',
        //         url: "{{ url('user/getbrnlist') }}" + "/" + state_cd,
        //         dataType: "json",
        //         success: function (data) {
        //             $('select[name="IN_BRNCD"]').empty();
        //             $.each(data, function (key, value) {
        //                 $('select[name="IN_BRNCD"]').append('<option value="' + key + '">' + value + '</option>');
        //             });
        //         }
        //     });
        // });

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
                        $('select[name="IN_BRNCD"]').append('<option>{{ Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --' }}</option>');
                        $.each(data, function (key, value) {
                            $('select[name="IN_BRNCD"]').append('<option value="' + key + '">' + value + '</option>');
                        })
                    },
                    complete: function (data) {
                        // $('#IN_BRNCD').trigger('change');
                        $('select[name="IN_BRNCD"]').trigger('change');
                    }
                });
            } else {
                $('select[name="IN_BRNCD"]').empty();
                $('select[name="IN_BRNCD"]').append('<option>{{ Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --' }}</option>');
                $('select[name="IN_BRNCD"]').trigger('change');
            }
        })
    });
    $(function () {
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
        $('#IN_REFTYPE').on('change', function () {
            var IN_REFTYPE = $(this).val();
            if(IN_REFTYPE === 'BGK') {
                $("#div_bgk").show();
                $("#div_other").hide();
                $("#div_ttpm").hide();
                $("#div_ttpmform").hide();
                $('#IN_AGAINSTLOCATION1').trigger('click');
                // $('#IN_AGAINSTLOCATION2').attr('disabled', false);
                $('#IN_AGAINSTLOCATION2').attr('disabled', true);
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
    });
    function againstlocation(value) {
        if (value === 'BRN') {
            $('#div_IN_AGAINST_BRSTATECD').show()
            $('#div_IN_BRNCD').show()
            $('#div_IN_AGENCYCD').hide()
        } else if (value === 'AGN') {
            $('#div_IN_AGAINST_BRSTATECD').hide()
            $('#div_IN_BRNCD').hide()
            $('#div_IN_AGENCYCD').show()
        } else {
            $('#div_IN_AGAINST_BRSTATECD').hide()
            $('#div_IN_BRNCD').hide()
            $('#div_IN_AGENCYCD').hide()
        }
    }    
</script>
@stop
