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
            <th style="text-align:center">Negeri</th>
            <th style="text-align:center">Jumlah Aduan</th>
            <th style="text-align:center">Aduan Melebihi 3 Hari</th>
            <th style="text-align:center">% Lewat Agih</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @foreach($states as $key => $state)
            <tr>
                <td style="text-align:center">{{ $count++ }}</td>
                <td>{{ $state }}</td>
                <td style="text-align:center">{{ $dataCount[$key]['total'] }}</td>
                <td style="text-align:center">{{ $dataCount[$key]['>3'] }}</td>
                <td style="text-align:center">{{ $dataCount[$key]['pct'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" style="text-align:center">Jumlah</th>
            <th style="text-align:center">{{ $dataCount['total']['total'] }}</th>
            <th style="text-align:center">{{ $dataCount['total']['>3'] }}</th>
            <th style="text-align:center">{{ $dataCount['total']['pct'] }}</th>
        </tr>
    </tfoot>
</table>
