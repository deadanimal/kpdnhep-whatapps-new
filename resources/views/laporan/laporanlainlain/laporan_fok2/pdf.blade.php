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
        <td>
            <div style="text-align: center;">
                <h3>
                    LAPORAN ADUAN OLEH PENGGUNA "FRIENDS OF KPDNHEP" (FOK) MENGIKUT KATEGORI
                </h3>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div style="text-align: center;">
                <h3>
                    TARIKH PENERIMAAN ADUAN : DARI 
                    {{ $datestart->format('d-m-Y') }} 
                    HINGGA 
                    {{ $dateend->format('d-m-Y') }}
                </h3>
            </div>
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
                    <td style="text-align: center;">
                        {{ $key+1 }}
                        <!-- {{-- $loop->iteration --}} -->
                    </td>
                    <td>{{ $datum->descr }}</td>
                    <td style="text-align: center;">{{ $datum->fok1 }}</td>
                    <td style="text-align: center;">{{ $datum->fok0 }}</td>
                    <td style="text-align: center;">{{ $datum->countfokind }}</td>
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
@endif