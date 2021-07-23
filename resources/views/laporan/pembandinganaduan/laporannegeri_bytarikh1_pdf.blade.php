<?php
    use App\Ref;
?>
<style>
    .text-align-center{
        text-align: center;
    }
</style>
<table style="width: 100%">
    <tbody>
        <tr><td colspan="8"><center><h3>LAPORAN MENGIKUT STATUS, HARIAN, NEGERI DAN KATEGORI ADUAN</h3></center></td></tr>
        <tr><td colspan="8"><center><h3>
            {{ $CA_RCVDT_DAY != '0' ? $CA_RCVDT_DAY : '' }}
            @if($CA_RCVDT_MONTH != 0)
                {{ Ref::GetDescr('206', $CA_RCVDT_MONTH, 'ms') }}
            @endif
            {{ $CA_RCVDT_YEAR }}
        </h3></center></td></tr>
        <tr><td colspan="8"><center><h3>
            {{ $BR_STATECD != '0' ? Ref::GetDescr('17', $BR_STATECD, 'ms') : 'SEMUA NEGERI' }}
        </h3></center></td></tr>
        <tr><td colspan="8"><center><h3>{{ $CA_DEPTCD != '0' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h3></center></td></tr>
        @if($CA_CMPLCAT != '0')
            <tr><td colspan="8"><center><h3>
                Kategori Aduan: {{ Ref::GetDescr('244', $CA_CMPLCAT, 'ms') }}
            </h3></center></td></tr>
        @endif
    </tbody>
</table>
<table style="width: 100%; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
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
                    {{-- substr($senaraiaduan->CA_SUMMARY, 0, 50).'...' --}}
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