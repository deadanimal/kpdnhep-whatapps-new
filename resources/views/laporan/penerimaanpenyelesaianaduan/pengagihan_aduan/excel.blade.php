<?php
    $filename = 'Laporan_Tempoh_Pindah_Aduan_'.date('YmdHis').'.xls';
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
        <td colspan="7" class="text-center">LAPORAN PINDAH ADUAN MENGIKUT NEGERI BAGI TAHUN {{ $SelectYear }}</td>
    </tr>
    <!-- <tr> -->
        <!-- <td colspan="7">TAHUN {{-- $SelectYear --}}</td> -->
    <!-- </tr> -->
    <tr>
        <td colspan="7" class="text-center">DARI {{ $monthFromDesc }} HINGGA {{$monthToDesc}}</td>
    </tr>
    <tr>
        <td colspan="7" class="text-center">{{ $departmentDesc }}</td>
    </tr>
</table>
<table border="1">
    <thead>
    <tr>
        <th>Bil.</th>
        <th>Negeri</th>
        <th>Jumlah Aduan</th>
        <th> < 1 hari</th>
        <th> 1 hari</th>
        <th> 2-3 hari</th>
        <th> > 3 hari</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($BR_STATECD as $state)
        <tr>
            <td class="text-center">{{ $i++ }}</td>
            <td>{{$stateList[$state]}}</td>
            <td class="text-center">{{ $dataCount[$state]['total'] }}</td>
            <td class="text-center">{{ $dataCount[$state]['<1'] }}</td>
            <td class="text-center">{{ $dataCount[$state]['1'] }}</td>
            <td class="text-center">{{ $dataCount[$state]['2-3'] }}</td>
            <td class="text-center">{{ $dataCount[$state]['>3'] }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th></th>
        <th><strong>JUMLAH</strong></th>
        <th>{{ $dataCount['total']['total'] }}</th>
        <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['<1'] }}</a></th>
        <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['1'] }}</a></th>
        <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['2-3'] }}</a></th>
        <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['>3'] }}</a></th>
    </tr>
    </tfoot>
</table>
</html>

<?php 
    exit;
    fclose($fp);
?>