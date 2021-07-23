@extends('layouts.main')
<?php
use App\MenuCms;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Kemaskini Menu CMS</h2>
                <div class="ibox-content">
                    {!! Form::open(['route' => ['menucms.update', $id], 'class'=>'form-horizontal']) !!}
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('menu_txt') ? ' has-error' : '' }}">
                                {{ Form::label('menu_txt', 'Nama Menu (BM)', ['class' => 'col-md-4 control-label required']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('menu_txt', old('menu_txt', $mMenuCms->menu_txt), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('menu_txt'))
                                        <span class="help-block"><strong>{{ $errors->first('menu_txt') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('menu_txt_en') ? ' has-error' : '' }}">
                                {{ Form::label('menu_txt_en', 'Nama Menu (BI)', ['class' => 'col-md-4 control-label required']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('menu_txt_en', old('menu_txt_en', $mMenuCms->menu_txt_en), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('menu_txt_en'))
                                        <span class="help-block"><strong>{{ $errors->first('menu_txt_en') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('menu_loc', 'Lokasi Menu', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('menu_loc', old('menu_loc', $mMenuCms->menu_loc), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('route_name', 'Nama Route', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('route_name', old('route_name', $mMenuCms->route_name), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('menu_parent_id', 'Menu Utama', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('menu_parent_id', MenuCms::MenuList(true), old('menu_parent_id', $mMenuCms->menu_parent_id), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('sort') ? ' has-error' : '' }}">
                                {{ Form::label('sort', 'Susunan', ['class' => 'col-md-4 control-label required']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('sort', old('sort', $mMenuCms->sort), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('sort'))
                                        <span class="help-block"><strong>{{ $errors->first('sort') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('remarks', 'Catatan', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('remarks', old('remarks', $mMenuCms->remarks), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('icon_name', 'Nama Ikon', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('icon_name', old('icon_name', $mMenuCms->icon_name), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('module_ind', 'Modul', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6" style="padding-top:7px !important;">
                                    {{ Form::checkbox('module_ind', $mMenuCms->module_ind, $mMenuCms->module_ind, []) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('hide_ind', 'Aktif?', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6" style="padding-top:7px !important;">
                                    {{ Form::checkbox('hide_ind', $mMenuCms->hide_ind, $mMenuCms->hide_ind, []) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('menu_cat', 'Kategori', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('menu_cat', [''=>'-- SILA PILIH --', '2'=>'Kepenggunaan', '3'=>'Integriti'], old('menu_cat', $mMenuCms->menu_cat), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            {{ Form::submit('Simpan', array('class' => 'btn btn-success btn-sm')) }}
                            <a class="btn btn-default btn-sm" href="{{ url('menucms') }}">Kembali</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
