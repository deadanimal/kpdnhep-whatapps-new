<?php
?>

<style>
    th, td {
        text-align: center;
        font-size: 12px;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td colspan="3">
            <div style="text-align: center;">
                <h3>
                    LAPORAN ADUAN OLEH PENGGUNA "FRIENDS OF KPDNHEP" (FOK)
                </h3>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="3">
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
        <!-- <h3> -->
            <!-- Tarikh:{{-- $datestart->format('d-m-Y') --}} hingga {{-- $dateend->format('d-m-Y') --}} -->
        <!-- </h3> -->
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
                    <td>
                        {{ $key+1 }}
                        <!-- {{-- $loop->iteration --}} -->
                    </td>
                    <td>{{ $datum->docno }}</td>
                    <td>{{ $datum->name }}</td>
                    <td>{{ $datum->total }}</td>
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
                <th>
                    {{ $total }}
                </th>
            </tr>
        </tfoot>
    </table>
@endif