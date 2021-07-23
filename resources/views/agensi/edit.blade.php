@extends('layouts.main')
<?php 
use App\Menu;
use App\Ref;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h1>Kemaskini Agensi</h1>
                <div class="ibox-content">
                    {!! Form::open(['route' => ['agensi.update', $model->id],'class'=>'form-horizontal', 'method' => 'PUT']) !!}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                {{ Form::label('MI_MINCD', 'Kod', ['class' => 'col-md-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('MI_MINCD', $model->MI_MINCD, ['class' => 'form-control input-sm', 'disabled'=>true]) }}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('MI_ADDR', 'Alamat', ['class' => 'col-md-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::textarea('MI_ADDR', $model->MI_ADDR, ['class' => 'form-control input-sm','rows'=>'3']) }}
                                    @if ($errors->has('MI_ADDR'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_POSCD') ? ' has-error' : '' }}">
                                {{ Form::label('MI_POSCD', 'Poskod', ['class' => 'col-md-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('MI_POSCD', $model->MI_POSCD, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('MI_POSCD'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_POSCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_STATECD') ? ' has-error' : '' }}">
                                {{ Form::label('MI_STATECD', 'Negeri', ['class' => 'col-md-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('MI_STATECD', Ref::GetList('17', true), $model->MI_STATECD, ['class' => 'form-control input-sm required', 'id' => 'MI_STATECD']) }}
                                    @if ($errors->has('MI_STATECD'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_STATECD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_DISTCD') ? ' has-error' : '' }}">
                                {{ Form::label('MI_DISTCD', 'Daerah', ['class' => 'col-md-3 control-label required']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('MI_DISTCD', $model->MI_DISTCD == ''? [''=>'-- SILA PILIH --'] : Ref::GetListDist($model->MI_STATECD, '18', true, 'ms'), $model->MI_DISTCD, ['class' => 'form-control input-sm', 'id' => 'MI_DISTCD']) }}
                                    @if ($errors->has('MI_DISTCD'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_DISTCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="form-group{{ $errors->has('MI_DESC') ? ' has-error' : '' }}">
                                {{ Form::label('MI_DESC', 'Nama Agensi/Kementerian', ['class' => 'col-md-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('MI_DESC', $model->MI_DESC, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('MI_DESC'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_DESC') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_TELNO') ? ' has-error' : '' }}">
                                {{ Form::label('MI_TELNO', 'No. Tel', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('MI_TELNO', $model->MI_TELNO, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('MI_DESC'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_TELNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_FAXNO') ? ' has-error' : '' }}">
                                {{ Form::label('MI_FAXNO', 'No. Fax', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('MI_FAXNO', $model->MI_FAXNO, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('MI_FAXNO'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_FAXNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('MI_EMAIL', 'Email', ['class' => 'col-md-5 control-label required']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('MI_EMAIL', $model->MI_EMAIL, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('MI_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_OFFNM') ? ' has-error' : '' }}">
                                {{ Form::label('MI_OFFNM', 'Nama Pegawai Jabatan', ['class' => 'col-md-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('MI_OFFNM', $model->MI_OFFNM, ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('MI_OFFNM'))
                                    <span class="help-block"><strong>{{ $errors->first('MI_OFFNM') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_MINTYP') ? ' has-error' : '' }}">
                                {{ Form::label('MI_MINTYP', 'Jenis Agensi', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    <div class="radio radio-primary radio-inline">
                                        <input id="gov" type="radio" value="1" name="MI_MINTYP" {{ $model->MI_MINTYP == 1? 'checked':'' }}>
                                        <label for="gov">KERAJAAN</label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                        <input id="swasta" type="radio" value="2" name="MI_MINTYP" {{ $model->MI_MINTYP == 2? 'checked':'' }}>
                                        <label for="swasta">SWASTA</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('MI_STS') ? ' has-error' : '' }}">
                                {{ Form::label('MI_STS', 'Status', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    <div class="radio radio-primary radio-inline">
                                        <input id="active" type="radio" value="1" name="MI_STS" {{ $model->MI_STS == 1? 'checked':'' }}>
                                        <label for="active">AKTIF</label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                        <input id="notactive" type="radio" value="0" name="MI_STS" {{ $model->MI_STS == 0? 'checked':'' }}>
                                        <label for="notactive">TIDAK AKTIF</label>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            {{ Form::submit('Simpan', array('class' => 'btn btn-success btn-sm')) }}
                            <a class="btn btn-default btn-sm" href="{{ route('agensi.index') }}">Kembali</a>
                        </div> 
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script_datatable')
<script type="text/javascript">

    $('#MI_STATECD').on('change', function (e) {
        var CA_STATECD = $(this).val();
        $.ajax({
            type: 'GET',
            url: "{{ url('sas-case/getdistrictlist') }}" + "/" + CA_STATECD,
            dataType: "json",
            success:function(data){
                $('select[name="MI_DISTCD"]').empty();
                $.each(data, function(key, value) {
                    if(value == '0')
                        $('select[name="MI_DISTCD"]').append('<option value="">'+ key +'</option>');
                    else
                        $('select[name="MI_DISTCD"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        });
    });

</script>
@stop