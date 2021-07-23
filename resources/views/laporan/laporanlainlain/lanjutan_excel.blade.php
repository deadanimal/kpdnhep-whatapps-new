<?php

use App\Ref;
use App\Laporan\ReportLainlain;
?>
@section('content')
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

 <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
             
                <div class="ibox-content">
                            
                    <div class="row">
                        <div style="text-align: center;">
                            <h3>
                                <div style="text-align: center;">LAPORAN LANJUTAN ADUAN BAGI
                                    TEMPOH {{ date('d-m-Y', strtotime($dateStart)) }}
                                    HINGGA {{ date('d-m-Y', strtotime($dateEnd)) }}<br/>
                                    @if ($departmentGroup != '')
                                        {{ Ref::GetDescr('315',$departmentGroup,'ms') }}
                                    @else
                                        SEMUA BAHAGIAN
                                    @endif <br>
                                </div>
                            </h3>
                            
                                @foreach ($reportFinal as $rf_cawangan => $rf_kategori_data)
                                    @foreach($rf_kategori_data as $rf_kategori => $rf_data)
                                        <p style="text-align: left">Cawangan: {{ $branchList[$rf_cawangan] }}</p>
                                        <p style="text-align: left">Kategori
                                            Aduan: {{ $departmentList[$rf_kategori] }}</p>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover dataTables-example"
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
                                        </div>
                                    @endforeach
                                @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
exit;
fclose($fp);
?>
