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
            <th style="text-align:center">Analisa Data</th>
            @foreach ($actTemplates as $actTemplate)
            <th style="text-align:center">{{ $actTemplate }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center">Proses Kerja Capai Objektif</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['achieveObjective'][$key] }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Proses Kerja Tak Capai Objektif</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['notAchieveObjective'][$key] }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Jumlah Aduan Diambil Tindakan</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['totalComplaintTakenAction'][$key] }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Masih Dalam Tindakan</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['inProgress'][$key] }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Jumlah Keseluruhan</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['total'][$key] }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Purata</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['average'][$key] }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Kekerapan</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['mode'][$key] }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Median</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['median'][$key] }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Minimum</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['min'][$key] }}</td>
            @endforeach
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align:center">Maksimum</td>
            @foreach ($dataTemplates as $key => $actTemplate)
            <td style="text-align:center">{{ $dataCount['max'][$key] }}</td>
            @endforeach
        </tr>
    </tfoot>
</table>
