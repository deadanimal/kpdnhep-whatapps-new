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
                <h1>Kemaskini Peranan</h1>
                <div class="ibox-content">
                    <hr>
                    {!! Form::open(['url' => '/role/update/'.$role_code.'/'.$menu_id,  'class'=>'form-horizontal', 'method' => 'PUT']) !!}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group">
                            {{ Form::label('Peranan', null, ['class' => 'col-md-4 control-label required']) }}
                            <div class="col-sm-6">
                                {{ Form::select('role_code', Ref::GetList('152', $mRole->role_code), $mRole->role_code, ['class' => 'form-control input-sm', 'id' => 'role_code']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('Menu', null, ['class' => 'col-md-4 control-label required']) }}
                            <div class="col-sm-6">
                                {{ Form::select('menu_id', Menu::MenuList($mRole->menu_id), $mRole->menu_id, ['class' => 'form-control input-sm', 'id' => 'menu_id']) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        {{ Form::submit('Kemaskini', array('class' => 'btn btn-success btn-sm')) }}
                        <a href="{{ url('/role') }}" class="btn btn-default btn-sm"> Kembali </a>
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

</div>
@endsection