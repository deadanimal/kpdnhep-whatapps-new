<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</html>
<table>
    <tr><td colspan="5"><center><h3>Laporan Integriti - Senarai Aduan dari {{date('d-m-Y', strtotime($DATE_FROM))}} hingga {{date('d-m-Y', strtotime($DATE_TO))}}</h3></center></td></tr>
</table>
<table>
    <thead>
        <tr>
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