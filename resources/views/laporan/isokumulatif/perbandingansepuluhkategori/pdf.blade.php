<?php
    use App\Ref;
    use App\Laporan\ReportLainlain;
?>
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
            <tr><td><center><h3>{{ $titlemonth }}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
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
        </div>
    </div>
</div>