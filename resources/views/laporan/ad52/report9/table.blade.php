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
            <th style="text-align:center">Jadual 2</th>
            @foreach ($actTemplates as $template)
            <th style="text-align:center">{{ $template }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataTemplateRows as $key => $dataTemplateRow)
        <tr>
            <th style="text-align:center">{{ $dataTemplateRow }}</th>
            @foreach ($actTemplates as $keyActTemplate => $actTemplate)
            <td style="text-align:center">{{ $dataCount[$key][$keyActTemplate] }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="text-align:center">Jumlah</th>
            @foreach ($actTemplates as $keyActTemplate => $actTemplate)
            <th style="text-align:center">{{ $dataCount['total'][$keyActTemplate] }}</th>
            @endforeach
        </tr>
    </tfoot>
</table>
