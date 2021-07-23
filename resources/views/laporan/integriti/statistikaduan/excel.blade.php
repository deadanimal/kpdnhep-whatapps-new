<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</html>
<table>
    <tr><td colspan="2"><center><h3>Laporan Integriti - Statistik Aduan dari {{date('d-m-Y', strtotime($DATE_FROM))}} hingga {{date('d-m-Y', strtotime($DATE_TO))}}</h3></center></td></tr>
</table>
<table>
    <thead>
        <tr>
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
            <tr>
                <td>Jumlah</td>
                <td>{{ $data->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>