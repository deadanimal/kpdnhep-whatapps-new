@extends('layouts.main')
<?php

use App\Menu;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h1>Tambah Menu</h1>
                <div class="ibox-content">
                    <hr>
                    {!! Form::open(['url' => '/menu','class'=>'form-horizontal', 'method' => 'post']) !!}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('Nama Menu', null, ['class' => 'col-md-4 control-label required']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('menu_txt', '', array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Lokasi Menu', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('menu_loc', '', array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Nama Route', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('route_name', '', array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Modul', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::checkbox('module_ind', null, '', array_merge(['id' => 'module_ind']))}}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Menu Utama', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('menu_parent_id', Menu::MenuList(true), null, ['class' => 'form-control input-sm', 'id' => 'menu_parent_id']) }}
                                    @if ($errors->has('menu_parent_id'))
                                    <span class="help-block"><strong>{{ $errors->first('menu_parent_id') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('Susunan', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('sort', '', array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Catatan', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('remarks', '', array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Nama Ikon', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('icon_name', '', array_merge(['class' => 'form-control input-sm'])) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Aktif?', null, ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::checkbox('hide_ind', null, '', array_merge(['id' => 'hide_ind']))}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            {{ Form::submit('Tambah', array('class' => 'btn btn-success btn-sm')) }}
                            <a href="{{ url('/menu') }}" class="btn btn-default btn-sm"> Kembali </a>
                        </div>  
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
@stop

@section('script_datatable')
<script type="text/javascript">
    // $(function () {

    //     $('#module_ind').on('change', function(e){
    //         alert($('#module_ind').val());
    //     });
        
    // });
</script>
@stop
