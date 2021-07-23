<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
<style>
    h5, td {
        text-align: center;
    }
</style>
<!--<title>Laporan Perbandingan Aduan Mengikut Negeri (Bulanan)</title>-->
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <h5>LAPORAN PERBANDINGAN ADUAN MENGIKUT NEGERI (BULANAN)</h5>
                    <h5>
                        @if(($CA_RCVDT_MONTH_FROM) && ($CA_RCVDT_MONTH_TO) && ($CA_RCVDT_MONTH_FROM < $CA_RCVDT_MONTH_TO))
                            DARI {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }} 
                            HINGGA {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_TO, 'ms') }} 
                        @elseif(($CA_RCVDT_MONTH_FROM == $CA_RCVDT_MONTH_TO) && ($CA_RCVDT_MONTH_FROM!='')&&($CA_RCVDT_MONTH_TO!=''))
                            {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }}
                        @endif
                        {{ $CA_RCVDT_YEAR }}
                    </h5>
                    <h5>{{ $CA_DEPTCD != '0' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h5>
                    <table class="table table-striped table-bordered table-hover" style="width: 100%; border:1px solid; border-collapse: collapse" border="1">
                        @if($action != '')
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Negeri</th>
                                    @for($mRefMonth=$CA_RCVDT_MONTH_FROM; $mRefMonth<=$CA_RCVDT_MONTH_TO; $mRefMonth++)
                                        <th>{{ Ref::GetDescr('206', $mRefMonth, 'ms') }}</th>
                                    @endfor
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $countcaseidtotal = 0;
                                ?>
                                @foreach ($laporannegeribulan as $rowstate)
                                    <tr>
                                        <td>{{ $bil++ }}</td>
                                        <td>{{ Ref::GetDescr('17', $rowstate->BR_STATECD, 'ms') }}</td>
                                        @for($month=$CA_RCVDT_MONTH_FROM; $month<=$CA_RCVDT_MONTH_TO; $month++)
                                        <td>
                                            {{ $rowstate->{'countmonth'.$month} }}
                                        </td>
                                        @endfor
                                        <td>{{ $rowstate->countcaseid }}</td>
                                    </tr>
                                    <?php
                                        $countmonth1total += $rowstate->countmonth1;
                                        $countmonth2total += $rowstate->countmonth2;
                                        $countmonth3total += $rowstate->countmonth3;
                                        $countmonth4total += $rowstate->countmonth4;
                                        $countmonth5total += $rowstate->countmonth5;
                                        $countmonth6total += $rowstate->countmonth6;
                                        $countmonth7total += $rowstate->countmonth7;
                                        $countmonth8total += $rowstate->countmonth8;
                                        $countmonth9total += $rowstate->countmonth9;
                                        $countmonth10total += $rowstate->countmonth10;
                                        $countmonth11total += $rowstate->countmonth11;
                                        $countmonth12total += $rowstate->countmonth12;
                                        $countcaseidtotal += $rowstate->countcaseid;
                                    ?>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Jumlah</td>
                                    @for($month=$CA_RCVDT_MONTH_FROM; $month<=$CA_RCVDT_MONTH_TO; $month++)
                                        <td>{{ ${'countmonth'.$month.'total'} }}</td>
                                    @endfor
                                    <td>{{ $countcaseidtotal }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>