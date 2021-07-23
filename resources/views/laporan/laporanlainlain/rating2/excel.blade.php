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
    </table>
    <table class="table table-striped table-bordered table-hover" style="width: 100%" border='1'>
        <thead>
            <tr>
                <th>Bil.</th>
                <th>No. Pertanyaan / Cadangan</th>
                <th>Keterangan</th>
                <th>Nama Pihak yang Bertanya</th>
                <th>Tarikh Terima</th>
                <th>Tarikh Dijawab</th>
                <th>Dijawab Oleh</th>
                <th>Hari</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach($query as $data)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $data->AS_ASKID }}</td>
                <td>{{ $data->AS_SUMMARY }}</td>
                <td>{{ $data->AS_NAME }}</td>
                <td>{{ $data->AS_RCVDT ? date('d-m-Y h:i A', strtotime($data->AS_RCVDT)) : '' }}</td>
                <td>{{ $data->AS_COMPLETEDT ? date('d-m-Y h:i A', strtotime($data->AS_COMPLETEDT)) : '' }}</td>
                <td>{{ $data->AS_COMPLETEBY ? $data->CompleteBy->name : '' }}</td>
                <td></td>
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