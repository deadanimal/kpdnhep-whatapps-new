@extends('layouts.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h3>Kemaskini Parameter</h3>
                {!! Form::open(['url' => ['ref/patchparam', $mRef->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                {{ csrf_field() }}{{ method_field('PATCH') }}
                <div class="col-sm-2"></div>
                <div class="col-sm-8">

                    <div class="form-group">
                        {{ Form::label('code', 'Kod', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            {{ Form::text('code', $mRef->code, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('descr', 'Penerangan', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            {{ Form::text('descr', $mRef->descr, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>
                    
                    <div class="form-group{{ $errors->has('descr_en') ? ' has-error' : '' }}">
                        {{ Form::label('descr_en', 'Penerangan Inggeris', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            {{ Form::text('descr_en', $mRef->descr_en, ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('descr_en'))
                            <span class="help-block"><strong>{{ $errors->first('descr_en') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('sort', 'Susunan', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            {{ Form::text('sort', $mRef->sort, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('status', 'Status', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            <div class="radio radio-success">
                            <input id="status1" type="radio" value="1" name="status" {{ $mRef->status == 1? 'checked':'' }}>
                            <label for="status1"> AKTIF </label>
                        </div>
                        <div class="radio radio-success">
                            <input id="status2" type="radio" value="0" name="status" {{ $mRef->status == 0? 'checked':'' }}>
                            <label for="status2"> TIDAK AKTIF </label>
                        </div>
                        </div>
                    </div>

                    <div class="form-group" align="center">
                        {{ Form::submit('Simpan', ['class' => 'btn btn-primary btn-sm']) }}
                        <a class="btn btn-default btn-sm" href="{{ url('ref/listparam',$mRef->cat) }}">Kembali</a>
                    </div>

                </div>
                <div class="col-sm-2"></div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
@endsection
