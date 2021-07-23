<?php
use App\Ref;
use App\Laporan\ReportYear;
use App\Laporan\TerimaSelesaiAduan;

$filename = 'Raw-Data.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
?>
<div class="ibox-content">
    <!--<h2>Laporan Aduan (Senarai)</h2>-->
    <table style="width: 100%;">
        <tr><td><h3><center>LAPORAN PENYELESAIAN ADUAN NEGERI <?php echo $BR_STATECD != '' ? Ref::GetDescr('17', $BR_STATECD) : '' ?> </center></h3></td></tr>
        <tr><td><h3><center>{{ date('d-m-Y', strtotime($CA_RCVDT_FROM)) }} <?php echo $CA_RCVDT_TO != '' ? 'hingga' : ''; ?> {{ date('d-m-Y', strtotime($CA_RCVDT_TO)) }}</center></h3></td></tr>
        <tr><td><h3><center><?php echo $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD) : 'Semua Bahagian' ?></center></h3></td></tr>
    </table>
    <div class="table-responsive">
        <table id="senaraitable" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%; font-size: 11px;">
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
                    <!--<th width="1%">Penyiasat</th>-->
                </tr>
            </thead>
            <tbody>
                @foreach($lists as $senaraiaduan)
                <tr>
                    <td width="3px">{{ $i ++ }}</td>
                    <!--<td width="1%">{{-- $senaraiaduan->CA_CASEID --}}</td>-->
                    <td width="1%">
                            {!! $senaraiaduan->CA_CASEID !!}
                        </td>
                        <td width="1%">
                            {{ implode(' ', array_slice(explode(' ', $senaraiaduan->CA_SUMMARY), 0, 5)).' ...' }}
                            {{-- substr($senaraiaduan->CA_SUMMARY, 0, 50).'...' --}}
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
    </div>
</div>
 <?php 
exit;
fclose($fp);
?>