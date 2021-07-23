<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid black;
        vertical-align: middle;
    }
</style>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th rowspan="2" style="text-align:center">Bil</th>
            <th rowspan="2" style="text-align:center">Akta</th>
            <th colspan="3" style="text-align:center">Status Makluman Hasil Siasatan</th>
            <th rowspan="2" style="text-align:center">Aduan Masih Dalam Tindakan</th>
            <th rowspan="2" style="text-align:center">Jumlah Keseluruhan</th>
        </tr>
        <tr>
            <th style="text-align:center">Kes</th>
            <th style="text-align:center">Tiada Kes</th>
            <th style="text-align:center">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($actTemplates as $key => $template)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $template }}</td>
            <td style="text-align:center">{{ $dataCount[$key]['case'] }}</td>
            <td style="text-align:center">{{ $dataCount[$key]['nocase'] }}</td>
            <td style="text-align:center">{{ $dataCount[$key]['subtotal'] }}</td>
            <td style="text-align:center">{{ $dataCount[$key]['investigate'] }}</td>
            <td style="text-align:center">{{ $dataCount[$key]['total'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" style="text-align:center">Jumlah Keseluruhan</th>
            <th style="text-align:center">{{ $dataCount['total']['case'] }}</th>
            <th style="text-align:center">{{ $dataCount['total']['nocase'] }}</th>
            <th style="text-align:center">{{ $dataCount['total']['subtotal'] }}</th>
            <th style="text-align:center">{{ $dataCount['total']['investigate'] }}</th>
            <th style="text-align:center">{{ $dataCount['total']['total'] }}</th>
        </tr>
    </tfoot>
</table>
