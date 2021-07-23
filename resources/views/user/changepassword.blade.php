@extends('layouts.main')
<?php

use App\User;
?>

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Kemaskini Kata Laluan</h2>
            <div class="ibox-content">
                <!--<form method="POST" id="search-form" class="form-horizontal">-->
                    {!! Form::open(['url' => ['user/updatepassword', $mUser->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                    {{ csrf_field() }}{{ method_field('PATCH') }}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('username', 'No.Kad Pengenalan / Passport', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('username', $mUser->username, ['class' => 'form-control input-sm', 'disabled' => '']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('name', 'Nama', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('name', $mUser->name, ['class' => 'form-control input-sm', 'disabled' => '']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('passwordOld', 'Kata Laluan Lama', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::password('passwordOld', ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('passwordOld'))
                                        <span class="help-block"><strong>{{ $errors->first('passwordOld') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('passwordNew', 'Kata Laluan Baru', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::password('passwordNew', ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('passwordNew'))
                                        <span class="help-block"><strong>{{ $errors->first('passwordNew') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('passwordRepeat', 'Ulang Kata Laluan Baru', ['class' => 'col-sm-4 control-label required']) }}
                                    <div class="col-sm-8">
                                        {{ Form::password('passwordRepeat', ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('passwordRepeat'))
                                        <span class="help-block"><strong>{{ $errors->first('passwordRepeat') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        {{ Form::submit('Kemaskini', ['class' => 'btn btn-success btn-sm']) }}
                        <!--<a class="btn btn-default btn-sm" href="{{-- route('publicuser') --}}">Kembali</a>-->
                    </div>
                <!--</form>-->
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

