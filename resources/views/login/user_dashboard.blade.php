@extends('layouts.main_public')
<?php 
    use App\Ref;
    use App\Aduan\PublicCase;
    use App\Articles;
    // use App\Integriti\IntegritiAdmin;
    use App\Integriti\IntegritiPublic;
?>
@section('content')
<div class="row">
    <div class="col-sm-12">
<!--        <div class="ibox float-e-margins">
            <div class="ibox-content row">
                <div class="col-sm-12" align="center">
                    <button onclick="window.location.href='{{ url('public-case/create')}}'" class="btn btn-success dim btn-large-dim btn-outline" type="button">@lang('public-case.case.newcomplaint')</button>
                    <button onclick="window.location.href=''" class="btn btn-info dim btn-large-dim btn-outline" type="button">@lang('public-case.case.newenquiry')</button>
                    <button class="btn btn-info dim btn-large-dim btn-outline" type="button">Pertanyaan/<br>Cadangan Baru</button>
                            </div>
                <div class="col-sm-12" align="center">
                    <button  onclick="window.location.href='{{ url('public-case/checkcase', Auth::user()->id)}}'" class="btn btn-success dim btn-large-dim btn-outline" type="button">@lang('public-case.case.checkcase')</button>
                    <button class="btn btn-info dim btn-large-dim btn-outline" type="button">Semak Pertanyaan/<br>Cadangan</button>
                            </div>
                        </div>
        </div>-->
                    </div>
                </div>
<!--<div class="row" align="center">
    <div class="col-sm-3"></div>
    <div class="col-sm-3">
        <a href="{{ url('public-case/create') }}">
            <div class="widget style1 blue-bg" style="min-height: 100px">
                <div class="row vertical-align">
                    <h2 class="font-bold vertical-align">@lang('public-case.case.newcomplaint')</h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-3">
        <a href="{{ route('pertanyaan-public.create') }}">
            <div class="widget style1 blue-bg" style="min-height: 100px;">
                <div class="row vertical-align">
                        <h2 class="font-bold">@lang('public-case.case.newenquiry')</h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-3"></div>
