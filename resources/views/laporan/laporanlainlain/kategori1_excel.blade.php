@extends('layouts.main')

@section('content')
<?php
$filename = 'Raw-Data.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
?>

    <table style="width: 100%; font-size: 16px; text-align: center">
        <tr><td colspan="5"><center><h3>LAPORAN KATEGORI</h3></center></td></tr>
        <tr><td colspan="5" ><center><h3>DARI {{ $CA_RCVDT_dri }} HINGGA {{ $CA_RCVDT_lst }}</h3></center></td></tr>
        <tr><td colspan="5"><center><h3>{{ $titlestate }}</h3></center></td></tr>
        <tr><td colspan="5"><center><h3>{{ $titlecmplcat }}</h3></center></td></tr>
    </table>
<table class="table table-striped table-bordered table-hover" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>No. Aduan</th>
            <th>Aduan</th>
            <th>Nama Pengadu</th>
            <th>Nama Diadu</th>
            <th>Daerah</th>
            <th>Negeri</th>
            <th>Cawangan KPDNHEP</th>
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
            <td><a onclick="ShowSummary('{{ $data->CA_CASEID }}')">'{{ $data->CA_CASEID }}</a></td>
            <td>{{ $data->CA_SUMMARY }}</td>
            <td>{{ $data->CA_NAME }}</td>
            <td>{{ $data->CA_AGAINSTNM }}</td>
            <td>{{ $data->againstdistcd ? $data->againstdistcd->descr : ''}}</td>
            <td>{{ $data->againststatecd ? $data->againststatecd->descr : ''}}</td>
            <!-- <td>{{-- $data->BrnCd ? $data->BrnCd->BR_BRNNM : $data->CA_BRNCD --}}</td> -->
            <td>{{ $data->BrnCd ? $data->BrnCd->BR_BRNNM : ''}}</td>
            <td>{{ $data->CmplCat ? $data->CmplCat->descr : ''}}</td>
            <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
            <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
            <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
            <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
            <td>
            <!-- {{-- $data->CA_INVBY? $data->InvBy->name:'' --}} -->
            {{ $data->CA_INVBY ? (count($data->InvBy) == '1' ? $data->InvBy->name : $data->CA_INVBY) : '' }}
            </td>
        </tr>
        <?php $i++; ?>
        @endforeach
    </tbody>
</table>
<?php 
exit;
fclose($fp);
?>
@stop