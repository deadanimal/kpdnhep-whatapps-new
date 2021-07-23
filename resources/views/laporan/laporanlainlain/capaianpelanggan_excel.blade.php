@extends('layouts.main')
<?php

use App\Ref;
use App\Laporan\ReportLainlain;
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

<table id="state-table" class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
                        <thead>
                        <h2><center>Laporan Pencapaian Piagam Pelanggan Bahagian Gerakan Kepenggunaan Bagi Bulan 
                             <br/>   @if(($Month_from) && ($Month_to) && ($Month_from < $Month_to))
                                Dari {{ Ref::GetDescr('206', $Month_from, 'ms') }} 
                                Hingga {{ Ref::GetDescr('206', $Month_to, 'ms') }} 
                                @elseif(($Month_from == $Month_to) && ($Month_from!='')&&($Month_to!=''))
                                {{ Ref::GetDescr('206', $Month_from, 'ms') }}
                                @endif <br/>
                                {{ $tahun }}</center></h2>

                        <tr>
                           
                            <th rowspan="3" style="border: 1px solid #000; background: #f3f3f3;">Standard Piagam Pelanggan</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="border: 1px solid #000; background: #f3f3f3;">Menetapi Tempoh Masa/Standard Piagam Pelanggan</th>
                            <th colspan="2" style="border: 1px solid #000; background: #f3f3f3;">Belum Mencapai Standard Piagam Pelanggan </th>
                            <th colspan="2" style="border: 1px solid #000; background: #f3f3f3;">Melebihi Tempoh Masa</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;"> Jumlah Perkhidmatan</th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Menetapi Standard</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;"> % Menetapi Standard</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Belum capai/Dalam Proses</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;"> % Belum capai/Dalam Proses</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Melebihi Standard</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;">% Melebihi Tempoh Standard</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counttetapstandard=0;
                            $countblumstandard=0;
                            $countlebihstandard=0;
                            ?>
                            <?php $i = 1; ?>
                            @foreach ($data as $Capaian)
                            <tr>
                            
                                <td align="center"> (a)Akuan penerimaan dan maklumbalas aduan yang diterima kepdada pengadu dalam tempoh tiga (3) hari berkerja bagi aduan yang dikemukan secara bertulis memalui surat atau emel,malalui panggilan telefon atau hadir sendiri
                                    - Ambil daripada jumlah aduan ke BPA yang diterima dlm setahun </td>
                                <td align=center class="td"><br>{{ $Capaian->Counta }}</td>
                                <td align=center class="td"><br>{{ round($Capaian->Counta/$Capaian->Counta*100,2)  }} % </td>
                                <td align=center class="td"><br> - </td>
                                <td align=center class="td"><br> - </td>
                                <td align=center class="td"><br> - </td>
                                <td align=center class="td"><br> - </td>
                            
                                <td align=center class="td"><br>{{ $Capaian->Counta }} </td>
                            </tr>
                            <?php $lebih = $Capaian->Counta - $Capaian->COUNT - $Capaian->Count1; ?>
                            <tr>
                                    
                                <td align="center"> (b) Penyelesaian aduan daripada pengadu dalam tempoh 21 hari berkerja
                                    - Tempoh penyelesaian aduan BPA</td>
                                <td align=center class="td"><br>{{ $Capaian->COUNT }}</td>
                                <td align=center class="td"><br> {{ round($Capaian->COUNT/$Capaian->Counta*100,2)  }}% </td>
                                <td align=center class="td"><br>{{ $Capaian->Count1 }}</td>
                                <td align=center class="td"><br> {{ round($Capaian->Count1/$Capaian->Counta*100,2)  }} %</td>
                                <td align=center class="td"><br> {{ $lebih }} </td>
                                <td align=center class="td"><br>{{ round($lebih/$Capaian->Counta*100,2)  }} % </td>
                                <td align=center class="td"><br> {{ $Capaian->Counta }}</td>
                            </tr>
                            <?php
                            $counttetapstandard +=$Capaian->Count3;
                            $countblumstandard +=$Capaian->COUNT;
                            $countlebihstandard +=$Capaian->Count1;
                            ?>
                              @endforeach
                        </tbody>
                    </table>
    
     @if($cari != '')
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                @endif
         @section('script_datatable')
<script type="text/javascript">
     Highcharts.setOptions({
            lang: {
                numericSymbols: null
            }
        });
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Laporan Pencapaian Piagam Pelanggan Bahagian Gerakan Kepenggunaan '
            },
            subtitle: {
                text: 'Bulan Dari: <?php echo $Month_from ?> Hingga: <?php echo $Month_to ?>'
            },
            xAxis: {
                crosshair: true,
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Pencapaian Piagam Pelanggan'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{series.name}</span><table>',
                pointFormat: '<tr><td style="color:{point.color};padding:0">{point.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            series: [{
                name: 'Jumlah Piagam Pelanggan',
                colorByPoint: true,
                data: [
//                    ['Jumlah Menetapi Standard (A) ', <?php // echo $Capaian->Counta ?>],
                    ['Jumlah Menetapi Standard (B) ', <?php echo $counttetapstandard ?>],
                    ['Jumlah Belum Capai/Dalam Proses', <?php echo $countblumstandard ?>],
                    ['Jumlah Melebihi Standard', <?php echo $countlebihstandard ?>],
                
                
                ]
            }],
            credits: {
                enabled: false
            }
        });
</script>
@stop
    <?php 
exit;
fclose($fp);
?>
@stop