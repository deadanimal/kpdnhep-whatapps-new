@extends('layouts.main')
<?php
    use App\Ref;
    use App\Branch;
    use App\Laporan\BandingAduan;
?>
<?php
$filename = 'Raw-Data.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
?>
<style>
    th, td {
        text-align: center;
        font-size: 12px;
    }
    </style>
@section('content')
<!--    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">-->
                <div class="ibox-content">
                    <!--<h2>Laporan Aduan (Senarai)</h2>-->
                    <h3 align="center">LAPORAN PERBANDINGAN ADUAN MENGIKUT NEGERI (BULANAN)</h3>
                    <h3 align="center">
                        @if($month)
                            BULAN {{ Ref::GetDescr('206', $month, 'ms') }}
                        @endif
                        TAHUN {{ $CA_RCVDT_YEAR }}
                    </h3>
                    <h3 align="center">
                        {{ Ref::GetDescr('17', $BR_STATECD, 'ms') }}
                    </h3>
                    <h3 align="center">{{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h3>
                 
                    <div class="table-responsive">
                        <table id="senaraitable" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%; font-size: 11px;">
                            <thead>
                                <tr>
                                    <th width="1%">Bil.</th>
                                    <th width="1%">No. Aduan</th>
                                    <th width="1%">Keterangan Aduan</th>
                                    <th width="1%">Nama Pengadu</th>
                                    <th width="1%">Nama Diadu</th>
                                    <th width="1%">Negeri</th>
                                    <th width="1%">Kategori</th>
                                    <th width="1%">Tarikh Penerimaan</th>
                                    <!--<th width="1%">Penyiasat</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($senarai as $senaraiaduan)
                                    <tr>
                                        <td width="1%">{{ $loop->iteration }}</td>
                                        <td width="1%">
                                            <a onclick="ShowSummary('{{ $senaraiaduan->CA_CASEID }}')">{{ $senaraiaduan->CA_CASEID }}</a>
                                        </td>
                                        <td width="1%">
                                            {{ $senaraiaduan->CA_SUMMARY }}
                                            {{-- substr($senaraiaduan->CA_SUMMARY, 0, 50).'...' --}}
                                        </td>
                                        <td width="1%">{{ $senaraiaduan->CA_NAME }} </td>
                                        <td width="1%">{{ $senaraiaduan->CA_AGAINSTNM }} </td>
                                        <td width="1%">
                                            {{ $senaraiaduan->BR_STATECD != '' ? Ref::GetDescr('17', $senaraiaduan->BR_STATECD, 'ms') : '' }}
                                        </td>
                                        <td width="1%">
                                            {{ $senaraiaduan->CA_CMPLCAT != '' ? Ref::GetDescr('244', $senaraiaduan->CA_CMPLCAT, 'ms') : '' }}
                                        </td>
                                        <td width="1%">
                                            {{ $senaraiaduan->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($senaraiaduan->CA_RCVDT)) : '' }}
                                        </td>
<!--                                        <td width="1%">
                                            {{-- $senaraiaduan->CA_INVBY != '' ? BandingAduan::getUserName($senaraiaduan->CA_INVBY) : '' --}}
                                        </td>-->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
<?php 
exit;
fclose($fp);
?>
@stop