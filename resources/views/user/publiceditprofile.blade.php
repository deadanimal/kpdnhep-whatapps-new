@extends('layouts.main_public')
<?php
use App\Ref;
use App\Branch;
use App\Aduan\PublicCase;
?>
@section('content')
<style>
    textarea {
        resize: vertical;
    }
     .fileinput-button {
    position: relative;
    overflow: hidden;
}
 .fileinput-button input {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    opacity: 0;
    -ms-filter:'alpha(opacity=0)';
    font-size: 200px;
    direction: ltr;
    cursor: pointer;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('public-profile.profail.profileupdate')</div>
                    <div class="panel-body">
                        {!! Form::open(['url' => ['user/pubupdateprofile', $mUser->id], 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        {{ csrf_field() }}{{ method_field('PATCH') }}
                            <div class="row">
                                <div class="col-sm-9 col-md-offset-3">
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
                            <div class="form-group{{ $errors->has('pic') ? ' has-error' : '' }}">
                                <div class="col-sm-9 col-md-offset-3">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-info btn-file btn-sm"><span class="fileinput-new"><i class="fa fa-file-image-o" aria-hidden="true"></i> @lang('public-profile.profail.choosefile')</span>
                                        <span class="fileinput-exists">Change</span><input type="file" name="pic"/></span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                                    </div>
                                    @if (Auth::user()->lang == 'ms')
                                        <a class="btn btn-danger btn-sm" href="{{ url('user/pubdeleteuserphoto') }}" onclick="return confirm('Anda pasti untuk hapus gambar ini?')"><i class="fa fa-trash" aria-hidden="true"></i> @lang('public-profile.profail.deletepicture')</a>
                                    @else
                                        <a class="btn btn-danger btn-sm" href="{{ url('user/pubdeleteuserphoto') }}" onclick="return confirm('Are you sure to delete the picture?')"><i class="fa fa-trash" aria-hidden="true"></i> @lang('public-profile.profail.deletepicture')</a>
                                    @endif
                                    <!--<input type="file" name="pic" id="files" />-->
                                    <!--<label class="btn btn-warning btn-sm" for="files">@lang('public-profile.profail.choosefile')</label>-->
                                    @if ($errors->has('pic'))
                                        <span class="help-block"><strong>@lang('public-profile.validation.pic')</strong></span>
                                    @endif
                                </div> 
                            </div>
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="col-sm-4 control-label">@lang('public-profile.profail.icnew')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('username', $mUser->username, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('username'))
                                    <span class="help-block"><strong>{{ $errors->first('username') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="icnew" class="col-sm-4 control-label">@lang('public-profile.profail.name')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name', $mUser->name, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('name'))
                                    <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                                <label for="mobile_no" class="col-sm-4 control-label required">@lang('public-profile.profail.mobile_no')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('mobile_no', old('mobile_no', $mUser->mobile_no), ['class' => 'form-control input-sm','onkeypress' => "return isNumberKey(event)", 'maxlength' => 12]) }}
                                    @if ($errors->has('mobile_no'))
                                    <span class="help-block"><strong>{{ $errors->first('mobile_no') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-sm-4 control-label required">@lang('public-profile.profail.email')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('email', old('email', $mUser->email), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('email'))
                                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                @if($mUser->citizen == '1')
                                <label class="col-sm-4 control-label required">@lang('public-profile.profail.address')</label>
                                @else
                                <label class="col-sm-4 control-label required">@lang('auth.register.lbl_address_in_mys')</label>
                                @endif
                                <div class="col-sm-8">
                                    {{ Form::textarea('address', old('address', $mUser->address), ['class' => 'form-control input-sm', 'rows'=>3]) }}
                                    @if ($errors->has('address'))
                                    <span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('postcode') ? ' has-error' : '' }}">
                                <label class="col-sm-4 control-label">@lang('public-profile.profail.postcode')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('postcode', old('postcode', $mUser->postcode), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => '5']) }}
                                    @if ($errors->has('postcode'))
                                    <span class="help-block"><strong>{{ $errors->first('postcode') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('state_cd') ? ' has-error' : '' }}">
                                @if($mUser->citizen == '1')
                                <label class="col-sm-4 control-label required">@lang('public-profile.profail.state_cd')</label>
                                @else
                                <label class="col-sm-4 control-label required">@lang('auth.register.lbl_state_in_mys')</label>
                                @endif
                                <div class="col-sm-8">
                                    {{ Form::hidden('citizen', $mUser->citizen, ['class' => 'form-control input-sm']) }}
                                    {{ Form::select('state_cd', Ref::GetList('17', true, Auth::user()->lang), old('state_cd', $mUser->state_cd), ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                                    @if ($errors->has('state_cd'))
                                    <span class="help-block"><strong>{{ $errors->first('state_cd') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('distrinct_cd') ? ' has-error' : '' }}">
                                @if($mUser->citizen == '1')
                                <label class="col-sm-4 control-label required">@lang('public-profile.profail.distrinct_cd')</label>
                                @else
                                <label class="col-sm-4 control-label required">@lang('auth.register.lbl_district_in_mys')</label>
                                @endif
                                <div class="col-sm-8">
                                    {{-- Form::select('distrinct_cd', $mUser->state_cd != ''? Ref::GetListDist($mUser->distrinct_cd, '18', true, 'ms') : (Auth::user()->lang == 'ms'? [''=>'-- SILA PILIH --']:[''=>'-- PLEASE SELECT --']), $mUser->distrinct_cd, ['class' => 'form-control input-sm']) --}}
                                    @if(old('state_cd'))
                                    {{ Form::select('distrinct_cd', (PublicCase::GetDstrtList(old('state_cd'))), old('distrinct_cd'), ['class' => 'form-control input-sm', 'id' => 'distrinct_cd']) }}
                                    @else
                                    {{ Form::select('distrinct_cd', ($mUser->state_cd == '' ? ['' => '-- SILA PILIH --'] : PublicCase::GetDstrtList($mUser->state_cd)), old('distrinct_cd', $mUser->distrinct_cd), ['class' => 'form-control input-sm', 'id' => 'distrinct_cd']) }}
                                    @endif
                                    @if ($errors->has('distrinct_cd'))
                                    <span class="help-block"><strong>{{ $errors->first('distrinct_cd') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div style="display: {{ $mUser->citizen == '0'? 'block':'none' }}" class="form-group{{ $errors->has('ctry_cd') ? ' has-error' : '' }}">
                                <label for="ctry_cd" class="col-md-4 control-label required">@lang('auth.register.lbl_ctry')</label>
                                <div class="col-sm-8">
                                    <!--{{-- Form::select('ctry_cd', Ref::GetList('334', true, app()->getLocale() != ''? app()->getLocale():'en'), null, ['class' => 'form-control input-sm']) --}}-->
                                    {{ Form::select('ctry_cd', Ref::GetList('334', true, $mUser->lang), old('ctry_cd', $mUser->ctry_cd), ['class' => 'form-control input-sm']) }}
                                    @if ($errors->has('ctry_cd'))
                                        <span class="help-block"><strong>@lang('public-profile.validation.ctry_cd')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('lang') ? ' has-error' : '' }}">
                                <label for="lang" class="col-sm-4 control-label required">@lang('public-profile.profail.lang')</label>
                                <div class="col-sm-8">
                                    {{ Form::select('lang', Auth::user()->lang == 'ms' ? Ref::GetList('295', true) : Ref::GetList('295', true, 'en'), old('lang', $mUser->lang), ['class' => 'form-control input-sm', 'id' => 'lang']) }}
                                    @if ($errors->has('lang'))
                                    <!--<span class="help-block"><strong>{{ $errors->first('lang') }}</strong></span>-->
                                    <span class="help-block"><strong>@lang('public-profile.validation.lang')</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-sm-12" align="center">
                                {{-- Form::submit('Simpan', ['class' => 'btn btn-success btn-sm']) --}}
                                <button type="submit" class="btn btn-success btn-sm">@lang('button.save')</button>
                                <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}">@lang('button.back')</a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
//$( document ).ready(function () {
    $('#state_cd').on('change', function (e) {
        var state_cd = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('admin-case/getdistlist') }}" + "/" + state_cd,
            dataType: "json",
            success:function(data){
                $('select[name="distrinct_cd"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="distrinct_cd"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        });
    });
//});
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)){
        return false;
    } else {
        return true;
    }
}    
</script>
@stop
