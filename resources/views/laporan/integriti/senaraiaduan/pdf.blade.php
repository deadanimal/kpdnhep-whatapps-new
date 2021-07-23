<?php
    use App\Laporan\ReportLainlain;
?>
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>Laporan Integriti - Senarai Aduan dari {{date('d-m-Y', strtotime($DATE_FROM))}} hingga {{date('d-m-Y', strtotime($DATE_TO))}}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table border="1" class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse">
            <thead>
            <tr style="background-color:grey">
                <th> Bil. </th>
                <th> Nama </th>
                <th> No. Kad Pengenalan </th>
                <th> Tajuk Aduan </th>
                <th> Tarikh Aduan </th>
            </tr>
            </thead>
            <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->IN_NAME }}</td>
                        <td>{{ $data->IN_DOCNO }}</td>
                        <td>{{ $data->IN_SUMMARY_TITLE }}</td>
                        <td>{{ $data->IN_RCVDT ? date('d-m-Y h:i A', strtotime($data->IN_RCVDT)) : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>