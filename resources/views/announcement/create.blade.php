@extends('layouts.main')
<?php
    use App\MenuCms;
?>
@section('content')

<div class="row">
    <div class="ibox float-e-margins">
        <h2>Kemaskini Pengumuman</h2>
        <div class="ibox-content">
        {!! Form::open(['route' => ['announcement.store'], 'class'=>'form-horizontal']) !!}
            {{ csrf_field() }}{{ method_field('POST') }}
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group {{ $errors->has('title_my') ? ' has-error' : '' }}">
                        {{ Form::label('title_my', 'Tajuk', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::text('title_my', old('title_my'), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('title_my'))
                                <span class="help-block"><strong>{{ $errors->first('title_my') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content_my') ? ' has-error' : '' }}">
                        {{ Form::label('content_my', 'Keterangan', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textArea('content_my', old('content_my'), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('content_my'))
                                <span class="help-block"><strong>{{ $errors->first('content_my') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content_en') ? ' has-error' : '' }}">
                        {{ Form::label('content_en', 'Keterangan Inggeris', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textArea('content_en', old('content_en'), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('content_en'))
                                <span class="help-block"><strong>{{ $errors->first('content_en') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('hits') ? ' has-error' : '' }}">
                        {{ Form::label('hits', 'Kategori', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            <div class="col-sm-10">
                                <div class="alert alert-success">
                                    <div class="radio radio-success">
                                        <input id="success" type="radio" value="success" name="hits" checked>
                                        <label for="success"> Success </label>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <div class="radio radio-success">
                                        <input id="info" type="radio" value="info" name="hits">
                                        <label for="info"> Info </label>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <div class="radio radio-success">
                                        <input id="warning" type="radio" value="warning" name="hits">
                                        <label for="warning"> Warning </label>
                                    </div>
                                </div>
                                <div class="alert alert-danger">
                                    <div class="radio radio-success">
                                        <input id="danger" type="radio" value="danger" name="hits">
                                        <label for="danger"> Danger </label>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('title_my'))
                                <span class="help-block"><strong>{{ $errors->first('title_my') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) }}
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
                    <div class="form-group">
                        {{ Form::label('cat', 'Pilihan Paparan', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            <div class="radio radio-success">
                                <input id="cat7" type="radio" value="7" name="cat" checked>
                                <label for="cat7"> Pengguna Awam </label>
                            </div>
                            <div class="radio radio-success">
                                <input id="cat8" type="radio" value="8" name="cat">
                                <label for="cat8"> Pengguna Dalaman </label>
                            </div>
                            <div class="radio radio-success">
                                <input id="cat9" type="radio" value="9" name="cat">
                                <label for="cat9"> Pengguna Awam & Dalaman </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" align="center">
                        {{ Form::submit('Simpan', ['class' => 'btn btn-primary btn-sm']) }}
                        {{ link_to('announcement', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        </div>
    </div>
</div>
@stop