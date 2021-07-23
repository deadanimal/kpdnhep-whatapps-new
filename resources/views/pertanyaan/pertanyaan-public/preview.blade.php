@extends('layouts.main_public')
<?php

use App\Ref;
use App\Aduan\PublicCase;
use App\Aduan\PublicCaseDoc;
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
            <!--<a>-->
            <a href='{{ $model->AS_ASKSTS == "1"? route("pertanyaan-public.edit",$model->id):"" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                @lang('pertanyaan.tab.enquiry')
            </a>
        </li>
        <li class="">
            <!--<a>-->
            <a href='{{ $model->AS_ASKSTS == "1"? route("pertanyaan.attachment",$model->id):"" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                @lang('pertanyaan.tab.attachment')
            </a>
        </li>
        <li class="active">
            <a style="color: black; background-color: #efefef;">
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                @lang('pertanyaan.tab.preview')
            </a>
        </li>
        <li class="">
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">4</span>
                </span>
                @lang('pertanyaan.tab.submit')
            </a>
        </li>
    </ul>
    <style>
        .form-control[readonly] {
            background-color: #ffffff;
        }
    </style>
    <div class="tab-content">
        <div id="caseinfo" class="tab-pane active">
            <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                <div class="col-lg-12">
                    <div class="panel panel-success" style="border: 1px solid #115272;">
                        <div class="panel-heading" style="background: #115272;">
                            <i class="fa fa-tags"></i> @lang('button.check2')
                        </div>
                        <div class="panel-body" style="color: black;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('AS_SUMMARY') ? ' has-error' : '' }}">
                                        <label for="AS_SUMMARY" class="col-sm-2 control-label required">@lang('pertanyaan.form_label.summary')</label>
                                        <div class="col-sm-10">
                                            {{ Form::textarea('AS_SUMMARY', $model->AS_SUMMARY, ['class' => 'form-control input-sm', 'rows'=> '7', 'readonly' => true]) }}
                                            @if ($errors->has('AS_SUMMARY'))
                                            <span class="help-block"><strong>@lang('public-case.validation.CA_SUMMARY')</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <h4>@lang('pertanyaan.title.attachment')</h4>
                                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                                    <table>
                                        <tr>
                                            @foreach($mPertanyaanPublicCaseDoc as $PertanyaanPublicCaseDoc)
                                            <?php $ExtFile = substr($PertanyaanPublicCaseDoc->img_name, -3); ?>
                                            @if($ExtFile == 'pdf' || $ExtFile == 'PDF')
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($PertanyaanPublicCaseDoc->path.$PertanyaanPublicCaseDoc->img) }}" target="_blank">
                                                        <img src="{{ url('img/PDF.png') }}" class="img-lg img-thumbnail"/>
                                                        <br />
                                                        {{ $PertanyaanPublicCaseDoc->img_name }}
                                                    </a>
                                                </div>
                                            </td>
                                            @else
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($PertanyaanPublicCaseDoc->path.$PertanyaanPublicCaseDoc->img) }}" target="_blank">
                                                        <img src="{{ Storage::disk('bahanpath')->url($PertanyaanPublicCaseDoc->path.$PertanyaanPublicCaseDoc->img) }}" class="img-lg img-thumbnail"/>
                                                        <br />
                                                        {{ $PertanyaanPublicCaseDoc->img_name }}
                                                    </a>
                                                </div>
                                            </td>
                                            @endif
            <!--                                <td style="max-width: 10%; min-width: 10%; ">
                                                @lang('pertanyaan.title.remarks') : <br />
                                                {{-- $PertanyaanPublicCaseDoc->remarks --}}
                                            </td>-->
                                            @endforeach
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12" align="center">
                                    <a class="btn btn-success btn-sm" href="{{ route('pertanyaan.attachment',$model->id) }}"><i class="fa fa-chevron-left"></i> {{ trans('button.previous') }}</a>
                                    <!--<a class="btn btn-warning btn-sm" href="{{-- route('dashboard',['#enquery']) --}}">@lang('button.back')</a>-->
                                    <a id="SubmitBtn" data-toggle="modal" data-target="#confirm-submit" class="btn btn-success btn-sm">{{ trans('button.send') }} <i class="fa fa-chevron-right"></i></a>
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
    <!-- Modal Confirmation Start -->
    <div class="modal inmodal" id="confirm-submit" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content animated bounceIn" id='modalEditContent' style="border-radius: 30px;">
                <div class="modal-header" style="border-radius: 25px 25px 0px 0px; background: #115272; background: -moz-linear-gradient(#115272, white); color: black; text-align: center;">
                    <strong>{{ trans('public-case.confirmation.service') }}</strong>
                </div>
                {!! Form::open(['route' => ['pertanyaan.submit',$model->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
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
                        <strong>{{ trans('pertanyaan.confirmation.submit') }}</strong>
                    </p>
                    <p class="text-center">
                        <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">@lang('public-case.confirmation.btncancel')</button>
                        <button type="submit" class="btn btn-sm btn-success" >@lang('public-case.confirmation.btnsubmit')</button>
                    </p>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
//    $('#SubmitBtn').click(function(e) {
////        e.preventDefault();
//        var Confirm = confirm('{{ trans('pertanyaan.confirmation.submit') }}');
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
                {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'},
            ]
        });
    });

</script>
@stop
