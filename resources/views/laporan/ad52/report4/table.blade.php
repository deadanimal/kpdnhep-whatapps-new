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
            <th colspan="2" style="text-align:center">Kod Alasan AD 51</th>
            <th colspan="2" style="text-align:center">Kod Alasan AD 52</th>
        </tr>
        <tr>
            <th style="text-align:center">Kod</th>
            <th style="text-align:center">Kekerapan</th>
            <th style="text-align:center">Kod</th>
            <th style="text-align:center">Kekerapan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center">{{ $caseReasonTemplatesAd51['P1'] }}</td>
            <td style="text-align:center">{{ $dataCount['P1']['ad51'] }}</td>
            <td style="text-align:center">{{ $caseReasonTemplatesAd52['S1'] }}</td>
            <td style="text-align:center">{{ $dataCount['S1']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">{{ $caseReasonTemplatesAd51['P2'] }}</td>
            <td style="text-align:center">{{ $dataCount['P2']['ad51'] }}</td>
            <td style="text-align:center">{{ $caseReasonTemplatesAd52['S2'] }}</td>
            <td style="text-align:center">{{ $dataCount['S2']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">{{ $caseReasonTemplatesAd51['P3'] }}</td>
            <td style="text-align:center">{{ $dataCount['P3']['ad51'] }}</td>
            <td style="text-align:center">{{ $caseReasonTemplatesAd52['S3'] }}</td>
            <td style="text-align:center">{{ $dataCount['S3']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center">{{ $caseReasonTemplatesAd51['P4'] }}</td>
            <td style="text-align:center">{{ $dataCount['P4']['ad51'] }}</td>
            <td style="text-align:center">{{ $caseReasonTemplatesAd52['S4'] }}</td>
            <td style="text-align:center">{{ $dataCount['S4']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center"></td>
            <td style="text-align:center"></td>
            <td style="text-align:center">{{ $caseReasonTemplatesAd52['S5'] }}</td>
            <td style="text-align:center">{{ $dataCount['S5']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center"></td>
            <td style="text-align:center"></td>
            <td style="text-align:center">{{ $caseReasonTemplatesAd52['S6'] }}</td>
            <td style="text-align:center">{{ $dataCount['S6']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center"></td>
            <td style="text-align:center"></td>
            <td style="text-align:center">{{ $caseReasonTemplatesAd52['S7'] }}</td>
            <td style="text-align:center">{{ $dataCount['S7']['ad52'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center"></td>
            <td style="text-align:center"></td>
            <td style="text-align:center">{{ $caseReasonTemplatesAd52['S8'] }}</td>
            <td style="text-align:center">{{ $dataCount['S8']['ad52'] }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th style="text-align:center">Jumlah</th>
            <th style="text-align:center">{{ $dataCount['total']['ad51'] }}</th>
            <th style="text-align:center">Jumlah</th>
            <th style="text-align:center">{{ $dataCount['total']['ad52'] }}</th>
        </tr>
    </tfoot>
</table>
