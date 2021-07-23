<?php
    $filename = 'Laporan_Mengikut_Status_Dan_Kategori_Aduan_'.date('YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<html>
<style>
    .text-center {
        text-align : center;
    }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table>
    <tr>
        <td colspan="4">LAPORAN MENGIKUT STATUS DAN KATEGORI ADUAN</td>
    </tr>
    <tr>
        <td colspan="4">
            BAGI TARIKH TERIMA : {{ date('d-m-Y', strtotime($dateStart)) }}
            SEHINGGA {{ date('d-m-Y', strtotime($dateEnd)) }}
        </td>
    </tr>
    <!--<tr>-->
        <!--<td colspan="4">-->
        <!-- </td> -->
    <!-- </tr> -->
    <tr>
        <td colspan="4">NEGERI : {{ $stateDesc }}</td>
    </tr>
    <tr>
        <td colspan="4">BAHAGIAN : {{ $departmentDesc }}</td>
    </tr>
</table>
<table border="1">
    <thead>
    <tr>
        <th>Bil.</th>
        <th>Kategori Aduan</th>
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
            <td class="text-center">{{$i++}}</td>
            <td>{{$subdepartmentList[$key]}}</td>
            <td class="text-center">{{$datum['total']}}</td>
            <td class="text-center">{{$datum['belum agih']}}</td>
            <td class="text-center">{{$datum['dalam siasatan']}}</td>
            <td class="text-center">{{$datum['maklumat tak lengkap']}}</td>
            <td class="text-center">{{$datum['selesai']}}</td>
            <td class="text-center">{{$datum['tutup']}}</td>
            <td class="text-center">{{$datum['agensi lain']}}</td>
            <td class="text-center">{{$datum['tribunal']}}</td>
            <td class="text-center">{{$datum['pertanyaan']}}</td>
            <td class="text-center">{{$datum['luar bidang']}}</td>
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