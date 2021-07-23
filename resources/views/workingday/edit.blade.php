@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h3>Kemaskini Hari Bekerja</h3>
                 {!! Form::open(['url' => ['/workingday/update',$id], 'class'=>'form-horizontal']) !!}
                  {{ csrf_field() }}
                  {{ method_field('PUT') }}
                <div class="col-sm-2"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <?php echo Form::label('Negeri',NULL, ['class' => 'col-sm-2 control-label']); ?>
                        <div class="col-sm-10">
                                          {{ Form::select('state_code', Ref::GetList('17', true),$mWd->state_code, ['class' => 'form-control input-sm', 'id' => 'state_code']) }}
                            @if ($errors->has('state_code'))
                                <span class="help-block"><strong>{{ $errors->first('state_code') }}</strong></span>
                            @endif
                        </div>
                    </div>
                       
                            <div class="form-group">
                                 <?php echo Form::label('Hari Berkerja',NULL, ['class' => 'col-sm-2 control-label']); ?>
                                <div class="col-sm-10">
                                        {{ Form::select('work_day', Ref::GetList('156', true), $mWd->work_day, ['class' => 'form-control input-sm', 'id' => 'work_day']) }}
                                        @if ($errors->has('work_day'))
                                            <span class="help-block"><strong>{{ $errors->first('work_day') }}</strong></span>
                                        @endif
                                </div>
                            </div>
                    <div class="form-group">
                                 <?php echo Form::label('Full Day/Half Day',NULL, ['class' => 'col-sm-2 control-label']); ?>
                                <div class="col-sm-10">
                                     {{ Form::select('work_code', Ref::GetList('146', true), $mWd->work_code, ['class' => 'form-control input-sm', 'id' => 'work_code']) }}
                                    @if ($errors->has('work_code'))
                                        <span class="help-block"><strong>{{ $errors->first('work_code') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                                   
                        
                    </div>
                    <div class="col-sm-2"><br></div>
                     <div class="form-group" align="center">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ Form::submit('Kemaskini', array('class' => 'btn btn-success btn-sm')) }}
                        {{ link_to('workingday', $title = 'Kembali', $attributes = ['class' => 'btn btn-default btn-sm'], $secure = null) }}
                    </div>
                </div>
                @if (count($errors))
                    <div class="form-group">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            
                {!! Form::close() !!}
               
            </div>
        </div>
    </div>
</div>
@endsection


