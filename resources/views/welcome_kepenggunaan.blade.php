@extends('layouts.main_portal')
@section('content')
    <style>
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            color: #fff;
            background-color: #774ce1;
        }

        .nav-item > a:hover {
            color: black;
            background-color: #9797ff;
        }

        .nav-pills .nav-link {
            border-radius: 0;
        }
    </style>
    <div class="container pt-4 bg-white shadow p-3 mb-5 bg-white rounded">
        <h2 class="text-center">{{__('portal.consumerism_portal.title')}}</h2>
        <div class="header-line"></div>
        <!-- Slider(2) -->
        <div class="container d-none d-md-block">
            <div class="owl-carousel owl-theme">
                @foreach($sliderImages as $slider)
                    <div class="item" style="height:300px">
                        <img src="{{ Storage::disk('portal')->url($slider->photo) }}" alt="Image">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section2(3) -->
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-justify">
                    <div class="mb-5 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="mb-4">{{__('portal.consumerism_portal.title')}}</h3>
                        {{-- <p>Selamat Datang ke sistem e-Aduan KPDNHEP yang telah dibangunkan khusus bagi membolehkan
                            pengguna
                            membuat aduan terus ke KPDNHEP tentang perkara-perkara berkaitan perdagangan dalam negeri
                            dan
                            hal ehwal pengguna.</p> --}}
                        <p>Portal e-Aduan 2.0 merupakan sistem yang diwujudkan bagi memudahkan pengguna menyalurkan aduan/pertanyaan/cadangan berkaitan isu kepenggunaan di bawah seliaan KPDNHEP.</p>
                        {{-- <ul>
                          <li><a href="{{ url('portal/57') }}" target="_blank">Pengenalan</a></li>
                          <li><a href="{{ url('portal/58') }}" target="_blank">Manual Pengguna</a></li>
                          <li><a href="{{ url('portal/59') }}" target="_blank">Aplikasi Ez Adu</a></li>
                          <li><a href="{{ url('portal/59') }}" target="_blank">Prosedur penggunaan e-aduan KPDNHEP</a></li>
                          <li><a href="{{ url('portal/60') }}" target="_blank">Saluran Aduan KPDNHEP</a></li>
                        </ul> --}}
                        <a href="{{ url('') }}" class="btn btn-primary btn-md text-white">Halaman Utama</a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div style="padding: 0px;">
                        <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist"
                            style="background-color: #d3d3d3">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                   role="tab" aria-controls="pills-home" aria-selected="true">Aduan Baru</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                   role="tab" aria-controls="pills-profile" aria-selected="false">Semak Aduan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact"
                                   role="tab" aria-controls="pills-contact" aria-selected="false">Semak Status
                                    Pertanyaan</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3" id="pills-tabContent"
                             style="background-image: linear-gradient(to bottom left, #5c009f, #1d1da1)">
                            <!-- Aduan Baru -->
                            <div class="tab-pane fade show active in" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab">
                                <form class="form-horizontal" role="form" method="POST"
                                      action="{{ url('/user-auth') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input class="form-control input-sm"
                                                   placeholder="@lang('auth.login.lbl_ic')"
                                                   name="username" id="username" type="text"
                                                   value="{{ old('username') }}"
                                                   required autofocus/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input class="form-control input-sm"
                                                   placeholder="@lang('auth.login.lbl_password')" name="password"
                                                   id="password" type="password" required/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-center">
                                                <div class="p-2">
                                                    <button class="btn btn-warning"
                                                            type="submit">@lang('auth.login.btn_login')</button>
                                                </div>
                                                <div class="p-2">
                                                    <a class="btn btn-outline-light"
                                                       href="{{ url('user-register') }}">@lang('auth.login.btn_register')</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 text-center pt-3 pb-3">
                                            <a href="{{ route('password.request') }}"
                                               class="text-white">  @lang('auth.login.btn_forget_pass')</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Semak Aduan -->
                            <div class="tab-pane fade show" id="pills-profile" role="tabpanel"
                                 aria-labelledby="pills-profile-tab">
                                <form class="form-horizontal" role="form" method="GET" action="{{ url('checkcase') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('CA_CASEID') ? ' has-error' : '' }}">
                                        <div class="col-sm-12">
                                            <input class="form-control input-sm"
                                                   placeholder="@lang('public-case.case.CA_CASEID')"
                                                   name="CA_CASEID" id="CA_CASEID" type="text" required/>
                                            @if ($errors->has('CA_CASEID'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('CA_CASEID') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('CA_DOCNO') ? ' has-error' : '' }}">
                                        <div class="col-sm-12" style="margin-top:10px;">
                                            <input class="form-control input-sm"
                                                   placeholder="@lang('auth.login.lbl_ic')"
                                                   name="CA_DOCNO" id="CA_DOCNO" type="text" required/>
                                            @if ($errors->has('CA_DOCNO'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('CA_DOCNO') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group pb-3" align="center">
                                        <button class="btn btn-warning"
                                                type="submit">@lang('public-case.case.checkcase')</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Semak Cadangan/Maklumbalas -->
                            <div class="tab-pane fade show" id="pills-contact" role="tabpanel"
                                 aria-labelledby="pills-contact-tab">
                                <form class="form-horizontal" role="form" method="GET"
                                      action="{{ url('checkenquiry') }}">

                                    <div class="form-group{{ $errors->has('AS_ASKID') ? ' has-error' : '' }}">
                                        <div class="col-sm-12">
                                            <input class="form-control input-sm"
                                                   placeholder="@lang('pertanyaan.table_header.enquiry.askid')"
                                                   name="AS_ASKID"
                                                   id="AS_ASKID" type="text" required/>
                                            @if ($errors->has('AS_ASKID'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('AS_ASKID') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('AS_MOBILENO') ? ' has-error' : '' }}">
                                        <div class="col-sm-12" style="margin-top:10px;">
                                            <input class="form-control input-sm"
                                                   placeholder="@lang('pertanyaan.form_label.mobileno')"
                                                   name="AS_MOBILENO"
                                                   id="AS_MOBILENO" type="text" required/>
                                            @if ($errors->has('AS_MOBILENO'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('AS_MOBILENO') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group pb-3" align="center">
                                        <button class="btn btn-warning" type="submit">@lang('button.check')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Section Link External-->
        <div class="container">
            <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
                <div class="col-lg-4 m-b-sm">
                    <a href="https://api.whatsapp.com/send?phone=60192794317" target="_blank">
                        <img src="{{asset('assets/portal/images/whatsapp.jpg')}}" style="width: 100%; border-radius: 0.85rem;">
                    </a>
                </div>
                <div class="col-lg-4 m-b-sm">
                    <a href="http://flashchat.infopengguna.com/" target="_blank">
                        <img src="{{asset('assets/portal/images/livechat.jpg')}}" style="width: 100%; border-radius: 0.85rem;">
                    </a>
                </div>
                <div class="col-lg-4 m-b-sm">
                    <a href="mailto:e-aduan@kpdnhep.gov.my">
                        <img src="{{asset('assets/portal/images/emel.jpg')}}" style="width: 100%; border-radius: 0.85rem;">
                    </a>
                </div>
            </div>
        </div>

        <!--Section Link Mobile Applications-->
        <div class="text-center">
            <h3 style="font-family:'Raleway'">{!! __('portal.main_portal.download_ez_adu_title') !!}</h3>
            <a href="https://play.google.com/store/apps/details?id=com.kpdnkk.ezaduforandroid&hl=en_ZA"
               target="_blank">
                <img src="assets/portal/images/gplay.svg" style="width:144px">
            </a>
            <a href="https://apps.apple.com/my/app/ez-adu-kpdnkk/id1012529241"
               target="_blank">
                <img src="assets/portal/images/ios.svg" style="width:133px">
            </a>
        </div>

        <!--Section3(4)-->
        <div class="container">
            <div class="mt-3 mb-3" id="faq-section"
                 style="background-image: linear-gradient(to bottom left, #5c009f, #1d1da1); padding:25px">
                <div class="row">
                    <div class="block-heading-1 col-12 text-center">
                        <h3 class="text-white">Mengenai Aduan Kepenggunaan</h3>
                        <div class="header-line-white"></div>
                    </div>
                </div>
                <div class="row">
                    @foreach($section3Images as $section3)
                        <div class="col-sm-3 p-2">
                            <a href="{{ url($section3->link.'/'.$section3->menu_id) }}">
                                <img src="{{ Storage::disk('portal')->url($section3->photo) }}"
                                     style="width: 100%; border-radius: 0.85rem;">
                            </a>
                        </div>
                    @endforeach
                </div>
                {{-- <div class="row"> --}}
                {{-- <div class="col-lg-6">
                  <img src="assets/portal/images/visi.svg" style="width: 90%;margin-bottom: 35px;">
                  <img src="assets/portal/images/misi.svg" style="width: 90%;margin-bottom: 0;">
                </div> --}}
                {{-- <div class="col-lg-6"> --}}
                {{-- <h3 class="mb-4">Pengenalan Integriti KPDNHEP</h3> --}}
                <?php //$i=90; ?>

                {{-- <div class="mb-3 aos-init" data-aos="fade-up" data-aos-delay="{{$i}}"> --}}
                {{-- <a href="{{ url($Box->link.'/'.$Box->menu_id) }}"> --}}
                {{-- <h5 class="text-black font-size-18"> --}}
                {{-- <span class="icon-question_answer text-primary mr-2"></span> --}}
                {{-- {{ app()->getLocale() == 'en' ? $Box->title_en:$Box->title_my }} --}}
                {{-- </h5> --}}
                {{-- </a> --}}
                {{-- </div> --}}
                <?php //$i-10; ?>

                {{-- </div> --}}
            </div>
        </div>

        <!--Section4(5)-->
        {{--        unsed --}}
        {{--        <div class="container">--}}
        {{--            @foreach($section4Images as $section4)--}}
        {{--                <div class="site-section-cover bg-light"--}}
        {{--                     style="max-height:300px; background-image: url('{{ Storage::disk('portal')->url($section4->photo) }}')"--}}
        {{--                     data-aos="fade"></div>--}}
        {{--            @endforeach--}}
        {{--        </div>--}}
    </div>
@stop

@section('javascript')
    <script>
        $(function () {
            $('.owl-carousel').owlCarousel({
                items: 1,
                loop: true,
                autoHeight: true,
                autoplay: true,
                autoplayTimeout: 5000
            })
        })
    </script>
@stop
