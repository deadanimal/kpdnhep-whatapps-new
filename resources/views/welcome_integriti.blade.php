@extends('layouts.main_portal')
@section('content')
    <div class="container pt-4 bg-white shadow p-3 mb-5 bg-white rounded">
        <h3 class="text-center">ADUAN INTEGRITI</h3>
        <div class="header-line"></div>
        <!-- Slider 102 -->
        <div class="container d-none d-md-block">
            <div class="owl-carousel owl-theme">
                @foreach($sliderImages as $Slider)
                    <div class="item">
                        <img src="{{ Storage::disk('portal')->url($Slider->photo) }}" alt="Image">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2 103 -->
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-justify">
                    <div class="aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{asset('assets/portal/images/nogift2.png')}}"
                             style="width: 534px;margin-bottom: 10px;">
                        <p>Tuan/puan amat dialu-alukan menggunakan perkhidmatan ini jika mempunyai sebarang
                            aduan/maklumat
                            integriti mengenai KPDNHEP &amp; agensi di bawahnya seperti <strong>rasuah, penyelewengan,
                                salah
                                guna kuasa, kelemahan sistem dan tatacara kerja dan salah laku</strong>. Aduan anda akan
                            membantu untuk meningkatkan profesionalisme, kecekapan dan tadbir urus di Kementerian ini.​
                        </p>
                        {{-- <ul>
                          <a href="http://online.fliphtml5.com/kjmoc/zcng/" target="_blank"><li>Polisi Tiada Hadiah</li></a>
                          <li>Prosedur dan Proses Aduan</li>
                          <li>Akta Perlindungan Pemberi Maklumat  2010(711)</li>
                          <li>Manual Pengguna </li>
                        </ul> --}}
                        <a href="{{ url('') }}" class="btn btn-primary btn-md text-white">Halaman Utama</a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div style="background-image: linear-gradient(to bottom left, #5c009f, #1d1da1); padding: 0px; border-style: solid; border-color: #d3d3d3; border-radius: 0.25rem">
                        <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist" style="background-color: #d3d3d3">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                   role="tab" aria-controls="pills-home" aria-selected="true">
                                    Daftar Masuk
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                   role="tab" aria-controls="pills-profile" aria-selected="false">
                                    Semak Aduan
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3" id="pills-tabContent" style="background-image: linear-gradient(to bottom left, #5c009f, #1d1da1)">
                            <!-- Aduan Baru -->
                            <div class="tab-pane fade show active in" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('/user-auth') }}">
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
                                                    <button class="btn btn-warning" type="submit">
                                                        @lang('auth.login.btn_login')
                                                    </button>
                                                </div>
                                                <div class="p-2">
                                                    <a class="btn btn-outline-light" href="{{ url('user-register') }}">
                                                        @lang('auth.login.btn_register')
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 text-center pt-3 pb-3">
                                            <a href="{{ route('password.request') }}" class="text-white">
                                                @lang('auth.login.btn_forget_pass')
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Semak Aduan -->
                            <div class="tab-pane fade show" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <form class="form-horizontal" role="form" method="GET" action="{{ url('checkintegriti') }}">
                                    {{-- <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist"
                                        style="background-color: #d3d3d3; height: 45px;"> --}}
                                    {{-- </ul> --}}
                                    <div class="form-group{{ $errors->has('IN_CASEID') ? ' has-error' : '' }}">
                                        <div class="col-sm-12">
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
                                    <div class="form-group{{ $errors->has('IN_DOCNO') ? ' has-error' : '' }}">
                                        <div class="col-sm-12" style="margin-top:10px;">
                                            <input class="form-control input-sm"
                                                   placeholder="{{ app()->getLocale() == 'ms' ? 'No. Kad Pengenalan / Pasport' : 'IC / Passport No.' }}"
                                                   name="IN_DOCNO" id="IN_DOCNO" type="text"/>
                                            @if ($errors->has('IN_DOCNO'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('IN_DOCNO') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row pb-5 pt-">
                                        <div class="col-sm-12" align="center">
                                            <button class="btn btn-warning" type="submit">@lang('button.check')</button>
                                            <a class="btn btn-success"
                                               href="{{ url('integritipublicuser/create') }}">
                                                {{ app()->getLocale() == 'ms' ? 'ADUAN BARU INTEGRITI' : 'INTEGRITY NEW COMPLAINT' }}
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3 104 -->
        <div class="container">
            <div class="mt-3 mb-3" id="faq-section"
                 style="background-image: linear-gradient(to bottom left, #5c009f, #1d1da1); padding:25px">

                <div class="row">
                    <div class="block-heading-1 col-12 text-center">
                        <h4 class="text-white">Mengenai Integriti KPDNHEP</h4>
                        <div class="header-line-white"></div>
                    </div>
                </div>

                <div class="row">
                    <?php $i = 90; ?>
                    @foreach($section3Images as $Box)
                        <div class="col-sm-3 p-2">
                            <a href="{{ url($Box->link.'/'.$Box->menu_id) }}">
                                <img src="{{ Storage::disk('portal')->url($Box->photo) }}"
                                     style="width: 100%; border-radius: 0.85rem;">
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        <!-- Section Complaint Channel -->
        <div class="container">
            <div class="mt-3 mb-3" id="faq-section" style="padding:25px">
                <div class="row">
                    <div class="block-heading-1 col-12 text-center">
                        <img src="{{asset('assets/portal/images/saluranintegriti.jpg')}}" style="margin-bottom: 10px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4 105 -->
        <div class="container">
            @foreach($section4Images as $Section4)
                <div class="site-section-cover bg-light"
                     style="max-height:300px; background-image: url('{{ Storage::disk('portal')->url($Section4->photo) }}')"
                     data-aos="fade"></div>
            @endforeach
        </div>
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
