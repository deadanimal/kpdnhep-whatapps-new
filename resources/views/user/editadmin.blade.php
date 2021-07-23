@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Kemaskini Pengguna Dalaman</h2>
            {{-- date('d-m-Y h:i A') --}}
            <!--<div class="ibox-content">-->
            {!! Form::open(['url' => ['user/patchadmin', $mUser->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
            {{ csrf_field() }}{{ method_field('PATCH') }}
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <div class="profile-image">
                        @if($mUser->user_photo != '')
                            <!--<a href="{{-- Storage::url('profile/'.$mUser->user_photo) --}}" data-lightbox="image-1" data-title="">-->
                            <img src="{{ Storage::url('profile/'.$mUser->user_photo) }}" class="img-circle circle-border m-b-md">
                            <!--</a>-->
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
                                <h2>{{ $mUser->name }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    {{ Form::label('password', 'Kata Laluan', ['class' => 'col-sm-4 control-label']) }}
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
                        {{ Form::text('mobile_no', $mUser->mobile_no, ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('mobile_no'))
                        <span class="help-block"><strong>{{ $errors->first('mobile_no') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('office_no') ? ' has-error' : '' }}">
                    {{ Form::label('office_no', 'No.Tel Pejabat', ['class' => 'col-sm-4 control-label']) }}
                    <div class="col-sm-8">
                        {{ Form::text('office_no', $mUser->office_no, ['class' => 'form-control input-sm']) }}
                        @if ($errors->has('office_no'))
                        <span class="help-block"><strong>{{ $errors->first('office_no') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                    {{ Form::label('role', 'Peranan', ['class' => 'col-sm-4 control-label required']) }}
                    <div class="col-sm-8">
                        <?php $Roles = Ref::where(['cat'=>'152', 'status' => '1'])->get();?>
                        {{ Form::select('role', Ref::GetRole('152', true), $mUserAccess != ''? $mUserAccess->role_code : '', ['class' => 'form-control input-sm']) }}
                        {{--@foreach ($Roles as $Role)--}}
<!--                            <div class="i-checks">
                                <label>{{-- Form::checkbox('role[]', $Role->code, in_array($Role->code, $mUserAccess)? true : false) --}} <i></i> {{-- $Role->descr --}} </label>
                            </div>-->
                        {{--@endforeach--}}
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
                     {{ Form::label('job_dest', 'Jawatan', ['class' => 'col-sm-3 control-label required']) }}
                     <div class="col-sm-9">
                         {{ Form::select('job_dest', Ref::GetList('164', true), $mUser->job_dest, ['class' => 'form-control input-sm']) }}
                         @if ($errors->has('job_dest'))
                        <span class="help-block"><strong>{{ $errors->first('job_dest') }}</strong></span>
                        @endif
                     </div>
                </div>
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {{ Form::label('status', 'Status', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        <div class="radio radio-success">
                            <input id="status1" type="radio" value="1" name="status" {{ $mUser->status == 1? 'checked':'' }}>
                            <label for="status1"> AKTIF </label>
                        </div>
                        <div class="radio radio-success">
                            <input id="status2" type="radio" value="0" name="status" {{ $mUser->status == 0? 'checked':'' }}>
                            <label for="status2"> TIDAK AKTIF </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-12" align="center">
                {{ Form::submit('Simpan', ['class' => 'btn btn-success btn-sm']) }}
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