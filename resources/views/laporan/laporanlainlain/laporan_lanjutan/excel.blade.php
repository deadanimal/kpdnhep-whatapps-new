<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
@foreach ($reportFinal as $rf_cawangan => $rf_kategori_data)
    @foreach($rf_kategori_data as $rf_kategori => $rf_data)
        <table>
            <tr>
                <td colspan="11">Cawangan: {{ $branchList[$rf_cawangan] }}</td>
            </tr>
            <tr>
                <td colspan="11">Kategori Aduan: {{ $departmentList[$rf_kategori] }}</td>
            </tr>
        </table>
        <table>
            <thead>
            <tr>
                <th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Diterima</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">No. Aduan</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Sumber</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Peg. Penyiasat</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Nama Pengadu</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Alamat Pengadu</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Nama Diadu</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Aduan</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Hasil Siatasan</th>
                <th style="border: 1px solid #000; background: #f3f3f3;">Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rf_data as $key => $datum)
                <tr>
                    <td style="vertical-align: top;">{{$key+1}}</td>
                    <td style="vertical-align: top;">{{$datum->ca_rcvdt}}</td>
                    <td style="vertical-align: top;">{{$datum->ca_caseid}}</td>
                    <td style="vertical-align: top;">{{trim($datum->ca_rcvtyp) != '' ? $sourceList[$datum->ca_rcvtyp] : '-'}}</td>
                    <td style="vertical-align: top;">{{$datum->name}}</td>
                    <td style="vertical-align: top;">{{$datum->ca_name}}</td>
                    <td style="vertical-align: top;">{{$datum->ca_addr}} {{$datum->ca_poscd}} {{$datum->ca_distcd}} {{$datum->ca_statecd}}</td>
                    <td style="vertical-align: top;">{{$datum->ca_againstnm}}</td>
                    <td style="vertical-align: top;">{{$datum->ca_result}}</td>
                    <td style="vertical-align: top;">{{$datum->ca_result}}</td>
                    <td style="vertical-align: top;">{{$datum->ca_casests}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
@endforeach
</html>

