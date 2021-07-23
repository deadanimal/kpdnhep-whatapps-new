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
            <!--<a>-->
            <a href='{{ $PertanyaanPublic->AS_ASKSTS == "1"? route("pertanyaan.attachment",$PertanyaanPublic->id):"" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                @lang('pertanyaan.tab.attachment')
            </a>
        </li>
        <li>
            <!--<a>-->
            <a href='{{ $PertanyaanPublic->AS_ASKSTS == "1"? route("pertanyaan.preview",$PertanyaanPublic->id):"" }}'>
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
                            {!! Form::open(['route' => ['pertanyaan-public.update',$PertanyaanPublic->id],'class'=>'form-horizontal', 'method' => 'patch']) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::label(trans('pertanyaan.form_label.summary'), '', ['class' => 'col-sm-2 control-label required']) }}
                                        <div class="col-sm-10">
                                            {{ Form::textarea('AS_SUMMARY', $PertanyaanPublic->AS_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 4]) }}
                                        </div>
                                    </div>
                                </div>
                                @if($PertanyaanPublic->AS_ASKSTS == '3')
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::label(trans('pertanyaan.form_label.answer'), '', ['class' => 'col-sm-2 control-label required']) }}
                                        <div class="col-sm-10">
                                            {{ Form::textarea('AS_ANSWER', $PertanyaanPublic->AS_ANSWER, ['class' => 'form-control input-sm', 'rows' => 4]) }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group" align="center">
                                    {{-- Form::submit(trans('button.send'), ['name' => 'btnHantar','class' => 'btn btn-success btn-sm']) --}}
                                    {{-- Form::submit(trans('button.save'), ['name' => 'btnSimpan','class' => 'btn btn-primary btn-sm']) --}}
                                    <!--<a class="btn btn-default btn-sm" href="{{-- route('dashboard') --}}">@lang('button.back')</a>-->
                                    <!--<a class="btn btn-warning btn-sm" href="{{-- route('dashboard',['#enquery']) --}}">@lang('button.back')</a>-->
                                    {{ Form::button(trans('button.continue').' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm'] )  }}
                                </div>  
                            </div>
                            <!--                        <div class="row">
                                                        <div class="form-group" align="center">
                                                            @if($PertanyaanPublic->AS_ASKSTS == '1')
                                                            {{-- Form::submit(trans('button.send'), ['name' => 'btnHantar','class' => 'btn btn-success btn-sm']) --}}
                                                            {{-- Form::submit(trans('button.save'), ['name' => 'btnSimpan','class' => 'btn btn-primary btn-sm']) --}}
                                                            @endif
                                                            <a class="btn btn-default btn-sm" href="{{-- route('dashboard') --}}">@lang('button.back')</a>
                                                        </div>  
                                                    </div>-->
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <!--            <div id="transaction_info" class="tab-pane">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table style="width: 100%" id="transaction-table" class="table table-striped table-bordered table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>@lang('pertanyaan.table_header.transaction.asksts')</th>
                                                        <th>@lang('pertanyaan.table_header.transaction.creby')</th>
                                                        <th>@lang('pertanyaan.table_header.transaction.credt')</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">

//    $(document).ready(function() {
//        var status = <?php echo $PertanyaanPublic->AS_ASKSTS; ?>;
//        if(status !== 1) {
//            $('.form-control').attr("disabled", true);
//        }
//    });
//    
//    $('#transaction-table').DataTable({
//            processing: true,
//            serverSide: true,
//            bFilter: false,
//            aaSorting: [],
//            bLengthChange: false,
//            bPaginate: false,
//            bInfo: false,
//            language: {
//                zeroRecords: 'Tiada rekod ditemui',
//                infoEmpty: 'Tiada rekod ditemui'
//            },
//            ajax: {
//                url: "{{ url('pertanyaan-public/getdatattable_transaction',$PertanyaanPublic->AS_ASKID)}}"
//            },
//            columns: [
//                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
//                {data: 'AD_ASKSTS', name: 'AD_ASKSTS'},
//                {data: 'AD_CREBY', name: 'AD_CREBY'},
//                {data: 'AD_CREDT', name: 'AD_CREDT'}
//            ]
//        });
</script>
@stop
