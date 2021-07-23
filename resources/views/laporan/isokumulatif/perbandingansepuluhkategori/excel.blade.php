<?php
    $filename = 'Laporan-Perbandingan10Kategori-'.date('Ymd_His').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Ref;
?>
<?php
    use App\Laporan\ReportLainlain;
?>
        <table style="width: 100%;">
            <tr><td colspan="3"><center><h3>{{ $titleyear }}</h3></center></td></tr>
            <tr><td colspan="3"><center><h3>{{ $titlemonth }}</h3></center></td></tr>
        </table>
            <table border="1">
                <thead>
                    <tr>
                        <th style="width: 5%;text-align:center">Bil.</th>
                        <th style="width: 50%;">Kategori</th>
                        <th style="width: 8%;text-align:center">Jumlah Aduan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    ?>
                    @foreach ($datas as $data)
                    
                    <tr>
                        <td style="text-align:center">{{ $i++ }}</td>
                        <td>{{ $data->descr }}</td>
                        <td style="text-align:center">{{ $data->countkategori }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
<?php 
    exit;
    fclose($fp);
?>