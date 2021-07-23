<?php
    $filename = 'Laporan-AduanMengikutNegeriDanKategori-'.date('Ymd_His').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Ref;
?>
<?php
    use App\Laporan\ReportLainlain;
?>
        <table style="width: 100%;">
            <tr><td colspan="18"><center><h3>{{ $titleyear }}</h3></center></td></tr>
            <tr><td colspan="18"><center><h3>{{ $titlemonth }}</h3></center></td></tr>
        </table>
            <table border="1">
                <thead>
                    <tr>
                        <!-- <th>Bil.</th> -->
                        <th>Kategori</th>
                        @foreach($ListState as $State)
                        <th>{{ $State->descr }}</th>
                        @endforeach
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $Total01 = 0;
                    $Total02 = 0;
                    $Total03 = 0;
                    $Total04 = 0;
                    $Total05 = 0;
                    $Total06 = 0;
                    $Total07 = 0;
                    $Total08 = 0;
                    $Total09 = 0;
                    $Total10 = 0;
                    $Total11 = 0;
                    $Total12 = 0;
                    $Total13 = 0;
                    $Total14 = 0;
                    $Total15 = 0;
                    $Total16 = 0;
                    $Gtotal = 0;
                    ?>
                    @foreach ($datas as $data)
                    <tr>
                        <!-- <td style="text-align:center">{{-- $i++ --}}</td> -->
                        <td>{{ $data->descr }}</td>
                        <td style="text-align:center">{{ $data->kod_01 }}</td>
                        <td style="text-align:center">{{ $data->kod_02 }}</td>
                        <td style="text-align:center">{{ $data->kod_03 }}</td>
                        <td style="text-align:center">{{ $data->kod_04 }}</td>
                        <td style="text-align:center">{{ $data->kod_05 }}</td>
                        <td style="text-align:center">{{ $data->kod_06 }}</td>
                        <td style="text-align:center">{{ $data->kod_07 }}</td>
                        <td style="text-align:center">{{ $data->kod_08 }}</td>
                        <td style="text-align:center">{{ $data->kod_09 }}</td>
                        <td style="text-align:center">{{ $data->kod_10 }}</td>
                        <td style="text-align:center">{{ $data->kod_11 }}</td>
                        <td style="text-align:center">{{ $data->kod_12 }}</td>
                        <td style="text-align:center">{{ $data->kod_13 }}</td>
                        <td style="text-align:center">{{ $data->kod_14 }}</td>
                        <td style="text-align:center">{{ $data->kod_15 }}</td>
                        <td style="text-align:center">{{ $data->kod_16 }}</td>
                        <td style="text-align:center; font-weight:bold">{{ $data->COUNT_CA_CASEID }}</td>
                    </tr>
                    <?php
                    $Total01 += $data->kod_01;
                    $Total02 += $data->kod_02;
                    $Total03 += $data->kod_03;
                    $Total04 += $data->kod_04;
                    $Total05 += $data->kod_05;
                    $Total06 += $data->kod_06;
                    $Total07 += $data->kod_07;
                    $Total08 += $data->kod_08;
                    $Total09 += $data->kod_09;
                    $Total10 += $data->kod_10;
                    $Total11 += $data->kod_11;
                    $Total12 += $data->kod_12;
                    $Total13 += $data->kod_13;
                    $Total14 += $data->kod_14;
                    $Total15 += $data->kod_15;
                    $Total16 += $data->kod_16;
                    $Gtotal += $data->COUNT_CA_CASEID;
                    ?>
                    @endforeach
                    <tr style="font-weight:bold">
                        <!-- <td></td> -->
                        <td>Jumlah</td>
                        <td style="text-align:center">{{ $Total01 }}</td>
                        <td style="text-align:center">{{ $Total02 }}</td>
                        <td style="text-align:center">{{ $Total03 }}</td>
                        <td style="text-align:center">{{ $Total04 }}</td>
                        <td style="text-align:center">{{ $Total05 }}</td>
                        <td style="text-align:center">{{ $Total06 }}</td>
                        <td style="text-align:center">{{ $Total07 }}</td>
                        <td style="text-align:center">{{ $Total08 }}</td>
                        <td style="text-align:center">{{ $Total09 }}</td>
                        <td style="text-align:center">{{ $Total10 }}</td>
                        <td style="text-align:center">{{ $Total11 }}</td>
                        <td style="text-align:center">{{ $Total12 }}</td>
                        <td style="text-align:center">{{ $Total13 }}</td>
                        <td style="text-align:center">{{ $Total14 }}</td>
                        <td style="text-align:center">{{ $Total15 }}</td>
                        <td style="text-align:center">{{ $Total16 }}</td>
                        <td style="text-align:center">{{ $Gtotal }}</td>
                    </tr>
                </tbody>
            </table>
<?php 
    exit;
    fclose($fp);
?>