</div>-->
<div class="row">
    <div class="col-sm-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#complain"> 
                    <!-- @lang('public-case.case.complaint') -->
                    @lang('public-case.case.consumercomplaint')
                    </a>
                </li>
                <li class=""><a data-toggle="tab" href="#enquery">@lang('public-case.case.enquiry')</a></li>
                <li class="">
                    <a data-toggle="tab" href="#integriti">
                        {{ Auth::user()->lang == 'ms' ? 'ADUAN INTEGRITI' : 'INTEGRITY COMPLAINT' }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="complain" class="tab-pane active">
                    <div class="panel-body" style="color: black; background-color: #e7eaec"><br>
                        <div class="row">
                            <p class='col-sm-12'>
                                {{ Auth::user()->lang == 'ms' ? 'Selamat Datang' : 'Welcome' }} <strong>{{ Auth::user()->name }}</strong>, {{ Auth::user()->lang == 'ms' ? 'sila mulakan proses membuat aduan dengan menekan butang Aduan Baru di bawah.' : 'please start the flow of creating new complaint by clicking on New Complaint button below.' }}
                            </p>
                        </div>
                        <div class="row" style="padding-bottom:10px;text-align:center;padding-top:10px;">
                            <a href="{{ url('public-case/create') }}">
                                <button type="button" class="btn btn-primary btn-danger" style="margin-left: 15px;"><i class="fa fa-plus"></i> @lang('public-case.case.newcomplaint')</button>
                            </a>
                        </div>
                        <hr style="background-color: #ccc; height: 1px; border: 0;">
                        <div class="row" style="padding-top: 10px; padding-bottom: 10px;">
                            <div class="col-lg-12">
                                <div class="panel panel-success" style="border: 1px solid #115272;">
                                    <div class="panel-heading" style="background: #115272;">
                                        <i class="fa fa-search"></i> @lang('button.search')
                                    </div>
                                    <div class="panel-body">
                                        <form method="POST" id="search-form" class="form-horizontal">
                                            <!--<div class="col-sm-1"></div>-->
                                            <!--<div class="row col-sm-10">-->
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="CA_CASEID" class="col-sm-6 control-label">@lang('public-case.case.CA_CASEID')</label>
                                                        <div class="col-sm-6">
                                                            {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm', 'id' => 'CA_CASEID']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <label for="CA_CASESTS" class="col-sm-3 control-label">@lang('public-case.case.CA_CASESTS')</label>
                                                        <div class="col-sm-9">
                                                            {{ Form::select('CA_CASESTS', Auth::user()->lang == 'ms' ? PublicCase::getRefList('292', true) : PublicCase::getRefList('292', true, 'en'), null, ['class' => 'form-control input-sm', 'id' => 'CA_CASESTS']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="CA_SUMMARY" class="col-sm-7 control-label">@lang('public-case.case.CA_SUMMARY')</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" name="CA_SUMMARY" id="CA_SUMMARY">
                                                    </div>
                                                </div>
                                            <!--</div>-->
                                            <!--<div class="col-sm-1"></div>-->
                                            <div class="row col-sm-12" align="center">
                                                <button style="color:#fff;background:#115272 !important;" type="submit" class="btn btn-success btn-sm">@lang('button.search')</button>
                                                <!--<a style="color: #fff;background:#fac626" class="btn btn-warning btn-sm" href="{{ url('dashboard')}}">@lang('button.reset')</a>-->
                                                <input type="reset" class="btn btn-warning btn-sm" style="color: #fff; background:#fac626" id="complainresetbtn" value="@lang('button.reset')">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div style="margin-bottom: 10px; border-bottom: 1px solid #ccc"></div>
                        <!--<h2 style="font-weight: 400;">{{-- Auth::user()->lang == 'ms' ? 'Senarai Aduan' : 'Complaint List' --}}</h2>-->
                        <div class="panel panel-success" style="border: 1px solid #115272;">
                            <div class="panel-heading" style="background: #115272;">
                                {{ Auth::user()->lang == 'ms' ? 'Senarai Aduan' : 'Complaint List' }}
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="case-table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 1%; color: white; background-color: #115272;">No</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_CASEID')</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_SUMMARY')</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_CASESTS')</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_BRNCD')</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_INVBY')</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_RCVDT')</th>
                                                <!--<th style="color: white; background-color: #446CB3;">@lang('public-case.case.tempoh')</th>-->
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.action')</th>
                                                <!--<th style="width: 1%; color: white; background-color: #446CB3;"></th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Start -->
                        <div id="modal-show-summary" class="modal fade" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id='ModalShowSummary'></div>
                            </div>
                        </div>
                        <!-- Modal End -->
                    </div>
                </div>
                <div id="enquery" class="tab-pane">
                    <div class="panel-body" style="color: black; background-color: #e7eaec">
                        <div class="row">
                            <p class='col-sm-12'>
                                {{ Auth::user()->lang == 'ms' ? 'Selamat Datang' : 'Welcome' }} <strong>{{ Auth::user()->name }}</strong>, {{ Auth::user()->lang == 'ms' ? 'sila kemukakan pertanyaan / cadangan dengan menekan butang Pertanyaan / Cadangan Baru di bawah.' : 'please submit your enquiry / suggestion by clicking the button below.' }}
                            </p>
                            <p class='col-sm-12'>
                                @lang('pertanyaan.description')
                            </p>
                        </div>
                        <div class="row" style="padding-bottom:10px;text-align:center;padding-top:10px;">
                            <a href="{{ route('pertanyaan-public.create') }}">
                                <button type="button" class="btn btn-primary btn-danger" style="margin-left: 15px;"><i class="fa fa-plus"></i> @lang('public-case.case.newenquiry')</button>
                            </a>
                        </div>
                        <hr style="background-color: #ccc; height: 1px; border: 0;">
                        <div class="row" style="padding-top: 10px; padding-bottom: 10px;">
                            <div class="col-lg-12">
                                <div class="panel panel-success" style="border: 1px solid #115272;">
                                    <div class="panel-heading" style="background: #115272;">
                                        <i class="fa fa-search"></i> @lang('button.search')
                                    </div>
                                    <div class="panel-body">
                                        <form method="POST" id="ask-search-form" class="form-horizontal">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="AS_ASKID" class="col-sm-7 control-label">@lang('pertanyaan.table_header.enquiry.askid')</label>
                                                    <div class="col-sm-5">
                                                        {{ Form::text('AS_ASKID', '', ['class' => 'form-control input-sm', 'id' => 'AS_ASKID']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="AS_ASKSTS" class="col-sm-3 control-label">@lang('pertanyaan.table_header.enquiry.asksts')</label>
                                                    <div class="col-sm-9">
                                                        {{ Form::select('AS_ASKSTS', Ref::GetList('1061', true, Auth::user()->lang), null, ['class' => 'form-control input-sm', 'id' => 'AS_ASKSTS']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <label for="AS_SUMMARY" class="col-sm-5 control-label">@lang('pertanyaan.table_header.enquiry.summary')</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="AS_SUMMARY" id="AS_SUMMARY">
                                                </div>
                                            </div>
                                            <div class="row col-sm-12" align="center">
                                                <button type="submit" class="btn btn-sm" style="color:#fff;background:#115272 !important;">@lang('button.search')</button>
                                                <input type="reset" class="btn btn-warning btn-sm" style="color: #fff;background:#fac626" id="resetbtn" value="@lang('button.reset')">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-bottom: 10px; border-bottom: 1px solid #ccc"></div>
                        <!--<h2 style="font-weight: 400;">{{ Auth::user()->lang == 'ms' ? 'Senarai Pertanyaan / Cadangan' : 'Enquiry / Suggestion List' }}</h2>-->
                        <div class="panel panel-success" style="border: 1px solid #115272;">
                            <div class="panel-heading" style="background: #115272;">
                                <i class="fa fa-question"></i> {{ Auth::user()->lang == 'ms' ? 'Senarai Pertanyaan':'Enquiry' }} | <i class="fa fa-lightbulb-o"></i> {{ Auth::user()->lang == 'ms' ? 'Cadangan':'Suggestion' }} 
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="pertanyaan-table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style="color: white; background-color: #115272;">No</th>
                                                <th style="color: white; background-color: #115272;">@lang('pertanyaan.table_header.enquiry.askid')</th>
                                                <th style="color: white; background-color: #115272;">@lang('pertanyaan.table_header.enquiry.summary')</th>
                                                <th style="color: white; background-color: #115272;">@lang('pertanyaan.table_header.enquiry.answer')</th>
                                                <th style="color: white; background-color: #115272;">@lang('pertanyaan.table_header.enquiry.date')</th>
                                                <th style="color: white; background-color: #115272;">@lang('pertanyaan.table_header.enquiry.asksts')</th>
                                                <th style="color: white; background-color: #115272;">@lang('pertanyaan.table_header.enquiry.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Start -->
                        <div id="modal-show-summary-enquery" class="modal fade" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id='ModalShowSummaryEnquery'></div>
                            </div>
                        </div>
                        <!-- Modal End -->
                    </div>
                </div>
                <div id="integriti" class="tab-pane">
                    <div class="panel-body" style="color: black; background-color: #e7eaec">
                        <div class="row">
                            <p class='col-sm-12' style="font-size: 14px;">
                                <strong>
                                    {{ Auth::user()->lang == 'ms' ? 'Selamat Datang' : 'Welcome' }} 
                                    {{ Auth::user()->name }}
                                </strong>, 
                                {{ 
                                    Auth::user()->lang == 'ms' 
                                    ? 'halaman ini adalah berkaitan Integriti / Salahlaku Pegawai Kementerian Perdagangan Dalam Negeri dan Hal Ehwal Pengguna (KPDNHEP) atau Agensi KPDNHEP. Sila kemukakan Aduan Integriti anda dengan menekan butang Aduan Integriti Baru di bawah.' 
                                    : 'this page is about integrity complaint / wrong behavior on Ministry Of Domestic Trade and Consumer Affairs (KPDNHEP) officers, or its agency. Please add your complaint by pressing the New Integrity Complaint button below.' 
                                }}
                            </p>
                            <!-- <p class='col-sm-12'> -->
                                <!-- @lang('pertanyaan.description') -->
                            <!-- </p> -->
                        </div>
                        <!-- <hr style="background-color: #ccc; height: 1px; border: 0;"> -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 style="color:#a94442;">
                                    {{ Auth::user()->lang == 'ms' ? 'Peringatan' : 'Reminder' }}
                                </h3>
                                <b style="font-size: 14px;">
                                    {{ 
                                        Auth::user()->lang == 'ms' 
                                        ? '"Kementerian Perdagangan Dalam Negeri Dan Hal Ehwal Pengguna (KPDNHEP) boleh untuk tidak melayan sebarang aduan yang menggunakan bahasa kesat, lucah, konfrontasi, penghinaan dan/atau tuduhan tanpa asas. KPDNHEP juga tidak akan campur tangan dalam kes-kes aduan yang melibatkan keputusan mana-mana mahkamah/badan arbitrary/seumpamanya serta mana-mana pertikaian yang melibatkan masalah peribadi antara syarikat/individu yang tiada kaitan dengan peranan/fungsi agensi KPDNHEP."' 
                                        : '"Ministry of Domestic Trade and Consumer Affairs (KPDNHEP) have the rights not address any complaints that are using abusive, obscene, confrontational, humiliating and / or non-basic allegations. KPDNHEP will also not intervene in any of the complaint cases involving which decision - arbitrary courts / similarities and any disputes involving private matters between companies / individuals not related to the role / function of the KPDNHEP agency."' 
                                    }}
                                </b>
                            </div>
                        </div>
                        <hr style="background-color: #ccc; height: 1px; border: 0;">
                        <div class="row" style="padding-bottom:10px;text-align:center;padding-top:10px;">
                            <a href="{{ route('public-integriti.create') }}">
                                <button type="button" class="btn btn-danger" style="margin-left: 15px;">
                                    <i class="fa fa-plus"></i> 
                                    {{ Auth::user()->lang == 'ms' ? 'Aduan Integriti Baru' : 'New Integrity Complaint' }}
                                </button>
                            </a>
                        </div>
                        <hr style="background-color: #ccc; height: 1px; border: 0;">
                        <div class="row" style="padding-top: 10px; padding-bottom: 10px;">
                            <div class="col-lg-12">
                                <div class="panel panel-success" style="border: 1px solid #115272;">
                                    <div class="panel-heading" style="background: #115272;">
                                        <i class="fa fa-search"></i> @lang('button.search')
                                    </div>
                                    <div class="panel-body">
                                        <!-- <form method="POST" id="integriti-search-form" class="form-horizontal"> -->
                                        {{ Form::open(['id' => 'integriti-search-form']) }}
                                            <!-- <div class="col-sm-3"> -->
                                            <!-- <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="IN_CASEID" class="col-sm-6 control-label">@lang('public-case.case.CA_CASEID')</label>
                                                    <div class="col-sm-6">
                                                        {{ Form::text('IN_CASEID', '', ['class' => 'form-control input-sm', 'id' => 'IN_CASEID']) }}
                                                    </div>
                                                </div>
                                            </div> -->
                                            <!-- <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label for="IN_INVSTS" class="col-sm-3 control-label">@lang('public-case.case.CA_CASESTS')</label>
                                                    <div class="col-sm-9">
                                                        {{-- Form::select('IN_INVSTS', Auth::user()->lang == 'ms' ? PublicCase::getRefList('292', true) : PublicCase::getRefList('292', true, 'en'), null, ['class' => 'form-control input-sm', 'id' => 'CA_CASESTS']) --}}
                                                    </div>
                                                </div>
                                            </div> -->
                                            <!-- <div class="col-sm-4"> -->
                                            <!-- <div class="col-sm-6">
                                                <label for="IN_SUMMARY" class="col-sm-7 control-label">@lang('public-case.case.CA_SUMMARY')</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" name="IN_SUMMARY" id="IN_SUMMARY">
                                                </div>
                                            </div> -->
                                            <!-- <div class="row col-sm-12" align="center">
                                                <button style="color:#fff;background:#115272 !important;" type="submit" class="btn btn-success btn-sm">@lang('button.search')</button>
                                                <input type="reset" class="btn btn-warning btn-sm" style="color: #fff; background:#fac626" id="integritiresetbtn" value="@lang('button.reset')">
                                            </div> -->
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        {{ Form::label('IN_CASEID', trans('public-case.case.CA_CASEID'), ['class' => 'control-label']) }}
                                                        {{ Form::text('IN_CASEID', '', ['class' => 'form-control', 'id' => 'IN_CASEID', 'placeholder' => trans('public-case.case.CA_CASEID')]) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        {{ Form::label('IN_INVSTS', trans('public-case.case.status'), ['class' => 'control-label']) }}
                                                        {{ Form::select(
                                                            'IN_INVSTS', 
                                                            IntegritiPublic::GetStatusList(
                                                                '1334', 
                                                                ['010','01','02','03','07']
                                                            ), 
                                                            '', 
                                                            [
                                                                'class' => 'form-control', 
                                                                'id' => 'IN_INVSTS',
                                                                'placeholder' => trans('public-case.case.all')
                                                            ]
                                                        ) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <!-- <label for="IN_SUMMARY" class="control-label"> -->
                                                            <!-- @lang('public-case.case.CA_SUMMARY') -->
                                                        <!-- </label> -->
                                                        {{ Form::label('IN_SUMMARY', trans('public-case.case.CA_SUMMARY'), ['class' => 'control-label']) }}
                                                        <!-- <input type="text" class="form-control" name="IN_SUMMARY" id="IN_SUMMARY"> -->
                                                        {{ Form::text('IN_SUMMARY', '', ['class' => 'form-control', 'id' => 'IN_SUMMARY', 'placeholder' => trans('public-case.case.CA_SUMMARY')]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="text-center">
                                                    <button style="color:#fff;background:#115272 !important;" type="submit" class="btn btn-success btn-sm">
                                                        @lang('button.search')
                                                    </button>
                                                    <input type="reset" class="btn btn-warning btn-sm" style="color: #fff; background:#fac626" id="integritiresetbtn" value="@lang('button.reset')">
                                                </div>
                                            </div>
                                        <!-- </form> -->
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-bottom: 10px; border-bottom: 1px solid #ccc"></div>
                        <!--<h2 style="font-weight: 400;">{{ Auth::user()->lang == 'ms' ? 'Senarai Pertanyaan / Cadangan' : 'Enquiry / Suggestion List' }}</h2>-->
                        <div class="panel panel-success" style="border: 1px solid #115272;">
                            <div class="panel-heading" style="background: #115272;">
                                <!-- <i class="fa fa-question"></i>  -->
                                {{ Auth::user()->lang == 'ms' ? 'Senarai Aduan Integriti':'Integrity Complaint' }} 
                                <!-- | <i class="fa fa-lightbulb-o"></i> {{-- Auth::user()->lang == 'ms' ? 'Cadangan':'Suggestion' --}}  -->
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="integrititable" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style="color: white; background-color: #115272;">No.</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_CASEID')</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_SUMMARY')</th>
                                                <th style="color: white; background-color: #115272;">
                                                    {{ Auth::user()->lang == 'ms' ? 'Status Aduan' : 'Complaint Status' }}
                                                </th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_BRNCD')</th>
                                                <!-- <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_INVBY')</th> -->
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.CA_RCVDT')</th>
                                                <th style="color: white; background-color: #115272;">@lang('public-case.case.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Start -->
                        <div id="modal-show-summary-integriti" class="modal fade" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id='ModalShowSummaryIntegriti'></div>
                            </div>
                        </div>
                        <!-- Modal End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-show-invby" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowInvBy'></div>
    </div>
</div>
@if (session('status'))
<div class="modal inmodal fade in" id="modal-announcement" role="dialog" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 30px;">
            <div class="modal-header" style=" background: #1c84c6; color: white; text-align: center;">
                <div style="font-weight:bold;">@lang('public-case.case.announcement')</div>
            </div>
            <div class="modal-body" style="background: white;">
                @if(Articles::GetAnnouncement())
                    @foreach(Articles::GetAnnouncement() as $data)
                        @if (App::getLocale() === 'ms')
                        <div class="alert alert-{{ $data->hits }}">
                            <h3>{{ $data->title_my }}</h3>
                            {{ $data->content_my }}
                        </div>
                        @else
                            <div class="alert alert-{{ $data->hits }}">
                                <h3>{{ $data->title_en }}</h3>
                                {{ $data->content_en }}
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="modal-footer" style="background: #1c84c6; text-align: center;">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><b>@lang('public-case.case.close')</b></button>
            </div>
        </div>
    </div>
</div>
@endif
@stop

@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        profileind = <?php echo  Auth::user()->profile_ind; ?>;
        if(profileind === 0) {
            swal({
                 title: "",
                 text: "Sila kemaskini profil sebelum meneruskan membuat aduan/pertanyaan dengan menekan butang Profil dibawah",
                 type: "warning",
                 timer: 600000,
                 allowEscapeKey: false,
                 showCancelButton: false,
                 confirmButtonColor: "#DD6B55",
                 confirmButtonText: "Profil",
             }, function () {
                 window.location = "/user/pubeditprofile";
             });
        }
    });
    
    function ShowSummary(CASEID)
    {
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
    }
    
    function ShowSummaryEnquery(ASKID)
    {
        $('#modal-show-summary-enquery').modal("show").find("#ModalShowSummaryEnquery").load("{{ route('pertanyaan.showsummary','') }}" + "/" + ASKID);
    }
    
    function ShowInvBy(id)
    {
        $('#modal-show-invby').modal("show").find("#ModalShowInvBy").load("{{ route('carian.showinvby','') }}" + "/" + id);
    }

    function showsummaryintegriti(id)
    {
        $('#modal-show-summary-integriti')
            .modal("show")
            .find("#ModalShowSummaryIntegriti")
            .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
    }
    
  $(function() {
      
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href='+hash+']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
    
    var oTable = $('#case-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: "@lang('datatable.lengthMenu')",
                zeroRecords: "@lang('datatable.infoEmpty')",
                info: "@lang('datatable.info')",
                infoEmpty: "@lang('datatable.infoEmpty')",
                infoFiltered: "(@lang('datatable.infoFiltered'))",
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: "@lang('datatable.first')",
                    last: "@lang('datatable.last')",
                },
            },
            ajax: {
                url: "{{ url('public-case/get_datatable',$mUser->username)}}",
                data: function (d) {
                    d.CA_CASEID = $('#CA_CASEID').val();
                    d.CA_CASESTS = $('#CA_CASESTS').val();
                    d.CA_SUMMARY = $('#CA_SUMMARY').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', searchable: false, orderable: false},
                {data: 'CA_CASEID', name: 'CA_CASEID'},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                {data: 'CA_CASESTS', name: 'CA_CASESTS'},
                {data: 'CA_BRNCD', name: 'CA_BRNCD'},
                {data: 'CA_INVBY', name: 'CA_INVBY'},
                {data: 'CA_RCVDT', name: 'CA_RCVDT'},
//                {data: 'tempoh', name: 'tempoh'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
        
    var PertanyaanTable = $('#pertanyaan-table').DataTable({
            processing: true,   
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: "@lang('datatable.lengthMenu')",
                zeroRecords: "@lang('datatable.infoEmpty')",
                info: "@lang('datatable.info')",
                infoEmpty: "@lang('datatable.infoEmpty')",
                infoFiltered: "(@lang('datatable.infoFiltered'))",
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: "@lang('datatable.first')",
                    last: "@lang('datatable.last')",
                },
            },
            ajax: {
                url: "{{ url('pertanyaan-public/getdatattable') }}",
                data: function (d) {
                    d.AS_ASKID = $('#AS_ASKID').val();
                    d.AS_ASKSTS = $('#AS_ASKSTS').val();
                    d.AS_SUMMARY = $('#AS_SUMMARY').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'AS_ASKID', name: 'AS_ASKID'},
                {data: 'AS_SUMMARY', name: 'AS_SUMMARY'},
                {data: 'AS_ANSWER', name: 'AS_ANSWER'},
                {data: 'AS_RCVDT', name: 'AS_RCVDT'},
                {data: 'AS_ASKSTS', name: 'AS_ASKSTS'},
//                {data: 'tempoh', name: 'tempoh'},
                {data: 'action', name: 'action', width: '5%', searchable: false, orderable: false}
            ]
        });

        var integrititable = $('#integrititable').DataTable({
            processing: true,   
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: "@lang('datatable.lengthMenu')",
                zeroRecords: "@lang('datatable.infoEmpty')",
                info: "@lang('datatable.info')",
                infoEmpty: "@lang('datatable.infoEmpty')",
                infoFiltered: "(@lang('datatable.infoFiltered'))",
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: "@lang('datatable.first')",
                    last: "@lang('datatable.last')",
                },
            },
            ajax: {
                url: "{{ url('public-integriti/getdatatable') }}",
                data: function (d) {
                    d.IN_CASEID = $('#IN_CASEID').val();
                    d.IN_INVSTS = $('#IN_INVSTS').val();
                    d.IN_SUMMARY = $('#IN_SUMMARY').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'IN_CASEID', name: 'IN_CASEID'},
                {data: 'IN_SUMMARY', name: 'IN_SUMMARY'},
                {data: 'IN_INVSTS', name: 'IN_INVSTS'},
                {data: 'IN_BRNCD', name: 'IN_BRNCD'},
                // {data: 'IN_INVBY', name: 'IN_INVBY'},
                {data: 'IN_RCVDT', name: 'IN_RCVDT'},
//                {data: 'tempoh', name: 'tempoh'},
                {data: 'action', name: 'action', width: '5%', searchable: false, orderable: false}
            ]
        });
        
        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#ask-search-form').on('submit', function (e) {
            PertanyaanTable.draw();
            e.preventDefault();
        });
        $('#resetbtn').on('click', function (e) {
            document.getElementById("ask-search-form").reset();
            PertanyaanTable.draw();
            e.preventDefault();
        });
        $('#complainresetbtn').on('click', function (e) {
            document.getElementById("search-form").reset();
            oTable.draw();
            e.preventDefault();
        });
        $('#integriti-search-form').on('submit', function (e) {
            integrititable.draw();
            e.preventDefault();
        });
        $('#integritiresetbtn').on('click', function (e) {
            document.getElementById("integriti-search-form").reset();
            integrititable.draw();
            e.preventDefault();
        });
        
        $(document).on("click",".link",function(){
            var hash = document.location.hash.replace('#', '');;
            $(this).attr("href", this.href + "/"+ hash);
        });
    });
    
    $(document).ready(function(){
        var data = <?php echo count(Articles::GetAnnouncement()); ?>;
        if(data > 0) {
            $('#modal-announcement').modal("show");
        }
        return false;
    });
</script>
@stop