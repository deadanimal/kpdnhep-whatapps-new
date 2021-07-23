<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table>
    <tr>
        <td colspan="4">LAPORAN MENGIKUT STATUS DAN KATEGORI ADUAN BAGI</td>
    </tr>
    <tr>
        <td colspan="4">TAHUN {{ date('d-m-Y', strtotime($dateStart)) }}</td>
    </tr>
    <tr>
        <td colspan="4">SEHINGGA {{ date('d-m-Y', strtotime($dateEnd)) }}</td>
    </tr>
    <tr>
        <td colspan="4">{{ $departmentDesc }} dan</td>
    </tr>
    <tr>
        <td colspan="4">Negeri {{ $stateDesc }}</td>
    </tr>
</table>
<table>
    <thead>
    <tr>
        <th>Bil.</th>
        <th>Kategori Aduan</th>
        <th>Diterima</th>
        <th>Belum Diagih</th>
        <th>Dalam Siasatan</th>
        <th>Diselesaikan</th>
        <th>Ditutup</th>
        <th>Agensi Lain</th>
        <th>Tribunal</th>
        <th>Pertanyaan</th>
        <th>Maklumat Tidak Lengkap</th>
        <th>Luar Bidang Kuasa</th>
        <th>Belum Selesai</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    @foreach($dataFinal as $key => $datum)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$subdepartmentList[$key]}}</td>
            <td>{{$datum['selesai']}}</td>
            <td>{{$datum['belum agih']}}</td>
            <td>{{$datum['dalam siasatan']}}</td>
            <td>{{$datum['selesai']}}</td>
            <td>{{$datum['tutup']}}</td>
            <td>{{$datum['agensi lain']}}</td>
            <td>{{$datum['tribunal']}}</td>
            <td>{{$datum['pertanyaan']}}</td>
            <td>{{$datum['maklumat tak lengkap']}}</td>
            <td>{{$datum['luar bidang']}}</td>
            <td>{{$datum['total']}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfooter>
        <tr>
            <th></th>
            <th>Jumlah</th>
            <th>{{$dataCounter['selesai']}}</th>
            <th>{{$dataCounter['belum agih']}}</th>
            <th>{{$dataCounter['dalam siasatan']}}</th>
            <th>{{$dataCounter['selesai']}}</th>
            <th>{{$dataCounter['tutup']}}</th>
            <th>{{$dataCounter['agensi lain']}}</th>
            <th>{{$dataCounter['tribunal']}}</th>
            <th>{{$dataCounter['pertanyaan']}}</th>
            <th>{{$dataCounter['maklumat tak lengkap']}}</th>
            <th>{{$dataCounter['luar bidang']}}</th>
            <th>{{$dataCounter['total']}}</th>
        </tr>
    </tfooter>
</table>
</html>

