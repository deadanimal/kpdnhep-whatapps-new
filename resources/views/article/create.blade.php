@extends('layouts.main')
<?php
    use App\Articles;
    use App\MenuCms;
    use App\Ref;
?>
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Tambah Artikal</h2>

                {!! Form::open(['url' => ['/article/store'], 'class'=>'form-horizontal', 'files'=>true]) !!}
                {{ csrf_field() }}{{ method_field('POST') }}

                <div class="form-group {{ $errors->has('start_dt') ? ' has-error' : '' }} " id="start_date">
                    {{ Form::label('start_dt', 'Tarikh Mula', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-3">
                        <div class="input-daterange" id="datepicker">
                            {{ Form::text('start_dt', '', ['class' => 'form-control input-sm', 'id' => 'start_dt']) }}
                        </div>
                        @if ($errors->has('start_dt'))
                            <span class="help-block"><strong>{{ $errors->first('start_dt') }}</strong></span>
                        @endif
                    </div>
                </div>
                        
                <div class="form-group {{ $errors->has('end_dt') ? ' has-error' : '' }}" id="end_date">
                    {{ Form::label('end_dt', 'Tarikh Tamat', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-3">
                        <div class="input-daterange" id="datepicker">
                            {{ Form::text('end_dt', '', ['class' => 'form-control input-sm', 'id' => 'end_dt']) }}
                        </div>
                        @if ($errors->has('end_dt'))
                            <span class="help-block"><strong>{{ $errors->first('end_dt') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('title_my') ? ' has-error' : '' }}">
                    {{ Form::label('title_my', 'Tajuk (BM)', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::text('title_my', old('title_my'), array_merge(['class' => 'form-control input-sm', 'id' => 'title_my'])) }}
                        @if ($errors->has('title_my'))
                            <span class="help-block"><strong>{{ $errors->first('title_my') }}</strong></span>
                        @endif
                    </div>
                </div>
                        
                <div class="form-group {{ $errors->has('title_en') ? ' has-error' : '' }}">
                    {{ Form::label('title_en', 'Tajuk (BI)', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::text('title_en', old('title_en'), array_merge(['class' => 'form-control input-sm', 'id' => 'title_en'])) }}
                        @if ($errors->has('title_en'))
                            <span class="help-block"><strong>{{ $errors->first('title_en') }}</strong></span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('content_my') ? ' has-error' : '' }}">
                    {{ Form::label('content_my', 'Isi Artikal (BM)', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::textarea('content_my', old('content_my'), array_merge(['class' => 'form-control input-sm', 'id' => 'content_my'])) }}
                        @if ($errors->has('content_my'))
                            <span class="help-block"><strong>{{ $errors->first('content_my') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('content_en') ? ' has-error' : '' }}">
                    {{ Form::label('content_en', 'Isi Artikal (BI)', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::textarea('content_en', old('content_en'), array_merge(['class' => 'form-control input-sm', 'id' => 'content_en'])) }}
                        @if ($errors->has('content_en'))
                            <span class="help-block"><strong>{{ $errors->first('content_en') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('menu_id') ? ' has-error' : '' }}">
                    {{ Form::label('menu_id', 'Menu', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('menu_id', MenuCms::MenuList(true), null, ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('menu_id'))
                            <span class="help-block"><strong>{{ $errors->first('menu_id') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('cat') ? ' has-error' : '' }}">
                    {{ Form::label('cat', 'Kategori', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('cat', Ref::GetList(1258), old('cat'), ['class' => 'form-control input-sm', 'id' => 'cat']) }}
                        @if ($errors->has('cat'))
                            <span class="help-block"><strong>{{ $errors->first('cat') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                    {{ Form::label('photo', 'Gambar', ['class' => 'col-sm-2 control-label']) }}
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
                        @if ($errors->has('status'))
                            <span class="help-block"><strong>{{ $errors->first('status') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('link') ? ' has-error' : '' }}">
                    {{ Form::label('link', 'Pautan', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('link', old('link'), array_merge(['class' => 'form-control input-sm', 'id' => 'link'])) }}
                        @if ($errors->has('link'))
                            <span class="help-block"><strong>{{ $errors->first('link') }}</strong></span>
                        @endif
                    </div>
                </div>


                <div class="form-group" align="center">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']) }}
                        {{ link_to('article', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
    <script>
        var ckview = document.getElementById("content_my");
        CKEDITOR.replace(ckview,{
            language:'ms-gb'
        });

        var ckview = document.getElementById("content_en");
        CKEDITOR.replace(ckview,{
            language:'ms-gb'
        });
        
        $('#start_date .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });
        
        $('#end_date .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });
    </script>
@stop