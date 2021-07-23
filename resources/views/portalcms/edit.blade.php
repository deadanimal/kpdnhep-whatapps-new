@extends('layouts.main')
<?php
    use App\MenuCms;
?>
@section('content')

<div class="row">
    <div class="ibox float-e-margins">
        <h2>Kemaskini Keterangan Portal CMS</h2>
        <div class="ibox-content">
        {!! Form::open(['route' => ['portalcms.update', $id], 'class'=>'form-horizontal', 'files'=>true]) !!}
            {{ csrf_field() }}{{ method_field('PUT') }}
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group {{ $errors->has('title_my') ? ' has-error' : '' }}">
                        {{ Form::label('title_my', 'Tajuk (BM)', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::text('title_my', old('title_my', $model->title_my), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('title_my'))
                                <span class="help-block"><strong>{{ $errors->first('title_my') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('title_en') ? ' has-error' : '' }}">
                        {{ Form::label('title_en', 'Tajuk (BI)', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::text('title_en', old('title_en', $model->title_en), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('title_en'))
                                <span class="help-block"><strong>{{ $errors->first('title_en') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content_my') ? ' has-error' : '' }}">
                        {{ Form::label('content_my', 'Keterangan (BM)', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textArea('content_my', old('content_my', $model->content_my), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('content_my'))
                                <span class="help-block"><strong>{{ $errors->first('content_my') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content_en') ? ' has-error' : '' }}">
                        {{ Form::label('content_en', 'Keterangan (BI)', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textArea('content_en', old('content_en', $model->content_en), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('content_en'))
                                <span class="help-block"><strong>{{ $errors->first('content_en') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    @if($model->cat == 4)
                    <div class="form-group {{ $errors->has('link') ? ' has-error' : '' }}">
                        {{ Form::label('link', 'Pautan', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::text('link', old('link', $model->link), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('link'))
                                <span class="help-block"><strong>{{ $errors->first('link') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('menu_id') ? ' has-error' : '' }}">
                        {{ Form::label('menu_id', 'Menu CMS', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::select('menu_id', MenuCms::MenuList(true), old('menu_id', $model->menu_id), ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('menu_id'))
                                <span class="help-block"><strong>{{ $errors->first('menu_id') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($model->cat == 2 || $model->cat == 4 || $model->cat == 10)
                    <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                        {{ Form::label('photo', 'Gambar', ['class' => 'col-sm-2 control-label']) }}
                        <!--<div class="p-sm text-center">-->
                            <!--<a href="{{-- Storage::disk('bahanpath')->url($PublicCaseDoc->CC_PATH.$PublicCaseDoc->CC_IMG) --}}" target="_blank">-->
                                
                            <!--</a>-->
                        <!--</div>-->
                        <div class="col-sm-10">
                            <a href="{{ Storage::disk('portal')->url($model->photo) }}" target="_blank">
                                <img src="{{ url(Storage::disk('portal')->url($model->photo)) }}" class="img-lg img-thumbnail"/>
                            </a>
                            {{ Form::file('photo') }}
                            <span style="color: red;">@lang('public-case.attachment.fileformat')</span>
                            @if ($errors->has('photo'))
                                <span class="help-block"><strong>{{ $errors->first('photo') }}</strong></span>
                            @endif
                        </div>
                        <!--<div>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: red;">@lang('public-case.attachment.fileformat')</span></div>-->
                    </div>
                    @endif
                    <div class="form-group">
                        {{ Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            <div class="radio radio-success">
                                <input id="status1" type="radio" value="1" name="status" {{ $model->status == 1? 'checked':'' }}>
                                <label for="status1"> AKTIF </label>
                            </div>
                            <div class="radio radio-success">
                                <input id="status2" type="radio" value="0" name="status" {{ $model->status == 0? 'checked':'' }}>
                                <label for="status2"> TIDAK AKTIF </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" align="center">
                        {{ Form::submit('Kemaskini', ['class' => 'btn btn-success btn-sm']) }}
                        {{ link_to('portalcms/list-content/'.$model->cat, 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        </div>
    </div>
</div>
@stop