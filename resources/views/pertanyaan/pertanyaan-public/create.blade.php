@extends('layouts.main_public')
<?php
use App\Menu;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
</style>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active">
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                @lang('pertanyaan.tab.enquiry')
            </a>
        </li>
        <li class="">
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                @lang('pertanyaan.tab.attachment')
            </a>
        </li>
        <li>
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                @lang('pertanyaan.tab.preview')
            </a>
        </li>
        <li>
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">4</span>
                </span>
                @lang('pertanyaan.tab.submit')
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="enq_info" class="tab-pane active">
            <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                            <div class="col-lg-12">
            <div class="panel panel-success" style="border: 1px solid #115272;">
                      <div class="panel-heading" style="background: #115272;">
                                        <i class="fa fa-question"></i> @lang('button.pertanyaan')
                                    </div>
            <div class="panel-body" style="color: black;">
                {!! Form::open(['route' => 'pertanyaan-public.store','class'=>'form-horizontal', 'method' => 'post']) !!}
                {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('AS_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label(trans('pertanyaan.form_label.summary'), '', ['class' => 'col-sm-2 control-label required']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('AS_SUMMARY', '', ['class' => 'form-control input-sm', 'rows' => 4]) }}
                                    @if ($errors->has('AS_SUMMARY'))
                                        <span class="help-block"><strong>@lang('pertanyaan.validation.AS_SUMMARY')</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            {{-- Form::submit(trans('button.send'), ['name' => 'btnHantar','class' => 'btn btn-success btn-sm']) --}}
                            {{-- Form::submit(trans('button.save'), ['name' => 'btnSimpan','class' => 'btn btn-primary btn-sm']) --}}
                            <!--<a class="btn btn-default btn-sm" href="{{-- route('dashboard') --}}">@lang('button.back')</a>-->
                            <a class="btn btn-warning btn-sm" href="{{ route('dashboard',['#enquery']) }}">@lang('button.back')</a>
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

@section('script_datatable')
<script type="text/javascript">
    $(function () {

        $('#module_ind').on('change', function(e){
            alert($('#module_ind').val());
        });
        
    });
</script>
@stop
