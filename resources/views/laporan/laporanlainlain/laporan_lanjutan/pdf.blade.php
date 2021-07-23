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
            <div style="text-align: center;"><h3>LAPORAN LANJUTAN ADUAN</h3></div>
        </td>
    </tr>

</table>
@if($search)
    @foreach ($reportFinal as $rf_cawangan => $rf_kategori_data)
        @foreach($rf_kategori_data as $rf_kategori => $rf_data)
            <table>
                <tr>
                    <td>
                        Cawangan: {{ $branchList[$rf_cawangan] }}<br/>
                        Kategori Aduan: {{ $departmentList[$rf_kategori] }}
                    </td>
                </tr>
            </table>
            <table id="state-table" class="table table-striped table-bordered table-hover dataTables-example"
                   style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
                <thead>
                <tr>
                    <th width="1%">Bil.</th>
                    <th width="6%">Tarikh Diterima</th>
                    <th width="5%">No. Aduan</th>
                    <th width="5%">Sumber</th>
                    <th width="5%">Peg. Penyiasat</th>
                    <th width="5%">Nama Pengadu</th>
                    <th width="5%">Alamat Pengadu</th>
                    <th width="5%">Nama Diadu</th>
                    <th width="10%">Aduan</th>
                    <th width="5%">Hasil Siatasan</th>
                    <th width="1%">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rf_data as $key => $datum)
                    <tr>
                        <td style="vertical-align: top;">{{$key+1}}</td>
                        <td style="vertical-align: top;">{{$datum->ca_rcvdt}}</td>
                        <td style="vertical-align: top;">{{$datum->ca_caseid}}</td>
                        <td style="vertical-align: top;">{{trim($datum->ca_rcvtyp) != '' ? $sourceList[$datum->ca_rcvtyp] : '-'}}</td>
                        <td style="vertical-align: top;">{{$datum->name}}</td>
                        <td style="vertical-align: top;">{{$datum->ca_name}}</td>
                        <td style="vertical-align: top;">{{$datum->ca_addr}} {{$datum->ca_poscd}} {{$datum->ca_distcd}} {{$datum->ca_statecd}}</td>
                        <td style="vertical-align: top;">{{$datum->ca_againstnm}}</td>
                        <td style="vertical-align: top;">{{$datum->ca_result}}</td>
                        <td style="vertical-align: top;">{{$datum->ca_result}}</td>
                        <td style="vertical-align: top;">{{$datum->ca_casests}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach
@endif