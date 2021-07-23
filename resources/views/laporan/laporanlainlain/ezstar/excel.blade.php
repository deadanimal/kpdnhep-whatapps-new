<?php
    $filename = 'Laporan-EzStar-'.date('Ymd_His').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Ref;
?>
<?php
    use App\Laporan\ReportLainlain;
?>
        <table style="width: 100%;">
            <tr><td colspan="11"><center><h3>{{ $titleyear }}</h3></center></td></tr>
            <tr><td colspan="11"><center><h3>{{ $titlemonth }}</h3></center></td></tr>
            <tr><td colspan="11"><center><h3>{{ $titlestate }}</h3></center></td></tr>
            <tr><td colspan="11"><center><h3>{{ $titlebrn }}</h3></center></td></tr>
        </table>
            <table border="1">
                <thead>
                    <tr>
                        <th>Bil.</th>
                        <th style="width: 20%;">Nama</th>
                        <th style="width: 20%;">Negeri</th>
                        <th style="width: 20%;">Cawangan</th>
                        @php
                            $status = ReportLainlain::EzStarGetStatus();
                        @endphp
                        @foreach($status as $stat)
                            <th style="width: 10%;">{{ $stat->descr }}</th>
                        @endforeach
                        <th style="width: 8%;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $tJan=0;
                    $tFeb=0;
                    $tMac=0;
                    $tApr=0;
                    $tMei=0;
                    $tJun=0;
                    $tJul=0;
                    $tOgo=0;
                    $tSep=0;
                    $tOkt=0;
                    $tNov=0;
                    $tDis=0;
                    $ttotal=0;
                    ?>
                    @foreach ($datas as $data)
                    
                    <tr>
                        <?php 
                            $statename = Ref::GetDescr(17,$data->BR_STATECD);
                        ?>
                        <td>{{ $i++ }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $statename }}</td>
                        <td>{{ $data->BR_BRNNM }}</td>
                        <td>{{ $data->code2 }}</td>
                        <td>{{ $data->code3 }}</td>
                        <td>{{ $data->code4 }}</td>
                        <td>{{ $data->code5 }}</td>
                        <td>{{ $data->code7 }}</td>
                        <td>{{ $data->code8 }}</td>
                        <td>{{ $data->code9 }}</td>
                        <td>{{ $data->code0 }}</td>
                        <td>{{ $data->code11 }}</td>
                        <td>{{ $data->code2 + $data->code3 + $data->code4 + $data->code5 + $data->code7 + $data->code8 + $data->code9 + $data->code0 + $data->code11 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
<?php 
    exit;
    fclose($fp);
?>