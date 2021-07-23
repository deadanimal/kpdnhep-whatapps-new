<?php
    $filename = 'Laporan-AduanSemuaIkutNegeriBahagian-'.date('Ymd_His').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
    use App\Ref;
?>
<?php
    use App\Laporan\ReportLainlain;
?>
<table style="width: 100%;">
    <tr><td colspan="12"><center><h3>{{ $titleyear }}</h3></center></td></tr>
    <tr><td colspan="12"><center><h3>{{ $titlemonth }}</h3></center></td></tr>
</table>
<table border="1">
    <thead>
        <tr>
            <th rowspan="2" style="width: 5%;text-align:center">Bil.</th>
            <th rowspan="2" style="width: 50%;">Negeri / Bahagian</th>
            <th colspan="10" style="width: 8%;text-align:center">Jumlah Aduan</th>
        </tr>
        <tr>
            <th>Diterima</th>
            <th>Baru Diterima</th>
            <th>Dalam Siasatan</th>
            <th>Diselesaikan</th>
            <th>Ditutup</th>
            <th>Agensi Lain</th>
            <th>Tribunal</th>
            <th>Pertanyaan</th>
            <th>Maklumat Tidak Lengkap</th>
            <th>Tidak Berasas</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 1;
            $TotalDITERIMA = 0;
            $TotalBARU = 0;
            $TotalSIASATAN = 0;
            $TotalSELESAI = 0;
            $TotalTUTUP = 0;
            $TotalAGENSILAIN = 0;
            $TotalTRIBUNAL = 0;
            $TotalPERTANYAAN = 0;
            $TotalMAKLUMATXLENGKAP = 0;
            $TotalLUARBIDANG = 0;
        ?>
        @foreach ($datas as $data)
            <tr>
                <td style="text-align:center">{{ $i++ }}</td>
                <td>{{ $data->descr }}</td>
                <td style="text-align:center">{{ $data->DITERIMA }}</td>
                <td style="text-align:center">{{ $data->BARU }}</td>
                <td style="text-align:center">{{ $data->SIASATAN }}</td>
                <td style="text-align:center">{{ $data->SELESAI }}</td>
                <td style="text-align:center">{{ $data->TUTUP }}</td>
                <td style="text-align:center">{{ $data->AGENSILAIN }}</td>
                <td style="text-align:center">{{ $data->TRIBUNAL }}</td>
                <td style="text-align:center">{{ $data->PERTANYAAN }}</td>
                <td style="text-align:center">{{ $data->MAKLUMATXLENGKAP }}</td>
                <td style="text-align:center">{{ $data->LUARBIDANG }}</td>
            </tr>
            <?php 
                $TotalDITERIMA += $data->DITERIMA;
                $TotalBARU += $data->BARU;
                $TotalSIASATAN += $data->SIASATAN;
                $TotalSELESAI += $data->SELESAI;
                $TotalTUTUP += $data->TUTUP;
                $TotalAGENSILAIN += $data->AGENSILAIN;
                $TotalTRIBUNAL += $data->TRIBUNAL;
                $TotalPERTANYAAN += $data->PERTANYAAN;
                $TotalMAKLUMATXLENGKAP += $data->MAKLUMATXLENGKAP;
                $TotalLUARBIDANG += $data->LUARBIDANG;
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
                <td style="text-align:center">{{ $data->TUTUP }}</td>
                <td style="text-align:center">{{ $data->AGENSILAIN }}</td>
                <td style="text-align:center">{{ $data->TRIBUNAL }}</td>
                <td style="text-align:center">{{ $data->PERTANYAAN }}</td>
                <td style="text-align:center">{{ $data->MAKLUMATXLENGKAP }}</td>
                <td style="text-align:center">{{ $data->LUARBIDANG }}</td>
            </tr>
            <?php 
                $TotalDITERIMA += $data->DITERIMA;
                $TotalBARU += $data->BARU;
                $TotalSIASATAN += $data->SIASATAN;
                $TotalSELESAI += $data->SELESAI;
                $TotalTUTUP += $data->TUTUP;
                $TotalAGENSILAIN += $data->AGENSILAIN;
                $TotalTRIBUNAL += $data->TRIBUNAL;
                $TotalPERTANYAAN += $data->PERTANYAAN;
                $TotalMAKLUMATXLENGKAP += $data->MAKLUMATXLENGKAP;
                $TotalLUARBIDANG += $data->LUARBIDANG;
            ?>
        @endforeach
        <tr style="font-weight:bold">
            <!-- <td></td> -->
            <td colspan="2" style="text-align:center">Jumlah</td>
            <td style="text-align:center">{{ $TotalDITERIMA }}</td>
            <td style="text-align:center">{{ $TotalBARU }}</td>
            <td style="text-align:center">{{ $TotalSIASATAN }}</td>
            <td style="text-align:center">{{ $TotalSELESAI }}</td>
            <td style="text-align:center">{{ $TotalTUTUP }}</td>
            <td style="text-align:center">{{ $TotalAGENSILAIN }}</td>
            <td style="text-align:center">{{ $TotalTRIBUNAL }}</td>
            <td style="text-align:center">{{ $TotalPERTANYAAN }}</td>
            <td style="text-align:center">{{ $TotalMAKLUMATXLENGKAP }}</td>
            <td style="text-align:center">{{ $TotalLUARBIDANG }}</td>
        </tr>
    </tbody>
</table>
<?php 
    exit;
    fclose($fp);
?>