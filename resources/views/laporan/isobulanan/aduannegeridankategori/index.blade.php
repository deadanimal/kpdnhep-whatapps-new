@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;
use App\Laporan\ReportYear;
use App\Laporan\BandingAduan;
?>

@section('content')
<style>
    .table-header-rotated th.row-header{
        width: auto;
    }

    .table-header-rotated td{
        width: 40px;
        border-top: 1px solid #dddddd;
        border-left: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        border-bottom: 1px solid #dddddd;
        vertical-align: middle;
        text-align: center;
    }

    .table-header-rotated th.rotate-45{
        height: 80px;
        width: 40px;
        min-width: 40px;
        max-width: 40px;
        position: relative;
        vertical-align: bottom;
        padding: 0;
        font-size: 12px;
        line-height: 0.8;
    }

    .table-header-rotated th.rotate-45 > div{
        position: relative;
        top: 0px;
        left: 40px; /* 80 * tan(45) / 2 = 40 where 80 is the height on the cell and 45 is the transform angle*/
        height: 100%;
        -ms-transform:skew(-45deg,0deg);
        -moz-transform:skew(-45deg,0deg);
        -webkit-transform:skew(-45deg,0deg);
        -o-transform:skew(-45deg,0deg);
        transform:skew(-45deg,0deg);
        overflow: hidden;
        border-left: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        border-top: 1px solid #dddddd;
        border-bottom: 1px solid #dddddd;
    }

    .table-header-rotated th.rotate-45 span {
        -ms-transform:skew(45deg,0deg) rotate(315deg);
        -moz-transform:skew(45deg,0deg) rotate(315deg);
        -webkit-transform:skew(45deg,0deg) rotate(315deg);
        -o-transform:skew(45deg,0deg) rotate(315deg);
        transform:skew(45deg,0deg) rotate(315deg);
        position: absolute;
        bottom: 35px; /* 40 cos(45) = 28 with an additional 2px margin*/
        left: -25px; /*Because it looked good, but there is probably a mathematical link here as well*/
        display: inline-block;
        // width: 100%;
        width: 85px; /* 80 / cos(45) - 40 cos (45) = 85 where 80 is the height of the cell, 40 the width of the cell and 45 the transform angle*/
        text-align: left;
        // white-space: nowrap; /*whether to display in one line or not*/
    }
</style>
<div class="row">
    <h2>LAPORAN ADUAN MENGIKUT NEGERI DAN KATEGORI ADUAN - BULANAN</h2>
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
                    {{ link_to('laporanisobulanan/aduannegeridankategori', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
            <table class="table table-striped table-header-rotated" style="width: 100%">
                <thead>
                    <tr>
                        <!-- <th style="width: 5%;text-align:center">Bil.</th> -->
                        <th>Kategori</th>
                        @foreach($ListState as $State)
                        <th class="rotate-45"><div><span>{{ $State->descr }}</span></div></th>
                        @endforeach
                        <th class="rotate-45" style="font-weight:bold"><div><span>Jumlah</span></div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $Total01 = 0;
                    $Total02 = 0;
                    $Total03 = 0;
                    $Total04 = 0;
                    $Total05 = 0;
                    $Total06 = 0;
                    $Total07 = 0;
                    $Total08 = 0;
                    $Total09 = 0;
                    $Total10 = 0;
                    $Total11 = 0;
                    $Total12 = 0;
                    $Total13 = 0;
                    $Total14 = 0;
                    $Total15 = 0;
                    $Total16 = 0;
                    $Gtotal = 0;
                    ?>
                    @foreach ($datas as $data)
                    <tr>
                        <!-- <td style="text-align:center">{{-- $i++ --}}</td> -->
                        <td>{{ $data->descr }}</td>
                        <td>{{ $data->kod_01 }}</td>
                        <td>{{ $data->kod_02 }}</td>
                        <td>{{ $data->kod_03 }}</td>
                        <td>{{ $data->kod_04 }}</td>
                        <td>{{ $data->kod_05 }}</td>
                        <td>{{ $data->kod_06 }}</td>
                        <td>{{ $data->kod_07 }}</td>
                        <td>{{ $data->kod_08 }}</td>
                        <td>{{ $data->kod_09 }}</td>
                        <td>{{ $data->kod_10 }}</td>
                        <td>{{ $data->kod_11 }}</td>
                        <td>{{ $data->kod_12 }}</td>
                        <td>{{ $data->kod_13 }}</td>
                        <td>{{ $data->kod_14 }}</td>
                        <td>{{ $data->kod_15 }}</td>
                        <td>{{ $data->kod_16 }}</td>
                        <td style="text-align:center; font-weight:bold">{{ $data->COUNT_CA_CASEID }}</td>
                    </tr>
                    <?php
                    $Total01 += $data->kod_01;
                    $Total02 += $data->kod_02;
                    $Total03 += $data->kod_03;
                    $Total04 += $data->kod_04;
                    $Total05 += $data->kod_05;
                    $Total06 += $data->kod_06;
                    $Total07 += $data->kod_07;
                    $Total08 += $data->kod_08;
                    $Total09 += $data->kod_09;
                    $Total10 += $data->kod_10;
                    $Total11 += $data->kod_11;
                    $Total12 += $data->kod_12;
                    $Total13 += $data->kod_13;
                    $Total14 += $data->kod_14;
                    $Total15 += $data->kod_15;
                    $Total16 += $data->kod_16;
                    $Gtotal += $data->COUNT_CA_CASEID;
                    ?>
                    @endforeach
                    <tr style="font-weight:bold">
                        <!-- <td></td> -->
                        <td>Jumlah</td>
                        <td>{{ $Total01 }}</td>
                        <td>{{ $Total02 }}</td>
                        <td>{{ $Total03 }}</td>
                        <td>{{ $Total04 }}</td>
                        <td>{{ $Total05 }}</td>
                        <td>{{ $Total06 }}</td>
                        <td>{{ $Total07 }}</td>
                        <td>{{ $Total08 }}</td>
                        <td>{{ $Total09 }}</td>
                        <td>{{ $Total10 }}</td>
                        <td>{{ $Total11 }}</td>
                        <td>{{ $Total12 }}</td>
                        <td>{{ $Total13 }}</td>
                        <td>{{ $Total14 }}</td>
                        <td>{{ $Total15 }}</td>
                        <td>{{ $Total16 }}</td>
                        <td>{{ $Gtotal }}</td>
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