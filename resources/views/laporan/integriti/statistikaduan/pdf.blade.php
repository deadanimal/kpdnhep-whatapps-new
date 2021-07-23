<?php
    use App\Laporan\ReportLainlain;
?>
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>Laporan Integriti - Statistik Aduan dari {{date('d-m-Y', strtotime($DATE_FROM))}} hingga {{date('d-m-Y', strtotime($DATE_TO))}}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table border="1" class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse">
            <thead>
            <tr style="background-color:grey">
                <th> Jenis </th>
                <th> Bilangan </th>
            </tr>
            </thead>
            <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td>Baru</td>
                        <td>{{ $data->baru }}</td>
                    </tr>
                    <tr>
                        <td>Dalam Tindakan</td>
                        <td>{{ $data->tindakan }}</td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td>Jumlah</td>
                        <td>{{ $data->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>