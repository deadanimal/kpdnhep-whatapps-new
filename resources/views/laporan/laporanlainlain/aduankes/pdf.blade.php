<?php
?>

<style>
    th, td {
        /* text-align: center; */
        font-size: 12px;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;">
            <h3>
                LAPORAN STATISTIK ADUAN MENGHASILKAN KES MENGIKUT NEGERI & CAWANGAN
            </h3>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <h3>
                TARIKH PENERIMAAN ADUAN : DARI 
                {{ $datestart->format('d-m-Y') }} 
                HINGGA 
                {{ $dateend->format('d-m-Y') }}
            </h3>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <h3>
                NEGERI : 
                {{ $statedesc }}
            </h3>
        </td>
    </tr>
</table>
@if($request->gen)
    <table 
        class="table table-striped table-bordered table-hover dataTables-example"
        style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" 
        border="1">
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
                    <td style="text-align: center;">{{ $key+1 }}</td>
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
@endif