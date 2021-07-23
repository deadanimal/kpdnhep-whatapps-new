<?php
$filename = date('Ymd_His').'.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
use App\Ref;
?>
<table style="width: 100%;">
    <tr><td colspan="12"><center><h3>STATUS SIASATAN ADUAN YANG MENGHASILKAN KES MENGIKUT</h3></center></td></tr>
    <tr><td colspan="12"><center><h3>SALURAN PENERIMAAN</h3></center></td></tr>
    <tr><td colspan="12"><center><h3>TAHUN : {{ $year }}</h3></center></td></tr>
    <tr><td colspan="12"><center><h3>SUMBER ADUAN : 
        @if (!empty($rcvtyp))
            @if($rcvtyp != 'total')
                {{ Ref::GetDescr('259', $rcvtyp, 'ms') }}
            @else
                SEMUA
            @endif
        @endif
    </h3></center></td></tr>
    <tr><td colspan="12"><center><h3>
        @if($column == '1')
            ADUAN DITERIMA
        @elseif($column == '2')
            ADUAN MENGHASILKAN KES
        @endif
    </h3></center></td></tr>
</table>
<table class="table table-striped table-bordered table-hover" style="width: 100%" border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>No. Aduan</th>
            <th>Aduan</th>
            <th>Nama Pengadu</th>
            <th>Nama Diadu</th>
            <th>Cawangan</th>
            <th>Kategori Aduan</th>
            <th>Tarikh Terima</th>
            <th>Tarikh Penugasan</th>
            <th>Tarikh Selesai</th>
            <th>Tarikh Penutupan</th>
            <th>Penyiasat</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($query as $data)
        <tr>
            <td>{{ $i }}</td>
            <td><a onclick="ShowSummary('{{ $data->CA_CASEID }}')">{{ $data->CA_CASEID }}</a></td>
            <td>{{ implode(' ', array_slice(explode(' ', ucfirst($data->CA_SUMMARY)), 0, 7)).'...' }}</td>
            <td>{{ $data->CA_NAME }}</td>
            <td>{{ $data->CA_AGAINSTNM }}</td>
            <td>{{ $data->BrnCd ? $data->BrnCd->BR_BRNNM : $data->CA_BRNCD }}</td>
            <td>{{ $data->CmplCat ? $data->CmplCat->descr : $data->CA_CMPLCAT }}</td>
            <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
            <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
            <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
            <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
            <td>{{ $data->InvBy ? $data->InvBy->name : $data->CA_INVBY }}</td>
        </tr>
        <?php $i++; ?>
        @endforeach
    </tbody>
</table>
<?php 
exit;
fclose($fp);
?>