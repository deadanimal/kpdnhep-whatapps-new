<?php
    $filename = 'Laporan Jenis Barangan '.date('YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<html>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> -->
<table>
    <thead>
        <tr>
            <th colspan="12">
                LAPORAN JENIS BARANGAN
            </th>
        </tr>
        <tr>
            <th colspan="12">
                TARIKH PENERIMAAN ADUAN : DARI 
                {{ $datestart->format('d-m-Y') }} 
                HINGGA 
                {{ $dateend->format('d-m-Y') }}
            </th>
        </tr>
        <tr>
            <th colspan="12">
                KATEGORI ADUAN : 
                {{ $categorydesc }}
            </th>
        </tr>
    </thead>
</table>
<table class="table table-striped table-bordered" border="1">
    <thead>
        <tr>
            <th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Jenis Barangan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Diterima</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Aduan Baru</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Dalam Siasatan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Maklumat Tidak Lengkap</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Diselesaikan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Ditutup</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Agensi KPDNHEP</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Tribunal</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Pertanyaan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Luar Bidang Kuasa</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total=0;
            $totalbelumagih=0;
            $totaldalamsiasatan=0;
            $totalmaklumatxlengkap=0;
            $totalselesai=0;
            $totaltutup=0;
            $totalagensi=0;
            $totaltribunal=0;
            $totalpertanyaan=0;
            $totalluarbidang=0;
        @endphp
        @foreach ($query as $key => $datum)
            <tr>
                <td style="text-align: center">{{ $key+1 }}</td>
                <td>{{ $datum->descr }}</td>
                <td style="text-align: center">{{ $datum->counttotal }}</td>
                <td style="text-align: center">
                    {{ $datum->BELUMAGIH }}
                </td>
                <td style="text-align: center">
                    {{ $datum->DALAMSIASATAN }}
                </td>
                <td style="text-align: center">
                    {{ $datum->MAKLUMATXLENGKAP }}
                </td>
                <td style="text-align: center">
                    {{ $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP }}
                </td>
                <td style="text-align: center">
                    {{ $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP }}
                </td>
                <td style="text-align: center">
                    {{ $datum->AGENSILAIN }}
                </td>
                <td style="text-align: center">
                    {{ $datum->TRIBUNAL }}
                </td>
                <td style="text-align: center">
                    {{ $datum->PERTANYAAN }}
                </td>
                <td style="text-align: center">
                    {{ $datum->LUARBIDANG }}
                </td>
            </tr>
            @php
                $total += $datum->counttotal;
                $totalbelumagih += $datum->BELUMAGIH;
                $totaldalamsiasatan += $datum->DALAMSIASATAN;
                $totalmaklumatxlengkap += $datum->MAKLUMATXLENGKAP;
                $totalselesai += $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP;
                $totaltutup += $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP;
                $totalagensi += $datum->AGENSILAIN;
                $totaltribunal += $datum->TRIBUNAL;
                $totalpertanyaan += $datum->PERTANYAAN;
                $totalluarbidang += $datum->LUARBIDANG;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Jumlah Aduan</th>
            <th>{{ $total }}</th>
            <th>{{ $totalbelumagih }}</th>
            <th>{{ $totaldalamsiasatan }}</th>
            <th>{{ $totalmaklumatxlengkap }}</th>
            <th>{{ $totalselesai }}</th>
            <th>{{ $totaltutup }}</th>
            <th>{{ $totalagensi }}</th>
            <th>{{ $totaltribunal }}</th>
            <th>{{ $totalpertanyaan }}</th>
            <th>{{ $totalluarbidang }}</th>
        </tr>
    </tfoot>
</table>
</html>

<?php 
    exit;
    fclose($fp);
?>