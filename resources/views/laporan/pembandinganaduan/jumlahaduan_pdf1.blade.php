<?php
    use App\Ref;
?>
<style>
    .text-align-center{
        text-align: center;
    }
</style>
<table style="width: 100%;">
    <tbody>
        <tr><td colspan="8"><center><h4>
            LAPORAN PERBANDINGAN ADUAN MENGIKUT STATUS (TAHUNAN)
        </h4></center></td></tr>
        <tr><td colspan="8"><center><h4>
            @if($CA_RCVDT_YEAR != '0')
                TAHUN {{ $CA_RCVDT_YEAR }}
            @elseif($CA_RCVDT_YEAR_FROM == $CA_RCVDT_YEAR_TO)
                TAHUN {{ $CA_RCVDT_YEAR_TO }}
            @elseif($CA_RCVDT_YEAR_FROM < $CA_RCVDT_YEAR_TO)
                DARI TAHUN {{ $CA_RCVDT_YEAR_FROM }} HINGGA TAHUN {{ $CA_RCVDT_YEAR_TO }}
            @endif
        </h4></center></td></tr>
        <tr><td colspan="8"><center><h4>
            {{ $BR_STATECD ? 'NEGERI ' . Ref::GetDescr('17', $BR_STATECD, 'ms') : 'SEMUA NEGERI' }}
        </h4></center></td></tr>
        <tr><td colspan="8"><center><h4>
            {{ $CA_DEPTCD ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}
        </h4></center></td></tr>
        <tr><td colspan="8"><center><h4>
            {{ $CA_INVSTS != '0' ? 'STATUS ADUAN : ' . Ref::GetDescr('292', $CA_INVSTS, 'ms') : 'SEMUA STATUS ADUAN' }}
        </h4></center></td></tr>
    </tbody>
</table>
<table style="width: 100%; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr >
            <th>Bil.</th>
            <th>No. Aduan</th>
            <th>Keterangan Aduan</th>
            <th>Nama Pengadu</th>
            <th>Nama Diadu</th>
            <th>Negeri</th>
            <th>Kategori</th>
            <th>Tarikh Penerimaan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($query as $senaraiaduan)
            <tr>
                <td class="text-align-center">{{ $loop->iteration }}</td>
                <td class="text-align-center">
                    {{ $senaraiaduan->CA_CASEID }}
                </td>
                <td>
                    {{ implode(' ', array_slice(explode(' ', $senaraiaduan->CA_SUMMARY), 0, 5)).' ...' }}
                </td>
                <td class="text-align-center">{{ $senaraiaduan->CA_NAME }} </td>
                <td class="text-align-center">{{ $senaraiaduan->CA_AGAINSTNM }} </td>
                <td class="text-align-center">
                    {{ $senaraiaduan->BR_STATECD != '' ? Ref::GetDescr('17', $senaraiaduan->BR_STATECD, 'ms') : '' }}
                </td>
                <td class="text-align-center">
                    {{ $senaraiaduan->CA_CMPLCAT != '' ? Ref::GetDescr('244', $senaraiaduan->CA_CMPLCAT, 'ms') : '' }}
                </td>
                <td class="text-align-center">
                    {{ $senaraiaduan->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($senaraiaduan->CA_RCVDT)) : '' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<br />
<table style="width: 100%;">
    <tbody>
        <tr>
            <td>Tarikh Dijana : </td>
            <td><?php echo date("d/m/Y h:i A") ?></td>
        </tr>
    </tbody>
</table>