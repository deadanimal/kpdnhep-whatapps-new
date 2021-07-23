<?php
    $filename = 'Laporan FOK Kategori '.date('YmdHis').'.xls';
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
                LAPORAN ADUAN OLEH PENGGUNA "FRIENDS OF KPDNHEP" (FOK) MENGIKUT KATEGORI
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
            <th style="border: 1px solid #000; background: #f3f3f3;">Kategori Aduan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Pengguna FOK</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Bukan Pengguna FOK</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Aduan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalfok0=0;
            $totalfok1=0;
            $totalcountfokind=0;
        @endphp
        @foreach ($query as $key => $datum)
            <tr>
                <td style="text-align: center">{{ $key+1 }}</td>
                <td>{{ $datum->descr }}</td>
                <td style="text-align: center">{{ $datum->fok1 }}</td>
                <td style="text-align: center">{{ $datum->fok0 }}</td>
                <td style="text-align: center">{{ $datum->countfokind }}</td>
            </tr>
            @php
                $totalfok0 += $datum->fok0;
                $totalfok1 += $datum->fok1;
                $totalcountfokind += $datum->countfokind;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Jumlah Aduan</th>
            <th>{{ $totalfok1 }}</th>
            <th>{{ $totalfok0 }}</th>
            <th>{{ $totalcountfokind }}</th>
        </tr>
    </tfoot>
</table>
</html>

<?php 
    exit;
    fclose($fp);
?>