@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('auth.register.title')</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('citizen') ? ' has-error' : '' }}">
                            <label for="citizen" class="col-md-4 control-label required">@lang('auth.register.lbl_resident_sts')</label>

                            <div class="col-sm-6">

                                <div><label> <input type="radio" value="1" name="citizen" checked onclick="check(this.value)"> <i></i> @lang('auth.register.lbl_citizen_ms') </label></div>
                                <div><label> <input type="radio" value="0" name="citizen" onclick="check(this.value)"> <i></i> @lang('auth.register.lbl_citizen_nonms') </label></div>

                                @if ($errors->has('citizen'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('citizen') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div id="citizen" style="display:block">
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="col-md-4 control-label required">@lang('auth.register.lbl_ic')</label>
                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                    @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label required">@lang('auth.register.lbl_name')</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                    @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label required">@lang('auth.register.lbl_email')</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                                <label for="mobile_no" class="col-md-4 control-label required">@lang('auth.register.lbl_mobile_no')</label>
                                <div class="col-sm-6">
                                    <input id="mobile_no" type="text" class="form-control" name="mobile_no" value="{{ old('mobile_no') }}" required>
                                    @if ($errors->has('mobile_no'))
                                    <span class="help-block"><strong>{{ $errors->first('mobile_no') }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label required">@lang('auth.register.lbl_password')</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label required">@lang('auth.register.lbl_confirm_password')</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    {!! app('captcha')->display(); !!}
                                    @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div id="noncitizen" style="display:none">
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="col-md-4 control-label">@lang('auth.register.lbl_passport')</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" >

                                    @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">

                                <button type="submit" class="btn btn-danger btn-sm">
                                    @lang('auth.register.btn_register')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function check(value) {
        if (value == '1') {
            $('#citizen').show();
            $('#noncitizen').hide();
        } else {
            $('#citizen').hide();
            $('#noncitizen').show();
        }
    }
</script>

@endsection
