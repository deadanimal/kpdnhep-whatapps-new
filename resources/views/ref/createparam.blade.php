@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h3>Tambah Parameter</h3>
            {!! Form::open(['url' => 'ref/storeparam', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
            {{ csrf_field() }}
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                    {{ Form::label('code', 'Kod', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        {{ Form::text('code', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('code'))
                        <span class="help-block"><strong>{{ $errors->first('code') }}</strong></span>
                        @endif
                        {{ Form::hidden('cat', $id, ['class' => 'form-control input-sm']) }}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('descr') ? ' has-error' : '' }}">
                    {{ Form::label('descr', 'Penerangan', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        {{ Form::text('descr', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('descr'))
                        <span class="help-block"><strong>{{ $errors->first('descr') }}</strong></span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group{{ $errors->has('descr_en') ? ' has-error' : '' }}">
                    {{ Form::label('descr_en', 'Penerangan Inggeris', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-9">
                        {{ Form::text('descr_en', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('descr_en'))
                        <span class="help-block"><strong>{{ $errors->first('descr_en') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('sort') ? ' has-error' : '' }}">
                    {{ Form::label('sort', 'Susunan', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        {{ Form::text('sort', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('sort'))
                        <span class="help-block"><strong>{{ $errors->first('sort') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {{ Form::label('status', 'Status', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        <div class="radio radio-success">
                            <input id="status1" type="radio" value="1" name="status" checked="">
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

                <div class="form-group" align="center">
                    {{ Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']) }}
                    <a class="btn btn-default btn-sm" href="{{ url('ref/listparam',$id) }}">Kembali</a>
                </div>
            </div>
            <div class="col-sm-2"></div>
            {!! Form::close() !!}

            <!--                <form method="POST" action="{{ url('ref/storekat') }}" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
            
                                    <div class="form-group">
                                        <label for="category" class="col-sm-2 control-label">Kategori</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-sm" id="category" name="category">
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label for="code" class="col-sm-2 control-label">Kod</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-sm" id="code" name="code">
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label for="descr" class="col-sm-2 control-label">Penerangan</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-sm" id="descr" name="descr">
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label for="sort" class="col-sm-2 control-label">Susunan</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control input-sm" id="sort" name="sort">
                                        </div>
                                    </div>
            
                                </div>
                                <div class="col-sm-2"></div>
            
                                <div class="form-group" align="center">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-success btn-sm">Tambah</button>
                                        <a class="btn btn-default btn-sm" href="{{ url('ref/listparam',$id) }}">Kembali</a>
                                    </div>
                                </div>
                            </form>-->
        </div>
    </div>
</div>
@endsection
