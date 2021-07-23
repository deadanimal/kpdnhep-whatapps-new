@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Tambah Templat Surat</h2>
                <hr>
                {!! Form::open(['url' => ['/letter/store'], 'class'=>'form-horizontal', 'method' => 'post']) !!}
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
                        {{ Form::label('header', 'Kepala Surat', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('header', old('header'), array_merge(['id' => 'header', 'class' => 'form-control input-sm'])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('body', 'Isi Surat', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('body', old('body'), array_merge(['id' => 'body', 'class' => 'form-control input-sm'])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('footer', 'Kaki Surat', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('footer', old('footer'), array_merge(['id' => 'footer', 'class' => 'form-control input-sm'])) }}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('letter_type') ? ' has-error' : '' }}">
                        {{ Form::label('letter_type', 'Jenis Surat', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            <!--{{ Form::text('letter_type', '', array_merge(['class' => 'form-control input-sm'])) }}-->
<!--                            <div class="radio i-checks">
                                {{ Form::radio('letter_type', '01') }}
                                {{ Form::label('Surat Penerimaan') }}
                            </div>
                            <div class="radio i-checks">
                                {{ Form::radio('letter_type', '02') }}
                                {{ Form::label('Surat Penugasan') }}
                            </div>-->
                            {{ Form::select('letter_type', Ref::GetList('143', true), null, ['class' => 'form-control input-sm', 'id' => 'letter_type']) }}
                            @if ($errors->has('letter_type'))
                                <span class="help-block"><strong>{{ $errors->first('letter_type') }}</strong></span>
                            @endif
                        </div>
                    </div>
<!--                    <div class="form-group">
                        {{-- Form::label('letter_cat', 'Kategori Surat', ['class' => 'col-sm-2 control-label']) --}}
                        <div class="col-sm-10">
                            {{-- Form::text('letter_cat', '', array_merge(['class' => 'form-control input-sm'])) --}}
                        </div>
                    </div>-->
                    <div class="form-group {{ $errors->has('letter_code') ? ' has-error' : '' }}">
                        {{ Form::label('letter_code', 'Kod Surat', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            <!--{{-- Form::text('letter_code', old('letter_code'), array_merge(['class' => 'form-control input-sm', 'id' => 'letter_code'])) --}}-->
                            {{ Form::select('letter_code', Ref::GetList('292', true), old('letter_code'), array_merge(['class' => 'form-control input-sm', 'id' => 'letter_code'])) }}
                            @if ($errors->has('letter_code'))
                                <span class="help-block"><strong>{{ $errors->first('letter_code') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('status', 'Status Surat', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            <div class="">
                                <!--{{ Form::radio('status', '1', true) }} Aktif-->
                                {{ Form::radio('status', '1', true, ['class' => 'radio-custom', 'id' => 'status1']) }}
                                {{ Form::label('status1', 'AKTIF', array('class' => 'radio-custom-label')) }}
                            </div>
                            <div class="">
                                <!--{{ Form::radio('status', '0') }} Tidak Aktif-->
                                {{ Form::radio('status', '0', false, ['class' => 'radio-custom', 'id' => 'status0']) }}
                                {{ Form::label('status0', 'TIDAK AKTIF', array('class' => 'radio-custom-label')) }}
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="col-sm-2"></div>-->
                <div class="form-group" align="center">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ Form::submit('Tambah', array('class' => 'btn btn-success btn-sm')) }}
                        {{ link_to('letter', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
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

<!--<iframe src="http://eaduan.kpdnkk.gov.my/eaduan/main.php?lang=2" width="500" height="500">
</iframe>-->

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
