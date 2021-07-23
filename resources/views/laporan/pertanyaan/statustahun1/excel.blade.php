<?php
    $filename = 'Laporan-Pertanyaan-Cadangan-'.date('Ymd_His').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Pertanyaan\PertanyaanAdmin;
?>

<table style="width: 100%;">
    <tr><td colspan="8"><center><h3>LAPORAN PERTANYAAN / CADANGAN</h3></center></td></tr>
    <tr><td colspan="8"><center><h3>TAHUN {{ $year }}</h3></center></td></tr>
    <tr><td colspan="8"><center><h3>{{ $titlemonth }}</h3></center></td></tr>
</table>

<div class="table-responsive">
    <table style="width: 100%" border="1">
        <thead>
            <tr>
                <th>Bil.</th>
                <th>No. Pertanyaan / Cadangan</th>
                <th>Keterangan</th>
                <th>Nama Pihak yang Bertanya</th>
                <th>Tarikh Terima</th>
                <th>Tarikh Dijawab</th>
                <th>Dijawab Oleh</th>
                <th>Hari</th>
            </tr>
        </thead>
        <tbody>
            @foreach($query as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->AS_ASKID }}</td>
                    <td>{{ implode(' ', array_slice(explode(' ', ucfirst($data->AS_SUMMARY)), 0, 7)).'...' }}</td>
                    <td>{{ $data->AS_NAME }}</td>
                    <td>{{ $data->AS_RCVDT ? date('d-m-Y h:i A', strtotime($data->AS_RCVDT)) : '' }}</td>
                    <td>{{ $data->AS_COMPLETEDT ? date('d-m-Y h:i A', strtotime($data->AS_COMPLETEDT)) : '' }}</td>
                    <td>{{ $data->AS_COMPLETEBY ? $data->CompleteBy->name : '' }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<?php 
    exit;
    fclose($fp);
?>