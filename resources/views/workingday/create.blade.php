@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h3>Hari Bekerja</h3>
                {!! Form::open(['url' => 'workingday/store','class'=>'form-horizontal', 'method' => 'post']) !!}
                {{ csrf_field() }}
                <div class="col-sm-2"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <?php echo Form::label('Negeri',NULL, ['class' => 'col-sm-2 control-label']); ?>
                        <div class="col-sm-10">
<!--                           {{ Form::select('state_code',array
                                      ( '0' => 'Pilih Negeri',
                                       '1' => 'Johor', 
                                       '2' => 'Melaka',
                                       '3' => 'Negeri Sembilan',
                                       '4' => 'Selangor',
                                       '5' => 'Perak',
                                       '6' => 'Pulau Pinang',
                                       '7' => 'Kedah',
                                       '8' => 'Perlis',
                                       '9' => 'Pahang',
                                       '10' => 'Terengganu',
                                       '11' => 'Kelantan',
                                       '12' => 'Sarawak',
                                       '13' => 'Sabah',
                                       '14' => 'WP Kuala Lumpur',
                                       '15' => 'WP Putrajaya',
                                       '16' => 'WP Labuan'), '',array('class' => 'form-control'))}}-->
                                       
                            {{ Form::select('state_code', ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_code']) }}
                            @if ($errors->has('state_code'))
                                <span class="help-block"><strong>{{ $errors->first('state_code') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo Form::label('Hari Berkerja',NULL, ['class' => 'col-sm-2 control-label']); ?>
                        <div class="col-sm-10">
<!--                                     {{ Form::select('work_day',array
                              ('0' => 'Pilih Hari',
                               '1' => 'Ahad', 
                               '2' => 'Isnin',
                               '3' => 'Selasa',
                               '4' => 'Rabu',
                               '5' => 'Khamis',
                               '6' => 'Jumaat',
                               '7' => 'Sabtu'),'',array('class' => 'form-control'))}}-->

                            {{ Form::select('work_day', Ref::GetList('156', true), null, ['class' => 'form-control input-sm', 'id' => 'work_day']) }}
                            @if ($errors->has('work_day'))
                                <span class="help-block"><strong>{{ $errors->first('work_day') }}</strong></span>
                            @endif
                        </div>
                    </div>
<!--                    <div class="form-group">
                                 <?php // echo Form::label('Sepenuh Hari/Separa Hari',NULL, ['class' => 'col-sm-2 control-label']); ?>
                                <div class="col-sm-10">
                                     {{ Form::select('work_code',array
                                      ('000' => '--Pilih Hari--',
                                       '011' => 'Full Day', 
                                       '012' => 'Half Day',
                                       '013' => 'Off Day',
                                     ),'',array('class' => 'form-control'))}}
                                     {{-- Form::select('work_code', Ref::GetList('146', true), null, ['class' => 'form-control input-sm', 'id' => 'work_code']) --}}
                                    @if ($errors->has('work_code'))
                                        <span class="help-block"><strong>{{ $errors->first('work_code') }}</strong></span>
                                    @endif
                                </div>
                            </div>-->
                                    
                        
                </div>
                <div class="col-sm-2"><br></div>
                <div class="form-group" align="center">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?php echo Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']); ?>
                        <a href="{{ url('workingday')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
                    </div>
                </div>
                <!--</div>-->
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
