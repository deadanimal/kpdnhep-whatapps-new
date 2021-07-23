<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table style="width: 100%; font-size: 16px; text-align: center">
    <tr>
        <td colspan="13"><h3>LAPORAN ADUAN MENGIKUT PEGAWAI PENYIASAT BAGI SEMUA BAHAGIAN</h3></td>
    </tr>
    <tr>
        <td colspan="13"><h3>{{$departmentName}}</h3></td>
    </tr>
    <tr>
        <td colspan="13"><h3>TARIKH: {{ $dateStart->format('d-m-Y') }} HINGGA {{ $dateEnd->format('d-m-Y') }}</h3></td>
    </tr>
</table>
<table class=""
       style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
    <tr>
        <th style="border: 1px solid #000; background: #f3f3f3;">No.</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">No. Aduan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Aduan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Nama Pengadu</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Nama Diadu</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Cawangan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Kategori Aduan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Terima</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Penugasan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Selesai</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Penutupan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Penyiasat</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tempoh Seliaan</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    @foreach($dataFinal as $data)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $data->CA_CASEID }}</td>
            <td>{{ $data->CA_SUMMARY }}</td>
            <td>{{ $data->CA_NAME }}</td>
            <td>{{ $data->CA_AGAINSTNM }}</td>
            <td>{{ $data->BR_BRNNM }}</td>
            <td>{{ $data->CmplCat->descr }}</td>
            <td>{{ $data->CA_RCVDT ? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
            <td>{{ $data->CA_ASGDT ? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
            <td>{{ $data->CA_COMPLETEDT ? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
            <td>{{ $data->CA_CLOSEDT ? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
            <td>{{ $data->name }}</td>
            <td>{{ $data->duration }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>