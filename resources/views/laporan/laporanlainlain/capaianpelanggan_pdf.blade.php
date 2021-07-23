<?php

use App\Ref;
use App\Laporan\ReportLainlain;
?>

<table style="width: 100%;">
    <tr><td colspan="18"><center><h3>Laporan Pencapaian Piagam Pelanggan Bahagian Gerakan Kepenggunaan Bagi Bulan</h3></center></td></tr>
    <tr><td colspan="18"><center><h3>Dari Bulan {{ $Month_from }} HINGGA Bulan{{ $Month_to }}</h3></center></td></tr>
    <tr><td colspan="18"><center><h3> Tahun {{ $tahun }}</h3></center></td></tr>
</table>

<table id="state-table" class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
                        <thead>
                        <h2><center>Laporan Pencapaian Piagam Pelanggan Bahagian Gerakan Kepenggunaan Bagi Bulan 
                                @if(($Month_from) && ($Month_to) && ($Month_from < $Month_to))
                                Dari {{ Ref::GetDescr('206', $Month_from, 'ms') }} 
                                Hingga {{ Ref::GetDescr('206', $Month_to, 'ms') }} 
                                @elseif(($Month_from == $Month_to) && ($Month_from!='')&&($Month_to!=''))
                                {{ Ref::GetDescr('206', $Month_from, 'ms') }}
                                @endif
                                {{ $tahun }}</center></h2>

                        <tr>
                           
                            <th rowspan="3" style="border: 1px solid #000; background: #f3f3f3;">Standard Piagam Pelanggan</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="border: 1px solid #000; background: #f3f3f3;">Menetapi Tempoh Masa/Standard Piagam Pelanggan</th>
                            <th colspan="2" style="border: 1px solid #000; background: #f3f3f3;">Belum Mencapai Standard Piagam Pelanggan </th>
                            <th colspan="2" style="border: 1px solid #000; background: #f3f3f3;">Melebihi Tempoh Masa</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Perkhidmatan</th>
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

              
             