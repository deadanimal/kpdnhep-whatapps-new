@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Tambah Templat Emel</h2>
                <hr>
                {!! Form::open(['url' => ['/email/store'], 'class'=>'form-horizontal', 'method' => 'post']) !!}
                {{ csrf_field() }}
                <!--<div class="col-sm-2"></div>-->
                <div class="col-sm-12">
                    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}" >
                        {{ Form::label('title', 'Tajuk', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            {{ Form::text('title', old('title'), array_merge(['class' => 'form-control input-sm'])) }}
                            @if ($errors->has('title'))
                                <span class="help-block"><strong>{{ $errors->first('title') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('header', 'Kepala Emel', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('header', old('header'), array_merge(['id' => 'header', 'class' => 'form-control input-sm'])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('body', 'Isi Emel', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('body', old('body'), array_merge(['id' => 'body', 'class' => 'form-control input-sm'])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('footer', 'Kaki Emel', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('footer', old('footer'), array_merge(['id' => 'footer', 'class' => 'form-control input-sm'])) }}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('email_type') ? ' has-error' : '' }}">
                        {{ Form::label('email_type', 'Jenis Emel', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            {{ Form::select('email_type', Ref::GetList('149', true), '', ['class' => 'form-control input-sm', 'id' => 'email_type']) }}
                            @if ($errors->has('email_type'))
                                <span class="help-block"><strong>{{ $errors->first('email_type') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('email_code') ? ' has-error' : '' }}">
                        {{ Form::label('email_code', 'Kod Emel', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            {{ Form::select('email_code', Ref::GetList('292', true), '', array_merge(['class' => 'form-control input-sm', 'id' => 'email_code'])) }}
                            @if ($errors->has('email_code'))
                                <span class="help-block"><strong>{{ $errors->first('email_code') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('status', 'Status Emel', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            <div class="radio radio-success">
                                <input id="status1" type="radio" value="1" name="status" checked>
                                <label for="status1"> AKTIF </label>
                            </div>
                            <div class="radio radio-success">
                                <input id="status2" type="radio" value="0" name="status">
                                <label for="status2"> TIDAK AKTIF </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="col-sm-2"></div>-->
                <div class="form-group" align="center">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ Form::submit('Tambah', array('class' => 'btn btn-success btn-sm')) }}
                        {{ link_to('email', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                </div>
<!--                @if (count($errors))
                    <div class="form-group">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif-->
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
    <script>
        var ckview = document.getElementById("header");
        CKEDITOR.replace(ckview,{
            language:'ms-gb'
        });
        var ckview = document.getElementById("body");
        CKEDITOR.replace(ckview,{
            language:'ms-gb'
        });
        var ckview = document.getElementById("footer");
        CKEDITOR.replace(ckview,{
            language:'ms-gb'
        });
    </script>
@stop
