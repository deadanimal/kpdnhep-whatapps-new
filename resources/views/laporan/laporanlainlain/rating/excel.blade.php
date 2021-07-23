<?php
    $filename = 'Laporan-Rating-'.date('Ymd_His').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Ref;
?>
    <table style="width: 100%;">
        <tr><td colspan="11"><center><h3>{{ $titleyear }}</h3></center></td></tr>
    </table>
    <table border="1" class="table table-striped table-bordered table-hover" style="width: 100%">
        <thead>
            <tr>
                <th> Bil. </th>
                <th> Status </th>
                <th> Jan </th>
                <th> Feb </th>
                <th> Mac </th>
                <th> Apr </th>
                <th> Mei </th>
                <th> Jun </th>
                <th> Jul </th>
                <th> Ogo </th>
                <th> Sep </th>
                <th> Okt </th>
                <th> Nov </th>
                <th> Dis </th>
                <th> Jumlah </th>
            </tr>
        </thead>
        <tbody>
            <?php
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->rate }}</td>
                    <td>{{ $data->jan }}</td>
                    <td>{{ $data->feb }}</td>
                    <td>{{ $data->mac }}</td>
                    <td>{{ $data->apr }}</td>
                    <td>{{ $data->mei }}</td>
                    <td>{{ $data->jun }}</td>
                    <td>{{ $data->jul }}</td>
                    <td>{{ $data->ogo }}</td>
                    <td>{{ $data->sep }}</td>
                    <td>{{ $data->okt }}</td>
                    <td>{{ $data->nov }}</td>
                    <td>{{ $data->dis }}</td>
                    <td>{{ $data->Total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
<?php 
    exit;
    fclose($fp);
?>