@extends('layouts.main')
<?php 
use App\Ref;
use App\Menu;
?>
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h3>Tambah Peranan</h3>
                <hr>
                {!! Form::open(['url' => '/role/store','class'=>'form-horizontal', 'method' => 'post']) !!}
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('role_code') ? ' has-error' : '' }}">
                    <div class="form-group">
                        {{ Form::label('role_code', 'Peranan', ['class' => 'col-md-4 control-label required']) }}
                        <div class="col-sm-6">
                            {{ Form::select('role_code', Ref::GetList('152', true), null, ['class' => 'form-control input-sm', 'id' => 'role_code']) }}
                            @if ($errors->has('role_code'))
                            <span class="help-block"><strong>{{ $errors->first('role_code') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('role_code') ? ' has-error' : '' }}">
                    <div class="form-group">
                        {{ Form::label('menu_id', 'Menu', ['class' => 'col-md-4 control-label required']) }}
                        <div class="col-sm-6">
                            {{ Form::select('menu_id', Menu::MenuList(true), null, ['class' => 'form-control input-sm', 'id' => 'menu_id']) }}
                            @if ($errors->has('menu_id'))
                            <span class="help-block"><strong>{{ $errors->first('menu_id') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group" align="center">
                    {{ Form::submit('Tambah', array('class' => 'btn btn-success btn-sm')) }}
                    <a href="{{ url('/role') }}" class="btn btn-default btn-sm"> Kembali </a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection