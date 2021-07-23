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
    .form-control[readonly] {
        background-color: #ffffff;
    }
</style>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="">
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
        <li class="active">
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
        <div id="caseinfo" class="tab-pane active">
            <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                <div class="col-lg-12">
                    <div class="panel panel-success" style="border: 1px solid #115272;">
                        <div class="panel-heading" style="background: #115272;">
                            <i class="fa fa-tags"></i> @lang('button.check1')
                        </div>
                        <div class="panel-body" style="color: black;">
                            {{ Form::open(['class' => 'form-horizontal']) }}
                            {{ csrf_field() }}
                            <!--{{-- method_field('PATCH') --}}-->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
<!--                                            <label id="" for="" class="col-lg-3 control-label">
                                            Nama Pegawai Yang Diadu
                                        </label>-->
                                        {{ Form::label('', Auth::user()->lang == 'ms' ? 'Nama Pegawai Yang Diadu (PYDA)' : 'Name of Claimed Officer', ['class' => 'col-lg-3 control-label']) }}
                                        <div class="col-lg-9">
                                            <!-- {{-- Form::text('', $model->IN_AGAINSTNM, ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
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
                                        <!-- {{-- Form::label('', trans('public-case.case.reference'), ['class' => 'col-lg-3 control-label']) --}} -->
                                        {{ Form::label('', Auth::user()->lang == 'ms' ? 'Jenis Rujukan Berkaitan' : 'Type Of Related Reference', ['class' => 'col-lg-3 control-label']) }}
                                        <div class="col-lg-9">
                                            <!-- {{-- 
                                                Form::select(
                                                    'IN_REFTYPE', 
                                                    [
                                                        'BGK' => Auth::user()->lang == 'ms' ? 'Aduan Kepenggunaan' : 'Consumer Complaint', 
                                                        'TTPM' => Auth::user()->lang == 'ms' ? 'No. TTPM' : 'TTPM No.', 
                                                        'OTHER' => Auth::user()->lang == 'ms' ? 'Lain-lain' : 'Others', 
                                                    ], 
                                                    old('IN_REFTYPE', $model->IN_REFTYPE), 
                                                    [
                                                        'class' => 'form-control input-sm select2',
                                                        'placeholder' => Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --'
                                                    ]
                                                )
                                            --}} -->
                                            <p class="form-control-static">
                                                <!-- {{-- $model->IN_REFTYPE --}} -->
                                                <!-- <br> -->
                                                @if($model->IN_REFTYPE == 'BGK')
                                                    {{ Auth::user()->lang == 'ms' ? 'Aduan Kepenggunaan' : 'Consumer Complaint' }}
                                                @elseif($model->IN_REFTYPE == 'TTPM')
                                                    {{ Auth::user()->lang == 'ms' ? 'No. TTPM' : 'TTPM No.' }}
                                                @elseif($model->IN_REFTYPE == 'OTHER')
                                                    {{ Auth::user()->lang == 'ms' ? 'Lain-lain' : 'Others' }}
                                                @endif
                                            </p>
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
                                    style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'BGK' ? 'block' : 'none') : ($model->IN_REFTYPE == 'BGK' ? 'block' : 'none') }};">
                                        {{ Form::label('IN_BGK_CASEID', Auth::user()->lang == 'ms' ? 'Aduan Kepenggunaan' : 'Consumer Complaint', ['class' => 'col-lg-3 control-label']) }}
                                        <div class="col-lg-9">
                                            <!-- {{-- 
                                                Form::select('IN_BGK_CASEID', IntegritiPublic::getpublicusercomplaintlist(), 
                                                    old('IN_BGK_CASEID', $model->IN_BGK_CASEID), 
                                                    [
                                                        'class' => 'form-control input-sm select2', 
                                                        'placeholder' => Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --'
                                                    ]
                                                ) 
                                            --}} -->
                                            <p class="form-control-static">
                                                {{ $model->IN_BGK_CASEID }}
                                            </p>
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
                                    style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'TTPM' ? 'block' : 'none') : ($model->IN_REFTYPE == 'TTPM' ? 'block' : 'none') }};">
                                        {{ Form::label('IN_TTPMNO', Auth::user()->lang == 'ms' ? 'No. TTPM' : 'TTPM No.', ['class' => 'col-lg-3 control-label']) }}
                                        <div class="col-lg-9">
                                            <!-- {{-- 
                                                Form::text('IN_TTPMNO',
                                                old('IN_TTPMNO', $model->IN_TTPMNO), 
                                                ['class' => 'form-control input-sm']) 
                                            --}} -->
                                            <p class="form-control-static">
                                                {{ $model->IN_TTPMNO }}
                                            </p>
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
                                    <div id="div_other" 
                                        class="form-group {{ $errors->has('IN_REFOTHER') ? ' has-error' : '' }}" 
                                        style="display: {{ old('IN_REFTYPE') ? (old('IN_REFTYPE') == 'OTHER' ? 'block' : 'none') : ($model->IN_REFTYPE == 'OTHER' ? 'block' : 'none') }};">
                                        {{ 
                                            Form::label('IN_REFOTHER', 
                                            Auth::user()->lang == 'ms' ? 'Lain-lain' : 'Others', 
                                            ['class' => 'col-lg-3 control-label']) 
                                        }}
                                        <div class="col-lg-9">
                                            <!-- {{-- 
                                                Form::text('IN_REFOTHER',
                                                old('IN_REFOTHER', $model->IN_REFOTHER), 
                                                ['class' => 'form-control input-sm']) 
                                            --}} -->
                                            <p class="form-control-static">
                                                {{ $model->IN_REFOTHER }}
                                            </p>
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
                                        {{ Form::label('IN_AGAINSTLOCATION', Auth::user()->lang == 'ms' ? 'Lokasi PYDA' : 'Officer Location', ['class' => 'col-lg-3 control-label']) }}
                                        <div class="col-lg-9">
                                            <!-- {{-- Form::text('IN_SUMMARY_TITLE','', ['class' => 'form-control input-sm']) --}} -->
                                            <!-- <div class="radio radio-primary radio-inline">
                                                <input type="radio" id="IN_AGAINSTLOCATION1" value="BRN" name="IN_AGAINSTLOCATION" 
                                                onclick="againstlocation(this.value)" 
                                                {{ 
                                                    old('IN_AGAINSTLOCATION') 
                                                    ? (old('IN_AGAINSTLOCATION') == 'BRN'? 'checked':'') 
                                                    : ($model->IN_AGAINSTLOCATION == 'BRN' ? 'checked' : '')
                                                }}>
                                                <label for="IN_AGAINSTLOCATION1">
                                                    KPDNHEP
                                                </label>
                                            </div> -->
                                            <!-- <div class="radio radio-info radio-inline">
                                                <input type="radio" id="IN_AGAINSTLOCATION2" value="AGN" name="IN_AGAINSTLOCATION" 
                                                onclick="againstlocation(this.value)" 
                                                {{ 
                                                    old('IN_AGAINSTLOCATION') 
                                                    ? old('IN_AGAINSTLOCATION') == 'AGN'? 'checked':'' 
                                                    : ($model->IN_AGAINSTLOCATION == 'AGN' ? 'checked' : '')
                                                }}>
                                                <label for="IN_AGAINSTLOCATION2">
                                                    {{ Auth::user()->lang == 'ms' ? 'Agensi KPDNHEP' : 'KPDNHEP Agencies' }}
                                                </label>
                                            </div> -->
                                            <p class="form-control-static">
                                                @if($model->IN_AGAINSTLOCATION == 'BRN')
                                                    {{ Auth::user()->lang == 'ms' ? 'Bahagian / Cawangan KPDNHEP' : 'KPDNHEP Branches' }}
                                                @elseif($model->IN_AGAINSTLOCATION == 'AGN')
                                                    {{ Auth::user()->lang == 'ms' ? 'Agensi KPDNHEP' : 'KPDNHEP Agencies' }}
                                                @endif
                                            </p>
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
                                        style="display: {{ 
                                            old('IN_AGAINSTLOCATION') 
                                            ? (old('IN_AGAINSTLOCATION') == 'BRN'? 'block' : 'none') 
                                            : ($model->IN_AGAINSTLOCATION == 'BRN' ? 'block' : 'none')
                                        }};">
                                            <!-- old('IN_AGAINSTLOCATION') == 'BRN' ? 'block' : 'none'  -->
<!--                                        <label id="" for="" class="col-lg-3 control-label">
                                            Bahagian / Cawangan
                                        </label>-->
                                        <!-- {{-- Form::label('', trans('public-case.case.IN_BRNCD'), ['class' => 'col-lg-3 control-label required']) --}} -->
                                        {{ Form::label('IN_AGAINST_BRSTATECD', 
                                            Auth::user()->lang == 'ms' ? 'Negeri' : 'State', 
                                            ['class' => 'col-lg-3 control-label']) 
                                        }}
                                        <div class="col-lg-9">
                                            <!--{{-- Form::text('','', ['class' => 'form-control input-sm']) --}}-->
                                            <!-- {{-- 
                                                Form::select('', Branch::GetListBranch(), null, [
                                                    'class' => 'form-control input-sm select2', 
                                                ]) 
                                            --}} -->
                                            <!-- {{-- 
                                                Form::select(
                                                    'IN_AGAINST_BRSTATECD', 
                                                    Ref::GetList('17', false, Auth::user()->lang), 
                                                    old('IN_AGAINST_BRSTATECD', $model->IN_AGAINST_BRSTATECD), 
                                                    [
                                                        'class' => 'form-control input-sm select2',
                                                        'placeholder' => Auth::user()->lang == 'ms' ? '-- SILA PILIH --' : '-- PLEASE SELECT --'
                                                    ]
                                                ) 
                                            --}} -->
                                            <p class="form-control-static">
                                                {{ $model->againstbrstatecd ? $model->againstbrstatecd->descr : $model->IN_AGAINST_BRSTATECD }}
                                            </p>
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
                                    <!-- <div class="form-group"> -->
                                    <div id="div_IN_BRNCD" 
                                        class="form-group {{ $errors->has('IN_BRNCD') ? ' has-error' : '' }}" 
                                        style="display: {{ 
                                            old('IN_AGAINSTLOCATION') 
                                            ? (old('IN_AGAINSTLOCATION') == 'BRN'? 'block' : 'none') 
                                            : ($model->IN_AGAINSTLOCATION == 'BRN' ? 'block' : 'none')
                                        }};">
                                        <!--<label id="" for="" class="col-lg-3 control-label">-->
                                            <!--Bahagian / Cawangan-->
                                        <!--</label>-->
                                        {{ Form::label('', 'Bahagian / Cawangan', ['class' => 'col-lg-3 control-label']) }}
                                        <div class="col-lg-9">
                                            <!--{{-- Form::text('', $model->IN_BRNCD, ['class' => 'form-control input-sm', 'readonly' => true]) --}}-->
                                            <!-- {{-- Form::text('', 
                                                !empty($model->IN_BRNCD) ? 
                                                    Branch::GetBranchName($model->IN_BRNCD) : '',
                                                ['class' => 'form-control input-sm', 'readonly' => true]) 
                                            --}} -->
                                            <p class="form-control-static">
                                                {{ $model->BrnCd ? $model->BrnCd->BR_BRNNM : $model->IN_BRNCD }}
                                            </p>
                                            <!--$model->IN_BRNCD,--> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- <div class="form-group{{ $errors->has('') ? ' has-error' : '' }}"> -->
                                    <div id="div_IN_AGENCYCD" 
                                        class="form-group {{ $errors->has('IN_AGENCYCD') ? ' has-error' : '' }}" 
                                        style="display: {{ 
                                            old('IN_AGAINSTLOCATION') 
                                            ? (old('IN_AGAINSTLOCATION') == 'AGN'? 'block' : 'none') 
                                            : ($model->IN_AGAINSTLOCATION == 'AGN' ? 'block' : 'none')
                                        }};">
                                            <!-- old('IN_AGAINSTLOCATION') == 'AGN' ? 'block' : 'none'  -->
                                        {{ 
                                            Form::label('IN_AGENCYCD', 
                                            Auth::user()->lang == 'ms' ? 'Agensi KPDNHEP' : 'KPDNHEP Agencies', 
                                            ['class' => 'col-lg-3 control-label']) 
                                        }}
                                        <div class="col-lg-9">
                                            <!-- {{-- 
                                                Form::select(
                                                    'IN_AGENCYCD', 
                                                    IntegritiAdmin::GetMagncdList(), 
                                                    old('IN_AGENCYCD', $model->IN_AGENCYCD), 
                                                    [
                                                        'class' => 'form-control input-sm select2'
                                                    ]
                                                ) 
                                            --}} -->
                                            <p class="form-control-static">
                                                {{ $model->agencycd ? $model->agencycd->MI_DESC : $model->IN_AGENCYCD }}
                                            </p>
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
                                    <div class="form-group">
                                        {{ Form::label('', 'Tajuk Aduan', ['class' => 'col-lg-3 control-label']) }}
                                        <div class="col-lg-9">
                                            <!-- {{-- Form::text('', $model->IN_SUMMARY_TITLE, ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                                            <p class="form-control-static">
                                                {{ $model->IN_SUMMARY_TITLE }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="CA_SUMMARY" class="col-lg-3 control-label">@lang('public-case.case.CA_SUMMARY')</label>
                                        <!--{{-- Form::label('', 'Tajuk Aduan', ['class' => 'col-lg-3 control-label']) --}}-->
                                        <div class="col-lg-9">
                                            <!-- {{-- Form::textarea('CA_SUMMARY', $model->IN_SUMMARY, ['class' => 'form-control input-sm', 'rows'=> '5', 'readonly' => true]) --}} -->
                                            <p class="form-control-static">
                                                {!! nl2br(htmlspecialchars($model->IN_SUMMARY)) !!}
                                            </p>
                                            @if ($errors->has('CA_SUMMARY'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_SUMMARY')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <h4>
                                        <!-- @lang('public-case.attachment.btn') -->
                                        {{ Auth::user()->lang == 'ms' ? 'Lampiran Aduan Integriti' : 'Integrity Complant Attachment' }}
                                    </h4>
                                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                                    <div class="table-responsive">
                                        <table>
                                            <tr>
                                                @foreach($mIntegritiPublicDoc as $IntegritiPublicDoc)
                                                <?php // $ExtFile = substr($IntegritiPublicDoc->CC_IMG_NAME, -3); ?>
                                                <?php $ExtFile = substr($IntegritiPublicDoc->IC_DOCFULLNAME, -3); ?>
                                                @if($ExtFile == 'pdf' || $ExtFile == 'PDF')
                                                <td style="max-width: 10%; min-width: 10%; ">
                                                    <div class="p-sm text-center">
                                                        <!-- <a href="{{-- Storage::disk('bahanpath')->url($IntegritiPublicDoc->CC_PATH.$IntegritiPublicDoc->CC_IMG) --}}" target="_blank"> -->
                                                        <a href="{{ Storage::disk('integritibahanpath')->url($IntegritiPublicDoc->IC_PATH.$IntegritiPublicDoc->IC_DOCNAME) }}" target="_blank">
                                                            <img src="{{ url('img/PDF.png') }}" class="img-lg img-thumbnail"/>
                                                            <br />
                                                            <!-- {{-- $IntegritiPublicDoc->CC_IMG_NAME --}} -->
                                                            {{ $IntegritiPublicDoc->IC_DOCFULLNAME }}
                                                        </a>
                                                    </div>
                                                </td>
                                                @else
                                                <td style="max-width: 10%; min-width: 10%; ">
                                                    <div class="p-sm text-center">
                                                        <!-- <a href="{{-- Storage::disk('bahanpath')->url($IntegritiPublicDoc->CC_PATH.$IntegritiPublicDoc->CC_IMG) --}}" target="_blank"> -->
                                                        <a href="{{ Storage::disk('integritibahanpath')->url($IntegritiPublicDoc->IC_PATH.$IntegritiPublicDoc->IC_DOCNAME) }}" target="_blank">
                                                            <!-- <img src="{{-- Storage::disk('bahanpath')->url($IntegritiPublicDoc->CC_PATH.$IntegritiPublicDoc->CC_IMG) --}}" class="img-lg img-thumbnail"/> -->
                                                            <img src="{{ Storage::disk('integritibahanpath')->url($IntegritiPublicDoc->IC_PATH.$IntegritiPublicDoc->IC_DOCNAME) }}" class="img-lg img-thumbnail"/>
                                                            <br />
                                                            <!-- {{-- $IntegritiPublicDoc->CC_IMG_NAME --}} -->
                                                            {{ $IntegritiPublicDoc->IC_DOCFULLNAME }}
                                                        </a>
                                                    </div>
                                                </td>
                                                @endif
                                                <!--<td style="max-width: 10%; min-width: 10%; ">-->
                                                    <!--<br />-->
                                                    <!--{{-- $IntegritiPublicDoc->CC_IMG_NAME --}}-->
                                                <!--</td>-->
                                                @endforeach
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12" align="center">
                                    <a class="btn btn-success btn-sm" href="{{ route('public-integriti.attachment',['id'=>$model->id]) }}">
                                        <i class="fa fa-chevron-left"></i> {{ trans('button.previous') }}
                                    </a>
                                    <!--<a class="btn btn-warning btn-sm" href="{{-- route('dashboard') --}}">@lang('button.back')</a>-->
                                    @if($model->IN_INVSTS == '07')
                                    <a id="SubmitBtn" data-toggle="modal" data-target="#confirm-submit-maklumatxlengkap" class="btn btn-success btn-sm">
                                        {{ trans('button.send') }} <i class="fa fa-chevron-right"></i>
                                    </a>
                                    @else
                                    <a id="SubmitBtn" data-toggle="modal" data-target="#confirm-submit" class="btn btn-success btn-sm">
                                        {{ trans('button.send') }} <i class="fa fa-chevron-right"></i>
                                    </a>
                                    @endif
                                    <!--<input type="button" name="btn" value="Submit" id="submitBtn1" data-toggle="modal" data-target="#confirm-submit" class="btn btn-default" />-->
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
<!-- Modal Confirmation Start -->
<div class="modal inmodal" id="confirm-submit" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content animated bounceIn" id='modalEditContent' style="border-radius: 30px;">
            <div class="modal-header" style="border-radius: 25px 25px 0px 0px; background: #115272; background: -moz-linear-gradient(#115272, white); color: black; text-align: center;">
                <strong>{{ trans('public-case.confirmation.service') }}</strong>
            </div>
            {!! Form::open(['route' => ['public-integriti.submit',$model->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
            {{ csrf_field() }}{{ method_field('POST') }}
            <div class="modal-body" style="background: white; ">
                    <div class="row">
                        <div class="col-sm-4 text-center">
                            <label for="rating3"><img for="rating3" style="width: 50% !important;" src="{{ url('img/perform5.png') }}" /></label>
                            <div class="radio radio-primary">
                                <input id="rating3" type="radio" value="3" name="rating" checked><label for="rating3"></label>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <label for="rating2"><img alt="image" style="width: 50% !important;" src="{{ url('img/perform4.png') }}" /></label>
                            <div class="radio radio-primary">
                                <input id="rating2" type="radio" value="2" name="rating"><label for="rating2"></label>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <label for="rating1"><img alt="image" style="width: 50% !important;" src="{{ url('img/perform3.png') }}" /></label>
                            <div class="radio radio-primary">
                                <input id="rating1" type="radio" value="1" name="rating"><label for="rating1"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-radius:0px 0px 25px 25px; background: #115272; background: -moz-linear-gradient(bottom, #115272, white); color: black;">
                    <p class="text-center">
                        <strong>{{ trans('public-case.confirmation.submit') }}</strong>
                    </p>
                    <!--<strong class="center">{{-- trans('public-case.confirmation.submit') --}}</strong>-->
                    <p class="text-center">
                        <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">@lang('public-case.confirmation.btncancel')</button>
                        <button type="submit" class="btn btn-sm btn-success" >@lang('public-case.confirmation.btnsubmit')</button>
                    </p>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="modal inmodal" id="confirm-submit-maklumatxlengkap" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content animated bounceIn" id='modalEditContent' style="border-radius: 30px;">
            <div class="modal-header" style="border-radius: 25px 25px 0px 0px; background: #115272; background: -moz-linear-gradient(#115272, white); color: black; text-align: center;">
                <!--<strong>{{-- trans('public-case.confirmation.service') --}}</strong>-->
            </div>
            {!! Form::open(['route' => ['public-integriti.submit',$model->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
            {{ csrf_field() }}{{ method_field('POST') }}
            <div class="modal-body" style="background: white; ">
                    <div class="row">
                        <p class="text-center">
                            <strong>{{ trans('public-case.confirmation.submit') }}</strong>
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="border-radius:0px 0px 25px 25px; background: #115272; background: -moz-linear-gradient(bottom, #115272, white); color: black;">
                    <!--<strong class="center">{{-- trans('public-case.confirmation.submit') --}}</strong>-->
                    <p class="text-center">
                        <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">@lang('public-case.confirmation.btncancel')</button>
                        <button type="submit" name="submitmaklumatxlengkap" value="1" class="btn btn-sm btn-success" >@lang('public-case.confirmation.btnsubmit')</button>
                    </p>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@section('javascript')
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
    
    function onlinecmplind() {
        if (document.getElementById('CA_ONLINECMPL_IND').checked)
        {
            $('#div_CA_ONLINECMPL_CASENO').show();
        }
        else
        {
            $('#div_CA_ONLINECMPL_CASENO').hide();
        }
    };
    
    function ModalAttachmentCreate(CASEID) {
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('public-case-doc.create','') }}" + "/" + CASEID);
        return false;
    }
    
    function ModalAttachmentEdit(ID) {;
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('public-case-doc.edit','') }}" + "/" + ID);
        return false;
    }
    
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href='+hash+']').tab('show');
    } 
    
    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
    
    var CA_INVSTS = $('#CA_INVSTS').val();
    if(CA_INVSTS === 1){
        $('#deletebutton').hide();
    }
    
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
        
        $('#CA_AGAINST_STATECD').on('change', function (e) {
            var CA_AGAINST_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('sas-case/getdistrictlist') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success:function(data){
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function(key, value) {
                        if(value == '0')
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="">'+ key +'</option>');
                        else
                            $('select[name="CA_AGAINST_DISTCD"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });
                }
            });
        });
        
        $('#CA_ONLINECMPL_PROVIDER').on('change', function (e) {
            var CA_ONLINECMPL_PROVIDER = $(this).val();
            if(CA_ONLINECMPL_PROVIDER === '999')
                $( "label[for='CA_ONLINECMPL_URL']" ).addClass( "required" );
            else
                $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
        });
        
        $('#CA_CMPLCAT').on('change', function (e) {
            var CA_CMPLCAT = $(this).val();
            
            if(CA_CMPLCAT === 'BPGK 01' || CA_CMPLCAT === 'BPGK 03') {
                $( "#CA_CMPLKEYWORD" ).show();
            }else{
                $( "#CA_CMPLKEYWORD" ).hide();
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
                $('#div_CA_ONLINECMPL_ACCNO').show();
                $('#div_CA_AGAINST_PREMISE').hide();
                $('#div_CA_AGAINSTADD').hide();
                $('#div_CA_AGAINST_POSTCD').hide();
                $('#div_CA_AGAINST_STATECD').hide();
                $('#div_CA_AGAINST_DISTCD').hide();
                $( "label[for='CA_ONLINECMPL_URL']" ).removeClass( "required" );
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
                {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'},
            ]
        });
    });

</script>
@stop
