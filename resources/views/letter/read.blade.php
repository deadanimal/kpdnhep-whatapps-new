@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Lihat Templat Surat</h2>
                <hr>
                <!--{!! Form::open(['url' => '/letter/edit/'.$id, 'class'=>'form-horizontal', 'method' => 'get']) !!}-->
                {!! Form::open(['url' => ['/letter/edit',$id], 'class'=>'form-horizontal', 'method' => 'get']) !!}
                {{ csrf_field() }}
                <!--<div class="col-sm-2"></div>-->
                <!--<div class="col-sm-8">-->
                    <div class="form-group">
                        {{ Form::label('Tajuk Surat', null, ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::text('title', $mLetter->title, array_merge(['class' => 'form-control input-sm', 'readonly' => true])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('Kepala Surat', null, ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('header', $mLetter->header, array_merge(['id' => 'header', 'class' => 'form-control input-sm', 'readonly' => true])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('Isi Surat', null, ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('body', $mLetter->body, array_merge(['id' => 'body', 'class' => 'form-control input-sm', 'readonly' => true])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('Hujung Surat', null, ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('footer', $mLetter->footer, array_merge(['id' => 'footer', 'class' => 'form-control input-sm', 'readonly' => true])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('Jenis Surat', null, ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            <!--{{ Form::text('letter_type', $mLetter->letter_type, array_merge(['class' => 'form-control input-sm', 'readonly' => true])) }}-->
<!--                            @if ($mLetter->letter_type == '01')
                                <div class="radio i-checks">
                                    {{ Form::radio('letter_type', '01', true, ['checked' => 'checked', 'class' => 'radio-custom', 'id' => 'letter-type-01', 'disabled' => true]) }}
                                    {{ Form::label('letter-type-01', 'Surat Penerimaan', array('class' => 'radio-custom')) }}
                                </div>
                                <div class="radio i-checks">
                                    {{ Form::radio('letter_type', '02', false, ['class' => 'radio-custom', 'id' => 'letter-type-02', 'disabled' => true]) }}
                                    {{ Form::label('letter-type-02', 'Surat Penugasan', array('class' => 'radio-custom-label')) }}
                                </div>
                            @elseif ($mLetter->letter_type == '02')
                                <div class="radio i-checks">
                                    {{ Form::radio('letter_type', '01', false, ['class' => 'radio-custom', 'id' => 'letter-type-01', 'disabled' => true]) }}
                                    {{ Form::label('letter-type-01', 'Surat Penerimaan', array('class' => 'radio-custom-label')) }}
                                </div>
                                <div class="radio i-checks">
                                    {{ Form::radio('letter_type', '02', true, ['checked' => 'checked', 'class' => 'radio-custom', 'id' => 'letter-type-02', 'disabled' => true]) }}
                                    {{ Form::label('letter-type-02', 'Surat Penugasan', array('class' => 'radio-custom-label')) }}
                                </div>
                            @endif-->
                            {{ Form::select('letter_type', Ref::GetList('143', true), $mLetter->letter_type, ['class' => 'form-control input-sm', 'id' => 'letter_type', 'disabled' => true]) }}
                            @if ($errors->has('letter_type'))
                                <span class="help-block"><strong>{{ $errors->first('letter_type') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {{-- Form::label('Kategori Surat', null, ['class' => 'col-sm-2 control-label']) --}}
                        <div class="col-sm-10">
                            {{-- Form::number('letter_cat', $mLetter->letter_cat, array_merge(['class' => 'form-control input-sm', 'readonly' => true])) --}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('Kod Surat', null, ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::text('letter_code', $mLetter->letter_code, array_merge(['class' => 'form-control input-sm', 'readonly' => true])) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('Status Surat', null, ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            <!--{{ Form::text('status', $mLetter->status, array_merge(['class' => 'form-control input-sm', 'readonly' => true])) }}-->
                            @if ($mLetter->status == '1')
                                <div class="radio i-checks">
                                    {{ Form::radio('status', '1', true, ['checked' => 'checked', 'class' => 'radio-custom', 'id' => 'status-1', 'disabled' => true]) }}
                                    {{ Form::label('status-1', 'AKTIF', array('class' => 'radio-custom')) }}
                                </div>
                                <div class="radio i-checks">
                                    {{ Form::radio('status', '0', false, ['class' => 'radio-custom', 'id' => 'status-0', 'disabled' => true]) }}
                                    {{ Form::label('status-0', 'TIDAK AKTIF', array('class' => 'radio-custom-label')) }}
                                </div>
                            @elseif ($mLetter->status == '0')
                                <div class="radio i-checks">
                                    {{ Form::radio('status', '1', false, ['class' => 'radio-custom', 'id' => 'status-1', 'disabled' => true]) }}
                                    {{ Form::label('status-1', 'AKTIF', array('class' => 'radio-custom-label')) }}
                                </div>
                                <div class="radio i-checks">
                                    {{ Form::radio('status', '0', true, ['checked' => 'checked', 'class' => 'radio-custom', 'id' => 'status-0', 'disabled' => true]) }}
                                    {{ Form::label('status-0', 'TIDAK AKTIF', array('class' => 'radio-custom-label')) }}
                                </div>
                            @endif
                        </div>
                    </div>
                <!--</div>-->
                <!--<div class="col-sm-2"></div>-->
                <div class="form-group" align="center">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ link_to_action('LetterController@edit', $title = 'Kemaskini', $parameters = [$id], $attributes = ['class' => 'btn btn-success btn-sm']) }}
                        {{ link_to_action('LetterController@pdf', $title = 'Cetak PDF', $parameters = [$id], $attributes = ['class' => 'btn btn-info btn-sm', 'target'=>'_blank']) }}
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