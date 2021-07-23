<?php
    use App\Ref;
    use App\Branch;
    use App\Laporan\BandingAduan;
?>
<style>
    .text-align-center{
        text-align: center;
    }
</style>
<table style="width: 100%;">
    <tbody>
        <tr><td colspan="4"><center><h3>LAPORAN STATUS BAGI ADUAN YANG DITERIMA </h3></center></td></tr>
        <tr><td colspan="4"><center><h3>
            @if(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM < $CA_RCVDT_YEAR_TO))
                DARI {{ $CA_RCVDT_YEAR_FROM }} HINGGA {{ $CA_RCVDT_YEAR_TO }}
            @elseif(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM == $CA_RCVDT_YEAR_TO))
                {{ $CA_RCVDT_YEAR_TO }}
            @endif
        </h3></center></td></tr>
        <tr><td colspan="4"><center><h3>
            @if($BR_STATECD != '0')
                {{ Ref::GetDescr('17', $BR_STATECD, 'ms') }}
            @else
                SEMUA NEGERI
            @endif
        </h3></center></td></tr>
        <tr><td colspan="4"><center><h3>
            @if($CA_DEPTCD != '0')
                {{ Ref::GetDescr('315', $CA_DEPTCD, 'ms') }}
            @else
                SEMUA BAHAGIAN
            @endif
        </h3></center></td></tr>
    </tbody>
</table>
<table class="table table-striped table-bordered table-hover" style="width: 100%; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
            <th>Bil.</th>
            <th>Status</th>
            @for($year=$CA_RCVDT_YEAR_FROM; $year<=$CA_RCVDT_YEAR_TO; $year++)
                <th>{{ $year }}</th>
            @endfor
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <?php $counttotal = 0; ?>
        @foreach ($mRefStatus as $status)
            <?php
                ${'counttotalstatus'.$status->code} = 0;
            ?>
            <tr>
                <td class="text-align-center">{{ $bil++ }}</td>
                <td>{{ $status->descr }}</td>
                @for($CA_RCVDT_YEAR=$CA_RCVDT_YEAR_FROM; $CA_RCVDT_YEAR<=$CA_RCVDT_YEAR_TO; $CA_RCVDT_YEAR++)
                    <td class="text-align-center">{{ ${'countstatus'.$status->code.'year'.$CA_RCVDT_YEAR} = BandingAduan::jumlahaduanstatustahun($status->code, $CA_RCVDT_YEAR, $BR_STATECD, $CA_DEPTCD) }}</td>
                    <?php ${'counttotalstatus'.$status->code} += ${'countstatus'.$status->code.'year'.$CA_RCVDT_YEAR}; ?>
                @endfor
                <td class="text-align-center">{{ ${'counttotalstatus'.$status->code} }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2"><center>Jumlah</center></td>
            @for($CA_RCVDT_YEAR=$CA_RCVDT_YEAR_FROM; $CA_RCVDT_YEAR<=$CA_RCVDT_YEAR_TO; $CA_RCVDT_YEAR++)
                <td class="text-align-center">{{ ${'counttotalyear'.$CA_RCVDT_YEAR} = BandingAduan::jumlahaduantahun($CA_RCVDT_YEAR, $BR_STATECD, $CA_DEPTCD) }}</td>
                <?php $counttotal += ${'counttotalyear'.$CA_RCVDT_YEAR}; ?>
            @endfor
            <td class="text-align-center">{{ $counttotal }}</td>
        </tr>
    </tbody>
</table>
