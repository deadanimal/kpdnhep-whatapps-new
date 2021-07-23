<?php
    use App\Ref;
    use App\Laporan\ReportLainlain;
?>
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
            <tr><td><center><h3>{{ $titlemonth }}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;text-align:center">Bil.</th>
                    <th rowspan="2" style="width: 50%;">Negeri</th>
                    <th colspan="4" style="width: 8%;text-align:center">Jumlah Aduan</th>
                </tr>
                <tr>
                    <th>Diterima</th>
                    <th>Baru Diterima</th>
                    <th>Dalam Siasatan</th>
                    <th>Diselesaikan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $TotalDITERIMA = 0;
                $TotalBARU = 0;
                $TotalSIASATAN = 0;
                $TotalSELESAI = 0;
                ?>
                @foreach ($datas as $data)
                <tr>
                    <td style="text-align:center">{{ $i++ }}</td>
                    <td>{{ $data->descr }}</td>
                    <td style="text-align:center">{{ $data->DITERIMA }}</td>
                    <td style="text-align:center">{{ $data->BARU }}</td>
                    <td style="text-align:center">{{ $data->SIASATAN }}</td>
                    <td style="text-align:center">{{ $data->SELESAI }}</td>
                </tr>
                <?php 
                $TotalDITERIMA += $data->DITERIMA;
                $TotalBARU += $data->BARU;
                $TotalSIASATAN += $data->SIASATAN;
                $TotalSELESAI += $data->SELESAI;
                ?>
                @endforeach
                @foreach ($datap as $data)
                <tr>
                    <td style="text-align:center">{{ $i++ }}</td>
                    <td>{{ $data->BR_BRNNM }}</td>
                    <td style="text-align:center">{{ $data->DITERIMA }}</td>
                    <td style="text-align:center">{{ $data->BARU }}</td>
                    <td style="text-align:center">{{ $data->SIASATAN }}</td>
                    <td style="text-align:center">{{ $data->SELESAI }}</td>
                </tr>
                <?php 
                $TotalDITERIMA += $data->DITERIMA;
                $TotalBARU += $data->BARU;
                $TotalSIASATAN += $data->SIASATAN;
                $TotalSELESAI += $data->SELESAI;
                ?>
                @endforeach
                <tr style="font-weight:bold">
                    <!-- <td></td> -->
                    <td colspan="2" style="text-align:center">Jumlah</td>
                    <td style="text-align:center">{{ $TotalDITERIMA }}</td>
                    <td style="text-align:center">{{ $TotalBARU }}</td>
                    <td style="text-align:center">{{ $TotalSIASATAN }}</td>
                    <td style="text-align:center">{{ $TotalSELESAI }}</td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>
</div>