@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportYear;
?>

@section('content')
<?php
$filename = 'Raw-Data.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
?>
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
<table class="table table-striped table-header-rotated" style="width: 90%" border='1'>
    <thead>
        <tr>
            <th style="width: 3%">Bil.</th>
            <th style="width: 20%">Cara Penerimaan</th>
            @foreach ($SenaraiNegeri as $Negeri)
            <th class="rotate-45"><div><span>{{ $Negeri->descr }}</span></div></th>
            @endforeach
            <th class="rotate-45"><div><span>Jumlah</span></div></th>
        </tr>
    </thead>
    <tbody>
        @if($cari)
        <?php
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
        <?php $i = 1;?>
        @foreach ($query as $data)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $data->descr }}</td>
            <td>{{ $data->KOD01 }}</td>
            <td>{{ $data->KOD02 }}</td>
            <td>{{ $data->KOD03 }}</td>
            <td>{{ $data->KOD04 }}</td>
            <td>{{ $data->KOD05 }}</td>
            <td>{{ $data->KOD06 }}</td>
            <td>{{ $data->KOD07 }}</td>
            <td>{{ $data->KOD08 }}</td>
            <td>{{ $data->KOD09 }}</td>
            <td>{{ $data->KOD10 }}</td>
            <td>{{ $data->KOD11 }}</td>
            <td>{{ $data->KOD12 }}</td>
            <td>{{ $data->KOD13 }}</td>
            <td>{{ $data->KOD14 }}</td>
            <td>{{ $data->KOD15 }}</td>
            <td>{{ $data->KOD16 }}</td>
            <td>{{ $data->Bilangan }}</td>
        </tr>
        <?php
        $Total01 += $data->KOD01;
        $Total02 += $data->KOD02;
        $Total03 += $data->KOD03;
        $Total04 += $data->KOD04;
        $Total05 += $data->KOD05;
        $Total06 += $data->KOD06;
        $Total07 += $data->KOD07;
        $Total08 += $data->KOD08;
        $Total09 += $data->KOD09;
        $Total10 += $data->KOD10;
        $Total11 += $data->KOD11;
        $Total12 += $data->KOD12;
        $Total13 += $data->KOD13;
        $Total14 += $data->KOD14;
        $Total15 += $data->KOD15;
        $Total16 += $data->KOD16;
        $Gtotal += $data->Bilangan
        ?>
        @endforeach
        <tr>
            <td></td>
            <td>Jumlah</td>
            <td>{{ $Total01 }}</a></td>
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
        @endif
    </tbody>
</table>
<?php 
exit;
fclose($fp);
?>
@stop