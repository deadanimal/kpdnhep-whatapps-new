<!DOCTYPE html>
<?php
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

    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&display=swap" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ url('fonts/icomoon/style.css') }}"> --}}
    <link rel="stylesheet" href="{{ url('assets/portal/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/portal/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/portal/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/portal/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/portal/fonts/flaticon/font/flaticon.css') }}">
    <link rel="stylesheet" href="{{ url('assets/portal/css/aos.css') }}">
    <link rel="stylesheet" href="{{ url('assets/portal/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/portal/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('themes/inspinia/font-awesome/css/font-awesome.css') }}">
    <style>
        body {
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='20' viewBox='0 0 100 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M21.184 20c.357-.13.72-.264 1.088-.402l1.768-.661C33.64 15.347 39.647 14 50 14c10.271 0 15.362 1.222 24.629 4.928.955.383 1.869.74 2.75 1.072h6.225c-2.51-.73-5.139-1.691-8.233-2.928C65.888 13.278 60.562 12 50 12c-10.626 0-16.855 1.397-26.66 5.063l-1.767.662c-2.475.923-4.66 1.674-6.724 2.275h6.335zm0-20C13.258 2.892 8.077 4 0 4V2c5.744 0 9.951-.574 14.85-2h6.334zM77.38 0C85.239 2.966 90.502 4 100 4V2c-6.842 0-11.386-.542-16.396-2h-6.225zM0 14c8.44 0 13.718-1.21 22.272-4.402l1.768-.661C33.64 5.347 39.647 4 50 4c10.271 0 15.362 1.222 24.629 4.928C84.112 12.722 89.438 14 100 14v-2c-10.271 0-15.362-1.222-24.629-4.928C65.888 3.278 60.562 2 50 2 39.374 2 33.145 3.397 23.34 7.063l-1.767.662C13.223 10.84 8.163 12 0 12v2z' fill='%238b8b8b' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        h2, h3, h4, h5 {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            color: #2a2d34;
        }
        .header-line{
            width: 60px;
            height: 1px;
            margin: 0 auto 30px;
            border-bottom: 2px solid #2a04a2;
        }
        .header-line-white{
            width: 60px;
            height: 1px;
            margin: 0 auto 30px;
            border-bottom: 2px solid white;
        }
    </style>

    <!-- Scripts -->
    <script>
      window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

<div id="overlayer"></div>

<div class="top-bar" style="background-image: linear-gradient(to bottom left, #5c009f, #1d1da1);">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ url('') }}"><img src="{{asset('assets/portal/images/logo1white.png')}}" style="width: 10%;"></a>
                <span class="mx-md-2 d-inline-block"></span>
                <a href="#" class="text-white">
                    <span class="mr-2 icon-phone"></span>
                    <span class="d-none d-md-inline-block">Hotline: 1 - 800 - 886 - 800</span>
                </a>
                <div class="float-right">
                    @if(session()->get('locale')=='ms')
                        <a href="{{route('locale.l10n','en')}}" style="background:none;color:#fff;"><small>Change to English</small> <img src="https://eaduan.kpdnhep.gov.my/img/United-Kingdom.png" width="20px"></a>
                    @else
                        <a href="{{route('locale.l10n','ms')}}" style="background:none;color:#fff;"><small>Tukar ke Bahasa Malaysia</small> <img src="https://eaduan.kpdnhep.gov.my/img/Malaysia.png" width="20px"></a>
                    @endif
{{--                    <a href="https://www.facebook.com/mykpdnhep" class="pl-3 pr-3" target="_blank"><span--}}
{{--                                class="icon-facebook icon-purple"></span></a>--}}
{{--                    <a href="https://twitter.com/mykpdnhep" target="_blank" class="pl-3 pr-3"><span--}}
{{--                                class="icon-twitter icon-purple"></span></a>--}}
{{--                    <a href="http://instagram.com/mykpdnhep" target="_blank" class="pl-3 pr-3"><span--}}
{{--                                class="icon-instagram icon-purple"></span></a>--}}
{{--                    <a href="https://www.youtube.com/user/kpdnkktvs" target="_blank" class="pl-3 pr-3"><span--}}
{{--                                class="icon-youtube icon-purple"></span></a>--}}
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
                    <h5 class="">{{config('app.name')}}</h5>
                    <p>{{__('portal.main_portal.objective')}}</p>
                </div>
            </div>
            <div class="col-md-8">
                <h5 class="">{{__('portal.main_portal.info.title')}}</h5>
                <p>
                    <a href="{{route('portal.article', 90)}}">{{__('portal.main_portal.info.disclaimer')}}</a>
                    | <a href="{{route('portal.article', 91)}}">{{__('portal.main_portal.info.privacy_policy')}}</a>
                    | <a href="{{route('portal.article', 92)}}">{{__('portal.main_portal.info.security_policy')}}</a>
                </p>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-md-12">
                <div class="border-top pt-5">
                    <span class="copyright text-white">
                        <small>
                            <a href="{{ url('login2') }}">{{__('portal.main_portal.copyright')}}</a><br>
                            {{__('base.browser_compatibility')}}
                        </small>
                    </span>
                </div>
            </div>
        </div>

    </div>
</footer>

<script src="{{ url('assets/web/assets/jquery/jquery.min.js') }}"></script>
<script src="{{ url('assets/portal/js/popper.min.js') }}"></script>
<script src="{{ url('assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ url('assets/portal/js/owl.carousel.min.js') }}"></script>
<script src="{{ url('assets/portal/js/jquery.sticky.js') }}"></script>
<script src="{{ url('assets/portal/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ url('assets/portal/js/jquery.animateNumber.min.js') }}"></script>
<script src="{{ url('assets/portal/js/jquery.fancybox.min.js') }}"></script>
<script src="{{ url('assets/portal/js/jquery.easing.1.3.js') }}"></script>
<script src="{{ url('assets/portal/js/aos.js') }}"></script>
<script src="{{ url('assets/portal/js/main.js') }}"></script>

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
