@extends('layouts.main')
<?php

use App\Ref;
use App\Branch;
?>

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Tambah Cawangan</h2>
                {!! Form::open(['url' => '/branch/store', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('BR_BRNCD') ? ' has-error' : '' }}">
                    {{ Form::label('BR_BRNCD', 'Kod Cawangan', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_BRNCD', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_BRNCD'))
                        <span class="help-block"><strong>{{ $errors->first('BR_BRNCD') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('BR_BRNNM') ? ' has-error' : '' }}">
                    {{ Form::label('BR_BRNNM', 'Nama', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_BRNNM', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_BRNNM'))
                        <span class="help-block"><strong>{{ $errors->first('BR_BRNNM') }}</strong></span>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="form-group {{ $errors->has('BR_ADDR1') ? ' has-error' : '' }}">
                    {{ Form::label('BR_ADDR1', 'Alamat Cawangan', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_ADDR1', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_ADDR1'))
                        <span class="help-block"><strong>{{ $errors->first('BR_ADDR1') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('BR_ADDR2') ? ' has-error' : '' }}">
                    {{ Form::label('', '', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_ADDR2', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_ADDR2'))
                        <span class="help-block"><strong>{{ $errors->first('BR_ADDR2') }}</strong></span>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="form-group {{ $errors->has('BR_POSCD') ? ' has-error' : '' }}">
                    {{ Form::label('BR_POSCD', 'Poskod', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_POSCD', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_POSCD'))
                        <span class="help-block"><strong>{{ $errors->first('BR_POSCD') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('BR_STATECD') ? ' has-error' : '' }}">
                    {{ Form::label('BR_STATECD', 'Negeri', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::select('BR_STATECD', Ref::GetList('17', true), null, ['class' => 'form-control input-sm required', 'id' => 'BR_STATECD']) }}
                        @if ($errors->has('BR_STATECD'))
                        <span class="help-block"><strong>{{ $errors->first('BR_STATECD') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('BR_DISTCD') ? ' has-error' : '' }}">
                    {{ Form::label('BR_DISTCD', 'Daerah', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('BR_DISTCD', ['-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'BR_DISTCD']) }}
                        @if ($errors->has('BR_DISTCD'))
                        <span class="help-block"><strong>{{ $errors->first('BR_DISTCD') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('BR_TELNO') ? ' has-error' : '' }}">
                    {{ Form::label('BR_TELNO', 'No Telefon', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_TELNO', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_TELNO'))
                        <span class="help-block"><strong>{{ $errors->first('BR_TELNO') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('BR_FAXNO') ? ' has-error' : '' }}">
                    {{ Form::label('BR_FAXNO', 'No Faks', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_FAXNO', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_FAXNO'))
                        <span class="help-block"><strong>{{ $errors->first('BR_FAXNO') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('BR_EMAIL') ? ' has-error' : '' }}">
                    {{ Form::label('BR_EMAIL', 'Emel', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_EMAIL', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_EMAIL'))
                        <span class="help-block"><strong>{{ $errors->first('BR_EMAIL') }}</strong></span>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="form-group{{ $errors->has('BR_REFNM') ? ' has-error' : '' }}">
                    {{ Form::label('BR_REFNM', 'Rujukan Untuk Surat', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_REFNM', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_REFNM'))
                        <span class="help-block"><strong>{{ $errors->first('BR_REFNM') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('BR_REFADD1') ? ' has-error' : '' }}">
                    {{ Form::label('BR_REFADD1', 'Alamat Rujukan', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_REFADD1', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_REFADD1'))
                        <span class="help-block"><strong>{{ $errors->first('BR_REFADD1') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('BR_REFADD2') ? ' has-error' : '' }}">
                    {{ Form::label('', '', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_REFADD2', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_REFADD2'))
                        <span class="help-block"><strong>{{ $errors->first('BR_REFADD2') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('BR_REFADD3') ? ' has-error' : '' }}">
                    {{ Form::label('', '', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('BR_REFADD3', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('BR_REFADD3'))
                        <span class="help-block"><strong>{{ $errors->first('BR_REFADD3') }}</strong></span>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    {{ Form::label('BR_DEPTCD', 'Bahagian', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('BR_DEPTCD', Ref::GetList('315', true), null, ['class' => 'form-control input-sm', 'id' => 'BR_DEPTCD']) }}
                        @if ($errors->has('BR_DEPTCD'))
                        <span class="help-block"><strong>{{ $errors->first('BR_DEPTCD') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('BR_STATUS') ? ' has-error' : '' }}">
                    {{ Form::label('BR_STATUS', 'Status', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        <div class="i-checks">
                            {{ Form::radio('BR_STATUS', '1', true, ['id' => 'BR_STATUS1']) }}
                            {{ Form::label('BR_STATUS1', 'AKTIF', array('class' => 'radio-custom-label')) }}
                        </div>
                        <div class="i-checks">
                            {{ Form::radio('BR_STATUS', '0', false, ['id' => 'BR_STATUS0']) }}
                            {{ Form::label('BR_STATUS0', 'TIDAK AKTIF', array('class' => 'radio-custom-label')) }}
                        </div>
                    </div>
                </div>
                </br>
                <div class="form-group" align="center">
                    {{ Form::label('', 'Cawangan di peringkat Negeri dan Daerah di bawah kawalan:') }}
                </div>
                <div class="form-group {{ $errors->has('BR_OTHSTATE') ? ' has-error' : '' }}">
                    {{ Form::label('BR_OTHSTATE', 'Negeri', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('BR_OTHSTATE', Ref::GetList('17', true), null, ['class' => 'form-control input-sm required', 'id' => 'BR_OTHSTATE']) }}
                        @if ($errors->has('BR_OTHSTATE'))
                        <span class="help-block"><strong>{{ $errors->first('BR_OTHSTATE') }}</strong></span>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">
                            <select name="BR_OTHDIST[]" class="form-control dual_select" multiple>
                                </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group" align="center">
                    <div class="col-sm-offset-2 col-sm-8">
                        {{ Form::submit('Simpan', array('class' => 'btn btn-success btn-sm')) }}
                        {{ link_to('branch', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
    $(document).ready(function(){
        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight: 260
        });
    });
    $(function () {
        $('#BR_STATECD').on('change', function (e) {
            var BR_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('branch/getdistlist') }}" + "/" + BR_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="BR_DISTCD"]').empty();
                    $.each(data, function (key, value) {
//                        $('select[name="BR_DISTCD"]').append('<option value="' + key + '">' + value + '</option>');
                        $('select[name="BR_DISTCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
                }
            });
        });
        
        $('#BR_OTHSTATE').on('change', function (e) {
            var BR_OTHSTATE = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('branch/getdistlist') }}" + "/" + BR_OTHSTATE,
                dataType: "json",
                success: function (data) {
                    $('select[name="BR_OTHDIST[]"]').empty();
                    $.each(data, function (key, value) {
//                        $('select[name="BR_OTHDIST[]"]').append('<option value="' + key + '">' + value + '</option>');
                        $('select[name="BR_OTHDIST[]"]').append('<option value="' + value + '">' + key + '</option>');
                        $('.dual_select').bootstrapDualListbox('refresh');
                    });
                }
            });
        });
    });
</script>
@stop