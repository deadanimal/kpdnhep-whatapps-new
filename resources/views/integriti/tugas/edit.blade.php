@extends('layouts.main')
<?php
    use App\Branch;
    use App\Ref;
    use App\Penugasan;
    use Carbon\Carbon;
    use App\Aduan\Penyiasatan;
    use App\Integriti\IntegritiAdmin;
    use App\Integriti\IntegritiMeeting;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
    .help-block-red {
        color: red;
    }
</style>
<h2>Penugasan Aduan (Integriti)</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#penugasan"> Penugasan</a></li>
            <!-- <li class=""><a data-toggle="tab" href="#case-info"> Maklumat Aduan</a></li> -->
            <!-- <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li> -->
            <!-- <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li> -->
            <!--<li class=""><a data-toggle="tab" href="#letter">Surat</a></li>-->
            <!-- <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li> -->
        </ul>
        <div class="tab-content">
            <div id="penugasan" class="tab-pane active">
                <div class="panel-body">
                    <!-- {{-- Form::open(['url' => '/tugas/tugas-kepada/'.$mPenugasan->IN_CASEID,  'class'=>'form-horizontal', 'method' => 'POST']) --}} -->
                    {{ Form::open(['route' => ['integrititugas.update', $mPenugasan->id], 'class' => 'form-horizontal']) }}
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <!-- {{-- Form::text('IN_CASEID', $mPenugasan->IN_CASEID, ['class' => 'form-control input-sm','readonly' => true]) --}} -->
                                    <p class="form-control-static">
                                        <strong>
                                            <a 
                                                onclick="showsummaryintegriti('{{ $mPenugasan->id }}')" 
                                                class="text-primary"
                                                data-toggle="tooltip" data-placement="right" title="Tekan untuk melihat maklumat aduan"
                                            >
                                                {{ $mPenugasan->IN_CASEID }}
                                            </a>
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <!-- <div class="form-group"> -->
                                <!-- {{-- Form::label('IN_FILEREF', 'No. Rujukan Fail', ['class' => 'col-md-4 control-label']) --}} -->
                                <!-- <div class="col-sm-8"> -->
                                    <!-- {{-- Form::text('IN_FILEREF', '', ['class' => 'form-control input-sm']) --}} -->
                                <!-- </div> -->
                            <!-- </div> -->
                            <div class="form-group">
                                {{ Form::label('IN_ASGBY', 'Penugasan Aduan Oleh', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <!-- {{-- Form::text('IN_ASGBY', $mUser->name, ['class' => 'form-control input-sm','readonly' => true]) --}} -->
                                    <p class="form-control-static">{{ $mUser->name }}</p>
                                </div>
                            </div>
                            <!-- <div class="form-group {{ $errors->has('IN_FILEREF') ? ' has-error' : '' }}"> -->
                                <!-- {{ Form::label('IN_FILEREF','No. Rujukan Fail', ['class' => 'col-lg-4 control-label']) }} -->
                                <!-- <div class="col-lg-8"> -->
                                    <!-- {{ Form::text('IN_FILEREF', old('IN_FILEREF', $mPenugasan->IN_FILEREF), ['class' => 'form-control input-sm']) }} -->
                                    <!-- @if ($errors->has('IN_FILEREF')) -->
                                        <!-- <span class="help-block"> -->
                                            <!-- <strong>{{ $errors->first('IN_FILEREF') }}</strong> -->
                                        <!-- </span> -->
                                    <!-- @endif -->
                                <!-- </div> -->
                            <!-- </div> -->
                        </div>
                        <div class="col-sm-6">
                            @if($mBukaSemula)
                                <div class="form-group">
                                    {{ Form::label('','No. Aduan Sebelum Dibuka Semula', ['class' => 'col-lg-5 control-label']) }}
                                    <div class="col-lg-7">
                                    @if(!empty($mBukaSemulaOld))
                                        <!-- <a onclick="ShowSummary('{{ $mBukaSemula->IF_CASEID }}')"> -->
                                        <a onclick="showsummaryintegriti('{{ $mBukaSemulaOld->id }}')">
                                    @endif
                                    {{ $mBukaSemula->IF_CASEID }}
                                    @if(!empty($mBukaSemulaOld))
                                        <!-- {{-- $mBukaSemulaOld->id --}} -->
                                        </a>
                                    @endif
                                    </div>
                                </div>
                            @endif
                            <div id="div_IN_CLASSIFICATION" class="form-group{{ $errors->has('IN_CLASSIFICATION') ? ' has-error' : '' }}">
                                {{ Form::label('IN_CLASSIFICATION', 'Klasifikasi', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ Form::select('IN_CLASSIFICATION', Ref::GetList('1380', true, 'ms', 'sort'), old('IN_CLASSIFICATION', $mPenugasan->IN_CLASSIFICATION), ['class' => 'form-control required', 'id' => 'IN_CLASSIFICATION']) }}
                                    @if ($errors->has('IN_CLASSIFICATION'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_CLASSIFICATION') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div id="div_IN_CMPLCAT" class="form-group{{ $errors->has('IN_CMPLCAT') ? ' has-error' : '' }}" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '02' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '01' || $mPenugasan->IN_INVSTS == '02' ? 'block' : 'none')) }}"> -->
                            <div id="div_IN_CMPLCAT" class="form-group{{ $errors->has('IN_CMPLCAT') ? ' has-error' : '' }}" style="display: {{ (old('IN_CLASSIFICATION') ? (old('IN_CLASSIFICATION') == '1' ? 'block':'none') : ($mPenugasan->IN_CLASSIFICATION == '1' ? 'block' : 'none')) }}">
                                {{ Form::label('IN_CMPLCAT', 'Kategori', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    <!--{{-- Form::select('IN_CMPLCAT', Ref::GetList('244', true, 'ms', 'descr'), old('IN_CMPLCAT') == ''? $mPenugasan->IN_CMPLCAT : old('IN_CMPLCAT'), ['class' => 'form-control input-sm required', 'id' => 'IN_CMPLCAT']) --}}-->
                                    {{ Form::select('IN_CMPLCAT', Ref::GetList('1344', true, 'ms', 'descr'), old('IN_CMPLCAT', $mPenugasan->IN_CMPLCAT), ['class' => 'form-control required', 'id' => 'IN_CMPLCAT']) }}
                                    @if ($errors->has('IN_CMPLCAT'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_CMPLCAT') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('IN_INVSTS') ? ' has-error' : '' }}">
                                {{ Form::label('IN_INVSTS', 'Status Aduan', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    <!-- {{-- Form::select('IN_INVSTS', Penugasan::GetStatusList(), $mPenugasan->IN_INVSTS, ['class' => 'form-control input-sm', 'id' => 'IN_INVSTS']) --}} -->
                                    {{ Form::select(
                                        'IN_INVSTS', 
                                        IntegritiAdmin::GetStatusList('1334', 
                                            ['02','05','06','07','08','013','014']
                                        ), 
                                        old('IN_INVSTS', $mPenugasan->IN_INVSTS),
                                        [
                                            'class' => 'form-control', 
                                            'id' => 'IN_INVSTS'
                                        ]
                                    ) }}
                                    @if ($errors->has('IN_INVSTS'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_INVSTS') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div id="div_IN_MEETINGNUM" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '02' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}" class="form-group{{ $errors->has('IN_MEETINGNUM') ? ' has-error' : '' }}"> -->
                            <!-- <div id="div_IN_MEETINGNUM" style="display: block" class="form-group{{ $errors->has('IN_MEETINGNUM') ? ' has-error' : '' }}"> -->
                            <!-- <div  style="display: {{ $errors->has('IN_MEETINGNUM')||old('IN_INVSTS') == '02' ? 'block' : 'none' }} ;" class="form-group{{ $errors->has('IN_MEETINGNUM') ? ' has-error' : '' }}"> -->
                            <!-- <div id="div_IN_MEETINGNUM" 
                            style="
                                display: {{ 
                                    (
                                        old('IN_INVSTS') 
                                        ? (
                                            in_array(
                                                old('IN_INVSTS'),['02','05','013','014']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                        : (
                                            (
                                                in_array(
                                                    $mPenugasan->IN_INVSTS,['02','05','013','014']
                                                ) 
                                                ? 'block'
                                                : 'none'
                                            )
                                        )
                                    ) }};" class="form-group{{ $errors->has('IN_MEETINGNUM') ? ' has-error' : '' }}"> -->
                            <div id="div_IN_MEETINGNUM" 
                                class="form-group{{ $errors->has('IN_MEETINGNUM') ? ' has-error' : '' }}"
                                style="
                                display: {{ 
                                    (
                                        old('IN_INVSTS') 
                                        ? (
                                            in_array(
                                                old('IN_INVSTS'),['02','05','013','014']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                        : 
                                        (
                                            in_array(
                                                $mPenugasan->IN_INVSTS,['02','05','013','014']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                    )
                                }};">
                                {{ Form::label('IN_MEETINGNUM', 'No. Bilangan Mesyuarat JMA', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        {{ Form::text('IN_MEETINGNUM', old('IN_MEETINGNUM', $mPenugasan->IN_MEETINGNUM), ['class' => 'form-control', 'readonly' => 'true', 'id' => 'meetingnum'])}}
                                        <!-- {{-- Form::hidden('IN_INVBY', $mPenugasan->IN_INVBY, ['class' => 'form-control', 'id' => 'InvById']) --}} -->
                                        <span class="input-group-btn">
                                            <button 
                                                type="button" 
                                                class="btn btn-primary" 
                                                id="meetingSearchModal"
                                                data-toggle="tooltip" 
                                                data-placement="right" 
                                                title="Pilihan Bilangan Mesyuarat Jawatankuasa Menilai Aduan" 
                                                data-original-title="Tooltip on right"
                                            >
                                                Carian
                                            </button>
                                        </span>
                                    </div>
                                    @if ($errors->has('IN_MEETINGNUM'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_MEETINGNUM') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div id="div_IN_IPNO" style="display: {{ $errors->has('IN_IPNO')||old('IN_INVSTS') == '02' ? 'block' : 'none' }} ;" class="form-group {{ $errors->has('IN_IPNO') ? ' has-error' : '' }}"> -->
                            <div id="div_IN_IPNO" 
                                class="form-group{{ $errors->has('IN_IPNO') ? ' has-error' : '' }}"
                                style="
                                display: {{ 
                                    (
                                        old('IN_INVSTS') 
                                        ? (
                                            in_array(
                                                old('IN_INVSTS'),['02']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                        : 
                                        (
                                            in_array(
                                                $mPenugasan->IN_INVSTS,['02']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                    )
                                }};">
                                {{ Form::label('IN_IPNO', 'No. IP', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ Form::text('IN_IPNO', old('IN_IPNO', $mPenugasan->IN_IPNO), ['class' => 'form-control']) }}
                                    @if ($errors->has('IN_IPNO'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_IPNO') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_IN_ACTION_BRSTATECD" 
                                class="form-group {{ $errors->has('IN_ACTION_BRSTATECD') ? ' has-error' : '' }}" 
                                style="display: {{ 
                                    old('IN_INVSTS') 
                                    ? (old('IN_INVSTS') == '013'? 'block' : 'none') 
                                    : ($mPenugasan->IN_INVSTS == '013' ? 'block' : 'none')
                                }};">
                                {{ Form::label('IN_ACTION_BRSTATECD', 
                                    'Negeri', 
                                    ['class' => 'col-lg-5 control-label required']) 
                                }}
                                <div class="col-lg-7">
                                    {{ 
                                        Form::select(
                                            'IN_ACTION_BRSTATECD', 
                                            Ref::GetList('17', false, 'ms'), 
                                            old('IN_ACTION_BRSTATECD', $mPenugasan->IN_ACTION_BRSTATECD), 
                                            [
                                                'class' => 'form-control select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_ACTION_BRSTATECD'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_ACTION_BRSTATECD') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div id="div_IN_ACTION_BRNCD" 
                                class="form-group {{ $errors->has('IN_ACTION_BRNCD') ? ' has-error' : '' }}" 
                                style="display: {{ 
                                    old('IN_INVSTS') 
                                    ? (old('IN_INVSTS') == '013'? 'block' : 'none') 
                                    : ($mPenugasan->IN_INVSTS == '013' ? 'block' : 'none')
                                }};">
                                {{ Form::label('IN_ACTION_BRNCD', 'Bahagian / Cawangan', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    {{ 
                                        Form::select(
                                            'IN_ACTION_BRNCD', 
                                            IntegritiAdmin::GetListByState(
                                                (
                                                    old('IN_ACTION_BRSTATECD')
                                                    ? old('IN_ACTION_BRSTATECD') 
                                                    : (
                                                        $mPenugasan->IN_ACTION_BRSTATECD
                                                        ? $mPenugasan->IN_ACTION_BRSTATECD
                                                        : ''
                                                    )
                                                )
                                                ,
                                                false
                                            )
                                            , 
                                            old('IN_ACTION_BRNCD')
                                            ? old('IN_ACTION_BRNCD') 
                                            : (
                                                $mPenugasan->IN_ACTION_BRNCD
                                                ? $mPenugasan->IN_ACTION_BRNCD
                                                : ''
                                            )
                                            ,
                                            [
                                                'class' => 'form-control select2',
                                                'placeholder' => '-- SILA PILIH --'
                                            ]
                                        ) 
                                    }}
                                    @if ($errors->has('IN_ACTION_BRNCD'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('IN_ACTION_BRNCD') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div id="magncd" style="display: {{ $errors->has('IN_MAGNCD')||$mPenugasan->IN_INVSTS == '04'||(old('IN_INVSTS') && old('IN_INVSTS') == '04') ? 'block' : 'none' }} ;"> -->
                            <div id="magncd" style="display: {{ 
                                old('IN_INVSTS') 
                                ? (old('IN_INVSTS') == '014'? 'block' : 'none') 
                                : ($mPenugasan->IN_INVSTS == '014' ? 'block' : 'none')
                                }} ;">
                                <div class="form-group{{ $errors->has('IN_MAGNCD') ? ' has-error' : '' }}">
                                    {{ Form::label('IN_MAGNCD', 'Agensi', ['class' => 'col-lg-5 control-label required']) }}
                                    <div class="col-lg-7">
                                        {{ Form::select('IN_MAGNCD', Penyiasatan::GetMagncdList(), old('IN_MAGNCD', $mPenugasan->IN_MAGNCD), ['class' => 'form-control']) }}
                                        @if ($errors->has('IN_MAGNCD'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_MAGNCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- <div id="div_BLOCK" style="display: {{ old('IN_INVSTS') != '' ? 'block' : 'none' }}"> -->
                                <!-- <div id="div_IN_CMPLCD" class="form-group{{ $errors->has('IN_CMPLCD') ? ' has-error' : '' }}" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}"> -->
                                    <!-- {{ Form::label('IN_CMPLCD', 'Subkategori', ['class' => 'col-sm-5 control-label required']) }} -->
                                    <!-- <div class="col-sm-7"> -->
                                        <!-- {{ Form::select('IN_CMPLCD', $mPenugasan->IN_CMPLCAT == '' ? ['-- SILA PILIH --'] : Penugasan::getcmplcdlist($mPenugasan->IN_CMPLCAT), old('IN_CMPLCD', $mPenugasan->IN_CMPLCD), ['class' => 'form-control input-sm']) }} -->
                                        <!-- {{-- Form::select('IN_CMPLCD', Ref::GetList('634', true, 'ms'), $mPenugasan->IN_CMPLCD, ['class' => 'form-control input-sm']) --}} -->
                                        <!-- @if ($errors->has('IN_CMPLCD')) -->
                                        <!-- <span class="help-block"><strong>{{ $errors->first('IN_CMPLCD') }}</strong></span> -->
                                        <!-- @endif -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <!--<div id="div_IN_INVBY" style="display: {{-- old('IN_INVSTS') == '2' || $mPenugasan->IN_INVSTS == '1' ? 'block':'none' --}}" class="form-group{{ $errors->has('IN_INVBY') ? ' has-error' : '' }}">-->
                                <!-- <div id="div_IN_INVBY" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}" class="form-group{{ $errors->has('IN_INVBY') ? ' has-error' : '' }}"> -->
                            <div id="div_IN_INVBY" 
                                class="form-group{{ $errors->has('IN_INVBY') ? ' has-error' : '' }}"
                                style="
                                display: {{ 
                                    (
                                        old('IN_INVSTS') 
                                        ? (
                                            in_array(
                                                old('IN_INVSTS'),['02']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                        : 
                                        (
                                            in_array(
                                                $mPenugasan->IN_INVSTS,['02']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                    )
                                }};">
                                {{ Form::label('IN_INVBY','Pegawai Penyiasat/Serbuan', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        {{ Form::text('IN_INVBY_NAME', $mPenugasan->invby ? $mPenugasan->invby->name : $mPenugasan->IN_INVBY, ['class' => 'form-control', 'readonly' => 'true', 'id' => 'InvByName'])}}
                                        {{ Form::hidden('IN_INVBY', $mPenugasan->IN_INVBY, ['class' => 'form-control', 'id' => 'InvById']) }}
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary" id="UserSearchModal">Carian</button>
                                        </span>
                                    </div>
                                    @if ($errors->has('IN_INVBY'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_INVBY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                                <!-- <div id="div_IN_INVBYOTH" class="form-group" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}"> -->
                                    <!-- {{ Form::label('', 'Pegawai-pegawai Lain', ['class' => 'col-sm-5 control-label']) }} -->
                                    <!-- <div class="col-sm-7"> -->
                                        <!-- <button type="button" class="btn btn-primary btn-sm" id="MultiUserSearchModal">Carian</button> -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <!-- <div class="form-group" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '2' ? 'block':'none') : ($mPenugasan->IN_INVSTS == '1' || $mPenugasan->IN_INVSTS == '0' ? 'block' : 'none')) }}"> -->
                                    <!-- {{ Form::label('', '', ['class' => 'col-sm-5 control-label']) }} -->
                                    <!-- <div class="col-sm-7"> -->
                                        <!-- <div id="recipient1"></div> -->
                                        <!-- <div id="recipient2"></div> -->
                                    <!-- </div> -->
                                <!-- </div> -->
                            <!-- <div id="div_IN_ACCESSIND" class="form-group{{ $errors->has('IN_ACCESSIND') ? ' has-error' : '' }}" style="display: {{ $errors->has('IN_ACCESSIND')||old('IN_INVSTS') == '02' ? 'block' : 'none' }} ;"> -->
                            <div id="div_IN_ACCESSIND" 
                                class="form-group{{ $errors->has('IN_ACCESSIND') ? ' has-error' : '' }}"
                                style="
                                display: {{ 
                                    (
                                        old('IN_INVSTS') 
                                        ? (
                                            in_array(
                                                old('IN_INVSTS'),['02']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                        : 
                                        (
                                            in_array(
                                                $mPenugasan->IN_INVSTS,['02']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                    )
                                }};">
                                {{ Form::label('IN_ACCESSIND', 'Akses Maklumat Pengadu Kepada Pegawai Penyiasat', ['class' => 'col-lg-5 control-label required']) }}
                                <div class="col-lg-7">
                                    <div class="radio radio-success radio-inline">
                                        <input id="yes" type="radio" value="1" name="IN_ACCESSIND" {{ old('IN_ACCESSIND') == '1'||$mPenugasan->IN_ACCESSIND == '1'? 'checked':'' }}>
                                        <label for="yes">Ya</label>
                                    </div>
                                    <div class="radio radio-success radio-inline">
                                        <input id="no" type="radio" value="0" name="IN_ACCESSIND" {{ old('IN_ACCESSIND') == '0'||$mPenugasan->IN_ACCESSIND == '0'? 'checked':'' }}>
                                        <label for="no">Tidak</label>
                                    </div>
                                    @if ($errors->has('IN_ACCESSIND'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_ACCESSIND') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!-- </div> -->
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IN_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-lg-2 control-label']) }}
                                <div class="col-lg-10">
                                    <!-- {{-- Form::textarea('IN_SUMMARY', old('IN_SUMMARY', $mPenugasan->IN_SUMMARY), ['class' => 'form-control input-sm', 'rows' => 5]) --}} -->
                                    <p class="form-control-static">
                                        {!! nl2br(htmlspecialchars($mPenugasan->IN_SUMMARY)) !!}
                                    </p>
                                    <!-- <span class="help-block m-b-none help-block-red">@lang('public-case.case.CA_SUMMARY_HELP')</span> -->
                                    <!-- {{-- @if ($errors->has('IN_SUMMARY')) --}} -->
                                        <!-- <span class="help-block"><strong>{{-- $errors->first('IN_SUMMARY') --}}</strong></span> -->
                                    <!-- {{-- @endif --}} -->
                                </div>
                            </div>
                            <!-- <div class="form-group{{ $errors->has('ID_DESC') ? ' has-error' : '' }}" id="div_ID_DESC" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '02' ? 'block':'none') : 'none') }}"> -->
                            <div id="div_ID_DESC" 
                                class="form-group{{ $errors->has('ID_DESC') ? ' has-error' : '' }}"
                                style="
                                display: {{ 
                                    (
                                        old('IN_INVSTS') 
                                        ? (
                                            in_array(
                                                old('IN_INVSTS'),['02']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                        : 
                                        (
                                            in_array(
                                                $mPenugasan->IN_INVSTS,['02']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                    )
                                }};">
                                {{ Form::label('ID_DESC', 'Hasil Keputusan JMA', ['class' => 'col-lg-2 control-label required']) }}
                                <div class="col-lg-10">
                                    {{ Form::textarea('ID_DESC', old('ID_DESC', (!empty($mPenugasanDetail) ? $mPenugasanDetail->ID_DESC : '')), ['class' => 'form-control', 'rows' => 5]) }}
                                    @if ($errors->has('ID_DESC'))
                                        <span class="help-block"><strong>{{ $errors->first('ID_DESC') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="form-group{{ $errors->has('ID_DESC_NOTE') ? ' has-error' : '' }}" id="div_ID_DESC_NOTE" style="display: {{ (old('IN_INVSTS') ? (old('IN_INVSTS') == '08' ? 'block':'none') : 'none') }}"> -->
                            <div id="div_ID_DESC_NOTE" 
                                class="form-group{{ $errors->has('ID_DESC_NOTE') ? ' has-error' : '' }}"
                                style="
                                display: {{ 
                                    (
                                        old('IN_INVSTS') 
                                        ? (
                                            in_array(
                                                old('IN_INVSTS'),['08']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                        : 
                                        (
                                            in_array(
                                                $mPenugasan->IN_INVSTS,['08']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                    )
                                }};">
                                {{ Form::label('ID_DESC_NOTE', 'Catatan', ['class' => 'col-lg-2 control-label required']) }}
                                <div class="col-lg-10">
                                    {{ Form::textarea('ID_DESC_NOTE', old('ID_DESC_NOTE', (!empty($mPenugasanDetail) ? $mPenugasanDetail->ID_DESC : '')), ['class' => 'form-control', 'rows' => 5]) }}
                                    @if ($errors->has('ID_DESC_NOTE'))
                                        <span class="help-block"><strong>{{ $errors->first('ID_DESC_NOTE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="form-group{{ $errors->has('IN_ANSWER') ? ' has-error' : '' }}" id="div_IN_ANSWER" style="display: {{ old('IN_INVSTS') ? (old('IN_INVSTS') != '2' ? 'block':'none') : 'none' }}"> -->
                            <div id="div_IN_ANSWER" 
                            class="form-group{{ $errors->has('IN_ANSWER') ? ' has-error' : '' }}"
                            style="
                                display: {{ 
                                    (
                                        old('IN_INVSTS') 
                                        ? (
                                            in_array(
                                                old('IN_INVSTS'),['02','05','06','07','08']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                        : 
                                        (
                                            in_array(
                                                $mPenugasan->IN_INVSTS,['02','05','06','07','08']
                                            ) 
                                            ? 'block'
                                            : 'none'
                                        ) 
                                    )
                                }};">
                                {{ Form::label('IN_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-lg-2 control-label required']) }}
                                <div class="col-lg-10">
                                    {{ Form::textarea('IN_ANSWER', old('IN_ANSWER'), ['class' => 'form-control', 'rows' => 5]) }}
                                    @if ($errors->has('IN_ANSWER'))
                                        <span class="help-block"><strong>{{ $errors->first('IN_ANSWER') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-sm-12" align="center">
                                {{ Form::submit('Hantar', ['class' => 'btn btn-primary btn-sm']) }}
                                <!-- {{-- Form::submit('Cetak Surat', ['class' => 'btn btn-success btn-sm']) --}} -->
                                <a href="{{ url('integrititugas')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
                            </div>
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
                                {{ Form::label('IN_CASEID','No. Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CASEID', $mPenugasan->IN_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_BRNCD','Nama Cawangan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_BRNCD', '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CASEID','No Rujukan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CASEID', '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_RCVDT','Tarikh Penerimaan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_RCVDT', $mPenugasan->IN_RCVDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->IN_RCVDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CREDT','Tarikh Cipta ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CREDT', $mPenugasan->IN_CREDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->IN_CREDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>MAKLUMAT ADUAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_RCVTYP') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::select('IN_RCVTYP', Ref::GetList('259', true), $mPenugasan->IN_RCVTYP, ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    {{ Form::text('IN_RCVTYP', $mPenugasan->IN_RCVTYP != ''? Ref::GetDescr('259', $mPenugasan->IN_RCVTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('IN_RCVTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('IN_RCVBY') ? ' has-error' : '' }}">
                                {{ Form::label('IN_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('IN_RCVBY', $mPenugasan->IN_RCVBY,  ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                    {{ Form::text('IN_RCVBY', count($mPenugasan->namapenerima) == '1'? $mPenugasan->namapenerima->name : $mPenugasan->IN_RCVBY, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('IN_RCVTYP'))
                                    <span class="help-block"><strong>{{ $errors->first('IN_RCVTYP') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_SUMMARY','Aduan ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_SUMMARY', $mPenugasan->IN_SUMMARY, ['class' => 'form-control input-sm','readonly' => true,'rows' => 5]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>PENUGASAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_ASGBY','Penugasan Aduan Oleh  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_ASGBY', $mPenugasan->IN_ASGBY != '' ? $mPenugasan->IN_ASGBY : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_INVBY','Pegawai Penyiasat/Serbuan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_INVBY', $mPenugasan->namapenyiasat ? $mPenugasan->namapenyiasat->name : $mPenugasan->IN_INVBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_ASGDT','Tarikh Penugasan  ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_ASGDT', $mPenugasan->IN_ASGDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->IN_ASGDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEDT','Tarikh Selesai ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEDT', $mPenugasan->IN_CLOSEDT != '' ? date('d-m-Y h:i A', strtotime($mPenugasan->IN_CLOSEDT)) : '', ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>SIASATAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_INVSTS','Status Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_INVSTS', Ref::GetList('292', true, 'ms'), old('IN_INVSTS', $mPenugasan->IN_INVSTS), ['class' => 'form-control input-sm', 'id' => 'IN_INVSTS','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_PREMISE','Kod Premis ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_AGAINST_PREMISE', Ref::GetList('221', true, 'ms'), old('IN_AGAINST_PREMISE', $mPenugasan->IN_AGAINST_PREMISE), ['class' => 'form-control input-sm','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEBY','Ditutup Oleh ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEBY', $mPenugasan->IN_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CMPLCAT','Kategori Aduan ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_CMPLCAT', Ref::GetList('244', true, 'ms'), old('IN_CMPLCAT', $mPenugasan->IN_CMPLCAT), ['class' => 'form-control input-sm required', 'id' => 'IN_CMPLCAT','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CMPLCD','SubKategori ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_CMPLCD', $mPenugasan->IN_CMPLCD == ''? ['-- SILA PILIH --'] : Ref::GetList('634', true, 'ms'), old('IN_CMPLCD', $mPenugasan->IN_CMPLCD), ['class' => 'form-control input-sm','disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEBY','Tarikh Ditutup ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEBY', $mPenugasan->IN_CLOSEBY, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_RESULT','Hasil Siasatan ', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_RESULT', $mPenugasan->IN_RESULT, ['class' => 'form-control input-sm','readonly' => true,'rows' => 5]) }}
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
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_NAME','Nama Pengadu ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_NAME', $mPenugasan->IN_NAME, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_SEXCD','Jantina ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_SEXCD', Ref::GetList('202 ', true), $mPenugasan->IN_SEXCD, ['class' => 'form-control input-sm', 'id' => 'IN_SEXCD', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ADDR','Alamat ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::textarea('IN_ADDR', $mPenugasan->IN_ADDR, ['class' => 'form-control input-sm','rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_DOCNO','No. KP atau Passport ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_DOCNO', $mPenugasan->IN_DOCNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_NATCD','Warganegara ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_NATCD', Ref::GetList('199 ', true), $mPenugasan->IN_NATCD, ['class' => 'form-control input-sm', 'id' => 'IN_NATCD', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_TELNO', $mPenugasan->IN_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_MOBILENO', 'No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_MOBILENO', $mPenugasan->IN_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_EMAIL', $mPenugasan->IN_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>MAKLUMAT YANG DIADU</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_AGAINSTNM','Nama ', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_AGAINSTNM', $mPenugasan->IN_AGAINSTNM, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINSTADD','Alamat ', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('IN_AGAINSTADD', $mPenugasan->IN_AGAINSTADD, ['class' => 'form-control input-sm', 'rows' => 3,'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 08'? 'block':'none')) }};">
                                {{ Form::label('IN_TTPMTYP', 'Penuntut/Penentang', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_TTPMTYP', $mPenugasan->IN_TTPMTYP != ''? Ref::GetDescr('1108', $mPenugasan->IN_TTPMTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 08'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 08'? 'block':'none')) }};">
                                {{ Form::label('IN_TTPMNO', 'No. TTPM', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_TTPMNO', old('IN_TTPMNO', $mPenugasan->IN_TTPMNO), ['class' => 'form-control input-sm', 'readonly' => true])}}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ONLINECMPL_AMOUNT', 'Jumlah Kerugian (RM)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_AMOUNT', $mPenugasan->IN_ONLINECMPL_AMOUNT, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (in_array(old('IN_CMPLCAT'),['BPGK 01','BPGK 03'])? 'block':'none') : ((in_array($mPenugasan->IN_CMPLCAT,['BPGK 01','BPGK 03'])? 'block':'none'))) }};">
                                {{ Form::label('IN_CMPLKEYWORD', 'Jenis Barangan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_CMPLKEYWORD', $mPenugasan->IN_CMPLKEYWORD != ''? Ref::GetDescr('1051', $mPenugasan->IN_CMPLKEYWORD, 'ms'):'', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'none':'block') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'none':'block')) }};">
                                {{ Form::label('IN_AGAINST_PREMISE', 'Jenis Premis', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_AGAINST_PREMISE', $mPenugasan->IN_AGAINST_PREMISE != ''? Ref::GetDescr('221', $mPenugasan->IN_AGAINST_PREMISE, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_PROVIDER', 'Pembekal Perkhidmatan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_PROVIDER', $mPenugasan->IN_ONLINECMPL_PROVIDER != ''? Ref::GetDescr('1091', $mPenugasan->IN_ONLINECMPL_PROVIDER, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_URL', 'Laman Web / URL / ID', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_URL', $mPenugasan->IN_ONLINECMPL_URL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_PYMNTTYP', 'Cara Pembayaran', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_PYMNTTYP', $mPenugasan->IN_ONLINECMPL_PYMNTTYP != ''? Ref::GetDescr('1207', $mPenugasan->IN_ONLINECMPL_PYMNTTYP, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_BANKCD', 'Nama Bank', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_BANKCD', $mPenugasan->IN_ONLINECMPL_BANKCD != ''? Ref::GetDescr('1106', $mPenugasan->IN_ONLINECMPL_BANKCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT')? (old('IN_CMPLCAT') == 'BPGK 19'? 'block':'none') : ($mPenugasan->IN_CMPLCAT == 'BPGK 19'? 'block':'none')) }};">
                                {{ Form::label('IN_ONLINECMPL_ACCNO', 'No. Akaun Bank / No. Transaksi FPX', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_ACCNO', $mPenugasan->IN_ONLINECMPL_ACCNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group" style="display: {{ (old('IN_CMPLCAT') == 'BPGK 19'? (old('IN_CMPLCAT') == 'BPGK 19' && old('IN_ONLINECMPL_IND') == 'on'? 'block':($mPenugasan->IN_CMPLCAT == 'BPGK 19' && $mPenugasan->IN_ONLINECMPL_IND == '1'? 'block':'none')): old('IN_CMPLCAT') == '' && $mPenugasan->IN_CMPLCAT == 'BPGK 19'? (old('IN_ONLINECMPL_IND') == '' && $mPenugasan->IN_ONLINECMPL_IND == '1'? 'block':(old('IN_ONLINECMPL_IND') == 'on'? 'block':'none')):'none' ) }} ;">
                                {{ Form::label('IN_ONLINECMPL_CASENO', 'No. Aduan Rujukan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_ONLINECMPL_CASENO', $mPenugasan->IN_ONLINECMPL_CASENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_TELNO','No. Tel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_TELNO', $mPenugasan->IN_AGAINST_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_MOBILENO', 'No. Tel Bimbit', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_MOBILENO', $mPenugasan->IN_AGAINST_MOBILENO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_FAXNO','No. Faks ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_FAXNO', $mPenugasan->IN_AGAINST_FAXNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINST_EMAIL','Emel ', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINST_EMAIL', $mPenugasan->IN_AGAINST_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
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
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="table-responsive">
                        <table id="penugasan-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                    <h4>GABUNGAN ADUAN</h4>
                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                    <!--<div class="hr-line-solid"></div>-->
                    <div class="table-responsive">
                        <table id="penugasan-gabung-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                                    <th>Surat Pengadu</th>
                                    <th>Surat Pegawai</th>
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
<!-- Modal Start -->
<!-- {{-- @include('aduan.tugas.usersearchmodal') --}} -->
@include('integriti.tugas.usersearchmodal')
<!-- {{-- @include('aduan.tugas.multiusersearchmodal') --}} -->
<div id="carian-mesyuarat" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Carian Mesyuarat JMA</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['id' => 'meeting-search-form', 'class' => 'form-horizontal']) !!}
                <div class="row">
                    <div class="col-sm-6">
                        <!-- <div class="form-group">
                            {{ Form::label('icnew', 'No. Kad Pengenalan', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('icnew', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div> -->
                        <div class="form-group">
                            {{ Form::label('IM_MEETINGNUM', 'Bilangan', ['class' => 'col-lg-4 control-label']) }}
                            <div class="col-lg-8">
                                {{ Form::text('IM_MEETINGNUM', '', ['class' => 'form-control input-sm', 'id' => 'IM_MEETINGNUM']) }}
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            {{ Form::label('IM_MEETINGDATE', 'Tarikh', ['class' => 'col-lg-4 control-label']) }}
                            <div class="col-lg-8">
                                {{ Form::text('IM_MEETINGDATE', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div> -->
                        <div class="form-group">
                            {{ Form::label('IM_MEETINGDATE_YEAR', 'Tahun', ['class' => 'col-lg-4 control-label']) }}
                            <div class="col-lg-8">
                                {{ Form::select('IM_MEETINGDATE_YEAR', IntegritiMeeting::getlistyear(), '', [
                                    'class' => 'form-control input-sm', 
                                    'id' => 'IM_MEETINGDATE_YEAR',
                                    'placeholder' => '-- SILA PILIH --'
                                ]) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!-- <div class="form-group"> -->
                            <!-- {{-- Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) --}} -->
                            <!-- <div class="col-sm-9"> -->
                                <!-- {{-- Form::text('state_cd', Ref::GetDescr('17', $mUser->state_cd), ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                            <!-- </div> -->
                        <!-- </div> -->
                        <!-- <div class="form-group{{ $errors->has('brn_cd') ? ' has-error' : '' }}"> -->
                            <!-- {{-- Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label']) --}} -->
                            <!-- <div class="col-sm-9"> -->
                                <!-- {{-- Form::text('brn_cd', Branch::GetBranchName($mUser->brn_cd), ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                            <!-- </div> -->
                        <!-- </div> -->
<!--                        <div class="form-group{{-- $errors->has('role_cd') ? ' has-error' : '' --}}">
                            {{-- Form::label('role_cd', 'Peranan', ['class' => 'col-sm-3 control-label']) --}}
                            <div class="col-sm-9">
                                {{-- Form::select('role_cd', Ref::GetList('152', true), null, ['class' => 'form-control input-sm', 'id' => 'role_cd']) --}}
                            </div>
                        </div>-->
                        <!-- <div class="form-group">
                            {{ Form::label('name', 'Nama', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div> -->
                        <div class="form-group">
                            {{ Form::label('IM_CHAIRPERSON', 'Pengerusi', ['class' => 'col-lg-4 control-label']) }}
                            <div class="col-lg-8">
                                {{ Form::text('IM_CHAIRPERSON', '', ['class' => 'form-control input-sm', 'id' => 'IM_CHAIRPERSON']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" align="center">
                        {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                        {{ Form::reset('Semula', ['class' => 'btn btn-default btn-sm', 'id' => 'meetingresetbtn']) }}
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="table-responsive">
                    <table id="meeting-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Bilangan</th>
                                <th>Tarikh</th>
                                <th>Pengerusi</th>
                                <th>Pilih</th>
                            </tr>
                        </thead>
                        <!-- <tbody> -->
                        <!-- </tbody> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->
<!-- Modal Start -->
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
<div id="modal-show-summary-integriti" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummaryIntegriti'></div>
    </div>
</div>
<!-- Modal End -->
@stop

@section('script_datatable')
<script type="text/javascript">    
    function ShowSummary(CASEID)
    {
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
    }

    function showsummaryintegriti(id)
    {
        $('#modal-show-summary-integriti')
            .modal("show")
            .find("#ModalShowSummaryIntegriti")
            .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
    }

    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href=' + hash + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    $('#IN_INVSTS').on('change', function (e) {
        var IN_INVSTS = $(this).val();
        if(IN_INVSTS === ''){
            $('#div_BLOCK').hide();
            $('#magncd').hide();
            $('#div_ID_DESC').hide();
            $('#div_ID_DESC_NOTE').hide();
            $('#div_IN_ANSWER').hide();
            // $('#div_IN_CMPLCAT').hide();
            $('#div_IN_ACCESSIND').hide();
            $('#div_IN_MEETINGNUM').hide();
            $('#div_IN_IPNO').hide();
            $('#div_IN_ACTION_BRSTATECD').hide();
            $('#div_IN_ACTION_BRNCD').hide();
        }
        // else
        //     $('#div_BLOCK').show();
        // else
        //     $('#magncd').hide();
        else if(IN_INVSTS === '02')
        {
            // $('#div_IN_CMPLCAT').show();
            // $('#div_IN_CMPLCD').show();
            $('#div_IN_INVBY').show();
            // $('#div_IN_INVBYOTH').show();
            $('#div_ID_DESC').show();
            $('#div_ID_DESC_NOTE').hide();
            $('#div_IN_ANSWER').show();
            $('#div_BLOCK').show();
            $('#magncd').hide();
            $('#div_IN_ACCESSIND').show();
            $('#div_IN_MEETINGNUM').show();
            $('#div_IN_IPNO').show();
            $('#div_IN_ACTION_BRSTATECD').hide();
            $('#div_IN_ACTION_BRNCD').hide();
        }
        else if(IN_INVSTS === '04'){
            $('#div_BLOCK').hide();
            $('#magncd').hide();
            $('#div_ID_DESC').hide();
            $('#div_ID_DESC_NOTE').hide();
            $('#div_IN_ANSWER').show();
            // $('#div_IN_CMPLCAT').hide();
            $('#div_IN_ACCESSIND').hide();
            $('#div_IN_MEETINGNUM').show();
            $('#div_IN_IPNO').hide();
            $('#div_IN_ACTION_BRSTATECD').hide();
            $('#div_IN_ACTION_BRNCD').hide();
        }
        else if(IN_INVSTS === '05')
        {
            $('#div_IN_MEETINGNUM').show();
            $('#div_BLOCK').hide();
            $('#div_IN_INVBY').hide();
            $('#magncd').hide();
            $('#div_IN_IPNO').hide();
            $('#div_IN_ACCESSIND').show();
            $('#div_ID_DESC').hide();
            $('#div_ID_DESC_NOTE').hide();
            $('#div_IN_ANSWER').show();
            $('#div_IN_ACTION_BRSTATECD').hide();
            $('#div_IN_ACTION_BRNCD').hide();
        }
        // else if (IN_INVSTS === '06' || IN_INVSTS === '07' || IN_INVSTS === '08')
        else if (IN_INVSTS === '06' || IN_INVSTS === '07')
        {
            // $('#div_IN_CMPLCAT').hide();
            // $('#div_IN_CMPLCD').hide();
            $('#div_IN_INVBY').hide();
            // $('#div_IN_INVBYOTH').hide();
            $('#div_ID_DESC').hide();
            $('#div_ID_DESC_NOTE').hide();
            $('#div_IN_ANSWER').show();
            $('#div_BLOCK').hide();
            $('#magncd').hide();
            $('#div_IN_ACCESSIND').hide();
            $('#div_IN_MEETINGNUM').hide();
            $('#div_IN_IPNO').hide();
            $('#div_IN_ACTION_BRSTATECD').hide();
            $('#div_IN_ACTION_BRNCD').hide();
        }
        else if (IN_INVSTS === '08')
        {
            // $('#div_IN_CMPLCAT').hide();
            // $('#div_IN_CMPLCD').hide();
            $('#div_IN_INVBY').hide();
            // $('#div_IN_INVBYOTH').hide();
            $('#div_ID_DESC').hide();
            $('#div_ID_DESC_NOTE').show();
            $('#div_IN_ANSWER').show();
            $('#div_BLOCK').hide();
            $('#magncd').hide();
            $('#div_IN_ACCESSIND').hide();
            $('#div_IN_MEETINGNUM').hide();
            $('#div_IN_IPNO').hide();
            $('#div_IN_ACTION_BRSTATECD').hide();
            $('#div_IN_ACTION_BRNCD').hide();
        }
        else if(IN_INVSTS === '013')
        {
            $('#div_IN_INVBY').hide();
            $('#div_ID_DESC').hide();
            $('#div_ID_DESC_NOTE').hide();
            $('#div_IN_ANSWER').hide();
            $('#div_BLOCK').hide();
            $('#magncd').hide();
            $('#div_IN_ACCESSIND').hide();
            $('#div_IN_MEETINGNUM').show();
            $('#div_IN_IPNO').hide();
            $('#div_IN_ACTION_BRSTATECD').show();
            $('#div_IN_ACTION_BRNCD').show();
        }
        else if(IN_INVSTS === '014')
        {
            $('#div_IN_INVBY').hide();
            $('#div_ID_DESC').hide();
            $('#div_ID_DESC_NOTE').hide();
            $('#div_IN_ANSWER').hide();
            $('#div_BLOCK').hide();
            $('#magncd').show();
            $('#div_IN_ACCESSIND').hide();
            $('#div_IN_MEETINGNUM').show();
            $('#div_IN_IPNO').hide();
            $('#div_IN_ACTION_BRSTATECD').hide();
            $('#div_IN_ACTION_BRNCD').hide();
        }
        else
        {
            // $('#div_IN_CMPLCAT').hide();
            // $('#div_IN_CMPLCD').hide();
            $('#div_IN_INVBY').hide();
            // $('#div_IN_INVBYOTH').hide();
            $('#div_ID_DESC').hide();
            $('#div_ID_DESC_NOTE').hide();
            $('#div_IN_ANSWER').hide();
            $('#div_BLOCK').hide();
            $('#magncd').hide();
            $('#div_IN_ACCESSIND').hide();
            $('#div_IN_MEETINGNUM').hide();
            $('#div_IN_IPNO').hide();
            $('#div_IN_ACTION_BRSTATECD').hide();
            $('#div_IN_ACTION_BRNCD').hide();
        }
    });

    $('#IN_CLASSIFICATION').on('change', function (e) {
        var IN_CLASSIFICATION = $(this).val();
        if(IN_CLASSIFICATION === '1'){
            $('#div_IN_CMPLCAT').show();
        }
        else
        {
            $('#div_IN_CMPLCAT').hide();
        }
    });

    function myFunction(id) {
        $.ajax({
            url: "{{ url('tugas/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("InvByName").value = key;
                    document.getElementById("InvById").value = value;
                });
                $('#carian-penerima').modal('hide');
            }
        });
    }
    ;

    $(document).ready(function () {

        $('#UserSearchModal').on('click', function (e) {
            $("#carian-penerima").modal();
        });
        
        $('#MultiUserSearchModal').on('click', function (e) {
            $("#carian-lain2-penerima").modal();
        });

        $('#carian-penerima').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        });

        $('#meetingSearchModal').on('click', function (e) {
            $("#carian-mesyuarat").modal();
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
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                // url: "{{-- url('tugas/getdatatableuser') --}}",
                url: "{{ url('integrititugas/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd_user').val();
                    d.brn_cd = $('#brn_cd').val();
                    d.role_cd = $('#role_cd').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                // {data: 'state_cd', name: 'state_cd'},
                // {data: 'brn_cd', name: 'brn_cd'},
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });
        
        // var oTableMulti = $('#users-multi-table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     bFilter: false,
        //     aaSorting: [],
        //     pagingType: "full_numbers",
        //     dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
        //         "<'row'<'col-sm-12'tr>>" +
        //         "<'row'<'col-sm-12'p>>",
        //     language: {
        //         lengthMenu: 'Memaparkan _MENU_ rekod',
        //         zeroRecords: 'Tiada rekod ditemui',
        //         info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
        //         infoEmpty: 'Tiada rekod ditemui',
        //         infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
        //         paginate: {
        //             previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
        //             next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
        //             first: 'Pertama',
        //             last: 'Terakhir',
        //         },
        //     },
        //     ajax: {
        //         url: "{{ url('tugas/getdatatablemultiuser') }}",
        //         data: function (d) {
        //             d.name = $('#name_multi').val();
        //             d.icnew = $('#icnew_multi').val();
        //         }
        //     },
        //     columns: [
        //         {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
        //         {data: 'username', name: 'username'},
        //         {data: 'name', name: 'name'},
        //         {data: 'state_cd', name: 'state_cd'},
        //         {data: 'brn_cd', name: 'brn_cd'},
        //         {data: 'count_case', name: 'count_case'},
        //         {data: 'role.role_code', name: 'role.role_code'},
        //         {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
        //     ],
        // });

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
            oTable.columns.adjust();
            e.preventDefault();
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        
        $('#search-form-multi').on('submit', function (e) {
            oTableMulti.draw();
            e.preventDefault();
        });
        
        $('#resetbtnmulti').on('click', function(e) {
            document.getElementById("search-form-multi").reset();
            oTableMulti.draw();
            e.preventDefault();
        });
        
        $('#meeting-search-form').on('submit', function (e) {
            mTable.draw();
            e.preventDefault();
        });

        $('#meetingresetbtn').on('click', function (e) {
            document.getElementById("meeting-search-form").reset();
            mTable.draw();
            mTable.columns.adjust();
            e.preventDefault();
        });

        var mTable = $('#meeting-table').DataTable({
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
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                // url: "{{-- url('tugas/getdatatableuser') --}}",
                // url: "{{-- url('integrititugas/getdatatableuser') --}}",
                // url: "{{-- url('integritimeeting/getdatatable') --}}",
                url: "{{ url('integritimeeting/getdatatabletugas') }}",
                data: function (d) {
                    // d.name = $('#name').val();
                    // d.icnew = $('#icnew').val();
                    // d.state_cd = $('#state_cd_user').val();
                    // d.brn_cd = $('#brn_cd').val();
                    // d.role_cd = $('#role_cd').val();
                    d.IM_MEETINGNUM = $('#IM_MEETINGNUM').val();
                    d.IM_CHAIRPERSON = $('#IM_CHAIRPERSON').val();
                    d.IM_MEETINGDATE_YEAR = $('#IM_MEETINGDATE_YEAR').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                // {data: 'username', name: 'username'},
                // {data: 'name', name: 'name'},
                // {data: 'state_cd', name: 'state_cd'},
                // {data: 'brn_cd', name: 'brn_cd'},
                // {data: 'count_case', name: 'count_case'},
                // {data: 'role.role_code', name: 'role.role_code'},
                // {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
                {data: 'IM_MEETINGNUM', name: 'IM_MEETINGNUM'},
                // {data: 'IN_CASEID', render: function (data, type) {
                //     return type === 'export' ?
                //         "' " + data :
                //         data;
                // }, name: 'IN_CASEID'},
                {data: 'IM_MEETINGDATE', name: 'IM_MEETINGDATE'},
                {data: 'IM_CHAIRPERSON', name: 'IM_CHAIRPERSON'},
                // {data: 'IM_STATUS', name: 'IM_STATUS'},
                // {data: 'action', name: 'action', searchable: false, orderable: false}
                {data: 'actionchoosemeeting', name: 'actionchoosemeeting', searchable: false, orderable: false}
            ],
        });

        $('#IN_ACTION_BRSTATECD').on('change', function (e) {
            var IN_ACTION_BRSTATECD = $(this).val();
            if(IN_ACTION_BRSTATECD){
                $.ajax({
                    type: 'GET',
                    // url: "{{-- url('user/getbrnlist') --}}" + '/' + IN_ACTION_BRSTATECD,
                    url: "{{ url('integritibase/getbrnlist') }}" + '/' + IN_ACTION_BRSTATECD,
                    dataType: 'json',
                    success: function (data) {
                        $('select[name="IN_ACTION_BRNCD"]').empty();
                        $('select[name="IN_ACTION_BRNCD"]').append('<option value="">-- SILA PILIH --</option>');
                        $.each(data, function (key, value) {
                            $('select[name="IN_ACTION_BRNCD"]').append('<option value="' + key + '">' + value + '</option>');
                        })
                    },
                    complete: function (data) {
                        // $('#IN_BRNCD').trigger('change');
                        $('select[name="IN_ACTION_BRNCD"]').trigger('change');
                    }
                });
            } else {
                // $('select[id="IN_BRNCD"]').empty();
                // $('select[id="IN_BRNCD"]').append('<option>-- SILA PILIH --</option>');
                // $('select[id="IN_BRNCD"]').trigger('change');
                $('select[name="IN_ACTION_BRNCD"]').empty();
                $('select[name="IN_ACTION_BRNCD"]').append('<option>-- SILA PILIH --</option>');
                $('select[name="IN_ACTION_BRNCD"]').trigger('change');
            }
        });
    });
    
    function choosemeeting(id) {
        $.ajax({
            url: "{{ url('integrititugas/getmeetingdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("meetingnum").value = key;
                    // document.getElementById("InvById").value = value;
                });
                $('#carian-mesyuarat').modal('hide');
            }
        });
    };
</script>
@stop
