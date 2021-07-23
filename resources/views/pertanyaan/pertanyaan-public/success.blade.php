@extends('layouts.main_public')
<?php

use App\Ref;
use App\Aduan\PublicCase;
use App\Aduan\PublicCaseDoc;
?>
@section('content')
<style>
    .btn:hover {
        background-color: #115272;
        color: white;
    }
    .btn-default{
        border : 1px solid #1c84c6;
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
                @lang('pertanyaan.tab.enquiry')
            </a>
        </li>
        <li class="">
            <a>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">2</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                @lang('pertanyaan.tab.attachment')
            </a>
        </li>
        <li class="">
            <a>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">3</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                @lang('pertanyaan.tab.preview')
            </a>
        </li>
        <li class="active">
            <!--<a style="color: black; background-color: #e7eaec ">-->
            <a style="color: black; background-color: #efefef; ">
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">4</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">4</span>
                </span>
                @lang('pertanyaan.tab.submit')
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="caseinfo" class="tab-pane active">
            <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                <div class="col-lg-12">
                    <div class="panel panel-success" style="border: 1px solid #115272;">
                        <div class="panel-heading" style="background: #115272;">
                            <i class="fa fa-check-square-o "></i> @lang('button.success')
                        </div>
                        <!--<div class="panel-body" style="color: black; background-color: #e7eaec">-->
                        <div class="panel-body" style="color: black; ">
                            <div class="row">
                                <!--<div class="col-sm-6 col-sm-offset-3 widget blue-bg text-center">-->
                                <div  style="color: #1c84c6;" class="col-sm-6 col-sm-offset-3 text-center">
                                    <div class="m-b-md">
                                        <!--<i class="fa fa-thumbs-o-up fa-5x"></i>-->
                                        <h3 class="font-bold no-margins">
                                            @lang('pertanyaan.title.successreceive')
                                        </h3>
                                        <h1 class="m-xs">@lang('pertanyaan.title.success') <strong>{{ $id }}</strong></h1>
                                        <!--<small>Email penerimaan aduan telah dihantar ke <b>{{-- Auth::user()->email --}}</b></small>-->
                                    </div>
                                    <a type="button" href="{{ url('dashboard'.'#enquery') }}" class="btn btn-outline btn-default">@lang('public-case.case.dashboard')</a>
                                    <a type="button" href="{{ url('pertanyaan-public/create') }}" class="btn btn-outline btn-default">@lang('public-case.case.newenquiry')</a>
                                    <a type="button" target="_blank" href="{{ url('pertanyaan-public/printsuccess',$id) }}" class="btn btn-outline btn-default"><i class="fa fa-print"></i> @lang('public-case.case.print')</a>
                                </div>
                            </div>
                            <!--                    <div class="row" align="center">
                                                    <div class="col-sm-1"></div>
                                                    <div class="col-sm-5">
                                                        <a href="{{ url('dashboard') }}">
                                                            <div class="widget style1 blue-bg">
                                                                <div class="row vertical-align">
                                                                    <div class="col-xs-3">
                                                                        <i class="fa fa-user fa-3x"></i>
                                                                    </div>
                                                                    <div class="col-xs-9 text-right">
                                                                        <h2 class="font-bold">Dashboard</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <a href="{{ url('pertanyaan-public/create') }}">
                                                            <div class="widget style1 blue-bg">
                                                                <div class="row vertical-align">
                                                                    <div class="col-xs-3">
                                                                        <i class="fa fa-phone fa-3x"></i>
                                                                    </div>
                                                                    <div class="col-xs-9 text-right">
                                                                        <h2 class="font-bold">@lang('pertanyaan.button.newenquery')</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-1"></div>
                                                    <a href="{{-- url('dashboard') --}}">
                                                        <button class="btn btn-success dim btn-large-dim" type="button">Kembali Ke Dashboard</button>
                                                    </a>
                                                    <a href="{{-- url('public-case/create') --}}">
                                                        <button class="btn btn-success dim btn-large-dim" type="button">Aduan Baru</button>
                                                    </a>
                                                </div>-->

                            <?php
//                        header("refresh: 10; url=/dashboard"); // redirect the user after 10 seconds
                            #exit; // note that exit is not required, HTML can be displayed.
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Start -->
<div class="modal inmodal" id="myModalInfo" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content animated bounceIn" id='modalEditContent' style="border-radius: 30px;">
            <div class="modal-header" style="border-radius: 25px 25px 0px 0px; background: #115272; background: -moz-linear-gradient(#115272, white); color: black; text-align: center;">
                <strong>{{ trans('public-case.success.questionnaire') }}</strong>
            </div>
            <div class="modal-body" style="background: white; ">
                <p>
                    <strong>
                        {{ trans('public-case.success.question') }}
                    </strong>
                </p>
                <p class="text-center">
                    <strong>
                        <a href="{{ url('https://docs.google.com/forms/d/e/1FAIpQLSfzANSMxFByW3Sv8NAwvtQykZYNPjakTzbGtjphORFbfbNYxQ/viewform') }}" target="_blank">
                            {{ trans('public-case.success.link') }}
                        </a>
                    </strong>
                </p>
            </div>
            <div class="modal-footer" style="border-radius:0px 0px 25px 25px; background: #115272; background: -moz-linear-gradient(bottom, #115272, white); color: black;">
                <p class="text-center">
                    <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">@lang('public-case.case.close')</button>
                </p>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
    $('#myModalInfo').modal("show");
</script>
@stop
