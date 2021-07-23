@extends('layouts.main')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <h2>Tambah Templat Media Sosial</h2>
                        <hr>
                        {!! Form::open(['route' => 'feedback.template.store']) !!}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
                                            {{ Form::label('category', 'Kategori', ['class' => 'control-label required']) }}
                                            {{ Form::select('category', $categories, null, ['class' => 'form-control input-sm']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('code') ? ' has-error' : '' }}">
                                            {{ Form::label('code', 'Kod', ['class' => 'control-label required']) }}
                                            {{ Form::text('code', old('code'), array_merge(['class' => 'form-control input-sm'])) }}
                                            @if ($errors->has('code'))
                                                <span class="help-block"><strong>{{ $errors->first('code') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                    {{ Form::label('title', 'Tajuk', ['class' => 'control-label required']) }}
                                    {{ Form::text('title', old('title'), array_merge(['class' => 'form-control input-sm'])) }}
                                    @if ($errors->has('title'))
                                        <span class="help-block"><strong>{{ $errors->first('title') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                    {{ Form::label('body', 'Ayat Templet', ['class' => 'control-label required']) }}
                                    {{ Form::textarea('body', old('body'), array_merge(['id' => 'body', 'class' => 'form-control input-sm'])) }}
                                    @if ($errors->has('body'))
                                        <span class="help-block"><strong>{{ $errors->first('body') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::submit('Tambah', array('class' => 'btn btn-success btn-sm')) }}
                                    {{ link_to('feedback.template.index', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection