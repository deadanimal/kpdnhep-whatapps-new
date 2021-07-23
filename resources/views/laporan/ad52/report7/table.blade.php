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
            <th style="text-align:center">Bil.</th>
            <th style="text-align:center">Akta</th>
            <th style="text-align:center">Jadual 1</th>
            <th style="text-align:center">Jadual 2</th>
        </tr>
    </thead>
    <tbody>
        @foreach($actTemplates as $key => $template)
            <tr>
                <td style="text-align:center">{{ $loop->iteration }}</td>
                <td>{{ $template }}</td>
                <td style="text-align:center">{{ $dataCount[$key]['table1'] }}</td>
                <td style="text-align:center">{{ $dataCount[$key]['table2'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" style="text-align:center">Jumlah Keseluruhan</th>
            <th style="text-align:center">{{ $dataCount['total']['table1'] }}</th>
            <th style="text-align:center">{{ $dataCount['total']['table2'] }}</th>
        </tr>
    </tfoot>
</table>
