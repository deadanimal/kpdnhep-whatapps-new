<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        
        <!--Select2-->
        <link href="{{ url('themes/inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
        <link href="{{ url('themes/inspinia/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ url('themes/inspinia/css/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet">
        
        <!-- Sweet Alert -->
        <link href="{{ url('themes/inspinia/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

        <link href="{{ url('themes/inspinia/css/plugins/dataTables/datatables.tribunal.min.css') }}" rel="stylesheet">
    </head>

    <!--<body class="top-navigation">-->
    <body class="top-navigation">

        <div id="wrapper">
            <!--<div id="page-wrapper" class="gray-bg" style="background-color: #115272; ">-->
            <div id="page-wrapper" class="gray-bg" style="background: #fff">
                @include('layouts.top_public')
                <div class="wrapper wrapper-content animated fadeInRight">
                    @if (session()->has('success'))
                    <div id="xalert" class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{ session()->get('success') }}
                    </div>
                    @elseif (session()->has('warning'))
                    <div id="xalert" class="alert alert-warning alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{ session()->get('warning') }}
                    </div>
                    @elseif (session()->has('alert'))
                    <div id="xalert" class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{ session()->get('alert') }}
                    </div>
                    @elseif (session()->has('info'))
                    <div id="xalert" class="alert alert-info alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{ session()->get('info') }}
                    </div>
                    @endif
                    <style>
                        .top-navigation .nav > li a:hover, .top-navigation .nav > li a:focus {
                            background: #115272;
                        }
                        .tabs-container .nav-tabs > li.active > a,
                        .tabs-container .nav-tabs > li.active > a:hover {
                            background-color: #e7eaec;
                            color: black;
                        }
                        .tabs-container .nav > li a {
                            color: black;
                            background-color: #fac626;
                        }
                        .tabs-container .nav > li a:hover {
                            color: white;
                            background-color: #115272;
                        }
                        .top-navigation .nav > li > a {
                            background-color: #fff;
                        }
/*                        .tabs-container .nav-tabs > li.active > a, .tabs-container .nav-tabs > li.active {
                            background-color: #65b8ed;
                        }*/
                        .tabs-container .nav-tabs > li a:hover {
                            background-color: #115272;
                            color: white;
                        }
                        .tabs-container .nav-tabs > li a {
                            background-color: #fac626;
                            color: black;
                        }
                        .tabs-container .nav-tabs > li.active > a {
                            background-color:#efefef;
                            color: black;
                        }
                        .tabs-container .nav-tabs > li.active > a:hover {
                            background-color: #efefef;
                            color: black;
                        }
                        .top-navigation .nav > li a:hover {
                            color: white;
                        }
                        .tabs-container .nav-tabs > li.active > a,
                        .tabs-container .nav-tabs > li.active {

                        }
                        table.dataTable thead .sorting,
                        table.dataTable thead .sorting_asc::after, 
                        table.dataTable thead .sorting_desc, 
                        table.dataTable thead .sorting_asc_disabled, 
                        table.dataTable thead .sorting_desc_disabled,
                        .table-bordered > thead > tr > th {
                            background-color: #c2c2c2;
                        }
                        table.table-bordered.dataTable tbody td {
                            background-color: white;
                        }
                    </style>
                    @yield('content')
                </div>
            </div>
        </div>
        <footer class="mbr-small-footer mbr-section mbr-section-nopadding" id="footer1-26" data-rv-view="98" style="background-color: rgb(13, 65, 91); padding-top: 1.75rem; padding-bottom: 1.75rem; text-align:center;border-top: 3px solid #fac626;color:#fff">

            <div class="container text-xs-center">
                <p>@lang('auth.home.copyright')</p>
            </div>
        </footer>



    <!-- Mainly scripts -->
    <script src="{{ asset('js/jquery-2.1.1.js') }}"></script>
    <script src="{{ url('js/store.js') }}"></script>
    <script src="{{ url('js/jquery-idleTimeout.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Data Tables -->
    {{-- <script src="{{ url('js/plugins/dataTables/datatables.min.js') }}"></script> --}}
    <script src="{{ url('js/plugins/dataTables/datatables.js') }}"></script>
    <script src="{{ url('js/plugins/dataTables/Buttons-1.4.2/js/buttons.colVis.min.js') }}"></script>
    
    <!--Select2-->
    <script src="{{ url('themes/inspinia/js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ url('themes/inspinia/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <script src="{{ url('themes/inspinia/js/plugins/ladda/spin.min.js') }}"></script>
    <script src="{{ url('themes/inspinia/js/plugins/ladda/ladda.min.js') }}"></script>
    <script src="{{ url('themes/inspinia/js/plugins/ladda/ladda.jquery.min.js') }}"></script>
    
    <!-- Sweet alert -->
    <script src="{{ url('themes/inspinia/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Idle Timer plugin -->
    <script src="{{ url('themes/inspinia/js/plugins/idle-timer/idle-timer.min.js') }}"></script>

    <!-- Page-Level Scripts -->
    @yield('javascript')
    <script>
        $(document).ready(function () {
            // Set idle time
            $( document ).idleTimer( 1200000 );
        });
        $( document ).on( "idle.idleTimer", function(event, elem, obj){
            swal({
                title: "@lang('public-case.alert.title')",
                text: "@lang('public-case.alert.text')",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "@lang('public-case.alert.cancelbuttontext')",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('public-case.alert.confirmbuttontext')",
                timer: 600000
            },
            function (isConfirm) {
                if (isConfirm) {
                    window.location = "/authlogout";
                } else {
                    window.location = "";
                }
            });
        });
    </script>
    <style>
        body.DTTT_Print {
            background: #fff;

        }
        .DTTT_Print #page-wrapper {
            margin: 0;
            background:#fff;
        }

        button.DTTT_button, div.DTTT_button, a.DTTT_button {
            border: 1px solid #e7eaec;
            background: #fff;
            color: #676a6c;
            box-shadow: none;
            padding: 6px 8px;
        }
        button.DTTT_button:hover, div.DTTT_button:hover, a.DTTT_button:hover {
            border: 1px solid #d2d2d2;
            background: #fff;
            color: #676a6c;
            box-shadow: none;
            padding: 6px 8px;
        }

        .dataTables_filter label {
            margin-right: 5px;
        }
        
        label.required:after {
            color: #cc0000;
            content: "*";
            font-weight: bold;
            margin-left: 5px;
        }
    </style>
</body>

</html>
