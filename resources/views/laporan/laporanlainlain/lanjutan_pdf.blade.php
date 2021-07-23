<?php

use App\Ref;
use App\Laporan\ReportLainlain;
?>

   <style>
    th, td {
        text-align: center;
        font-size: 12px;
    }
    </style>
<table style="width: 100%;">
    <tr><td colspan="18"><center><h3>LAPORAN LANJUTAN ADUAN BAGI TEMPOH</h3></center></td></tr>
   <tr><td colspan="18"><center><h3>BAGI {{ $request->get('dateStart') }} HINGGA {{ $request->get('dateEnd') }}</h3></center></td></tr>
   
    <tr><td colspan="18"><center><h3> @if ($departmentGroup != '')
                                        {{ Ref::GetDescr('315',$departmentGroup,'ms') }}
                                    @else
                                        SEMUA BAHAGIAN
                                    @endif </h3></center></td></tr>
</table>         
    
                        @foreach ($reportFinal as $rf_cawangan => $rf_kategori_data)
                                    @foreach($rf_kategori_data as $rf_kategori => $rf_data)
                                        <p style="text-align: left">Cawangan: {{ $branchList[$rf_cawangan] }}</p>
                                        <p style="text-align: left">Kategori
                                            Aduan: {{ $departmentList[$rf_kategori] }}</p>
                                            <table class="table table-bordered" style="width: 100%; font-size: 10px; " border="1">
                                                <thead>
                                                <tr>
                                                    <th width="3%">Bil.</th>
                                                    <th width="6%">Tarikh Diterima</th>
                                                    <th width="5%">No. Aduan</th>
                                                    <th width="6%">Sumber</th>
                                                    <th width="8%">Peg. Penyiasat</th>
                                                    <th width="6%">Nama Pengadu</th>
                                                    <th width="6%">Alamat Pengadu</th>
                                                    <th width="5%">Nama Diadu</th>
                                                    <th width="8%">Aduan</th>
                                                    <th width="5%">Hasil Siatasan</th>
                                                    <th width="5%">Status</th>
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
              
