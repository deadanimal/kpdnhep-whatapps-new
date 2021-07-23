<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table style="width: 100%; font-size: 16px; text-align: center">
    <tr>
        <td colspan="15"><h2>LAPORAN MATRIKS BAGI {{ $ds->format('d-m-Y') }} HINGGA {{ $de->format('d-m-Y') }}</h2></td>
    </tr>
</table>
<table border="1">
    <thead>
    <tr>
        <th style="border: 1px solid #000; background: #f3f3f3;">No.</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Perkara</th>
        @foreach ($col_data as $data)
            <th style="border: 1px solid #000; background: #f3f3f3;">{{$col_list[$data]}}</th>
        @endforeach
        <th style="border: 1px solid #000; background: #f3f3f3;"> Jumlah</th>
    </tr>
    </thead>
    <tbody>
    @php $i = 1 @endphp
    @foreach($row_data as $datum)
        <tr>
            <td>{{$i++}}</td>
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
            <th></th>
            <th>Jumlah</th>
            @foreach ($col_data as $data)
                <td>{{$data_final['total'][$data]}}</td>
            @endforeach
            <td>{{$data_final['total']['total']}}</td>
        </tr>
    </tfooter>
</table>