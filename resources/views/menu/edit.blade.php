@extends('layouts.main')
<?php use App\Menu;?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h1>Kemaskini Menu</h1>
                <div class="ibox-content">
                    <hr>
                    {!! Form::open(['url' => '/menu/update/'.$menu_id,'class'=>'form-horizontal', 'method' => 'PUT']) !!}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('Nama Menu', null, ['class' => 'col-md-4 control-label required']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('menu_txt', $mMenu->menu_txt, array('class' => 'form-control')) }}
                                    @if ($errors->has('menu_txt'))
                                    <span class="help-block"><strong>{{ $errors->first('menu_txt') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Lokasi Menu', null, ['class' => 'col-md-4 control-label required']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('menu_loc', $mMenu->menu_loc, array_merge(['class' => 'form-control input-sm'])) }}
                                    @if ($errors->has('menu_loc'))
                                    <span class="help-block"><strong>{{ $errors->first('menu_loc') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Nama Route', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('route_name', $mMenu->route_name, array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Modul', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::checkbox('module_ind', $mMenu->module_ind, $mMenu->module_ind, array_merge(['id' => 'menu_parent_id']))}}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Menu Utama', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('menu_parent_id', Menu::MenuList(true), $mMenu->menu_parent_id, ['class' => 'form-control input-sm', 'id' => 'menu_parent_id']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('Susunan', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('sort', $mMenu->sort, array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Catatan', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('remarks', $mMenu->remarks, array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Nama Ikon', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('icon_name', $mMenu->icon_name, array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Aktif?', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::checkbox('hide_ind', $mMenu->hide_ind, $mMenu->hide_ind, array_merge(['id' => 'hide_ind']))}}
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            {{ Form::submit('Simpan', array('class' => 'btn btn-success btn-sm')) }}
                            <a class="btn btn-default btn-sm" href="{{ url('menu') }}">Kembali</a>
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

</div>
@endsection
