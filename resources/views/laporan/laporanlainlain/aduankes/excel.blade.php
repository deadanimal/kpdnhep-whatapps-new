<?php
    $filename = 'Laporan Aduan Menghasilkan Kes '.date('YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<html>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> -->
<table>
    <thead>
        <tr>
            <th colspan="5">
                LAPORAN STATISTIK ADUAN MENGHASILKAN KES MENGIKUT NEGERI & CAWANGAN
            </th>
        </tr>
        <tr>
            <th colspan="5">
                TARIKH PENERIMAAN ADUAN : DARI 
                {{ $datestart->format('d-m-Y') }} 
                HINGGA 
                {{ $dateend->format('d-m-Y') }}
            </th>
        </tr>
        <tr>
            <th colspan="5">
                NEGERI : 
                {{ $statedesc }}
            </th>
        </tr>
    </thead>
</table>
<table class="table table-striped table-bordered" border="1">
    <thead>
        <tr>
            <th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Nama Cawangan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Aduan Diterima</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Aduan Menghasilkan Kes</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Aduan Tidak Menghasilkan Kes</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalditerima=0;
            $totalkes=0;
            $totalbukankes=0;
        @endphp
        @foreach ($query as $key => $datum)
            <tr>
                <td style="text-align: center">{{ $key+1 }}</td>
                <td>{{ $datum->namacawangan }}</td>
                <td style="text-align: center">{{ $datum->jumlahaduanterima }}</td>
                <td style="text-align: center">{{ $datum->aduankes }}</td>
                <td style="text-align: center">{{ $datum->aduanbukankes }}</td>
            </tr>
            @php
                $totalditerima += $datum->jumlahaduanterima;
                $totalkes += $datum->aduankes;
                $totalbukankes += $datum->aduanbukankes;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Jumlah Aduan</th>
            <th>{{ $totalditerima }}</th>
            <th>{{ $totalkes }}</th>
            <th>{{ $totalbukankes }}</th>
        </tr>
    </tfoot>
</table>
</html>

<?php 
    exit;
    fclose($fp);
?>