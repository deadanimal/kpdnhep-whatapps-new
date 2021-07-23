<?php

use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;

?>

<style>
    th, td {
        text-align: center;
        font-size: 12px;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td colspan="18">
            <div style="text-align: center;"><h3>LAPORAN ADUAN MENGIKUT PEGAWAI PENYIASAT </h3></div>
        </td>
    </tr>

</table>
@if($search)
    <table id="state-table" class="table table-striped table-bordered table-hover dataTables-example"
           style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
        <thead>
        <h3>
            Cawangan:{{ $branchName }}<br/>
            Tarikh:{{ $dateStart->format('d-m-Y') }} hingga {{ $dateEnd->format('d-m-Y') }}
        </h3>
        <tr>
            <th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Penyiasat</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Unit</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Selesai</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Belum Selesai</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Aduan</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total=0;
        @endphp
        @foreach ($dataFinal as $key => $datum)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $datum->investigator_name }}</td>
                <td>{{ $datum->branch_name }}</td>
                <td>{{ $datum->investigation_done }}</td>
                <td>{{ $datum->investigation_not_finished }}</td>
                <td>{{ $datum->total }}</td>
            </tr>
            @php
                $total += $datum->total;
            @endphp
        @endforeach
        </tbody>
        <tfooter>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Jumlah</th>
                <th>
                    {{$total}}
                </th>
            </tr>
        </tfooter>
    </table>
@endif