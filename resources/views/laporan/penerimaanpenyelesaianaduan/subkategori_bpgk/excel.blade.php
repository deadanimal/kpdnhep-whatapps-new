<?php
    $filename = 'Laporan Mengikut Status Dan Subkategori Aduan '.date('YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table>
    <tr>
        <td colspan="4">LAPORAN MENGIKUT STATUS DAN SUBKATEGORI ADUAN BAGI</td>
    </tr>
    <tr>
        <td colspan="4">TARIKH PENERIMAAN ADUAN : {{ date('d-m-Y', strtotime($dateStart)) }}</td>
    </tr>
    <tr>
        <td colspan="4">SEHINGGA {{ date('d-m-Y', strtotime($dateEnd)) }}</td>
    </tr>
    <tr>
        <td colspan="4">KATEGORI ADUAN : {{ $kategoriDesc != '' ? $kategoriDesc : 'SEMUA' }}</td>
    </tr>
    <tr>
        <td colspan="4">NEGERI : {{ $stateDesc != '' ? $stateDesc : 'SEMUA' }}</td>
    </tr>
</table>
<table border="1">
    <thead>
    <tr>
        <th>Bil.</th>
        <th>Subkategori Aduan</th>
        <th>Diterima</th>
        <th>Aduan Baru</th>
        <th>Dalam Siasatan</th>
        <th>Maklumat Tidak Lengkap</th>
        <th>Diselesaikan</th>
        <th>Ditutup</th>
        <th>Agensi KPDNHEP</th>
        <th>Tribunal</th>
        <th>Pertanyaan</th>
        <th>Luar Bidang Kuasa</th>
        <!--<th>Belum Selesai</th>-->
    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    @foreach($dataFinal as $key => $datum)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$subdepartmentList[$key]}}</td>
            <td>{{$datum['total']}}</td>
            <td>{{$datum['belum agih']}}</td>
            <td>{{$datum['dalam siasatan']}}</td>
            <td>{{$datum['maklumat tak lengkap']}}</td>
            <td>{{$datum['selesai']}}</td>
            <td>{{$datum['tutup']}}</td>
            <td>{{$datum['agensi lain']}}</td>
            <td>{{$datum['tribunal']}}</td>
            <td>{{$datum['pertanyaan']}}</td>
            <td>{{$datum['luar bidang']}}</td>
            <!--<td>{{-- $datum['dalam siasatan'] ---}}</td>-->
            <!--<td>{{-- $datum['belum selesai'] --}}</td>-->
        </tr>
    @endforeach
    </tbody>
    <tfooter>
        <tr>
            <th></th>
            <th>Jumlah</th>
            <th>{{$dataCounter['total']}}</th>
            <!--<th>{{-- $dataCounter['selesai'] --}}</th>-->
            <th>{{$dataCounter['belum agih']}}</th>
            <th>{{$dataCounter['dalam siasatan']}}</th>
            <th>{{$dataCounter['maklumat tak lengkap']}}</th>
            <th>{{$dataCounter['selesai']}}</th>
            <th>{{$dataCounter['tutup']}}</th>
            <th>{{$dataCounter['agensi lain']}}</th>
            <th>{{$dataCounter['tribunal']}}</th>
            <th>{{$dataCounter['pertanyaan']}}</th>
            <th>{{$dataCounter['luar bidang']}}</th>
            <!--<th>{{-- $dataCounter['total'] --}}</th>-->
            <!--<th>{{-- $dataCounter['belum selesai'] --}}</th>-->
        </tr>
    </tfooter>
</table>
</html>
<?php 
    exit;
    fclose($fp);
?>
