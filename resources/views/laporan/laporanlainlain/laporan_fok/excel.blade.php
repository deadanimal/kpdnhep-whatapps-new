<?php
    $filename = 'Laporan FOK '.date('YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<html>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> -->
<table>
    <thead>
        <tr>
            <th colspan="4">
                LAPORAN ADUAN OLEH PENGGUNA "FRIENDS OF KPDNHEP" (FOK)
            </th>
        </tr>
        <tr>
            <th colspan="4">
                TARIKH PENERIMAAN ADUAN : DARI 
                {{ $datestart->format('d-m-Y') }} 
                HINGGA 
                {{ $dateend->format('d-m-Y') }}
            </th>
        </tr>
    </thead>
</table>
<table class="table table-striped table-bordered" border="1">
    <thead>
        <!-- <tr> -->
            <!-- <th colspan="4"> -->
                <!-- LAPORAN ADUAN OLEH PENGGUNA "FRIENDS OF KPDNHEP" (FOK) -->
            <!-- </th> -->
        <!-- </tr> -->
        <!-- <tr> -->
            <!-- <th colspan="4"> -->
                <!-- TARIKH PENERIMAAN ADUAN : DARI  -->
                <!-- {{-- $datestart->format('d-m-Y') --}}  -->
                <!-- HINGGA  -->
                <!-- {{-- $dateend->format('d-m-Y') --}} -->
            <!-- </th> -->
        <!-- </tr> -->
        <tr>
            <th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">No. Kad Pengenalan / Pasport</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Nama</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Aduan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total=0;
        @endphp
        @foreach ($query as $key => $datum)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>'{{ $datum->docno }}</td>
                <td>{{ $datum->name }}</td>
                <td style="text-align: center">{{ $datum->total }}</td>
            </tr>
            @php
                $total += $datum->total;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th>Jumlah</th>
            <th>{{$total}}</th>
        </tr>
    </tfoot>
</table>
</html>

<?php 
    exit;
    fclose($fp);
?>