<table style="width: 100%;">
    <tr><td><center><h3>LAPORAN CARA PENERIMAAN BAGI TAHUN {{ $year }}</h3></center></td></tr>
    <tr><td><center><h3>{{ $titlercvtyp }}</h3></center></td></tr>
    <tr><td><center><h3>{{ $titlemonth }}</h3></center></td></tr>
</table>
<table class="table table-striped table-bordered table-hover" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
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
        <?php $i = 1; ?>
        @foreach($query as $data)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $data->AS_ASKID }}</td>
            <td>{{ $data->AS_SUMMARY }}</td>
            <td>{{ $data->AS_NAME }}</td>
            <td>{{ $data->AS_RCVDT ? date('d-m-Y h:i A', strtotime($data->AS_RCVDT)) : '' }}</td>
            <td>{{ $data->AS_COMPLETEDT ? date('d-m-Y h:i A', strtotime($data->AS_COMPLETEDT)) : '' }}</td>
            <td>{{ $data->AS_COMPLETEBY ? $data->CompleteBy->name : '' }}</td>
            <td></td>
        </tr>
        <?php $i++; ?>
        @endforeach
    </tbody>
</table>