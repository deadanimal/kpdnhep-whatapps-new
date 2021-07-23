@extends('layouts.portal.main')

@section('slider')
    @include('portal.slider')
@endsection

@section('content')
    <section id="intro" class="container">
        <div class="row features-block" style="margin-top: 20px">
            <div class="col-lg-6 wow fadeInLeft">
                <h2>{{__('portal.pc_s1_title')}}</h2>
                <p style="text-align: justify">
                    {{__('portal.pc_s1_desc')}}
                </p>
                {{--                <h3>{{__('portal.pc_s2_title')}}</h3>--}}
                {{--                <p style="text-align: justify">--}}
                {{--                    {{__('portal.pc_s2_desc')}}--}}
                {{--                </p>--}}
            </div>
            <div class="col-lg-6 text-right wow fadeInRight">
                <div class="row">
                    <div class="col-md-6">
                        <a class="nav-link btn btn-primary btn-white-outline btn-block" href="#" data-toggle="modal"
                           data-target="#myModallogin">
                            {{-- @lang('portal.btn.login') --}}
                            @lang('public-case.case.consumercomplaint')
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="nav-link btn btn-primary btn-white-outline btn-block"
                           href="{{ url('integritipublicuser/create') }}" style="padding:10px">
                            {{ app()->getLocale() == 'ms' ? 'ADUAN BARU INTEGRITI' : 'INTEGRITY NEW COMPLAINT' }}
                        </a>
                    </div>
                    <div class="col-md-12 m-t">
                        <a class="nav-link btn btn-primary btn-white-outline btn-block" href="#"
                           data-toggle="modal"
                           data-target="#myModalcheck">@lang('portal.btn.checkcomplain')
                        </a>
                        <a class="nav-link btn btn-primary btn-white-outline btn-block" href="#"
                           data-toggle="modal"
                           data-target="#myModalask">@lang('portal.btn.checkask')
                        </a>
                        <a class="nav-link btn btn-primary btn-white-outline btn-block" href="#" data-toggle="modal"
                           data-target="#myModalintegriti" style="padding:10px">
                            {{ app()->getLocale() == 'ms' ? 'SEMAKAN ADUAN INTEGRITI' : 'INTEGRITY COMPLAINT CHECK' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="features" class="container services">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>{{__('portal.pc_s3_b1_title')}}</h1>
                <p class="text-center">{{__('portal.pc_s3_b1_desc')}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h2>{{__('portal.pc_s3_b2_title')}}</h2>
                <p>
                    {{__('portal.pc_s3_b2_desc')}}
                </p>
            </div>
            <div class="col-sm-4">
                <h2>{{__('portal.pc_s3_b3_title')}}</h2>
                <p>
                    {{__('portal.pc_s3_b3_desc')}}
                </p>
            </div>
            <div class="col-sm-4">
                <h2>{{__('portal.pc_s3_b4_title')}}</h2>
                <p>
                    {{__('portal.pc_s3_b4_desc')}}
                </p>
            </div>
        </div>
    </section>

    <section id="features" class="features timeline gray-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="navy-line"></div>
                    <h1>{{__('portal.pc_s4_b1_title')}}</h1>
                    <p class="text-center">{{__('portal.pc_s4_b1_desc')}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <h2>{{__('portal.pc_s4_b2_title')}}</h2>
                    <p>
                        {{__('portal.pc_s4_b2_desc')}}
                    </p>
                    <p><a class="navy-link" href="/portal/claim/type" role="button">Read More &raquo;</a></p>
                </div>
                <div class="col-sm-3">
                    <h2>{{__('portal.pc_s4_b3_title')}}</h2>
                    <p>
                        {{__('portal.pc_s4_b3_desc')}}
                    </p>
                    <p><a class="navy-link" href="/portal/claim/procedure/filings" role="button">Read More &raquo;</a>
                    </p>
                </div>
                <div class="col-sm-3">
                    <h2>{{__('portal.pc_s4_b4_title')}}</h2>
                    <p>
                        {{__('portal.pc_s4_b4_desc')}}
                    </p>
                    <p><a class="navy-link" href="#" role="button">Read More &raquo;</a></p>
                </div>
                <div class="col-sm-3">
                    <h2>{{__('portal.pc_s4_b5_title')}}</h2>
                    <p>
                        {{__('portal.pc_s4_b5_desc')}}
                    </p>
                    <p><a class="navy-link" href="/files/consumers_act.pdf" role="button">Download &raquo;</a></p>
                </div>
            </div>
        </div>
    </section>

    <section id="clients-charter" class="container features">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>{{__('portal.pc_s5_b1_title')}}</h1>
                <p class="text-center">{{__('portal.pc_s5_b1_desc')}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 text-center wow fadeInLeft">
                <div>
                    <i class="fa fa-calendar features-icon" style="color: #2C487D"></i>
                    <h2>{{__('portal.pc_s5_b2_title')}}</h2>
                    <p>
                        {{__('portal.pc_s5_b2_desc')}}
                    </p>
                </div>
                <div class="m-t-lg">
                    <i class="fa fa-clock-o features-icon" style="color: #2C487D"></i>
                    <h2>{{__('portal.pc_s5_b3_title')}}</h2>
                    <p>
                        {{__('portal.pc_s5_b3_desc')}}
                    </p>
                </div>
            </div>
            <div class="col-md-6 text-center wow fadeInRight">
                <div>
                    <i class="fa fa-legal features-icon" style="color: #2C487D"></i>
                    <h2>{{__('portal.pc_s5_b4_title')}}</h2>
                    <p>
                        {{__('portal.pc_s5_b4_desc')}}
                    </p>
                </div>
                <div class="m-t-lg">
                    <i class="fa fa-trophy features-icon" style="color: #2C487D"></i>
                    <h2>{{__('portal.pc_s5_b5_title')}}</h2>
                    <p>
                        {{__('portal.pc_s5_b5_desc')}}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="features timeline gray-section hidden">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-lg-offset-1 features-text">
                    <small>Jurisdiction</small>
                    <h2>Jurisdiction Limitation</h2>
                    <i class="fa fa-bar-chart big-icon pull-right" style="color: #2C487D"></i>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores exercitationem nobis quia
                        saepe ut! Animi atque, dignissimos dolores ex explicabo fugit illum incidunt, itaque minus nemo
                        optio ratione repellendus veritatis.
                    </p>
                </div>
                <div class="col-lg-5 features-text">
                    <small>Membership</small>
                    <h2>Membership of Tribunal </h2>
                    <i class="fa fa-bolt big-icon pull-right" style="color: #2C487D"></i>
                    <p>
                        Section 86 of the Act provides that the Tribunal shall consist of a Chairman and a Deputy
                        Chairman from among members of the Judicial and Legal Service and not less than five other
                        members of the Tribunal appointed by the Minister
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-lg-offset-1 features-text">
                    <small>Claims</small>
                    <h2>Type of Claims </h2>
                    <i class="fa fa-clock-o big-icon pull-right" style="color: #2C487D"></i>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias at aut, debitis eveniet
                        exercitationem illum inventore, minus modi molestiae nulla omnis perferendis possimus quidem
                        repudiandae voluptatem? Facere ipsa quia reprehenderit!
                    </p>
                </div>
                <div class="col-lg-5 features-text">
                    <small>Award</small>
                    <h2>Awards of Tribunal </h2>
                    <i class="fa fa-users big-icon pull-right" style="color: #2C487D"></i>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi asperiores, blanditiis
                        consequuntur deleniti dignissimos doloribus eius esse et fuga fugit ipsum itaque laudantium
                        maiores maxime nostrum perferendis sed temporibus voluptatem.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="gray-section contact">
        <div class="container">
            <div class="row m-b-lg">
                <div class="col-lg-12 text-center">
                    <div class="navy-line"></div>
                    <h1>{{__('base.system_short_name')}}</h1>
                    <p class="text-center">{{__('base.system_long_name')}}</p>
                </div>
            </div>
            <div class="row m-b-lg">
                <div class="col-sm-6 col-md-4 col-lg-offset-1">
                    <address>
                        <strong><span
                                    class="navy">Ministry of Domestic Trade and Consumer Affairs (MDTCA)</span></strong><br>
                        Level 5 (Podium 2) <br>
                        Persiaran Perdana, Precinct 2 <br>
                        Federal Government Administration Center <br>
                        <abbr title="Phone"><i class="fa fa-phone"></i></abbr> : +603 - 8882 5822 <br>
                        <abbr title="Fax"><i class="fa fa-fax"></i></abbr> : +603 - 8882 5831 <br>
                        <abbr title="Web"><i class="fa fa-globe"></i></abbr> : https://www.kpdnhep.gov.my
                    </address>
                </div>
                <div class="col-sm-6 col-lg-6">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <h5>About</h5>
                            <ul class="list-unstyled">
                                <li><a target="_blank" href="https://www.kpdnhep.gov.my/">MDTCA</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <h5>Support</h5>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="/portal/faq">FAQ</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <h5>Contact</h5>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="/portal/directory/headquarters">Directory</a>
                                </li>
                                <li>
                                    <a href="/portal/directory/branches">Branch TTPM</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <h5>Download</h5>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="/portal/download/guideline">Form Filling Guidelines</a>
                                </li>
                                <li>
                                    <a href="/portal/download/form">Filling Form</a>
                                </li>
                                <li>
                                    <a href="/portal/download/flowchart">Flow Chart</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                    <p><strong>&copy; {{date('Y')}} {{__('portal.tribunal')}}</strong></p>
                    <p>{{__('portal.copyright')}}</p>
                </div>
            </div>
        </div>
    </section>

    <div id="myModallogin" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="color:#fff;background:#115272">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><img src="assets/images/eaduanlogo-100x100.png" alt="Mobirise"
                                                 style="width:50px;margin-right:20px;">{{__('portal.title.login')}}</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal col-sm-12" role="form" method="POST"
                          action="{{ url('/user-auth') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                    <p class="text-muted text-center" >
                                            <small>{{__('portal.modal.login')}}</small></p>
                            </div>
                            <div class="form-group-md col-md-12 m-b{{ $errors->has('username') ? ' has-error' : '' }}">
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                            <input class="form-control input-md" placeholder="{{__('auth.login.lbl_ic')}}"
                                                   name="username" id="username" type="text" value="{{ old('username') }}"
                                                   required autofocus />
                                        </div>
                                        @if ($errors->has('username'))
                                            <span class="help-block" style="color: #d9534f !important;">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group-md col-md-12 m-b{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div>
                                            <div class="input-group">
                                                <div class="input-group-addon" ><i class="fa fa-unlock-alt"></i></div>
                                                <input class="form-control input-md" placeholder="{{__('auth.login.lbl_password')}}"
                                                       name="password" id="password" type="password" required/>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                        </div>

                        <button class="btn btn-primary btn-lg" type="submit"
                                style="width:100%;">{{__('auth.login.btn_login')}}</button> 
                                <br><br>
                        <a href="{{ route('password.request') }}" class="btn btn-primary btn-lg"
                           style="margin:0;width:100%;"> {{__('auth.login.btn_forget_pass')}}</a>
                        <p class="text-muted text-center" style="margin-top:10px;width:100%;text-align: center;">
                            <small>@lang('auth.home.or')</small></p>
                        <a class="btn btn-success" href="{{ url('user-register') }}"
                           style="width:100%;">{{__('auth.login.btn_register')}}</a>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0px;">
                </div>
            </div>
        </div>
    </div>

    <div id="myModalcheck" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="color:#fff;background:#115272">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><img src="assets/images/eaduanlogo-100x100.png" alt="Mobirise"
                                                 style="width:50px;">@lang('portal.title.checkcomplain')
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="GET" action="{{ url('checkcase') }}">
                        {{ csrf_field() }}
                        <div class="row">
                                <div class="col-md-12">
                                        <p class="text-muted text-center" style="margin-top:10px;width:100%;text-align: center;">
                                            <small>@lang('portal.modal.complaincheck')</small></p>
                                </div>
                        <div class="form-group-md col-md-12 {{ $errors->has('CA_CASEID') ? ' has-error' : '' }}">
                            <label class="form-group-md col-md-12">@lang('public-case.case.CA_CASEID')</label>
                            <div class="form-group-md col-md-12 m-b">
                                <input class="form-control input-md" placeholder="@lang('public-case.case.CA_CASEID')"
                                       name="CA_CASEID" id="CA_CASEID" type="text" required/>
                                @if ($errors->has('CA_CASEID'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('CA_CASEID') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group-md col-md-12 {{ $errors->has('CA_DOCNO') ? ' has-error' : '' }}">
                            <label class="form-group-md col-md-12"
                                   >@lang('auth.login.lbl_ic')</label>
                            <div class="form-group-md col-md-12">
                                <input class="form-control input-md" placeholder="@lang('auth.login.lbl_ic')"
                                       name="CA_DOCNO" id="CA_DOCNO" type="text" required/>
                                @if ($errors->has('CA_DOCNO'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('CA_DOCNO') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                        <div class="form-group-md col-md-12 m-t" align="center">
                            <button class="btn btn-primary btn-block" type="submit"
                                    >@lang('public-case.case.checkcase')</button>
                        </div>
                    </form>
                <div class="modal-footer" style="border-top: 0px;"></div>
            </div>
        </div>
    </div>
</div>

    <div id="myModalask" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="color:#fff;background:#115272">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><img src="assets/images/eaduanlogo-100x100.png" alt="Mobirise"
                                                 style="width:50px;margin-right:20px;">@lang('portal.title.checkask')
                    </h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                <div class="col-md-12">
                                        <p class="text-muted text-center" style="margin-top:10px;width:100%;text-align: center;">
                                                <small>@lang('portal.modal.enquirycheck')</small></p>
                                </div>
                    <form class="form-horizontal" role="form" method="GET" action="{{ url('checkenquiry') }}">
                        <div class="form-group-md col-md-12 {{ $errors->has('AS_ASKID') ? ' has-error' : '' }}">
                            <label class="form-group-md col-md-12 ">@lang('pertanyaan.table_header.enquiry.askid')</label>
                            <div class="form-group-md col-md-12">
                                <input class="form-control input-md"
                                       placeholder="@lang('pertanyaan.table_header.enquiry.askid')" name="AS_ASKID"
                                       id="AS_ASKID" type="text" required/>
                                @if ($errors->has('AS_ASKID'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('AS_ASKID') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group-md col-md-12{{ $errors->has('AS_MOBILENO') ? ' has-error' : '' }}">
                            <label class="form-group-md col-md-12"
                                   style="margin-top:10px;">@lang('pertanyaan.form_label.mobileno')</label>
                            <div class="form-group-md col-md-12">
                                <input class="form-control input-md"
                                       placeholder="@lang('pertanyaan.form_label.mobileno')" name="AS_MOBILENO"
                                       id="AS_MOBILENO" type="text" required/>
                                @if ($errors->has('AS_MOBILENO'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('AS_MOBILENO') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group-md col-md-12" align="center">
                            <button class="btn btn-primary" type="submit"
                                    style="margin-top:20px;width:95%;">@lang('button.check')</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0px;"></div>
            </div>
        </div>
    </div>
</div>

    <div id="myModalintegriti" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="color:#fff;background:#115272">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title">
                        <img src="assets/images/eaduanlogo-100x100.png" alt="Mobirise"
                             style="width:50px;margin-right:20px;">
                        {{ app()->getLocale() == 'ms' ? 'SEMAKAN ADUAN INTEGRITI' : 'INTEGRITY COMPLAINT CHECK' }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                                <p class="text-muted text-center" style="margin-top:10px;width:100%;text-align: center;">
                                        <small>
                                            {{
                                                app()->getLocale() == 'ms'
                                                ? 'Sila masukkan nombor aduan integriti dan nombor kad pengenalan / pasport anda untuk menyemak status aduan integriti.'
                                                : 'Please enter your complaint number and IC/Passport number to check status of a complaint.'
                                            }}
                                        </small>
                                    </p>
                        </div>
                    </div>
                    <form class="form-horizontal" role="form" method="GET" action="{{ url('checkintegriti') }}">
                        <div class="form-group-md col-md-12{{ $errors->has('IN_CASEID') ? ' has-error' : '' }}">
                            <label class="form-group-md col-md-12">
                                {{ app()->getLocale() == 'ms' ? 'No. Aduan' : 'Complaint No.' }}
                            </label>
                            <div class="form-group-md col-md-12">
                                <input class="form-control input-sm"
                                       placeholder="{{ app()->getLocale() == 'ms' ? 'No. Aduan' : 'Complaint No.' }}"
                                       name="IN_CASEID" id="IN_CASEID" type="text" required/>
                                @if ($errors->has('IN_CASEID'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('IN_CASEID') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group-md col-md-12{{ $errors->has('IN_DOCNO') ? ' has-error' : '' }}">
                            <label class="form-group-md col-md-12" style="margin-top:10px;">
                                {{ app()->getLocale() == 'ms' ? 'No. Kad Pengenalan / Pasport' : 'IC / Passport No.' }}
                            </label>
                            <div class="form-group-md col-md-12">
                                <input class="form-control input-sm"
                                       placeholder="{{ app()->getLocale() == 'ms' ? 'No. Kad Pengenalan / Pasport' : 'IC / Passport No.' }}"
                                       name="IN_DOCNO" id="IN_DOCNO" type="text" required/>
                                @if ($errors->has('IN_DOCNO'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('IN_DOCNO') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button class="btn btn-primary" type="submit"
                                    style="margin-top:20px;width:95%;">@lang('button.check')</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0px;"></div>
            </div>
        </div>
    </div>
@endsection
