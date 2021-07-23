@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;
use App\Laporan\ReportYear;
use App\Laporan\BandingAduan;
use App\Laporan\TerimaSelesaiAduan;
?>

@section('content')
<div class="row">
    <h2>PERBANDINGAN SEPULUH (10) KATEGORI ADUAN TERTINGGI - KUMULATIF</h2>
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
            </div>
            <div class="col-sm-12 col-sm-offset-3" align="center">
                <div class="form-group">
                    {{ Form::label('CA_RCVDT_MONTH', 'Bulan', ['class' => 'col-sm-1 control-label']) }}
                    <div class="col-sm-5">
                        <div class="input-group">
                            {{ Form::select('CA_RCVDT_MONTH_FROM', TerimaSelesaiAduan::GetMonth(), null, ['class' => 'form-control input-sm']) }}
                            <span class="input-group-addon">hingga</span>
                            {{ Form::select('CA_RCVDT_MONTH_TO', TerimaSelesaiAduan::GetMonth(), null, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                    {{ link_to('laporanisokumulatif/perbandingansepuluhkategori', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
                        <th style="width: 5%;text-align:center">Bil.</th>
                        <th style="width: 50%;">Kategori</th>
                        <th style="width: 8%;text-align:center">Jumlah Aduan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    ?>
                    @foreach ($datas as $data)
                    <tr>
                        <td style="text-align:center">{{ $i++ }}</td>
                        <td>{{ $data->descr }}</td>
                        <td style="text-align:center">{{ $data->countkategori }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@stop
@section('script_datatable')
@stop