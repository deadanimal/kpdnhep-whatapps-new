@extends('layouts.main_portal')
<?php
?>
@section('content')
    <style>
        textarea {
            resize: vertical;
        }
    </style>
    <div class="container mt-4 mb-4 shadow-sm p-3 mb-5 bg-white rounded checkcase"
         style="background-color: white; border: 1px solid #ccc;">
        <div class="row">
            <div class="col">
                <div class="px-2 pt-2">
                    <h2>@lang('auth.register.title')</h2>
                    <small>{{__('portal.message')}}</small>
                    <hr>
                </div>
                @if (session()->has('success'))
                    <div id="xalert" class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
                        {{ session()->get('success') }}
                    </div>
                @elseif (session()->has('warning'))
                    <div id="xalert" class="alert alert-warning alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
                        {{ session()->get('warning') }}
                    </div>
                @elseif (session()->has('alert'))
                    <div id="xalert" class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
                        {{ session()->get('alert') }}
                    </div>
                @elseif (session()->has('info'))
                    <div id="xalert" class="alert alert-info alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
                        {{ session()->get('info') }}
                    </div>
                @endif
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/user-submit') }}">
                    {{ csrf_field() }}
                    {{-- old('citizen') --}}
                    <div class="form-group{{ $errors->has('citizen') ? ' has-error' : '' }}">
                        <label for="citizen"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_resident_sts')</label>
                        <div class="col-md-6">
                            <div class="radio radio-success radio-inline">
                                <input id='citizen1'
                                       type="radio"
                                       value="1"
                                       name="citizen"
                                        {{ old('citizen') != ''
                                            ? (old('citizen') == '1' ? 'checked' : '')
                                            : (session()->get('locale')=='ms' ? 'checked': '') }}>
                                <label for="citizen1">@lang('auth.register.lbl_citizen_ms')</label>
                            </div>
                            <div class="radio radio-success radio-inline">
                                <input id='citizen0'
                                       type="radio"
                                       value="0"
                                       name="citizen"
                                        {{ old('citizen') != ''
                                            ? (old('citizen') == '0' ? 'checked' : '')
                                            : (session()->get('locale') == 'en' ? 'checked' : '') }}>
                                <label for="citizen0">@lang('auth.register.lbl_citizen_nonms')</label>
                            </div>
                            @if ($errors->has('citizen'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('citizen') }}</strong>
                                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" id="div_icnew">
                        <label id="lbl_ic"
                               style="display: {{ (old('citizen') != ''? (old('citizen') == '1'? 'block':'none'):'block') }};"
                               for="username"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_ic')</label>
                    <!--<label id="lbl_passport" style="display: {{-- (old('citizen') != ''? (old('citizen') == '0'? 'block':'none'):'none') --}};" for="username" class="col-md-4 control-label required">@lang('auth.register.lbl_passport')</label>-->
                        <div class="col-md-6">
                            <input id="username" type="text" class="form-control"
                                   placeholder="(ie: 570831141234)" name="username"
                                   value="{{ old('username') }}" maxlength="12">
                            @if ($errors->has('username'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('passport') ? ' has-error' : '' }}" id="div_passport">
                        <label style="display: {{ (old('citizen') != ''? (old('citizen') == '0'? 'block':'none'):'block') }};"
                               for="passport"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_passport')</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="(ie: A12345678)"
                                   name="passport" value="{{ old('passport') }}" maxlength="20">
                            @if ($errors->has('passport'))
                                <span class="help-block">
                                                    <strong>{{ $errors->first('passport') }}</strong>
                                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_name')</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name"
                                   placeholder="{{ $placeholder }}" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_email')</label>
                        <div class="col-md-6">
                            <style scoped>input:invalid, textarea:invalid {
                                    color: red;
                                }</style>
                            <input id="email" type="email" class="form-control" name="email"
                                   placeholder="(ie : example@example.com)" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                        <label for="mobile_no"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_mobile_no')</label>
                        <div class="col-md-6">
                            <input id="mobile_no" type="text" onkeypress="return isNumberKey(event)"
                                   placeholder="(ie: 0131234567)" class="form-control" name="mobile_no"
                                   value="{{ old('mobile_no') }}">
                            @if ($errors->has('mobile_no'))
                                <span class="help-block"><strong>{{ $errors->first('mobile_no') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div id='gender' style="display: {{ old('citizen') == '0'? 'block':'none' }}"
                         class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                        <label for="gender"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_gender')</label>
                        <div class="col-md-6">
                            {{ Form::select('gender', \App\Ref::GetList('202', true, app()->getLocale() != ''? app()->getLocale():'en'), null, ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('gender'))
                                <span class="help-block"><strong>{{ $errors->first('gender') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div id='age' style="display: {{ old('citizen') == '0'? 'block':'none' }}"
                         class="form-group{{ $errors->has('age') ? ' has-error' : '' }}">
                        <label for="age"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_age')</label>
                        <div class="col-md-6">
                            {{-- Form::select('age', Ref::GetList('309', true, app()->getLocale() != ''? app()->getLocale():'en'), null, ['class' => 'form-control input-sm']) --}}
                            {{ Form::text('age', '', ['class' => 'form-control input', 'placeholder'=> "(ie: 25)", 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 3]) }}
                            @if ($errors->has('age'))
                                <span class="help-block"><strong>{{ $errors->first('age') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div id='address' style="display: {{ old('citizen') == '0'? 'block':'none' }}"
                         class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label for="address"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_address_in_mys')</label>
                        <div class="col-md-6">
                            {{ Form::textarea('address', '', ['class' => 'form-control input-sm', 'rows'=>'4']) }}
                            @if ($errors->has('address'))
                                <span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div id='postcode' style="display: {{ old('citizen') == '0'? 'block':'none' }}"
                         class="form-group{{ $errors->has('postcode') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">@lang('public-profile.profail.postcode')</label>
                        <div class="col-md-6">
                            {{ Form::text('postcode', '', ['class' => 'form-control input-sm', 'placeholder' => '(ie: 73000/23000)','onkeypress' => "return isNumberKey(event)", 'maxlength' => '5']) }}
                            @if ($errors->has('postcode'))
                                <span class="help-block"><strong>{{ $errors->first('postcode') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div id='state' style="display: {{ old('citizen') == '0'? 'block':'none' }}"
                         class="form-group{{ $errors->has('state_cd') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label required">@lang('auth.register.lbl_state_in_mys')</label>
                        <div class="col-md-6">
                            {{ Form::select('state_cd', \App\Ref::GetList('17', true, config('app.locale')), '', ['class' => 'form-control input-sm', 'onChange' => 'statecd(this.value)']) }}
                            @if ($errors->has('state_cd'))
                                <span class="help-block"><strong>{{ $errors->first('state_cd') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div id='district_cd' style="display: {{ old('citizen') == '0'? 'block':'none' }}"
                         class="form-group{{ $errors->has('distrinct_cd') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label required">@lang('auth.register.lbl_district_in_mys')</label>
                        <div class="col-md-6">
                            {{-- Form::select('distrinct_cd', old('state_cd') ? Ref::GetListDist($mUser->distrinct_cd, '18', true, 'ms')(config('app.locale') == 'ms') ? ['' => '-- SILA PILIH --'] : ['' => '-- PLEASE SELECT --'], old('distrinct_cd')), ['class' => 'form-control input-sm', 'id' => 'distrinct_cd']) --}}
                            @if (old('state_cd'))
                                {{ Form::select('distrinct_cd', \App\Ref::GetListDist(old('state_cd')), null, ['class' => 'form-control input-sm', 'id' => 'distrinct_cd']) }}
                            @else
                                {{ Form::select('distrinct_cd', app()->getLocale() == 'ms' ? ['' => '-- SILA PILIH --'] : ['' => '-- PLEASE SELECT --'], null, ['class' => 'form-control input-sm', 'id' => 'distrinct_cd']) }}
                            @endif
                            @if ($errors->has('distrinct_cd'))
                                <span class="help-block"><strong>{{ $errors->first('distrinct_cd') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div id='ctry_cd' style="display: {{ old('citizen') == '0'? 'block':'none' }}"
                         class="form-group{{ $errors->has('ctry_cd') ? ' has-error' : '' }}">
                        <label for="ctry_cd"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_ctry')</label>
                        <div class="col-md-6">
                            {{ Form::select('ctry_cd', \App\Ref::GetList('334', true, app()->getLocale() != ''? app()->getLocale():'en'), null, ['class' => 'form-control input-sm']) }}
                            @if ($errors->has('ctry_cd'))
                                <span class="help-block"><strong>{{ $errors->first('ctry_cd') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_password')</label>
                        <div class="col-md-6">
                            <input id="password" type="password"
                                   placeholder="@lang('auth.register.ph_password')" class="form-control"
                                   name="password">
                            <span style="color: blue; font-size: 12px">
                                                @lang('auth.register.password_text')
                                            </span>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm"
                               class="col-md-4 control-label required">@lang('auth.register.lbl_confirm_password')</label>
                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" onfocusout="checkrepeatpassword()">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                        <div class="col-md-6 col-md-offset-4">
                            <p><img width="50%" class="img-thumbnail" id="captcha"
                                    src="{{ Captcha::src('flat') }}">&nbsp
                                <button onclick="reloadcaptcha()"
                                        class="btn btn-success btn-circle"
                                        type="button">
                                    <i class="fa fa-redo"></i>
                                </button>
                            </p>
                            <p><input type="text" class="form-control input-sm" style="width: 40%;"
                                      name="captcha"></p>
                            @if ($errors->has('captcha'))
                                <span style="color:red">
                                    <strong>{{ $errors->first('captcha') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="locale" value="{{ app()->getLocale() }}">
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit"
                                    class="btn btn-success btn-sm">@lang('auth.register.btn_register')</button>&nbsp;
                            <a href="{{ url('kepenggunaan')}}" type="button"
                               class="btn btn-primary btn-sm">@lang('button.back')</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', () => {
        var radio = $('input[name=citizen]:checked').val()
        if (radio === '1') {
//            $('#lbl_ic').show();
          $('#div_icnew').show()
//            $('#lbl_passport').hide();
          $('#div_passport').hide()
          $('#gender').hide()
          $('#age').hide()
          $('#address').hide()
          $('#ctry_cd').hide()
          $('#state').hide()
          $('#district_cd').hide()
          $('#postcode').hide()
        } else {
//            $('#lbl_ic').hide();
          $('#div_icnew').hide()
//            $('#lbl_passport').show();
          $('#div_passport').show()
          $('#gender').show()
          $('#age').show()
          $('#address').show()
          $('#ctry_cd').show()
          $('#state').show()
          $('#district_cd').show()
          $('#postcode').show()
        }
        $(document).on('change', 'input[name=citizen]', function () {
          check(this.value)
        })
      })

      function checkrepeatpassword() {
        if ($('#password').val() !== $('#password-confirm').val()) {
          alert("@lang('auth.register.password_check')")
          $('#password-confirm').val('')
        }
      }

      function reloadcaptcha() {
        document.getElementById('captcha').setAttribute('src', '/captcha?' + Math.random())
      }

      //    $('#captcha').click(function(){
      //        alert('berjaya');
      //    $('#captcha').attr('src','/captcha/default?'+Math.random());
      //    document.getElementsByTagName("H1")[0].setAttribute("class", "democlass");
      //    });
      function statecd(statecd) {
        $.ajax({
          type: 'GET',
          url: "{{ url('getdistlist') }}" + '/' + statecd,
          dataType: 'json',
          success: function (data) {
            $('select[name="distrinct_cd"]').empty()
            $.each(data, function (key, value) {
              $('select[name="distrinct_cd"]').append('<option value="' + value + '">' + key + '</option>')
            })
          }
        })
      }

      //$('#state_cd').on('change', function (e) {
      //        alert('berjaya');
      //        var state_cd = $(this).val();
      //        $.ajax({
      //            type:'GET',
      //            url:"{{ url('admin-case/getdistlist') }}" + "/" + state_cd,
      //            dataType: "json",
      //            success:function(data){
      //                $('select[name="distrinct_cd"]').empty();
      //                $.each(data, function(key, value) {
      //                    $('select[name="distrinct_cd"]').append('<option value="'+ value +'">'+ key +'</option>');
      //                });
      //            }
      //        });
      //    });

      function check(value) {
        if (value === '1') {
          $.get('locale/ms', function () {
            location.reload()
          })
        } else {
          $.get('locale/en', function () {
            location.reload()
          })
        }
      }

      function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false
        return true
      }
    </script>

@endsection
