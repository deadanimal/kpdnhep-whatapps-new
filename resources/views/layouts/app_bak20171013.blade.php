<!DOCTYPE html>
<?php
    use App\MenuCms;
?>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
    </head>

    <body class="canvas-menu">
        <div id="wrapper">
            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom">
                    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                        <div class="navbar-header">
                            <a class="minimalize-styl-2 btn btn-success" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                        <div class="navbar-collapse collapse" id="navbar">
                            <ul class="nav navbar-top-links navbar-right">
                                <!-- Authentication Links -->
                                @if (Auth::guest())
<!--                                    @foreach (MenuCMS::GetMainMenu() as $mainmenu)
                                        @if($mainmenu->menu_loc != '')
                                            <li class="{{ Request::segment(1) == $mainmenu->menu_loc? 'active':'' }}">
                                            <li>
                                                <a href="{{ url($mainmenu->menu_loc, $mainmenu->id) }}">
                                                    <span class="nav-label">{{-- $mainmenu->menu_txt --}}
                                                        {{ app()->getLocale() == 'en' ? $mainmenu->menu_txt_en : $mainmenu->menu_txt }}
                                                    </span>
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a href="/">
                                                    <span class="nav-label">{{-- $mainmenu->menu_txt --}}
                                                        {{ app()->getLocale() == 'en' ? $mainmenu->menu_txt_en : $mainmenu->menu_txt }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach-->
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> @lang('auth.main.lang') <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="{{ app()->getLocale() == 'en' ? 'active' : 'no' }}"><a href="{{ url('locale/en') }}">English</a></li>
                                            <li class="{{ app()->getLocale() == 'ms' ? 'active' : 'no' }}"><a href="{{ url('locale/ms') }}">Malay</a></li>
                                        </ul>
                                    </li>
                                    <!--<li><a href="{{ url('/') }}">Home</a></li>-->
                                    <!--<li><a href="{{ url('user-login') }}">Login</a></li>-->
                                @else
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                            {{ Auth::user()->name }} <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                                    Logout
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
                </div>
                
                <div class="container">
                    <div class="row">
                        <div class="wrapper wrapper-content  animated fadeInRight article">
                            <div class="row">
                                <!--<div class="col-sm-10 col-sm-offset-1">-->
                                <div class="col-sm-12">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="footer">
                    <div align="center">
                        <!--<a href="admin-login">  tulisan jd biru -->
                        <a href="{{ url('admin-login') }}"> <!-- tulisan jd biru -->
                            <span class="brand-copyright"><strong>Hak Cipta Terpelihara</strong> &copy; 2017 KPDNKK</span><br>
                            <!--<i class="fa fa-desktop"></i>--> 
                            <span class="brand-bestview">Paparan Terbaik Menggunakan 
                                <!--<i class="fa fa-internet-explorer"></i>-->
                                Internet Explorer 10, 
                                <!--<i class="fa fa-firefox"></i>-->
                                Mozilla Firefox 40.0, 
                                <!--<i class="fa fa-chrome"></i>-->
                                Google Chrome 53.0 Ke Atas Dengan Resolusi 1280 x 800 Ke Atas
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!--<script src="themes/inspinia/js/jquery-3.1.1.min.js"></script>-->
        <!--<script src="themes/inspinia/js/plugins/pace/pace.min.js"></script>-->
        <!--<script src="js/app.js"></script>-->
        <script src="{{ url('themes/inspinia/js/jquery-3.1.1.min.js') }}"></script>
        <script src="{{ url('themes/inspinia/js/plugins/pace/pace.min.js') }}"></script>
        <script src="{{ url('js/app.js') }}"></script>
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
