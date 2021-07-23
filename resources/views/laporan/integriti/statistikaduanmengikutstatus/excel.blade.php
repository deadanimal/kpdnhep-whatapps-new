<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</html>
<table style="width: 100%;">
    <tr><td colspan="3"><center><h3>Laporan Integriti - Statistik Aduan Mengikut Kategori dari {{date('d-m-Y', strtotime($DATE_FROM))}} hingga {{date('d-m-Y', strtotime($DATE_TO))}}</h3></center></td></tr>
</table>
<table border="1" class="table table-striped table-bordered table-hover" style="width: 100%">
    <thead>
        <tr>
            <th> Bil. </th>
            <th> Kategori Aduan </th>
            <th> Jumlah </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $key => $value)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $statusList[$key] }}</td>
            <td>{{ $value }}</td>
        </tr>
        @endforeach
    </tbody>
</table>