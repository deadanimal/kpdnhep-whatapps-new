<?php
    use App\Laporan\ReportLainlain;
?>
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table border="1" class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse">
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
        </div>
    </div>
</div>