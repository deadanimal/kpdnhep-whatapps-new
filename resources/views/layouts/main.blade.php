<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Dual List Script -->
    <link href="{{ url('../themes/inspinia/css/plugins/dualListbox/bootstrap-duallistbox.min.css') }}" rel="stylesheet">
    <link href="{{ url('../themes/inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">

    <!-- Ladda style -->
    <link href="{{ url('themes/inspinia/css/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet">

    <link href="{{ url('themes/inspinia/css/plugins/dataTables/datatables.tribunal.min.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ url('themes/inspinia/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

    <script src="{{url('ckeditor/ckeditor.js')}}"></script>
    <style>
        .filter {
            display: none !important;
        }

        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc::after,
        table.dataTable thead .sorting_desc,
        table.dataTable thead .sorting_asc_disabled,
        table.dataTable thead .sorting_desc_disabled,
        .table-bordered > thead > tr > th {
            background-color: #115272;
            color: white;
        }
    </style>
</head>

<body>

<!--<div id="wrapper">-->
<div id="wrapper" style="background:#115272;">
    @include('layouts.left')
    <div id="page-wrapper" class="gray-bg">
    @include('layouts.top')
    <!--@include('layouts.breadcrumb')-->
        <div class="wrapper wrapper-content animated fadeInRight">
            @if (session()->has('success'))
                <div id="xalert" class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?= session()->get('success'); ?>
                </div>
            @elseif (session()->has('warning'))
                <div id="xalert" class="alert alert-warning alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?= session()->get('warning'); ?>
                </div>
            @elseif (session()->has('alert'))
                <div id="xalert" class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?= session()->get('alert'); ?>
                </div>
            @elseif (session()->has('info'))
                <div id="xalert" class="alert alert-info alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?= session()->get('info'); ?>
                </div>
            @endif

            @yield('content')

        </div>
        @include('layouts.bottom')

    </div>
</div>
<script>
  //            setTimeout(function () {
  //                $('#xalert').hide('slow');
  //            }, 5000);
</script>

<!-- Mainly scripts -->
<script src="{{ url('js/jquery-2.1.1.js') }}"></script>
<script src="{{ url('js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ url('js/plugins/pace/pace.min.js') }}"></script>
<script src="{{ url('js/store.js') }}"></script>
<script src="{{ url('js/jquery-idleTimeout.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<!-- HighChart -->
<script src="{{ url('js/plugins/highcharts/highcharts.js') }}"></script>
<script src="{{ url('js/plugins/highcharts/highcharts-3d.js') }}"></script>
<script src="{{ url('js/plugins/highcharts/modules/exporting.js') }}"></script>
<!-- Data Tables -->
<script src="{{ url('js/plugins/dataTables/datatables.js') }}"></script>
<script src="{{ url('js/plugins/dataTables/Buttons-1.4.2/js/buttons.colVis.min.js') }}"></script>
<script src="{{ url('../themes/ace/js/moment.js') }}"></script>
<script src="{{ url('../themes/ace/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ url('../themes/ace/js/daterangepicker.js') }}"></script>
<script src="{{ url('../themes/ace/js/bootstrap-timepicker.js') }}"></script>
<script src="{{ url('../themes/ace/js/bootstrap-datetimepicker.js') }}"></script>

<!-- Dual Listbox -->
<script src="{{ url('../themes/inspinia/js/plugins/dualListbox/jquery.bootstrap-duallistbox.js') }}"></script>
<script src="{{ url('../themes/inspinia/js/plugins/select2/select2.full.min.js') }}"></script>

<!-- Ladda -->
<script src="{{ url('themes/inspinia/js/plugins/ladda/spin.min.js') }}"></script>
<script src="{{ url('themes/inspinia/js/plugins/ladda/ladda.min.js') }}"></script>
<script src="{{ url('themes/inspinia/js/plugins/ladda/ladda.jquery.min.js') }}"></script>

<!-- Sweet alert -->
<script src="{{ url('themes/inspinia/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<!-- Idle Timer plugin -->
<!--<script src="js/plugins/idle-timer/idle-timer.min.js"></script>-->
<script src="{{ url('themes/inspinia/js/plugins/idle-timer/idle-timer.min.js') }}"></script>

<!-- Page-Level Scripts -->
@yield('script_datatable')

<!-- Dual List Script-->
<script>
  $(document).ready(function () {
    $('.dual_select').bootstrapDualListbox({
      selectorMinimalHeight: 160
    })
    $(document).idleTimer(1200000)
  })
  $(document).on('idle.idleTimer', function (event, elem, obj) {
    swal({
        title: 'Peringatan',
        text: 'Kerana anda tidak aktif menggunakan sistem, sesi anda akan tamat.',
        type: 'warning',
        timer: 600000,
        showCancelButton: true,
        cancelButtonText: 'Kekal Di Log Masuk',
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Log Keluar'
      },
      function (isConfirm) {
        if (isConfirm) {
          window.location = '/authlogout'
        } else {
          window.location = ''
        }
      })
  })
</script>

<style>
    body.DTTT_Print {
        background: #fff;

    }

    .DTTT_Print #page-wrapper {
        margin: 0;
        background: #fff;
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

    label.required1:after {
        color: #cc0000;
        content: "**";
        font-weight: bold;
        margin-left: 5px;
    }

    #idletimer_warning_dialog {
        background-color: white;
    }

    .ui-dialog-buttonset {
        background-color: white;
        text-align: center;
    }
</style>
</body>

</html>
