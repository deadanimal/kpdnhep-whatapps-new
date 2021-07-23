@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Tambah Pengguna Dalaman</h2>
            <!--<div class="ibox-content">-->
            {!! Form::open(['url' => 'user/storeadmin', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
            {{ csrf_field() }}
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('icnew') ? ' has-error' : '' }}">
                    {{ Form::label('icnew', 'No.Kad Pengenalan', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::text('icnew', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('icnew'))
                        <span class="help-block"><strong>{{ $errors->first('icnew') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ Form::label('name', 'Nama', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    {{ Form::label('password', 'Kata Laluan', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::password('password', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('password'))
                        <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                    {{ Form::label('mobile_no', 'No.Tel Bimbit', ['class' => 'col-sm-4 control-label']) }}
                    <div class="col-sm-8">
                        {{ Form::text('mobile_no', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('mobile_no'))
                        <span class="help-block"><strong>{{ $errors->first('mobile_no') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('office_no') ? ' has-error' : '' }}">
                    {{ Form::label('office_no', 'No.Tel Pejabat', ['class' => 'col-sm-4 control-label']) }}
                    <div class="col-sm-8">
                        {{ Form::text('office_no', '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('office_no'))
                        <span class="help-block"><strong>{{ $errors->first('office_no') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                    {{ Form::label('role', 'Peranan', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::select('role', Ref::GetRole('152', true), '', ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('role'))
                        <span class="help-block"><strong>{{ $errors->first('role') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('state_cd') ? ' has-error' : '' }}">
                    {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        {{ Form::select('state_cd', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                        @if ($errors->has('state_cd'))
                        <span class="help-block"><strong>{{ $errors->first('state_cd') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('brn_cd') ? ' has-error' : '' }}">
                     {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label required']) }}
                     <div class="col-sm-9">
                         {{ Form::select('brn_cd', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                         @if ($errors->has('brn_cd'))
                        <span class="help-block"><strong>{{ $errors->first('brn_cd') }}</strong></span>
                        @endif
                     </div>
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                     {{ Form::label('email', 'Emel', ['class' => 'col-sm-3 control-label required']) }}
                     <div class="col-sm-9">
                         {{ Form::text('email', '', ['class' => 'form-control input-sm']) }}
                         @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                        @endif
                     </div>
                </div>
                <div class="form-group{{ $errors->has('job_dest') ? ' has-error' : '' }}">
                     {{ Form::label('job_dest', 'Jawatan', ['class' => 'col-sm-3 control-label required']) }}
                     <div class="col-sm-9">
                         {{ Form::select('job_dest', Ref::GetList('164', true), null, ['class' => 'form-control input-sm']) }}
                         @if ($errors->has('job_dest'))
                        <span class="help-block"><strong>{{ $errors->first('job_dest') }}</strong></span>
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
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-12" align="center">
                {{ Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']) }}
                <a class="btn btn-default btn-sm" href="{{ route('adminuser') }}">Kembali</a>
            </div>
            {!! Form::close() !!}
            <!--</div>-->
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
    $(function () {

        $('#state_cd').on('change', function (e) {
            var state_cd = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('user/getbrnlist') }}" + "/" + state_cd,
                dataType: "json",
                success:function(data){
                    $('select[name="brn_cd"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="brn_cd"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            }); 
        });
        
    });
</script>
@stop