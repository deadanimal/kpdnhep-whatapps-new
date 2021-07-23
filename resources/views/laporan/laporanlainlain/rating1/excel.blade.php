@extends('layouts.main')

@section('content')
<?php
$filename = 'Laporan-Rating-'.date('Ymd_His').'.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
?>
<div class="table-responsive">
    <table style="width: 100%;">
        <tr><td colspan="12"><center><h3>LAPORAN RATING</h3></center></td></tr>
        <tr><td colspan="12"><center><h3>BAGI TAHUN {{ $year }}</h3></center></td></tr>
        <tr><td colspan="12"><center><h3>{{ $titlemonth }}</h3></center></td></tr>
        <tr><td colspan="12"><center><h3>{{ $titlestate }}</h3></center></td></tr>
        <tr><td colspan="12"><center><h3>{{ $titlebrn }}</h3></center></td></tr>
    </table>
    <table class="table table-striped table-bordered table-hover" style="width: 100%" border='1'>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Aduan</th>
                <th>Aduan</th>
                <th>Nama Pengadu</th>
                <th>Nama Diadu</th>
                <th>Cawangan</th>
                <th>Kategori Aduan</th>
                <th>Tarikh Terima</th>
                <th>Tarikh Penugasan</th>
                <th>Tarikh Selesai</th>
                <th>Tarikh Penutupan</th>
                <th>Penyiasat</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach($query as $data)
            <tr>
                <td>{{ $i }}</td>
                <td>'{{ $data->CA_CASEID }}</td>
                <td>{{ $data->CA_SUMMARY }}</td>
                <td>{{ $data->CA_NAME }}</td>
                <td>{{ $data->CA_AGAINSTNM }}</td>
                <td>{{ $data->BrnCd->BR_BRNNM }}</td>
                <td>{{ $data->CmplCat->descr }}</td>
                <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
                <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
                <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
                <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
                <td>{{ $data->CA_INVBY? $data->InvBy->name:'' }}</td>
            </tr>
            <?php $i++; ?>
            @endforeach
        </tbody>
    </table>
</div>
<?php 
exit;
fclose($fp);
?>
@stop