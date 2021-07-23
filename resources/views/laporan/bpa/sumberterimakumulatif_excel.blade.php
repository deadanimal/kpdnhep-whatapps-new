 <?php
    use App\Ref;
    use App\Laporan\Bpa;
?>
<?php
$filename = 'Raw-Data.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
?>
<style>
    th, td {
        text-align: center;
        font-size: 12px;
    }
    </style>


<table class="table table-striped table-bordered table-hover" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
                        <thead>
                        <h3 align="center">LAPORAN ADUAN MENGIKUT CARA PENERIMAAN</h3>
                        <h3 align="center">DARI {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }} HINGGA {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_TO, 'ms') }} {{ $CA_RCVDT_YEAR }}</h3>
                        <h3 align="center">{{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h3>
                        <h3 align="center">{{ $BR_STATECD != '' ? Ref::GetDescr('17', $BR_STATECD, 'ms') : 'SEMUA NEGERI' }}</h3>
                        <tr>
                            <th style="border: 1px solid #000; background: #f3f3f3;">Cara Penerimaan</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Aduan</th>
                            <th style="border: 1px solid #000; background: #f3f3f3;">% Aduan</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counttotal = 0;
                            $percenttotal = 0;
                            ?>
                            @foreach($refsumberaduan as $sumberaduan)
                            <?php
                            $sumberpenerimaanbulankumulatifcount = Bpa::sumberpenerimaanbulankumulatif($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_DEPTCD, $BR_STATECD, $sumberaduan->code)->first();
                            if (!empty($sumberpenerimaanbulankumulatifcount)) {
                                $counttotal += $sumberpenerimaanbulankumulatifcount->countcaseid;
                            }
                            ?>
                            @endforeach
                            <?php
//                            $sumberpenerimaanbulankumulatifcountempty = Bpa::sumberpenerimaanbulankumulatif($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_DEPTCD, $BR_STATECD, '')->first();
//                            if (!empty($sumberpenerimaanbulankumulatifcountempty)) {
//                                $counttotal += $sumberpenerimaanbulankumulatifcountempty->countcaseid;
//                            }
                            ?>
                            @foreach($refsumberaduan as $sumberaduan)
                            <?php
                            $sumberpenerimaanbulankumulatif = Bpa::sumberpenerimaanbulankumulatif($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_DEPTCD, $BR_STATECD, $sumberaduan->code)->first();
                            if (!empty($sumberpenerimaanbulankumulatif)) {
                                ?>
                                <tr>
                                    <td>{{ $sumberaduan->descr }}</td>
                                    <td>{{ $sumberpenerimaanbulankumulatif->countcaseid }}</td>
    <?php $percent = ($sumberpenerimaanbulankumulatif->countcaseid / $counttotal) * 100; ?>
                                    <td>{{ $percent >= 0.01 ? round($percent, 2) : round($percent, 3) }}</td>
                                </tr>
                                <?php
                                $percenttotal += $percent;
                            }
                            ?>
                            @endforeach
                            <?php
//                            $sumberpenerimaanbulankumulatifempty = Bpa::sumberpenerimaanbulankumulatif($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_DEPTCD, $BR_STATECD, '')->first();
//                            if (!empty($sumberpenerimaanbulankumulatifempty)) {
                                ?>
                                <!--<tr>-->
                                    <!--<td>Lain-Lain</td>-->
                                    <!--<td>{{-- $sumberpenerimaanbulankumulatifempty->countcaseid --}}</td>-->
    <?php // $percent = ($sumberpenerimaanbulankumulatifempty->countcaseid / $counttotal) * 100; ?>
                                    <!--<td>{{-- $percent >= 0.01 ? round($percent, 2) : round($percent, 3) --}}</td>-->
                                <!--</tr>-->
                                <?php
//                                $percenttotal += $percent;
//                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Jumlah</td>
                                <td>{{ $counttotal }}</td>
                                <td>{{ round($percenttotal) }}</td>
                            </tr>
                        </tfoot>
                    </table>
               <?php 
exit;
fclose($fp);
?> 