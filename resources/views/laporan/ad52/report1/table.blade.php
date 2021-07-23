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
            <th style="text-align:center">AD 51</th>
            <th style="text-align:center">AD 52</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center">Proses Kerja Capai Objektif</td>
            <td style="text-align:center">{{ $dataCount['achieveObjective']['ad51'] }}</td>
            <td style="text-align:center">{{ $dataCount['achieveObjective']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">Proses Kerja Tak Capai Objektif</td>
            <td style="text-align:center">{{ $dataCount['notAchieveObjective']['ad51'] }}</td>
            <td style="text-align:center">{{ $dataCount['notAchieveObjective']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">Jumlah Aduan Diambil Tindakan</td>
            <td style="text-align:center">{{ $dataCount['totalComplaintTakenAction']['ad51'] }}</td>
            <td style="text-align:center">{{ $dataCount['totalComplaintTakenAction']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">Masih Dalam Tindakan</td>
            <td style="text-align:center">0</td>
            <td style="text-align:center">0</td>
        </tr>
        <tr>
            <td style="text-align:center">Jumlah Keseluruhan</td>
            <td style="text-align:center">{{ $dataCount['totalComplaintTakenAction']['ad51'] }}</td>
            <td style="text-align:center">{{ $dataCount['totalComplaintTakenAction']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">Purata</td>
            <td style="text-align:center">{{ $dataCount['average']['ad51'] }}</td>
            <td style="text-align:center">{{ $dataCount['average']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">Kekerapan</td>
            <td style="text-align:center">{{ $dataCount['mode']['ad51'][0] }}</td>
            <td style="text-align:center">{{ $dataCount['mode']['ad52'][0] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">Median</td>
            <td style="text-align:center">{{ $dataCount['median']['ad51'] }}</td>
            <td style="text-align:center">{{ $dataCount['median']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">Minimum</td>
            <td style="text-align:center">{{ $dataCount['min']['ad51'] }}</td>
            <td style="text-align:center">{{ $dataCount['min']['ad52'] }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align:center">Maksimum</td>
            <td style="text-align:center">{{ $dataCount['max']['ad51'] }}</td>
            <td style="text-align:center">{{ $dataCount['max']['ad52'] }}</td>
        </tr>
    </tfoot>
</table>
