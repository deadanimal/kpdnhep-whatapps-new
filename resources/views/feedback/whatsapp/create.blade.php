@extends('layouts.main')
<?php
use App\Menu;
use App\Ref;
use App\Pertanyaan\PertanyaanAdmin;
?>
@section('content')
    <style>
        textarea {
            resize: vertical;
        }
        span.select2 {
            width: 100% !important;
        }
    </style>
    <h2>Tambah WhatsApp Baru</h2>
    <div class="tabs-container">
        <div class="tab-content">
            <div id="enq_info" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => 'whatsapp.storeWeb','class'=>'form-horizontal', 'method' => 'post']) !!}
                    {{ csrf_field() }}
                    <h4><strong>Maklumat WhatsApp</strong></h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                {{ Form::label('phone', 'No. Telefon', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('phone', '', ['class' => 'form-control input-sm', 'id' => 'phone',
                                     'maxlength' => 15, 'onkeypress' => "return isNumberKey(event)", 'placeholder' => 'e.g 01234567890' ]) }}
                                    @if ($errors->has('phone'))
                                        <span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('message') ? ' has-error' : '' }}">
                                {{ Form::label('message', 'Keterangan Pertanyaan', ['class' => 'col-sm-3 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('message', '', ['class' => 'form-control input-sm', 'rows' => 10]) }}
                                    @if ($errors->has('message'))
                                        <span class="help-block"><strong>{{ $errors->first('message') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('message') ? ' has-error' : '' }}">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-7">
                                    {{ Form::checkbox('is_first_time', '1', ['class' => 'form-control input-sm']) }} Hantar Mesej Pertama
                                    @if ($errors->has('is_first_time'))
                                        <span class="help-block"><strong>{{ $errors->first('message') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            <a class="btn btn-default btn-sm" href="{{ route('pertanyaan-admin.index') }}">Kembali</a>
                            {{ Form::button('Simpan'.' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop