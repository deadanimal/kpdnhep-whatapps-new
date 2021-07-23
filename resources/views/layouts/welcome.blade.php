<!DOCTYPE html>
<?php
    use App\MenuCms;
?>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="assets/images/eaduanlogo-100x100.png" type="image/x-icon">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700"> --}}
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"> --}}
        {{-- <link rel="stylesheet" href="{{ url('assets/bootstrap-material-design-font/css/material.css') }}"> --}}
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic&subset=latin"> --}}
        {{-- <link rel="stylesheet" href="{{ url('assets/tether/tether.min.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ url('assets/bootstrap/css/bootstrap.min.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ url('assets/dropdown/css/style.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ url('assets/animate.css/animate.min.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ url('assets/socicon/css/styles.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ url('assets/theme/css/style.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ url('assets/mobirise/css/mbr-additional.css') }}" type="text/css"> --}}
        {{-- <link rel="stylesheet" href="{{ url('../themes/inspinia/font-awesome/css/font-awesome.min.css') }}" type="text/css"> --}}
        {{-- <link rel="stylesheet" href="https://rawgit.com/hawk-ip/jquery.tabSlideOut.js/master/jquery.tabSlideOut.css">  --}}

        <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
        {{-- <link rel="stylesheet" href="{{ url('fonts/icomoon/style.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ url('themes/inspinia/css/bootstrap.min.css') }}"> --}}
        <link rel="stylesheet" href="{{ url('assets/portal/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/portal/css/jquery.fancybox.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/portal/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/portal/css/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/portal/fonts/flaticon/font/flaticon.css') }}">
        <link rel="stylesheet" href="{{ url('assets/portal/css/aos.css') }}">
        <link rel="stylesheet" href="{{ url('assets/portal/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/portal/css/style.css') }}">

        
        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
    </head>

    <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
        
        <div id="overlayer"></div>

            <div class="top-bar">
                <div class="container">
                    <div class="row">
                    <div class="col-12">
                                <!-- <a href="#" class=""><span class="mr-2 "></span> <span class="d-none d-md-inline-block">Kementerian Perdagangan Dalam Negeri dan Hal Ehwal Pengguna</span></a> -->
                                <a href="{{ url('') }}"><img src="assets/portal/images/logo1white.png" style="width: 10%;"></a>
                                <span class="mx-md-2 d-inline-block"></span>
                                <a href="#" class=""><span class="mr-2  icon-phone"></span> <span class="d-none d-md-inline-block">Hotline: 1 - 800 - 886 - 800</span></a>
                                    <div class="float-right">
                                    <a href="https://www.facebook.com/mykpdnhep" class="pl-3 pr-3" target="_blank"><span class="icon-facebook icon-purple"></span></a>
                                    <a href="https://twitter.com/mykpdnhep" target="_blank" class="pl-3 pr-3"><span class="icon-twitter icon-purple"></span></a>
                                    <a href="http://instagram.com/mykpdnhep" target="_blank" class="pl-3 pr-3"><span class="icon-instagram icon-purple"></span></a>
                                    <a href="https://www.youtube.com/user/kpdnkktvs" target="_blank" class="pl-3 pr-3"><span class="icon-youtube icon-purple"></span></a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            @yield('content')

            <footer class="site-footer">
                <div class="container">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="row">
                        <h2 class="footer-heading mb-4">E-Aduan 2.0</h2>
                        <p>Laman E-Aduan ini dikhaskan bagi menyalurkan aduan isu-isu kepenggunaan dan intergriti untuk kegunaan
                          KPDNHEP </p>
                      </div>
                    </div>
                    <div class="col-md-8">
                        <h2 class="footer-heading mb-4">Maklumat</h2>
                        <p>
                            <a href="#">Hakcipta</a> | <a href="#">Penafian</a> | <a href="#">Dasar Privasi</a> | <a href="#">Dasar Keselamatan</a>
                        </p>
                    </div>
                  </div>
            
                  <div class="row text-center">
                    <div class="col-md-12">
                      <div class="border-top pt-5">
                        <p class="copyright">
                          <small><a href="{{ url('login2') }}">Hakcipta &copy;
                            <script>document.write(new Date().getFullYear());</script> Kementerian Perdagangan Dalam Negeri Dan Hal
                            Ehwal Pengguna (KPDNHEP) </a><br>
                            Paparan Terbaik Menggunakan Internet Explorer 10, Mozilla Firefox 40.0, Google Chrome 53.0 Ke Atas
                            Dengan Resolusi 1280 x 800 Ke Atas</p></small>
                      </div>
                    </div>
                  </div>
            
                </div>
            </footer>

        <script src="{{ url('assets/web/assets/jquery/jquery.min.js') }}"></script>
        {{-- <script src="{{ url('portal/js/jquery-3.3.1.min.js') }}"></script> --}}
        <script src="{{ url('assets/portal/js/popper.min.js') }}"></script>
        <script src="{{ url('assets/bootstrap/js/bootstrap.min.js') }}"></script>
        {{-- <script src="{{ url('portal/js/bootstrap.min.js') }}"></script> --}}
        <script src="{{ url('assets/portal/js/owl.carousel.min.js') }}"></script>
        <script src="{{ url('assets/portal/js/jquery.sticky.js') }}"></script>
        <script src="{{ url('assets/portal/js/jquery.waypoints.min.js') }}"></script>
        <script src="{{ url('assets/portal/js/jquery.animateNumber.min.js') }}"></script>
        <script src="{{ url('assets/portal/js/jquery.fancybox.min.js') }}"></script>
        <script src="{{ url('assets/portal/js/jquery.easing.1.3.js') }}"></script>
        <script src="{{ url('assets/portal/js/aos.js') }}"></script>
        <script src="{{ url('assets/portal/js/main.js') }}"></script>

        {{-- <script src="{{ url('assets/web/assets/jquery/jquery.min.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/tether/tether.min.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/bootstrap/js/bootstrap.min.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/smooth-scroll/smooth-scroll.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/touch-swipe/jquery.touch-swipe.min.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/dropdown/js/script.min.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/jquery-mb-ytplayer/jquery.mb.ytplayer.min.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/social-likes/social-likes.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/viewport-checker/jquery.viewportchecker.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/bootstrap-carousel-swipe/bootstrap-carousel-swipe.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/theme/js/script.js') }}"></script> --}}
        {{-- <script src="{{ url('assets/mobirise-slider-video/script.js') }}"></script> --}}
        {{-- <script src="https://use.fontawesome.com/2be9406092.js"></script> --}}
        {{-- <script src="https://rawgit.com/hawk-ip/jquery.tabSlideOut.js/master/jquery.tabSlideOut.js"></script> --}}

        @yield('javascript')
        <style>
            label.required:after {
                color: #cc0000;
                content: "*";
                font-weight: bold;
                margin-left: 5px;
            }
        </style> 
    </body>

</html>
