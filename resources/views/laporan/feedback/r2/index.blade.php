@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>{{ $title }}</h2>
                <div class="ibox-content">
                    <div class="row m-b">
                        <div class="col-md-6">
                            {!! Form::open(['route' => 'laporan.feedback.r2.index', 'class'=>'form-report no-print', 'method' => 'GET']) !!}
                            <div class="form-group" id="date">
                                {{ Form::label('year', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('date_start',date('d-m-Y', strtotime($date_start)), ['class' => 'form-control input-sm', 'id' => 'ds']) }}
                                        <span class="input-group-addon">hingga</span>
                                        {{ Form::text('date_end', date('d-m-Y', strtotime($date_end)), ['class' => 'form-control input-sm', 'id' => 'de']) }}
                                    </div>
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
                            {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'gem', 'value' => 'p', 'formtarget' => '_blank']) }}
                        </div>
                    @endif
                    {!! Form::close() !!}
                    @if($is_search)
                        <hr>
                        <h3 align="center">{{ $title }}</h3>
                        <h3 align="center">
                            {{ $date_start }} - {{ $date_end }}
                        </h3>
                        <div class="table-responsive">
                            @include('laporan.feedback.r2.table')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop