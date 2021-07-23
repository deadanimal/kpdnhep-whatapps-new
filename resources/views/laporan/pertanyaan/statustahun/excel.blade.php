<?php
    $filename = 'Laporan-Pertanyaan-Cadangan-'.date('Ymd_His').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Laporan\ReportLainlain;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content" style="padding-bottom: 0px">
                <div class="table-responsive">
                    <table style="width: 100%;">
                        <tr><td colspan="15"><center><h3>{{ $titleyear }}</h3></center></td></tr>
                    </table>
                    <table class="table table-striped table-bordered table-hover" style="width: 100%" border="1">
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
                                <td>{{ $data->descr }}</td>
                                <td><a target="_blank">{{ $data->jan }}</a></td>
                                <td><a target="_blank">{{ $data->feb }}</a></td>
                                <td><a target="_blank">{{ $data->mac }}</a></td>
                                <td><a target="_blank">{{ $data->apr }}</a></td>
                                <td><a target="_blank">{{ $data->mei }}</a></td>
                                <td><a target="_blank">{{ $data->jun }}</a></td>
                                <td><a target="_blank">{{ $data->jul }}</a></td>
                                <td><a target="_blank">{{ $data->ogo }}</a></td>
                                <td><a target="_blank">{{ $data->sep }}</a></td>
                                <td><a target="_blank">{{ $data->okt }}</a></td>
                                <td><a target="_blank">{{ $data->nov }}</a></td>
                                <td><a target="_blank">{{ $data->dis }}</a></td>
                                <td><a target="_blank">{{ $data->Total }}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    exit;
    fclose($fp);
?>