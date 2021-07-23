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
            <th style="text-align:center">Alasan</th>
            @foreach($caseReasonTemplates as $template)
                <th style="text-align:center">{{ $template }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            <th style="text-align:center">Jumlah Alasan</th>
            @foreach($caseReasonTemplates as $keyTemplate => $template)
                <td style="text-align:center">{{ $dataCount[$keyTemplate] }}</td>
            @endforeach
        </tr>
    </tbody>
    <tfoot>
    </tfoot>
</table>
