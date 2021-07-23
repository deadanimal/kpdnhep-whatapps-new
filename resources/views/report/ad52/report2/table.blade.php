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
            <th style="text-align:center">Analisa Data Aduan</th>
            @foreach ($data['acttemplates'] as $key => $actTemplate)
            <th style="text-align:center">{{ $actTemplate }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center">Proses Kerja Capai Objektif (Aduan)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['achieveObjective'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Proses Kerja Tak Capai Objektif (Aduan)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['notAchieveObjective'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Jumlah Aduan Diambil Tindakan</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['totalComplaintTakenAction'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Masih Dalam Tindakan (Aduan)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['inProgress'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Jumlah Keseluruhan (Aduan)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['total'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Purata (Hari)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['average'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Kekerapan (Hari)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['mode'][$key][0] ?? 0 }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Median (Hari)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['median'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
        <tr>
            <td style="text-align:center">Minimum (Hari)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['min'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align:center">Maksimum (Hari)</td>
            @foreach ($data['templates'] as $key => $actTemplate)
            <td style="text-align:center">{{ $data['count']['max'][$key] ?? 0 }}</td>
            @endforeach
        </tr>
    </tfoot>
</table>
