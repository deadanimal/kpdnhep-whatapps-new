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
            <th style="text-align:center">Tempoh</th>
            <th style="text-align:center">AD 51</th>
            <th style="text-align:center">AD 52</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataTemplateRow as $key => $template)
            <tr>
                <td style="text-align:center">{{ $template }}</td>
                <td style="text-align:center">{{ $dataCount[$key]['ad51'] }}</td>
                <td style="text-align:center">{{ $dataCount[$key]['ad52'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="text-align:center">Jumlah Keseluruhan Aduan</th>
            <th style="text-align:center">{{ $dataCount['total']['ad51'] }}</th>
            <th style="text-align:center">{{ $dataCount['total']['ad52'] }}</th>
        </tr>
    </tfoot>
</table>
