@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
?>
@section('content')
<h2>Kemaskini Profail</h2>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="profile-image">
            @if($mUser->user_photo != '')
            <img src="{{ Storage::url('profile/'.Auth::user()->user_photo) }}" class="img-circle circle-border m-b-md" alt="profile">
            @else
            <img src="{{ url('img/default.jpg') }}" class="img-circle circle-border m-b-md" alt="profile">
            @endif
        </div>
        <div class="profile-info">
            <div class="">
                <div>
                    <h2 class="no-margins">
                        &nbsp;
                    </h2>
                    <h2>{{ Auth::user()->name }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::open(['url' => ['user/updateuserphoto', $mUser->id], 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="form-group{{ $errors->has('pic') ? ' has-error' : '' }}">
    <div class="col-sm-6 col-sm-offset-3">
        {{ Form::file('pic') }}
        @if ($errors->has('pic'))
            <span class="help-block"><strong>{{ $errors->first('pic') }}</strong></span>
        @endif
    </div> 
</div>
<div class="form-group">
    <div class="col-sm-6 col-sm-offset-3">
            <button type="submit" class="btn btn-primary btn-sm">Kemaskini Gambar</button>
            <a class="btn btn-danger btn-sm" onclick="return confirm('Anda pasti untuk padam gambar profil?')" href="{{ url('user/admindeleteuserphoto') }}">Hapus Gambar</a>
    </div> 
</div>
{!! Form::close() !!}

<div class="ibox">
    <div class="ibox-content">
        {!! Form::open(['url' => ['user/updateprofile', $mUser->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
        {{ csrf_field() }}{{ method_field('PATCH') }}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('icnew') ? ' has-error' : '' }}">
                    {{ Form::label('icnew', 'No.Kad Pengenalan', ['class' => 'col-sm-4 control-label']) }}
                    <div class="col-sm-8">
                        {{ Form::text('icnew', $mUser->username, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                        @if ($errors->has('icnew'))
                        <span class="help-block"><strong>{{ $errors->first('icnew') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ Form::label('name', 'Nama', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::text('name', $mUser->name, ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                    {{ Form::label('mobile_no', 'No. Tel Bimbit', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::text('mobile_no', $mUser->mobile_no, ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('mobile_no'))
                        <span class="help-block"><strong>{{ $errors->first('mobile_no') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('office_no') ? ' has-error' : '' }}">
                    {{ Form::label('office_no', 'No. Tel Pejabat', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        {{ Form::text('office_no', $mUser->office_no, ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('office_no'))
                        <span class="help-block"><strong>{{ $errors->first('office_no') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('state_cd') ? ' has-error' : '' }}">
                    {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        {{ Form::select('state_cd', Ref::GetList('17', true), $mUser->state_cd, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                        @if ($errors->has('state_cd'))
                        <span class="help-block"><strong>{{ $errors->first('state_cd') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('brn_cd') ? ' has-error' : '' }}">
                     {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label required']) }}
                     <div class="col-sm-9">
                         {{ Form::select('brn_cd', $mUser->state_cd == ''? ['-- SILA PILIH --'] : Branch::GetListByState($mUser->state_cd), $mUser->brn_cd, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                         @if ($errors->has('brn_cd'))
                        <span class="help-block"><strong>{{ $errors->first('brn_cd') }}</strong></span>
                        @endif
                     </div>
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                     {{ Form::label('email', 'Emel', ['class' => 'col-sm-3 control-label required']) }}
                     <div class="col-sm-9">
                         {{ Form::text('email', $mUser->email, ['class' => 'form-control input-sm']) }}
                         @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                        @endif
                     </div>
                </div>
                <div class="form-group{{ $errors->has('job_dest') ? ' has-error' : '' }}">
                     {{ Form::label('job_dest', 'Jawatan', ['class' => 'col-sm-3 control-label']) }}
                     <div class="col-sm-9">
                         {{ Form::select('job_dest', Ref::GetList('164', true), $mUser->job_dest, ['class' => 'form-control input-sm', ' disabled']) }}
                         @if ($errors->has('job_dest'))
                        <span class="help-block"><strong>{{ $errors->first('job_dest') }}</strong></span>
                        @endif
                     </div>
                </div>
            </div>
            <div class="form-group col-sm-12" align="center">
                {{ Form::submit('Simpan', ['class' => 'btn btn-success btn-sm']) }}
                <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}">Kembali</a>
            </div>
        </div>
        
        {!! Form::close() !!}
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
