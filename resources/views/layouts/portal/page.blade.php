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

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
        <link rel="stylesheet" href="{{ url('assets/bootstrap-material-design-font/css/material.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic&subset=latin">
        <link rel="stylesheet" href="{{ url('assets/tether/tether.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/dropdown/css/style.css') }}">
        <link rel="stylesheet" href="{{ url('assets/animate.css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/socicon/css/styles.css') }}">
        <link rel="stylesheet" href="{{ url('assets/theme/css/style.css') }}">
        <link rel="stylesheet" href="{{ url('assets/mobirise/css/mbr-additional.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ url('../themes/inspinia/font-awesome/css/font-awesome.min.css') }}" type="text/css">
        <link rel="stylesheet" href="https://rawgit.com/hawk-ip/jquery.tabSlideOut.js/master/jquery.tabSlideOut.css"> 
        
        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
    </head>

    <body>
                
        @yield('content')

        <script src="{{ url('assets/web/assets/jquery/jquery.min.js') }}"></script>
        <script src="{{ url('assets/tether/tether.min.js') }}"></script>
        <script src="{{ url('assets/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ url('assets/smooth-scroll/smooth-scroll.js') }}"></script>
        <script src="{{ url('assets/touch-swipe/jquery.touch-swipe.min.js') }}"></script>
        <script src="{{ url('assets/dropdown/js/script.min.js') }}"></script>
        <script src="{{ url('assets/jquery-mb-ytplayer/jquery.mb.ytplayer.min.js') }}"></script>
        <script src="{{ url('assets/social-likes/social-likes.js') }}"></script>
        <script src="{{ url('assets/viewport-checker/jquery.viewportchecker.js') }}"></script>
        <script src="{{ url('assets/bootstrap-carousel-swipe/bootstrap-carousel-swipe.js') }}"></script>
        <script src="{{ url('assets/theme/js/script.js') }}"></script>
        <script src="{{ url('assets/mobirise-slider-video/script.js') }}"></script>
        <script src="https://use.fontawesome.com/2be9406092.js"></script>
        <script src="https://rawgit.com/hawk-ip/jquery.tabSlideOut.js/master/jquery.tabSlideOut.js"></script>
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
