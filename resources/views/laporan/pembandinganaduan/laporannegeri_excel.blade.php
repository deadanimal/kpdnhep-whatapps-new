@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
@section('content')
<?php
    $filename = 'Laporan-Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 100%" border="1">
                        @if($action != '')
                            <h3 align="center">LAPORAN PERBANDINGAN ADUAN MENGIKUT NEGERI (BULANAN)</h3>
                            <h3 align="center">
                                @if(($CA_RCVDT_MONTH_FROM) && ($CA_RCVDT_MONTH_TO) && ($CA_RCVDT_MONTH_FROM < $CA_RCVDT_MONTH_TO))
                                    DARI {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }} 
                                    HINGGA {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_TO, 'ms') }} 
                                @elseif(($CA_RCVDT_MONTH_FROM == $CA_RCVDT_MONTH_TO) && ($CA_RCVDT_MONTH_FROM!='')&&($CA_RCVDT_MONTH_TO!=''))
                                    {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }}
                                @endif
                                {{ $CA_RCVDT_YEAR }}
                            </h3>
                            <h3 align="center">{{ $CA_DEPTCD != '0' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h3>
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
<?php 
    exit;
    fclose($fp);
?>
@stop