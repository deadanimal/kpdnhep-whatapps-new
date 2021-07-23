@extends('layouts.main')
<?php
    use App\MenuCms;
?>
@section('content')

<div class="row">
    <div class="ibox float-e-margins">
        <h2>Tambah Keterangan Portal CMS</h2>
        <div class="ibox-content">
        {!! Form::open(['route' => ['portalcms.storecontent',$cat], 'class'=>'form-horizontal', 'files'=>true]) !!}
            {{ csrf_field() }}{{ method_field('POST') }}
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group {{ $errors->has('title_my') ? ' has-error' : '' }}">
                        {{ Form::label('title_my', 'Tajuk (BM)', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            {{ Form::text('title_my', old('title_my'), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('title_my'))
                                <span class="help-block"><strong>{{ $errors->first('title_my') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('title_en') ? ' has-error' : '' }}">
                        {{ Form::label('title_en', 'Tajuk (BI)', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            {{ Form::text('title_en', old('title_en'), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('title_en'))
                                <span class="help-block"><strong>{{ $errors->first('title_en') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content_my') ? ' has-error' : '' }}">
                        {{ Form::label('content_my', 'Keterangan (BM)', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            {{ Form::textArea('content_my', old('content_my'), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('content_my'))
                                <span class="help-block"><strong>{{ $errors->first('content_my') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content_en') ? ' has-error' : '' }}">
                        {{ Form::label('content_en', 'Keterangan (BI)', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            {{ Form::textArea('content_en', old('content_en'), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('content_en'))
                                <span class="help-block"><strong>{{ $errors->first('content_en') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                        {{ Form::label('photo', 'Gambar', ['class' => 'col-sm-2 control-label required']) }}
                        <div class="col-sm-10">
                            {{ Form::file('photo') }}
                            <span style="color: red;">Format : jpg, jpeg, png.</span>
                            @if ($errors->has('photo'))
                                <span class="help-block"><strong>{{ $errors->first('photo') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('status', 'Status', ['class' => 'col-sm-2 control-label required']) }}
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
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" align="center">
                        {{ Form::submit('Tambah', ['class' => 'btn btn-primary btn-sm']) }}
                        {{ link_to('portalcms/list-content/'.$cat, 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        </div>
    </div>
</div>
@stop