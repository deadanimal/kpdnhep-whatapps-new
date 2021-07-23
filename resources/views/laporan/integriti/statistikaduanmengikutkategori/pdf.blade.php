<?php
    use App\Laporan\ReportLainlain;
?>
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>Laporan Integriti - Statistik Aduan Mengikut Kategori dari {{date('d-m-Y', strtotime($DATE_FROM))}} hingga {{date('d-m-Y', strtotime($DATE_TO))}}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table border="1" class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse">
            <thead>
            <tr style="background-color:grey">
                <th> Bil. </th>
                <th> Kategori Aduan </th>
                <th> Jumlah </th>
            </tr>
            </thead>
            <tbody>
                @foreach ($datas as $key => $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $categoryList[$key] }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>