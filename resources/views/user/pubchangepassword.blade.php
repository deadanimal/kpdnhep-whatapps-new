@extends('layouts.main_public')
<?php

use App\User;
?>

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('public-profile.profail.passwordupdate')</div>
                    <div class="panel-body">
                        {!! Form::open(['url' => ['user/pubupdatepassword', $mUser->id], 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        {{ csrf_field() }}{{ method_field('PATCH') }}
                        <div class="form-group">
                            <label for="username" class="col-sm-4 control-label">@lang('public-profile.profail.icnew')</label>
                            <div class="col-sm-8">
                                {{ Form::text('username', $mUser->username, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icnew" class="col-sm-4 control-label">@lang('public-profile.profail.name')</label>
                            <div class="col-sm-8">
                                {{ Form::text('name', $mUser->name, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('passwordOld') ? ' has-error' : '' }}">
                            <label for="passwordOld" class="col-sm-4 control-label required">@lang('public-profile.profail.passwordOld')</label>
                            <div class="col-sm-8">
                                {{ Form::password('passwordOld', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('passwordOld'))
                                <span class="help-block"><strong>{{ $errors->first('passwordOld') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('passwordNew') ? ' has-error' : '' }}">
                            <label for="passwordNew" class="col-sm-4 control-label required">@lang('public-profile.profail.passwordNew')</label>
                            <div class="col-sm-8">
                                {{ Form::password('passwordNew', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('passwordNew'))
                                <span class="help-block"><strong>{{ $errors->first('passwordNew') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('passwordRepeat') ? ' has-error' : '' }}">
                            <label for="passwordRepeat" class="col-sm-4 control-label required">@lang('public-profile.profail.passwordRepeat')</label>
                            <div class="col-sm-8">
                                {{ Form::password('passwordRepeat', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('passwordRepeat'))
                                <span class="help-block"><strong>{{ $errors->first('passwordRepeat') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" align="center">
                        <button class="btn btn-success btn-sm" type="submit">@lang('button.update')</button>
                        <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}">@lang('button.back')</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

