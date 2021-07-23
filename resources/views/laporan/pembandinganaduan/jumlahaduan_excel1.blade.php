<?php
    $filename = 'Laporan-Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Ref;
?>
<table style="width: 100%;">
    <tbody>
        <tr><td colspan="8"><center><h3>
            LAPORAN PERBANDINGAN ADUAN MENGIKUT STATUS (TAHUNAN)
        </h3></center></td></tr>
        <tr><td colspan="8"><center><h3>
            @if($CA_RCVDT_YEAR != '0')
                TAHUN {{ $CA_RCVDT_YEAR }}
            @elseif($CA_RCVDT_YEAR_FROM == $CA_RCVDT_YEAR_TO)
                TAHUN {{ $CA_RCVDT_YEAR_TO }}
            @elseif($CA_RCVDT_YEAR_FROM < $CA_RCVDT_YEAR_TO)
                DARI TAHUN {{ $CA_RCVDT_YEAR_FROM }} HINGGA TAHUN {{ $CA_RCVDT_YEAR_TO }}
            @endif
        </h3></center></td></tr>
        <tr><td colspan="8"><center><h3>
            {{ $BR_STATECD ? 'NEGERI ' . Ref::GetDescr('17', $BR_STATECD, 'ms') : 'SEMUA NEGERI' }}
        </h3></center></td></tr>
        <tr><td colspan="8"><center><h3>
            {{ $CA_DEPTCD ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}
        </h3></center></td></tr>
        <tr><td colspan="8"><center><h3>
            {{ $CA_INVSTS != '00' ? 'STATUS ADUAN : ' . Ref::GetDescr('292', $CA_INVSTS, 'ms') : 'SEMUA STATUS ADUAN' }}
        </h3></center></td></tr>
    </tbody>
</table>
<table style="width: 100%;" border="1">
    <thead>
        <tr>
            <th width="1%">Bil.</th>
            <th width="1%">No. Aduan</th>
            <th width="1%">Keterangan Aduan</th>
            <th width="1%">Nama Pengadu</th>
            <th width="1%">Nama Diadu</th>
            <th width="1%">Negeri</th>
            <th width="1%">Kategori</th>
            <th width="1%">Tarikh Penerimaan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($query as $senaraiaduan)
            <tr>
                <td width="1%">{{ $loop->iteration }}</td>
                <td width="1%">
                    <a onclick="ShowSummary('{{ $senaraiaduan->CA_CASEID }}')">{{ $senaraiaduan->CA_CASEID }}</a>
                </td>
                <td width="1%">
                    {{ implode(' ', array_slice(explode(' ', $senaraiaduan->CA_SUMMARY), 0, 5)).' ...' }}
                </td>
                <td width="1%">{{ $senaraiaduan->CA_NAME }} </td>
                <td width="1%">{{ $senaraiaduan->CA_AGAINSTNM }} </td>
                <td width="1%">
                    {{ $senaraiaduan->BR_STATECD != '' ? Ref::GetDescr('17', $senaraiaduan->BR_STATECD, 'ms') : '' }}
                </td>
                <td width="1%">
                    {{ $senaraiaduan->CA_CMPLCAT != '' ? Ref::GetDescr('244', $senaraiaduan->CA_CMPLCAT, 'ms') : '' }}
                </td>
                <td width="1%">
                    {{ $senaraiaduan->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($senaraiaduan->CA_RCVDT)) : '' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<?php 
    exit;
    fclose($fp);
?>
