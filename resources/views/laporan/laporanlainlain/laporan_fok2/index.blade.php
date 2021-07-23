@extends('layouts.main')
@php
    {{-- use App\Laporan\ReportYear; --}}
    use App\User;
@endphp
@section('content')
    <style>
        .form-control[readonly][type="text"] {
            background-color: #ffffff;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>LAPORAN ADUAN OLEH PENGGUNA "FRIENDS OF KPDNHEP" (FOK) MENGIKUT KATEGORI</h2>
                <div class="ibox-content">
                    <div class="row">
                        {{ Form::open(['class' => 'form-horizontal', 'method' => 'GET', 'url'=>'fok2']) }}
                            <!-- <div class="col-sm-3 col-md-4"> -->
                            <!-- </div> -->
                            <!-- <div class="col-sm-6 col-md-4"> -->
                            <div class="col-sm-12">
                                <!-- <div class="form-group"> -->
                                    <!-- {{ Form::label('year', 'Tahun Daftar', ['class' => 'col-sm-6 control-label']) }} -->
                                    <!-- <div class="col-sm-6"> -->
                                        <!-- {{-- Form::select('year', ReportYear::GetByYear(false), date('Y'), ['class' => 'form-control input-sm']) --}} -->
                                        <!-- {{-- Form::select('year', User::GetListYearPublicUser(), date('Y'), ['class' => 'form-control input-sm']) --}} -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <div class="form-group" id="data_5">
                                    {{ Form::label('date', 'Tarikh Penerimaan Aduan : ', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <div class="input-daterange input-group" id="datepicker">
                                            {{ Form::text(
                                                'datestart', 
                                                date('d-m-Y', strtotime($datestart)), 
                                                [
                                                    'class' => 'form-control', 
                                                    'id' => 'datestart', 
                                                    'onkeypress' => "return false", 
                                                    'onpaste' => "return false",
                                                    'readonly' => true,
                                                    'placeholder' => 'HH-BB-TTTT'
                                                ]
                                            ) }}
                                            <span class="input-group-addon">Hingga</span>
                                            {{ Form::text(
                                                'dateend', 
                                                date('d-m-Y', strtotime($dateend)), 
                                                [
                                                    'class' => 'form-control', 
                                                    'id' => 'dateend', 
                                                    'onkeypress' => "return false", 
                                                    'onpaste' => "return false",
                                                    'readonly' => true,
                                                    'placeholder' => 'HH-BB-TTTT'
                                                ]
                                            ) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-sm-3 col-md-4">
                            </div> -->
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    <!-- {{-- Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'gen', 'value'=>'search']) --}} -->
                                    <!-- {{-- link_to('fok2', 'Semula', ['class' => 'btn btn-default']) --}} -->
                                    <a class="btn btn-default" href="{{ url('fok2') }}">
                                        <i class="fa fa-refresh"></i>&nbsp;&nbsp;Semula
                                    </a>
                                    &nbsp;
                                    {{ Form::button(
                                        '<i class="fa fa-search"></i>&nbsp;&nbsp;Carian', 
                                        [
                                            'type' => 'submit', 
                                            'class' => 'btn btn-primary', 
                                            'name'=>'gen', 
                                            'value'=>'web'
                                        ]
                                    ) }}
                                </div>
                            </div>
                            @if($request->gen)
                                <div class="col-sm-12">
                                    <div class="form-group" align="center">
                                        <!-- {{-- Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'excel']) --}} -->
                                        <!-- {{-- Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'pdf', 'formtarget' => '_blank']) --}} -->
                                        <a target="_blank"
                                            href="/fok2?datestart={{ $datestart }}&dateend={{ $dateend }}&gen=excel"
                                            class="btn btn-success">
                                            <i class="fa fa-file-excel-o"></i> Muat Turun Versi Excel
                                        </a>
                                        <a target="_blank"
                                            href="/fok2?datestart={{ $datestart }}&dateend={{ $dateend }}&gen=pdf"
                                            class="btn btn-success">
                                            <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Muat Turun Versi PDF
                                        </a>
                                    </div>
                                </div>
                            @endif
                        {{ Form::close() }}
                    </div>
                    <!-- {{-- @if($search) --}} -->
                    @if($request->gen)
                        <h3 class="text-center">
                            LAPORAN ADUAN OLEH PENGGUNA "FRIENDS OF KPDNHEP" (FOK) MENGIKUT KATEGORI
                        </h3>
                        <!-- {{-- @if($request->year) --}} -->
                        <!-- <h3 class="text-center"> -->
                            <!-- TAHUN DAFTAR: {{-- $request->year --}} -->
                        <!-- </h3> -->
                        <!-- {{-- @endif --}} -->
                        <h3 class="text-center">
                            TARIKH PENERIMAAN ADUAN : DARI 
                            {{ $datestart->format('d-m-Y') }} 
                            HINGGA 
                            {{ $dateend->format('d-m-Y') }}
                        </h3>
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered table-hover dataTables-example"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>Kategori Aduan</th>
                                        <th>Pengguna FOK</th>
                                        <th>Bukan Pengguna FOK</th>
                                        <th>Jumlah Aduan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalfok0=0;
                                        $totalfok1=0;
                                        $totalcountfokind=0;
                                    @endphp
                                    @foreach ($query as $row)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $row->descr }}
                                            </td>
                                            <td>
                                                {{ $row->fok1 }}
                                            </td>
                                            <td>
                                                {{ $row->fok0 }}
                                            </td>
                                            <td>
                                                <!-- <a target="_blank"  -->
                                                <!-- href= -->
                                                <!-- "fok2/dd?datestart={!! $datestart !!}&dateend={!! $dateend !!}&gen=web" -->
                                                <!-- > -->
                                                {{ $row->countfokind }}
                                                <!-- </a> -->
                                            </td>
                                        </tr>
                                        @php
                                            $totalfok0 += $row->fok0;
                                            $totalfok1 += $row->fok1;
                                            $totalcountfokind += $row->countfokind;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Jumlah Aduan</th>
                                        <th>{{ $totalfok1 }}</th>
                                        <th>{{ $totalfok0 }}</th>
                                        <th>{{ $totalcountfokind }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('script_datatable')
    <script type="text/javascript">
        $('#data_5 .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            todayBtn: "linked",
            todayHighlight: true,
        })
    </script>
@stop
