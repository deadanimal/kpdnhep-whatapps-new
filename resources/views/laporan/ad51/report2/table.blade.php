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
            <th style="text-align:center" rowspan="2">Bil.</th>
            <th style="text-align:center" rowspan="2">Negeri</th>
            <th style="text-align:center" rowspan="2">Aduan Melebihi 3 Hari</th>
            @if($countCaseReasonTemplate > 0)
                <th style="text-align:center" colspan="{{ $countCaseReasonTemplate }}">
                    Keterangan Bagi Kelewatan Pengagihan
                </th>
            @endif
        </tr>
        <tr>
            @foreach($caseReasonTemplates as $template)
                <th style="text-align:center">{{ $template }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @foreach($states as $key => $state)
            <tr>
                <td style="text-align:center">{{ $count++ }}</td>
                <td>{{ $state }}</td>
                <td style="text-align:center">{{ $dataCount[$key]['>3'] }}</td>
                @foreach($caseReasonTemplates as $keyTemplate => $template)
                    <td style="text-align:center">{{ $dataCount[$key][$keyTemplate] }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="text-align:center" colspan="2">Jumlah</th>
            <th style="text-align:center">{{ $dataCount['total']['>3'] }}</th>
            @foreach($caseReasonTemplates as $keyTemplate => $template)
                <th style="text-align:center">{{ $dataCount['total'][$keyTemplate] }}</th>
            @endforeach
        </tr>
    </tfoot>
</table>
