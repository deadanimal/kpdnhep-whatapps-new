<?php
    use App\Ref;
  use App\Laporan\Bpa;
?>
<style>
    th, td {
        text-align: center;
       
    }
    </style>
               <table style="width: 100%;">
    <tr><td colspan="18"><center><h3>LAPORAN ADUAN MENGIKUT CARA PENERIMAAN</h3></center></td></tr>
    <tr><td colspan="18"><center><h3>{{ Ref::GetDescr('206', $CA_RCVDT_MONTH, 'ms') }} {{ $CA_RCVDT_YEAR }}</h3></center></td></tr>
    <tr><td colspan="18"><center><h3>{{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h3></center></td></tr>
    <tr><td colspan="18"><center><h3>{{ $BR_STATECD != '' ? Ref::GetDescr('17', $BR_STATECD, 'ms') : 'SEMUA NEGERI' }}</h3></center></td></tr>
</table>
                        <table  id="state-table" class="table table-bordered" style="width: 100%; font-size: 10px;border:1px solid; border-collapse: collapse" border="1">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #000; background: #f3f3f3;" >Cara Penerimaan</th>
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
                                        $sumberpenerimaanbulancount = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, $sumberaduan->code)->first();
                                        if(!empty($sumberpenerimaanbulancount)){
                                            $counttotal += $sumberpenerimaanbulancount->countcaseid;
                                        }
                                    ?>
                                @endforeach
                                <?php
//                                    $sumberpenerimaanbulancountempty = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, '')->first();
//                                    if(!empty($sumberpenerimaanbulancountempty)){
//                                        $counttotal += $sumberpenerimaanbulancountempty->countcaseid;
//                                    }
                                ?>
                                @foreach($refsumberaduan as $sumberaduan)
                                    <?php
                                        $sumberpenerimaanbulan = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, $sumberaduan->code)->first();
                                        if(!empty($sumberpenerimaanbulan)){
                                    ?>
                                        <tr>
                                            <td>{{ $sumberaduan->descr }}</td>
                                            <td>{{ $sumberpenerimaanbulan->countcaseid }}</td>
                                            <td>{{ $percent = round((($sumberpenerimaanbulan->countcaseid/$counttotal)*100), 2) }}</td>
                                        </tr>
                                    <?php 
                                            $percenttotal += $percent;
                                        }
                                    ?>
                                @endforeach
                                <?php
//                                    $sumberpenerimaanbulanempty = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, '')->first();
//                                    if(!empty($sumberpenerimaanbulanempty)){
                                ?>
<!--                                    <tr>
                                        <td>Lain-Lain</td>
                                        <td>{{-- $sumberpenerimaanbulanempty->countcaseid --}}</td>
                                        <td>{{-- $percent = round((($sumberpenerimaanbulanempty->countcaseid/$counttotal)*100), 2) --}}</td>
                                    </tr>-->
                                <?php 
//                                        $percenttotal += $percent;
//                                    }
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