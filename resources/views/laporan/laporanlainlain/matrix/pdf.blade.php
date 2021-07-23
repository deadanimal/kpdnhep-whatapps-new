<style>
    th, td {
        text-align: center;
        font-size: 12px;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td colspan="5">
            <div style="text-align: center;">
                <h2>
                    LAPORAN MATRIKS BAGI {{ $ds->format('d-m-Y') }} HINGGA {{ $de->format('d-m-Y') }}
                </h2>
            </div>
        </td>
    </tr>
</table>
<table id="state-table" class="table table-bordered"
       style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
    <tr>
        <th>No.</th>
        @foreach ($col_data as $data)
            <th>{{$col_list[$data]}}</th>
        @endforeach
        <th> Jumlah</th>
    </tr>
    </thead>
    <tbody>
    @foreach($row_data as $datum)
        <tr>
            <td>{{$row_list[$datum]}}</td>
            @foreach ($col_data as $data)
                <td>{{$data_final[$datum][$data]}}</td>
            @endforeach
            <td>{{$data_final[$datum]['total']}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfooter>
        <tr>
            <th>Jumlah</th>
            @foreach ($col_data as $data)
                <td>{{$data_final['total'][$data]}}</td>
            @endforeach
            <td>{{$data_final['total']['total']}}</td>
        </tr>
    </tfooter>
</table>