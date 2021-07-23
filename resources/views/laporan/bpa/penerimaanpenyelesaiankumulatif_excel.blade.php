<?php
    use App\Ref;
    use App\Laporan\Bpa;
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
    
     <table class="table table-striped table-bordered table-hover" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
                            <thead>
                                <h3 align="center">JUMLAH ADUAN YANG DITERIMA DAN DISELESAIKAN BAGI TAHUN {{ $CA_RCVDT_YEAR }}</h3>
                                <h3 align="center">
                                    {{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}
                                </h3>
                                <tr><th colspan="7" style="text-align: center; border: 1px solid #000; background: #f3f3f3;"">Jumlah Aduan</th></tr>
                                <tr>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">Bulan</th>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">Agensi</th>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">Diterima</th>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">Dalam Tindakan</th>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">%</th>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">Selesai</th>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $jumlahterima = 0;
                                    $jumlahterimadalamtindakan = 0;
                                    $jumlahterimaselesaikerajaan = 0;
                                    $jumlahperatusdalamtindakankerajaan = 0;
                                    $jumlahperatusselesaikerajaan = 0;
                                @endphp
                                @foreach($senarai as $senaraidata)
                                    @php
                                        $jumlahterima += $senaraidata->countcaseid;
                                        $jumlahterimadalamtindakan += $senaraidata->Count1 + $senaraidata->Count2;
                                        $jumlahterimaselesaikerajaan += $senaraidata->Count3 + $senaraidata->Count4 + $senaraidata->Count5 + $senaraidata->Count6 + $senaraidata->Count7 + $senaraidata->Count8 + $senaraidata->Count9;
                                    @endphp
                                @endforeach
                                @foreach($senarai as $senaraidata)
                                    @php
                                        $terimakerajaan = $senaraidata->countcaseid - $senaraidata->Count4;
                                        $terimaswasta = $senaraidata->Count4;
                                        $dalamtindakankerajaan = $senaraidata->Count1 + $senaraidata->Count2;
                                        $peratusdalamtindakankerajaan = round((($dalamtindakankerajaan / $jumlahterima) * 100), 2);
                                        $jumlahperatusdalamtindakankerajaan += $peratusdalamtindakankerajaan;
                                        $selesaikerajaan = $senaraidata->Count3 + $senaraidata->Count4 + $senaraidata->Count5 + $senaraidata->Count6 + $senaraidata->Count7 + $senaraidata->Count8 + $senaraidata->Count9;
                                        $peratusselesaikerajaan = round((($selesaikerajaan / $jumlahterima) * 100), 2);
                                        $jumlahperatusselesaikerajaan += $peratusselesaikerajaan;
                                    @endphp
                                    <tr>
                                        <td rowspan="2">{{ $senaraidata->month }}</td>
                                        <td>Kerajaan</td>
                                        <td>{{ $terimakerajaan }}</td>
                                        <td>{{ $dalamtindakankerajaan }}</td>
                                        <td>{{ $peratusdalamtindakankerajaan }}</td>
                                        <td>{{ $selesaikerajaan }}</td>
                                        <td>{{ $peratusselesaikerajaan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Swasta</td>
                                        <td>{{ $terimaswasta }}</td>
                                        <td> - </td>
                                        <td> - </td>
                                        <td> - </td>
                                        <td> - </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Jumlah</td>
                                    <td>{{ $jumlahterima }}</td>
                                    <td>{{ $jumlahterimadalamtindakan }}</td>
                                    <td>{{ $jumlahperatusdalamtindakankerajaan }}</td>
                                    <td>{{ $jumlahterimaselesaikerajaan }}</td>
                                    <td>{{ $jumlahperatusselesaikerajaan }}</td>
                                </tr>
                            </tfoot>
                        </table>
    
    
    
    
    
    
    
 <?php 
exit;
fclose($fp);
?>    