@extends('layouts.main')
@php
    use App\Ref;
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
                <div class="ibox-content">
                    <h2>Laporan Statistik Aduan Menghasilkan Kes Mengikut Negeri & Cawangan</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="dashboard">Dashboard</a>
                        </li>
                        <li class="active">
                            <strong>Laporan Aduan Menghasilkan Kes</strong>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Carian</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        {{ Form::open(['class' => 'form-horizontal', 'method' => 'GET', 'url'=>'aduankes']) }}
                            <div class="col-lg-12">
                                <div class="form-group" id="data_5">
                                    {{ Form::label('date', 'Tarikh Penerimaan Aduan', ['class' => 'col-sm-4 control-label']) }}
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
                                <div class="form-group">
                                    {{ Form::label('state', 'Negeri', ['class' => 'col-sm-4 col-md-4 col-lg-4 control-label']) }}
                                    <div class="col-sm-8 col-md-6 col-lg-4">
                                        {{ 
                                            Form::select(
                                                'state', 
                                                Ref::GetList('17', false), 
                                                null, 
                                                [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'SEMUA NEGERI'
                                                ]
                                            ) 
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group" align="center">
                                    <!-- <div class="btn-group"> -->
                                        <a class="btn btn-outline btn-success btn-rounded" href="{{ url('aduankes') }}">
                                            <i class="fa fa-refresh"></i>&nbsp;&nbsp;Semula
                                        </a>
                                        &nbsp;
                                        {{ Form::button(
                                            '<i class="fa fa-search"></i>&nbsp;&nbsp;Carian', 
                                            [
                                                'type' => 'submit', 
                                                'class' => 'btn btn-success btn-rounded', 
                                                'name' => 'gen', 
                                                'value' => 'web'
                                            ]
                                        ) }}
                                    <!-- </div> -->
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($request->gen)
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Laporan</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="text-center">
                            <a target="_blank"
                                href="/aduankes?datestart={{ $datestart }}&dateend={{ $dateend }}&state={{ $state }}&gen=excel"
                                class="btn btn-primary btn-rounded">
                                <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Muat Turun Versi Excel
                            </a>
                            &nbsp;
                            <a target="_blank"
                                href="/aduankes?datestart={{ $datestart }}&dateend={{ $dateend }}&state={{ $state }}&gen=pdf"
                                class="btn btn-danger btn-rounded btn-outline">
                                <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Muat Turun Versi PDF
                            </a>
                        </div>
                    </div>
                    <!-- <div class="row"> -->
                        <h3 class="text-center">
                            Laporan Statistik Aduan Menghasilkan Kes Mengikut Negeri & Cawangan
                        </h3>
                        <h3 class="text-center">
                            Tarikh Penerimaan Aduan : Dari 
                            {{ $datestart->format('d-m-Y') }} 
                            Hingga 
                            {{ $dateend->format('d-m-Y') }}
                        </h3>
                        <h3 class="text-center">
                            Negeri : 
                            {{ $statedesc }}
                        </h3>
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered table-hover dataTables-example"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Bil.</th>
                                        <th style="text-align: center">Nama Cawangan</th>
                                        <th style="text-align: center">Jumlah Aduan Diterima</th>
                                        <th style="text-align: center">Aduan Menghasilkan Kes</th>
                                        <th style="text-align: center">Aduan Tidak Menghasilkan Kes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalditerima=0;
                                        $totalkes=0;
                                        $totalbukankes=0;
                                    @endphp
                                    @foreach ($query as $row)
                                        <tr>
                                            <td style="text-align: center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $row->namacawangan }}
                                            </td>
                                            <td style="text-align: center">
                                                <a target="_blank"
                                                    href="
                                                    aduankes/dd?datestart={{ $datestart->format('d-m-Y') }}&dateend={{ $dateend->format('d-m-Y') }}&state={{ $state }}&branch={{ $row->BR_BRNCD }}
                                                    ">
                                                    {{ $row->jumlahaduanterima }}
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                <a target="_blank"
                                                    href="
                                                    aduankes/dd?datestart={{ $datestart->format('d-m-Y') }}&dateend={{ $dateend->format('d-m-Y') }}&state={{ $state }}&branch={{ $row->BR_BRNCD }}&case=2
                                                    ">
                                                    {{ $row->aduankes }}
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                <a target="_blank"
                                                    href="
                                                    aduankes/dd?datestart={{ $datestart->format('d-m-Y') }}&dateend={{ $dateend->format('d-m-Y') }}&state={{ $state }}&branch={{ $row->BR_BRNCD }}&case=1
                                                    ">
                                                    {{ $row->aduanbukankes }}
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $totalditerima += $row->jumlahaduanterima;
                                            $totalkes += $row->aduankes;
                                            $totalbukankes += $row->aduanbukankes;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: center">Jumlah</th>
                                        <th style="text-align: center">
                                            <a target="_blank"
                                                href="
                                                aduankes/dd?datestart={{ $datestart->format('d-m-Y') }}&dateend={{ $dateend->format('d-m-Y') }}&state={{ $state }}
                                                ">
                                                {{ $totalditerima }}
                                            </a>
                                        </th>
                                        <th style="text-align: center">
                                            <a target="_blank"
                                                href="
                                                aduankes/dd?datestart={{ $datestart->format('d-m-Y') }}&dateend={{ $dateend->format('d-m-Y') }}&state={{ $state }}&case=2
                                                ">
                                                {{ $totalkes }}
                                            </a>
                                        </th>
                                        <th style="text-align: center">
                                            <a target="_blank"
                                                href="
                                                aduankes/dd?datestart={{ $datestart->format('d-m-Y') }}&dateend={{ $dateend->format('d-m-Y') }}&state={{ $state }}&case=1
                                                ">
                                                {{ $totalbukankes }}
                                            </a>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
    @endif
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
