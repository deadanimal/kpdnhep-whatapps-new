@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;
use App\Laporan\ReportYear;
use App\Laporan\BandingAduan;
?>

@section('content')
<div class="row">
    <h2>LAPORAN STATUS ADUAN KESELURUHAN MENGIKUT NEGERI DAN BAHAGIAN - BULANAN</h2>
    {!! Form::open(['method' =>'GET', 'class' => 'form-horizontal']) !!}
    <div class="ibox-content" style="padding-bottom: 0px">
        <div class="form-group" style="padding-bottom: 0px">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group">
                    {{ Form::label('year', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('year', ReportYear::GetByYear(false), date('Y'), ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('month', 'Bulan', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('month', BandingAduan::GetRefList('206', 'sp'), null, ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                    {{ link_to('laporanisobulanan/aduanallikutnegeribahagian2', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    @if($Request->cari)
                    {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                    {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@if($Request->cari)
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
            <tr><td><center><h3>{{ $titlemonth }}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 5%;text-align:center">Bil.</th>
                        <th rowspan="2" style="width: 50%;">Negeri / Bahagian</th>
                        <th colspan="10" style="width: 8%;text-align:center">Jumlah Aduan</th>
                    </tr>
                    <tr>
                        <th>Diterima</th>
                        <th>Baru Diterima</th>
                        <th>Dalam Siasatan</th>
                        <th>Diselesaikan</th>
                        <th>Ditutup</th>
                        <th>Agensi Lain</th>
                        <th>Tribunal</th>
                        <th>Pertanyaan</th>
                        <th>Maklumat Tidak Lengkap</th>
                        <th>Tidak Berasas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $TotalDITERIMA = 0;
                    $TotalBARU = 0;
                    $TotalSIASATAN = 0;
                    $TotalSELESAI = 0;
                    $TotalTUTUP = 0;
                    $TotalAGENSILAIN = 0;
                    $TotalTRIBUNAL = 0;
                    $TotalPERTANYAAN = 0;
                    $TotalMAKLUMATXLENGKAP = 0;
                    $TotalLUARBIDANG = 0;
                    ?>
                    @foreach ($datas as $data)
                    <tr>
                        <td style="text-align:center">{{ $i++ }}</td>
                        <td>{{ $data->descr }}</td>
                        <td style="text-align:center">{{ $data->DITERIMA }}</td>
                        <td style="text-align:center">{{ $data->BARU }}</td>
                        <td style="text-align:center">{{ $data->SIASATAN }}</td>
                        <td style="text-align:center">{{ $data->SELESAI }}</td>
                        <td style="text-align:center">{{ $data->TUTUP }}</td>
                        <td style="text-align:center">{{ $data->AGENSILAIN }}</td>
                        <td style="text-align:center">{{ $data->TRIBUNAL }}</td>
                        <td style="text-align:center">{{ $data->PERTANYAAN }}</td>
                        <td style="text-align:center">{{ $data->MAKLUMATXLENGKAP }}</td>
                        <td style="text-align:center">{{ $data->LUARBIDANG }}</td>
                    </tr>
                    <?php 
                    $TotalDITERIMA += $data->DITERIMA;
                    $TotalBARU += $data->BARU;
                    $TotalSIASATAN += $data->SIASATAN;
                    $TotalSELESAI += $data->SELESAI;
                    $TotalTUTUP += $data->TUTUP;
                    $TotalAGENSILAIN += $data->AGENSILAIN;
                    $TotalTRIBUNAL += $data->TRIBUNAL;
                    $TotalPERTANYAAN += $data->PERTANYAAN;
                    $TotalMAKLUMATXLENGKAP += $data->MAKLUMATXLENGKAP;
                    $TotalLUARBIDANG += $data->LUARBIDANG;
                    ?>
                    @endforeach
                    @foreach ($datap as $data)
                    <tr>
                        <td style="text-align:center">{{ $i++ }}</td>
                        <td>{{ $data->BR_BRNNM }}</td>
                        <td style="text-align:center">{{ $data->DITERIMA }}</td>
                        <td style="text-align:center">{{ $data->BARU }}</td>
                        <td style="text-align:center">{{ $data->SIASATAN }}</td>
                        <td style="text-align:center">{{ $data->SELESAI }}</td>
                        <td style="text-align:center">{{ $data->TUTUP }}</td>
                        <td style="text-align:center">{{ $data->AGENSILAIN }}</td>
                        <td style="text-align:center">{{ $data->TRIBUNAL }}</td>
                        <td style="text-align:center">{{ $data->PERTANYAAN }}</td>
                        <td style="text-align:center">{{ $data->MAKLUMATXLENGKAP }}</td>
                        <td style="text-align:center">{{ $data->LUARBIDANG }}</td>
                    </tr>
                    <?php 
                    $TotalDITERIMA += $data->DITERIMA;
                    $TotalBARU += $data->BARU;
                    $TotalSIASATAN += $data->SIASATAN;
                    $TotalSELESAI += $data->SELESAI;
                    $TotalTUTUP += $data->TUTUP;
                    $TotalAGENSILAIN += $data->AGENSILAIN;
                    $TotalTRIBUNAL += $data->TRIBUNAL;
                    $TotalPERTANYAAN += $data->PERTANYAAN;
                    $TotalMAKLUMATXLENGKAP += $data->MAKLUMATXLENGKAP;
                    $TotalLUARBIDANG += $data->LUARBIDANG;
                    ?>
                    @endforeach
                    <tr style="font-weight:bold">
                        <!-- <td></td> -->
                        <td colspan="2" style="text-align:center">Jumlah</td>
                        <td style="text-align:center">{{ $TotalDITERIMA }}</td>
                        <td style="text-align:center">{{ $TotalBARU }}</td>
                        <td style="text-align:center">{{ $TotalSIASATAN }}</td>
                        <td style="text-align:center">{{ $TotalSELESAI }}</td>
                        <td style="text-align:center">{{ $TotalTUTUP }}</td>
                        <td style="text-align:center">{{ $TotalAGENSILAIN }}</td>
                        <td style="text-align:center">{{ $TotalTRIBUNAL }}</td>
                        <td style="text-align:center">{{ $TotalPERTANYAAN }}</td>
                        <td style="text-align:center">{{ $TotalMAKLUMATXLENGKAP }}</td>
                        <td style="text-align:center">{{ $TotalLUARBIDANG }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@stop
@section('script_datatable')
@stop