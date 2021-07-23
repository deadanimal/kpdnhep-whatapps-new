@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>{{ $title }}</h2>
                <div class="ibox-content">
                    {!! Form::open(['route' => 'laporan.feedback.r1.index', 'class'=>'form-report no-print', 'method' => 'GET']) !!}
                    <div class="row m-b">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('year', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-5">
                                    {{ Form::select('year', $year_list, null , ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit('Jana', ['class' => 'btn btn-primary btn-sm', 'name'=>'action'])  !!}
                                <a href="{{ route('laporan.feedback.r1.index') }}"
                                   class="btn btn-default btn-sm">Semula</a>
                            </div>
                        </div>
                    </div>
                    @if($is_search)
                        <div class="form-group" align="center">
                            {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'e']) }}
                            {{ Form::button('<i class="fa fa-file-text-o"></i> Muat Turun CSV', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'c']) }}
                            {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'p', 'formtarget' => '_blank']) }}
                        </div>
                    @endif
                    {!! Form::close() !!}
                    @if($is_search)
                        <hr>
                        <h3 align="center">{{ $title }}</h3>
                        <h3 align="center">
                            {{ $year }}
                        </h3>
                        <div class="table-responsive">
                            @include('laporan.feedback.r1.table')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
