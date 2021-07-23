@extends('layouts.main')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <h2>Lihat Templet Feedback</h2>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('Kategori :', null, ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">{{$categories[$template->category]}}</div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('Kod :', null, ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">{{$template->code}}</div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('Tajuk :', null, ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">{{$template->title}}</div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('Templet :', null, ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">{!! nl2br($template->body) !!}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-xl">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <a class="btn btn-primary btn-sm" href="{{ route('feedback.template.edit', $template->id) }}">Kemaskini</a>
                                    <a class="btn btn-default btn-sm" href="{{ route('feedback.template.index') }}">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script_datatable')
@stop
