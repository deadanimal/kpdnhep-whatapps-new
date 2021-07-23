@extends('layouts.main_portal')
<?php
?>
@section('content')
    <div class="site-wrap" id="home-section">
        <div class="site-mobile-menu site-navbar-target">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close mt-3">
                    <span class="icon-close2 js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>
        <header>
            <div class="container">
                <div class="row align-items-center position-relative">
                    <div class="toggle-button d-inline-block d-lg-none">
                        <a href="#" class="site-menu-toggle py-5 js-menu-toggle text-black">
                            <span class="icon-menu h3"></span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="ftco-blocks-cover-1">
            <div class="ftco-cover-1 overlay"
                 style="background-image: url({{asset('assets/portal/images/kpdnhep.jpg')}})">
                <div class="container">
                    <div class="row align-items-center text-center">
                        <div class="col-lg-12 font-white">
                            <img src="{{asset('assets/portal/images/logo1white.png')}}"
                                 style="width: 30%;margin-bottom: 15px;">
                            <h2 class="text-white">{{__('portal.main_portal.title')}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END .ftco-cover-1 -->
            <div class=" ftco-service-image-1 pb-5">
                <div class="container" style="max-width:900px">
                    <div class="owl-carousel owl-all">
                        <div class="service text-center">
                            <a href="{{ url('kepenggunaan') }}">
                                <img src="{{asset('assets/portal/images/aduanpengguna_'.session()->get('locale').'.jpg')}}" alt="aduan pengguna"
                                     class="img-fluid">
                            </a>
                        </div>
                        <div class="service text-center">
                            <a href="{{ url('integriti') }}">
                                <img src="{{asset('assets/portal/images/aduanintergriti_'.session()->get('locale').'.jpg')}}" alt="aduan integriti"
                                     class="img-fluid">
                            </a>
                        </div>
                        {{--          <div class="service text-center">--}}
                        {{--            <a href="https://helpdeskpsp.kpdnhep.gov.my/" target="_blank"><img src="assets/portal/images/aduanpsp.jpg" alt="Image" class="img-fluid"></a>--}}
                        {{--          </div>--}}
                    </div>

                    <div class="text-center">
                        <h3 style="font-family:'Raleway'">{!! __('portal.main_portal.download_ez_adu_title') !!}</h3>
                        <a href="https://play.google.com/store/apps/details?id=com.kpdnkk.ezaduforandroid&hl=en_ZA"
                           target="_blank"><img src="assets/portal/images/gplay.svg"
                                                style="width:144px"></a>
                        <a href="https://apps.apple.com/my/app/ez-adu-kpdnkk/id1012529241"
                           target="_blank"><img
                                    src="assets/portal/images/ios.svg" style="width:133px"></a></div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
      $(function () {

      })
    </script>
@stop
