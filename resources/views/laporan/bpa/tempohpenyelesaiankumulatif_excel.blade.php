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
                                <h3 align="center">TEMPOH PENYELESAIAN (KUMULATIF)</h3>
                                <h3 align="center">
                                    DARI {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }} HINGGA {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_TO, 'ms') }} {{ $CA_RCVDT_YEAR }}
                                </h3>
                                <h3 align="center">
                                    {{ $BR_STATECD != '' ? Ref::GetDescr('17', $BR_STATECD, 'ms') : 'SEMUA NEGERI' }}
                                </h3>
                                <h3 align="center">
                                    {{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}
                                </h3>
                                <tr>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">Tempoh (hari)</th>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Selesai</th>
                                    <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Selesai (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataCount as $key => $cnt)
                                    @if($key !== 'total')
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $cnt }}</td>
                                            <td>{{ $dataPctg[$key] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Jumlah</td>
                                    <td>{{ $dataCount['total'] }}</td>
                                    <td>{{ $dataPctg['total'] }}</td>
                                </tr>
                            </tfoot>
                        </table>
    
 <?php 
exit;
fclose($fp);
?>    