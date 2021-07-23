@extends('layouts.main')
<?php
    use App\Branch;
    use App\Ref;
    use App\Aduan\AdminCase;
?>
@section('content')
    <style>
        textarea {
            resize: vertical;
        }

        span.select2 {
            width: 100% !important;
        }

        .select2-dropdown {
            z-index: 3000 !important;
        }

        .help-block-red {
            color: red;
        }
    </style>
    <!--<div class="row">-->
    <!--<div class="col-lg-12">-->
    <!--<div class="ibox float-e-margins">-->
    <h2>Tambah Tetapan Mesyuarat JMA Baru (Integriti)</h2>
    <!--<div class="ibox-content">-->
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a>
                <!-- <span class="fa-stack"> -->
                    <!-- <span style="font-size: 14px;" class="badge badge-danger">1</span> -->
                <!-- </span> -->
                    Mesyuarat
                </a>
            </li>
            <!-- <li class=""> -->
                <!-- <a> -->
                <!-- <span class="fa-stack"> -->
                    <!-- <span style="font-size: 14px;" class="badge badge-danger">2</span> -->
                <!-- </span> -->
                    <!-- LAMPIRAN -->
                <!-- </a> -->
            <!-- </li> -->
            <!-- <li class=""> -->
                <!-- <a> -->
                <!-- <span class="fa-stack"> -->
                    <!-- <span style="font-size: 14px;" class="badge badge-danger">3</span> -->
                <!-- </span> -->
                    <!-- SEMAKAN ADUAN -->
                <!-- </a> -->
            <!-- </li> -->
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => 'integritimeeting.store', 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }}
                    <!-- <h4>Cara Terima</h4> -->
                    <!--<div class="hr-line-solid"></div>-->
                    <!-- <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;"> -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IM_MEETINGNUM') ? ' has-error' : '' }}">
                                {{ Form::label('IM_MEETINGNUM', 'No. Bilangan Mesyuarat JMA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-4">
                                    {{ Form::text('IM_MEETINGNUM', '', ['class' => 'form-control', 'placeholder' => 'contoh: 10/2018']) }}
                                    @if ($errors->has('IM_MEETINGNUM'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IM_MEETINGNUM') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IM_MEETINGDATE') ? ' has-error' : '' }}">
                                {{ Form::label('IM_MEETINGDATE', 'Tarikh Mesyuarat JMA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-4">
                                    {{ Form::text('IM_MEETINGDATE', '', ['class' => 'form-control']) }}
                                    @if ($errors->has('IM_MEETINGDATE'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IM_MEETINGDATE') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IM_CHAIRPERSON') ? ' has-error' : '' }}">
                                {{ Form::label('IM_CHAIRPERSON', 'Pengerusi Mesyuarat JMA', ['class' => 'col-lg-3 control-label required']) }}
                                <div class="col-lg-4">
                                    {{ Form::text('IM_CHAIRPERSON', '', ['class' => 'form-control']) }}
                                    @if ($errors->has('IM_CHAIRPERSON'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IM_CHAIRPERSON') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('IM_STATUS') ? ' has-error' : '' }}">
                                {{ Form::label('IM_STATUS', 'Status Mesyuarat JMA', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-4">
                                    <div class="radio">
                                        <input type="radio" name="IM_STATUS" id="radio1" value="1" {{ old('IM_STATUS') == '1'? 'checked':'' }}>
                                        <label for="radio1">
                                            Selesai
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <input type="radio" name="IM_STATUS" id="radio2" value="2" {{ old('IM_STATUS') == '2'? 'checked':'' }}>
                                        <label for="radio2">
                                            Tangguh
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <input type="radio" name="IM_STATUS" id="radio3" value="0" {{ old('IM_STATUS') == '0'? 'checked':'' }}>
                                        <label for="radio3">
                                            Batal
                                        </label>
                                    </div>
                                    @if ($errors->has('IM_STATUS'))
                                        <span class="help-block">
                                            <strong>
                                                {{ $errors->first('IM_STATUS') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="text-center">
                                    <a class="btn btn-warning" href="{{ url('integritimeeting') }}">
                                        <i class="fa fa-home"></i> Kembali
                                    </a>
                                    {{ Form::button('Simpan'.' <i class="fa fa-save"></i>', 
                                    ['type' => 'submit', 'class' => 'btn btn-success']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Start -->
    @include('aduan.admin-case.usersearchmodal')
    <!-- Modal End -->

@stop

@section('script_datatable')
    <script type="text/javascript">
        $(function () {
            
        })

        $(document).ready(function () {
            // $('#rcvdt .input-daterange').datepicker({
            //     format: 'dd-mm-yyyy',
            //     // format: 'yyyy-mm-dd',
            //     todayHighlight: true,
            //     keyboardNavigation: false,
            //     forceParse: false,
            //     autoclose: true
            // })
            $('#IM_MEETINGDATE').datepicker({
                format: 'dd-mm-yyyy',
                todayBtn: "linked",
                todayHighlight: true,
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });
        })
    </script>
@stop