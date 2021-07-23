@extends('layouts.main_public')
<?php

use App\Ref;
use App\Aduan\PublicCase;
?>
@section('content')
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="">
            <a href='{{ $model->CA_INVSTS == "10"? route("public-case.edit",$model->id):"" }}'>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">1</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                @lang('public-case.case.complaintinfo')
            </a>
        </li>
        <li class="active">
            <a>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">2</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                @lang('public-case.case.attachment')
            </a>
        </li>
        <li class="">
            <a href='{{ $model->CA_INVSTS == "10"? route('public-case.preview',$model->id):"" }}'>
                <span class="fa-stack">
                    <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">3</strong>-->
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                @lang('public-case.case.complaintreview')
            </a>
        </li>
        @if($model->CA_INVSTS == '10')
            <li class="">
                <a>
                    <span class="fa-stack">
                        <!--<span class="fa fa-circle-o fa-stack-2x"></span><strong class="fa-stack-1x">4</strong>-->
                        <span style="font-size: 14px;" class="badge badge-danger">4</span>
                    </span>
                    @lang('public-case.case.acceptancedeclaration')
                </a>
            </li>
        @endif
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                <div class="col-lg-12">
                    <div class="panel panel-success" style="border: 1px solid #115272;">
                        <div class="panel-heading" style="background: #115272;">
                            <i class="fa fa-paperclip"></i> 
                            <!--@lang('button.attach')-->
                            @lang('public-case.attachment.lampiranlabel')
                        </div>
                        <div class="panel-body" style="color: black;">
                            <h4>(@lang('public-case.attachment.lampiranmax'))</h4>
                            @if($CountDoc < 5 && ($model->CA_INVSTS == 10 || $model->CA_INVSTS == 7))
                                <div class="row">
                                    <div class="col-md-9">
                                        <a onclick="ModalAttachmentCreate('{{ $model->id }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> @lang('public-case.attachment.lampiranlabel')</a>
                                    </div>
                                </div>
                                <br />
                            @else
                            @endif
                            <div class="table-responsive">
                                <table style="width: 100%" id="public-case-attachmnt-table" class="table table-bordered" >
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>@lang('public-case.case.file_name')</th>
                                            <th>@lang('public-case.attachment.remarks')</th>
                                            <th>@lang('public-case.case.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12" align="center">
                                    <a class="btn btn-success btn-sm" href="{{ route('public-case.edit',$model->id) }}"><i class="fa fa-chevron-left"></i> {{ trans('button.previous') }}</a>
                                    <!--<a class="btn btn-warning btn-sm" href="{{-- route('dashboard') --}}">@lang('button.back')</a>-->
                                    <a class="btn btn-success btn-sm" href="{{ route('public-case.preview',$model->id) }}">{{ trans('button.continue') }} <i class="fa fa-chevron-right"></i></a>
                                </div>
                            </div>
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
    
    function ModalAttachmentCreate(id) {
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('public-case-doc.create','') }}" + "/" + id);
        return false;
    }
    
    function ModalAttachmentEdit(id) {
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('public-case-doc.edit','') }}" + "/" + id);
        return false;
    }
    
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
                url: "{{ url('public-case-doc/getDatatable',$model->id)}}"
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
                {data: 'CC_REMARKS', name: 'CC_REMARKS'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
</script>
@stop
