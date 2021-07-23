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
            <h2>Kemaskini Cuti</h2>
            {{-- date('d-m-Y h:i A') --}}
                {!! Form::open(['url' => ['/holiday/update',$id], 'class'=>'form-horizontal']) !!}
                {{ csrf_field() }}
                {{ method_field('PUT') }}
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('holiday_name') ? ' has-error' : '' }}">
                    {{ Form::label('holiday_name', 'Nama Cuti', ['class' => 'col-sm-4 control-label control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::text('holiday_name', old('holiday_name', $mHoliday->holiday_name), ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('holiday_name'))
                        <span class="help-block"><strong>{{ $errors->first('holiday_name') }}</strong></span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group{{ $errors->has('holiday_date') ? ' has-error' : '' }}">
                    {{ Form::label('holiday_date', 'Tarikh Cuti', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8" id="data_6">
                        <div class="input-daterange input-group" id="datepicker">
                        {{ Form::text('holiday_date', old('holiday_date', $mHoliday->holiday_date), ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('holiday_date'))
                        <span class="help-block"><strong>{{ $errors->first('holiday_date') }}</strong></span>
                        @endif
                    </div>
                </div>
                </div>
                
                 <div class="form-group{{ $errors->has('state_code') ? ' has-error' : '' }}">
                    {{ Form::label('state_code', 'Negeri', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::select('state_code', Ref::GetList('17', true), old('state_code', $mHoliday->state_code), ['class' => 'form-control input-sm', 'id' => 'state_code']) }}
                        @if ($errors->has('state_code'))
                        <span class="help-block"><strong>{{ $errors->first('state_code') }}</strong></span>
                        @endif
                    </div>
                </div>
                
                
                <!-- <div class="form-group{{ $errors->has('work_code') ? ' has-error' : '' }}">
                    <?php // Form::label('work_code', 'Full Day/Half Day', ['class' => 'col-sm-4 control-label']) ?>
                    <div class="col-sm-8">
                         <?php // Form::select('work_code', Ref::GetList('146', true), $mHoliday->work_code, ['class' => 'form-control input-sm', 'id' => 'work_code']) ?>
                        @if ($errors->has('password'))
                        <span class="help-block"><strong>{{ $errors->first('work_code') }}</strong></span>
                        @endif
                    </div>
                </div> -->
                
                    <div class="form-group{{ $errors->has('repeat_yearly') ? ' has-error' : '' }}">
                    {{ Form::label('repeat_yearly', 'Berulang Setiap Tahun?', ['class' => 'col-sm-4 control-label required']) }}
                    <!-- <div class="col-sm-8">
                        <div class="radio i-checks"><label> <input type="radio" value="1" name="repeat_yearly" {{ $mHoliday->repeat_yearly == 1? 'checked':'' }}> <i></i> YA </label></div>
                        <div class="radio i-checks"><label> <input type="radio" value="2" name="repeat_yearly" {{ $mHoliday->repeat_yearly == 2? 'checked':'' }}> <i></i> TIDAK </label></div>
                    </div> -->

                    <div class="col-sm-6">
                    <div class="radio radio-success radio-inline">
                        <input id="repeat_yearly1" type="radio" name="repeat_yearly" value="1" {{ $mHoliday->repeat_yearly == 1? 'checked':'' }}>
                        <label for="repeat_yearly1"> Ya </label>
                    </div>
                    <i title="Pilih Ya jika tarikh cuti adalah sama setiap tahun" class="fa fa-info-circle" style="font-size:20px"></i>
                    <div style="padding-left:50px" class="radio radio-danger radio-inline">
                        <input id="repeat_yearly2" type="radio" name="repeat_yearly" value="2" {{ $mHoliday->repeat_yearly == 2? 'checked':'' }}>
                        <label for="repeat_yearly2"> Tidak </label>
                    </div>
                    <i title="Pilih Tidak jika tarikh cuti adalah TIDAK sama setiap tahun" class="fa fa-info-circle" style="font-size:20px"></i>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-12" align="center">
                {{ Form::submit('Kemaskini', array('class' => 'btn btn-success btn-sm')) }}
                {{ link_to('holiday', $title = 'Kembali', $attributes = ['class' => 'btn btn-default btn-sm'], $secure = null) }}
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