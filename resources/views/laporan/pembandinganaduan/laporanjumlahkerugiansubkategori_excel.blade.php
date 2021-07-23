<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table>
    <tr>
        <td colspan="4">LAPORAN JUMLAH KERUGIAN MENGIKUT BULAN & SUBKATEGORI ADUAN</td>
    </tr>
    <tr>
        <td colspan="4">{{ $CA_RCVDT_YEAR }}</td>
    </tr>
</table>
<table>
    <thead>
    <tr>
        <th>Bil.</th>
        <th>Subkategori Aduan</th>
        <th>JAN</th>
        <th>FEB</th>
        <th>MAC</th>
        <th>APR</th>
        <th>MEI</th>
        <th>JUN</th>
        <th>JUL</th>
        <th>OGO</th>
        <th>SEP</th>
        <th>OKT</th>
        <th>NOV</th>
        <th>DIS</th>
    </tr>
    </thead>
    <tbody>
        <?php
            $countmonth1total = 0;
            $countmonth2total = 0;
            $countmonth3total = 0;
            $countmonth4total = 0;
            $countmonth5total = 0;
            $countmonth6total = 0;
            $countmonth7total = 0;
            $countmonth8total = 0;
            $countmonth9total = 0;
            $countmonth10total = 0;
            $countmonth11total = 0;
            $countmonth12total = 0;
        ?>
        @foreach ($dataFinal as $key => $value)
            <tr>
                <td>{{ $bil++ }}</td>
                <td>{{ $subcategoryList[$key] }}</td>
                @foreach ($mRefMonth as $month)
                    <td>{{ number_format($value[$month->code],2) }}</td>
                @endforeach
            </tr>
            <?php
                $countmonth1total += $value['1'];
                $countmonth2total += $value['2'];
                $countmonth3total += $value['3'];
                $countmonth4total += $value['4'];
                $countmonth5total += $value['5'];
                $countmonth6total += $value['6'];
                $countmonth7total += $value['7'];
                $countmonth8total += $value['8'];
                $countmonth9total += $value['9'];
                $countmonth10total += $value['10'];
                $countmonth11total += $value['11'];
                $countmonth12total += $value['12'];
            ?>
        @endforeach
    </tbody>
    <tfooter>
        <tr>
            <td></td>
            <td>Jumlah</td>
            @foreach ($mRefMonth as $month)
                <td>{{ number_format(${'countmonth'.$month->code.'total'},2) }}</td>
            @endforeach
        </tr>
    </tfooter>
</table>
</html>