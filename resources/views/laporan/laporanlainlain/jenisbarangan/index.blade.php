@extends('layouts.main')
@php
    use App\Integriti\IntegritiAdmin;
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
                <h2>LAPORAN JENIS BARANGAN</h2>
                <div class="ibox-content">
                    <div class="row">
                        {{ Form::open(['class' => 'form-horizontal', 'method' => 'GET', 'url'=>'jenisbarangan']) }}
                            <div class="col-sm-12">
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
                                <div class="form-group">
                                    {{ Form::label('category', 'Kategori Aduan', ['class' => 'col-sm-4 col-md-4 col-lg-4 control-label']) }}
                                    <div class="col-sm-8 col-md-6 col-lg-4">
                                        {{ 
                                            Form::select(
                                                'category', 
                                                IntegritiAdmin::GetStatusList('244', ['BPGK 03','BPGK 01'], false), 
                                                null, 
                                                [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Semua'
                                                ]
                                            ) 
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    <a class="btn btn-default" href="{{ url('jenisbarangan') }}">
                                        <i class="fa fa-refresh"></i>&nbsp;&nbsp;Semula
                                    </a>
                                    &nbsp;
                                    {{ Form::button(
                                        '<i class="fa fa-search"></i>&nbsp;&nbsp;Carian', 
                                        [
                                            'type' => 'submit', 
                                            'class' => 'btn btn-success', 
                                            'name' => 'gen', 
                                            'value' => 'web'
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
                                            href="/jenisbarangan?datestart={{ $datestart }}&dateend={{ $dateend }}&category={{ $category }}&gen=excel"
                                            class="btn btn-primary">
                                            <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Muat Turun Versi Excel
                                        </a>
                                        <!-- <a target="_blank" -->
                                            <!-- href="/jenisbarangan?datestart={{ $datestart }}&dateend={{ $dateend }}&gen=pdf" -->
                                            <!-- class="btn btn-success"> -->
                                            <!-- <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Muat Turun Versi PDF -->
                                        <!-- </a> -->
                                    </div>
                                </div>
                            @endif
                        {{ Form::close() }}
                    </div>
                    @if($request->gen)
                        <h3 class="text-center">
                            LAPORAN JENIS BARANGAN
                        </h3>
                        <h3 class="text-center">
                            TARIKH PENERIMAAN ADUAN : DARI 
                            {{ $datestart->format('d-m-Y') }} 
                            HINGGA 
                            {{ $dateend->format('d-m-Y') }}
                        </h3>
                        <h3 class="text-center">
                            KATEGORI ADUAN : 
                            {{ $categorydesc }}
                            <!-- {{-- $category --}} -->
                        </h3>
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered table-hover dataTables-example"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Bil.</th>
                                        <th rowspan="2">Jenis Barangan</th>
                                        <th rowspan="2">Diterima</th>
                                        <th colspan="3">Belum Selesai</th>
                                        <th colspan="6">Tindakan Aduan</th>
                                    </tr>
                                    <tr>
                                        <!-- <th>Bil.</th> -->
                                        <!-- <th>Jenis Barangan</th> -->
                                        <!-- <th>Diterima</th> -->
                                        <th>Aduan Baru</th>
                                        <th>Dalam Siasatan</th>
                                        <th>Maklumat Tidak Lengkap</th>
                                        <th>Diselesaikan</th>
                                        <th>Ditutup</th>
                                        <th>Agensi KPDNHEP</th>
                                        <th>Tribunal</th>
                                        <th>Pertanyaan</th>
                                        <th>Luar Bidang Kuasa</th>
                                        <!-- <th>Jumlah Aduan</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total=0;
                                        $totalbelumagih=0;
                                        $totaldalamsiasatan=0;
                                        $totalmaklumatxlengkap=0;
                                        $totalselesai=0;
                                        $totaltutup=0;
                                        $totalagensi=0;
                                        $totaltribunal=0;
                                        $totalpertanyaan=0;
                                        $totalluarbidang=0;
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
                                                {{ $row->counttotal }}
                                            </td>
                                            <td>
                                                {{ $row->BELUMAGIH }}
                                            </td>
                                            <td>
                                                {{ $row->DALAMSIASATAN }}
                                            </td>
                                            <td>
                                                {{ $row->MAKLUMATXLENGKAP }}
                                            </td>
                                            <td>
                                                {{ $row->SELESAI + $row->SELESAIMAKLUMATXLENGKAP }}
                                            </td>
                                            <td>
                                                {{ $row->TUTUP + $row->TUTUPMAKLUMATXLENGKAP }}
                                            </td>
                                            <td>
                                                {{ $row->AGENSILAIN }}
                                            </td>
                                            <td>
                                                {{ $row->TRIBUNAL }}
                                            </td>
                                            <td>
                                                {{ $row->PERTANYAAN }}
                                            </td>
                                            <td>
                                                {{ $row->LUARBIDANG }}
                                            </td>
                                        </tr>
                                        @php
                                            $total += $row->counttotal;
                                            $totalbelumagih += $row->BELUMAGIH;
                                            $totaldalamsiasatan += $row->DALAMSIASATAN;
                                            $totalmaklumatxlengkap += $row->MAKLUMATXLENGKAP;
                                            $totalselesai += $row->SELESAI + $row->SELESAIMAKLUMATXLENGKAP;
                                            $totaltutup += $row->TUTUP + $row->TUTUPMAKLUMATXLENGKAP;
                                            $totalagensi += $row->AGENSILAIN;
                                            $totaltribunal += $row->TRIBUNAL;
                                            $totalpertanyaan += $row->PERTANYAAN;
                                            $totalluarbidang += $row->LUARBIDANG;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Jumlah Aduan</th>
                                        <th>{{ $total }}</th>
                                        <th>{{ $totalbelumagih }}</th>
                                        <th>{{ $totaldalamsiasatan }}</th>
                                        <th>{{ $totalmaklumatxlengkap }}</th>
                                        <th>{{ $totalselesai }}</th>
                                        <th>{{ $totaltutup }}</th>
                                        <th>{{ $totalagensi }}</th>
                                        <th>{{ $totaltribunal }}</th>
                                        <th>{{ $totalpertanyaan }}</th>
                                        <th>{{ $totalluarbidang }}</th>
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
