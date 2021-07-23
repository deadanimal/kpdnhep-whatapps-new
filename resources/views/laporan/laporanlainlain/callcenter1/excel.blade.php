<?php
    $filename = 'Laporan-Call-Center-'.date('Ymd_His').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Ref;
?>
<table style="width: 100%">
    <tr><td colspan="11"><center><h4>LAPORAN CALL CENTER</h4></center></td></tr>
    <tr><td colspan="11"><center><h4>TAHUN {{ $year }}</h4></center></td></tr>
    <tr><td colspan="11"><center><h4>{{ $titlemonth }}</h4></center></td></tr>
    <tr><td colspan="11"><center><h4>NAMA: {{ $mUser->name }}</h4></center></td></tr>
</table>
<table style="width: 100%" border="1">
    <thead>
        <tr>
            <th>Bil.</th>
            <th>No. Aduan</th>
            <th>Aduan</th>
            <th>Nama Pengadu</th>
            <th>Nama Diadu</th>
            <th>Kategori Aduan</th>
            <th>Tarikh Terima</th>
            <th>Tarikh Penugasan</th>
            <th>Tarikh Selesai</th>
            <th>Tarikh Penutupan</th>
            <th>Penyiasat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($query as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->CA_CASEID }}</td>
                <td>{{ implode(' ', array_slice(explode(' ', ucfirst($data->CA_SUMMARY)), 0, 7)).'...' }}</td>
                <td>{{ $data->CA_NAME }}</td>
                <td>{{ $data->CA_AGAINSTNM }}</td>
                <td>{{ $data->CA_CMPLCAT != '' ? Ref::GetDescr('244', $data->CA_CMPLCAT, 'ms') : '' }}</td>
                <td>{{ $data->CA_RCVDT ? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
                <td>{{ $data->CA_ASGDT ? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
                <td>{{ $data->CA_COMPLETEDT ? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
                <td>{{ $data->CA_CLOSEDT ? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
                <td>{{ $data->CA_INVBY ? $data->InvBy->name : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<?php 
    exit;
    fclose($fp);
?>