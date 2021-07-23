@extends('layouts.main')
<?php

use App\Ref;
use App\Holiday;
?>
@section('content')
<div class="ibox-content">
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Tambah Cuti Baru</h2>
            {!! Form::open(['url' => 'holiday/store', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
            {{ csrf_field() }}
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('holiday_name') ? ' has-error' : '' }}">
                    {{ Form::label('holiday_name', 'Nama Cuti', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::text('holiday_name', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('holiday_name'))
                        <span class="help-block"><strong>{{ $errors->first('holiday_name') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('holiday_date') ? ' has-error' : '' }}" id="data_6">
                    {{ Form::label('holiday_date', 'Tarikh Cuti', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-4">
                        <div class="input-daterange input-group" id="datepicker">
                            {{ Form::text('holiday_date', '', ['class' => 'form-control input-sm', 'id' => 'holiday_date']) }}
                            @if ($errors->has('holiday_date'))
                        <span class="help-block"><strong>{{ $errors->first('holiday_date') }}</strong></span>
                        @endif
                        </div>
                    </div>
                </div>

<!--                <div class="form-group">
                    <?php // echo Form::label('Full Day/Half Day', NULL, ['class' => 'col-sm-4 control-label required']); ?>
                    <div class="col-sm-8">
                        {{ Form::select('work_code', Ref::GetList('146', true), null, ['class' => 'form-control input-sm', 'id' => 'work_code']) }}
                        @if ($errors->has('work_code'))
                        <span class="help-block"><strong>{{ $errors->first('work_code') }}</strong></span>
                        @endif
                    </div>
                </div>-->

                <div class="form-group{{ $errors->has('state_code') ? ' has-error' : '' }}">
                    {{ Form::label('Negeri', null, ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">

                        {{ Form::select('state_code', Holiday::GetStateList(), '', ['class' => 'form-control input-sm', 'id' => 'state_code']) }}
                        @if ($errors->has('state_code'))
                        <span class="help-block"><strong>{{ $errors->first('state_code') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('repeat_yearly') ? ' has-error' : '' }}">
                    {{ Form::label('repeat_yearly', 'Berulang Setiap Tahun?', ['class' => 'col-sm-4 control-label required']) }}
                    <!-- <div class="col-sm-6">
                        <div class="radio checks"><label> <input type="radio" value="1" name="repeat_yearly" checked=""> <i></i> Ya </label></div>
                        <div class="radio checks"><label> <input type="radio" value="2" name="repeat_yearly"> <i></i> Tidak </label></div>
                        @if ($errors->has('repeat_yearly holiday'))
                        <span class="help-block"><strong>{{-- $errors->first('repeat_yearly') --}}</strong></span>
                        @endif
                    </div> -->

                    <div class="col-sm-6">
                    <div class="radio radio-success radio-inline">
                        <input id="repeat_yearly1" type="radio" name="repeat_yearly" value="1">
                        <label for="repeat_yearly1"> Ya </label>
                    </div>
                    <i title="Pilih Ya jika tarikh cuti adalah sama setiap tahun" class="fa fa-info-circle" style="font-size:20px"></i>
                    <div style="padding-left:50px" class="radio radio-danger radio-inline">
                        <input id="repeat_yearly2" type="radio" name="repeat_yearly" value="2">
                        <label for="repeat_yearly2"> Tidak </label>
                    </div>
                    <i title="Pilih Tidak jika tarikh cuti adalah TIDAK sama setiap tahun" class="fa fa-info-circle" style="font-size:20px"></i>
                    @if ($errors->has('repeat_yearly'))
                    <span class="help-block"><strong>{{ $errors->first('repeat_yearly') }}</strong></span>
                    @endif
                    </div>

                </div>

            </div>
            <div class="form-group col-sm-12" align="center">
                {{ Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']) }}
                <a href="{{ url('holiday')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">

    $(function () {
        $('#holiday0').on('click', function (e) {
            $('#ya').show();
        });
        $('#holiday1').on('click', function (e) {
            $('#tidak').hide();
        });
    });
    
     $('#data_6 .input-daterange').datepicker({
//        format: 'yyyy-mm-dd',
         format: 'dd-mm-yyyy',
//        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
</script>
@stop