<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table style="width: 100%; font-size: 16px; text-align: center">
    <tr>
        <td colspan="12"><h2>LAPORAN AKTA ADUAN MENGIKUT NEGERI BAGI {{ $ds->format('d-m-Y') }}
                HINGGA {{ $de->format('d-m-Y') }} <br/>
                {{ $dp_desc }}</h2></td>
    </tr>
</table>
<?php
$i = 1;
?>
<table id="state-table"
       class="table table-bordered"
       style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
    <tr>
        <th>Bil.</th>
        <th>Negeri</th>
        <th>Akta Kawalan Bekalan</th>
        <th>Akta Perihal Dagangan</th>
        <th>Akta Kawalan Harga & Pencatutan</th>
        <th>Akta Perlindungan Pengguna</th>
        <th>Akta Cakera Optik</th>
        <th>Akta Timbang dan Sukat</th>
        <th>Akta Jualan Langsung</th>
        <th>Akta Sewa Beli</th>
        <th>Akta Hakcipta</th>
        <th>Jumlah</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($st as $state)
        <tr>
            <td>{{ $i++ }}</td>
            <td><b>{{ $state_list[$state] }}</b></td>
            <td>{{ $data_final[$state]['AKB'] }}</td>
            <td>{{ $data_final[$state]['APD'] }}</td>
            <td>{{ $data_final[$state]['AKH'] }}</td>
            <td>{{ $data_final[$state]['APP'] }}</td>
            <td>{{ $data_final[$state]['ACO'] }}</td>
            <td>{{ $data_final[$state]['ATS'] }}</td>
            <td>{{ $data_final[$state]['AJL'] }}</td>
            <td>{{ $data_final[$state]['ASB'] }}</td>
            <td>{{ $data_final[$state]['AHC'] }}</td>
            <td>{{ $data_final[$state]['total'] }}</td>
        </tr>
        @if($is_branch == 1)
            @foreach($data_final[$state]['branch'] as $key => $stateBranch)
                <tr>
                    <td></td>
                    <td>{{ $branch_list[$key] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['AKB'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['APD'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['AKH'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['APP'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['ACO'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['ATS'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['AJL'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['ASB'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['AHC'] }}</td>
                    <td>{{ $data_final[$state]['branch'][$key]['total'] }}</td>
                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th></th>
        <th>Jumlah</th>
        <th>{{ $data_final['total']['AKB'] }}</th>
        <th>{{ $data_final['total']['APD'] }}</th>
        <th>{{ $data_final['total']['AKH'] }}</th>
        <th>{{ $data_final['total']['APP'] }}</th>
        <th>{{ $data_final['total']['ACO'] }}</th>
        <th>{{ $data_final['total']['ATS'] }}</th>
        <th>{{ $data_final['total']['AJL'] }}</th>
        <th>{{ $data_final['total']['ASB'] }}</th>
        <th>{{ $data_final['total']['AHC'] }}</th>
        <th>{{ $data_final['total']['total'] }}</th>
    </tr>
    </tfoot>
</table>