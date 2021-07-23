<?php
    $filename = 'Laporan Status Aduan' . date("_Ymd_His").'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table style="width: 100%; font-size: 16px; text-align: center">
    <tr>
        <td colspan="21" style="text-align: center;"><h3>LAPORAN STATUS ADUAN MENGIKUT NEGERI</h3></td>
    </tr>
    <tr>
        <td colspan="21" style="text-align: center;">
            <h3>
            BAGI 
            <!-- {{-- $ds->toDateString() --}}  -->
            {{ date('d-m-Y', strtotime($ds)) }}
            HINGGA 
            <!-- {{-- $de->toDateString() --}} -->
            {{ date('d-m-Y', strtotime($de)) }}
            </h3>
        </td>
    </tr>
    <!-- tr -->
        <!-- td colspan="21" style="text-align: center;" -->
            <!-- h3 -->
                <!-- {{-- $department_list[$dp] --}} -->
                {{-- $br != 0 ? Ref::GetDescr('315',$br,'ms') : 'SEMUA BAHAGIAN' --}}
            <!-- /h3 -->
        <!-- /td -->
    <!-- /tr -->
</table>
<table style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
            <th> Bil.</th>
            <th> Negeri</th>
            <!--<th rowspan="2">Bil.</th>-->
            <!--<th rowspan="2"> Negeri</th>-->
            <!--<th colspan="19" style="text-align: center">Jumlah Aduan</th>-->
        <!--</tr>-->
        <!--<tr>-->
            <th> Diterima</th>
            @if($status['TERIMA'])
                <th> Aduan Baru</th>
                <th> %</th>
            @endif
            @if($status['DALAMSIASATAN'])
                <th> Dalam Siasatan</th>
                <th> %</th>
            @endif
            @if($status['SELESAI'])
                <th> Diselesaikan</th>
                <th> %</th>
            @endif
            @if($status['AGENSILAIN'])
                <th> Agensi KPDNHEP</th>
                <th> %</th>
            @endif
            @if($status['TRIBUNAL'])
                <th> Tribunal</th>
                <th> %</th>
            @endif
            @if($status['PERTANYAAN'])
                <th> Pertanyaan</th>
                <th> %</th>
            @endif
            @if($status['MKLUMATXLENGKAP'])
                <th> Maklumat Tidak Lengkap</th>
                <th> %</th>
            @endif
            @if($status['LUARBIDANG'])
                <th> Luar Bidang Kuasa</th>
                <th> %</th>
            @endif
            @if($status['TUTUP'])
                <th> Ditutup</th>
                <th> %</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach($st as $state)
        <tr>
            <td>{{ $i++ }}</td>
            <td> {{ $state_list[$state] }}</td>
            <td>{{$data_final[$state]['total']}}</td>
            @if($status['TERIMA'])
                <td>{{$data_final[$state]['TERIMA']}}</td>
                <td>{{$data_final[$state]['TERIMA_pct']}}</td>
            @endif
            @if($status['DALAMSIASATAN'])
                <td>{{$data_final[$state]['DALAMSIASATAN']}}</td>
                <td>{{$data_final[$state]['DALAMSIASATAN_pct']}}</td>
            @endif
            @if($status['SELESAI'])
                <td>{{$data_final[$state]['SELESAI']}}</td>
                <td>{{$data_final[$state]['SELESAI_pct']}}</td>
            @endif
            @if($status['AGENSILAIN'])
                <td>{{$data_final[$state]['AGENSILAIN']}}</td>
                <td>{{$data_final[$state]['AGENSILAIN_pct']}}</td>
            @endif
            @if($status['TRIBUNAL'])
                <td>{{$data_final[$state]['TRIBUNAL']}}</td>
                <td>{{$data_final[$state]['TRIBUNAL_pct']}}</td>
            @endif
            @if($status['PERTANYAAN'])
                <td>{{$data_final[$state]['PERTANYAAN']}}</td>
                <td>{{$data_final[$state]['PERTANYAAN_pct']}}</td>
            @endif
            @if($status['MKLUMATXLENGKAP'])
                <td>{{$data_final[$state]['MKLUMATXLENGKAP']}}</td>
                <td>{{$data_final[$state]['MKLUMATXLENGKAP_pct']}}</td>
            @endif
            @if($status['LUARBIDANG'])
                <td>{{$data_final[$state]['LUARBIDANG']}}</td>
                <td>{{$data_final[$state]['LUARBIDANG_pct']}}</td>
            @endif
            @if($status['TUTUP'])
                <td>{{$data_final[$state]['TUTUP']}}</td>
                <td>{{$data_final[$state]['TUTUP_pct']}}</td>
            @endif
        </tr>
        @if($br == 1)
            @foreach($data_final[$state]['branch'] as $key => $branch)
                <tr>
                    <td></td>
                    <td>{{ $branch_list[$key] }}</td>
                    <td>{{$branch['total']}}</td>
                    @if($status['TERIMA'])
                        <td>{{$branch['TERIMA']}}</td>
                        <td>{{$branch['TERIMA_pct']}}</td>
                    @endif
                    @if($status['DALAMSIASATAN'])
                        <td>{{$branch['DALAMSIASATAN']}}</td>
                        <td>{{$branch['DALAMSIASATAN_pct']}}</td>
                    @endif
                    @if($status['SELESAI'])
                        <td>{{$branch['SELESAI']}}</td>
                        <td>{{$branch['SELESAI_pct']}}</td>
                    @endif
                    @if($status['AGENSILAIN'])
                        <td>{{$branch['AGENSILAIN']}}</td>
                        <td>{{$branch['AGENSILAIN_pct']}}</td>
                    @endif
                    @if($status['TRIBUNAL'])
                        <td>{{$branch['TRIBUNAL']}}</td>
                        <td>{{$branch['TRIBUNAL_pct']}}</td>
                    @endif
                    @if($status['PERTANYAAN'])
                        <td>{{$branch['PERTANYAAN']}}</td>
                        <td>{{$branch['PERTANYAAN_pct']}}</td>
                    @endif
                    @if($status['MKLUMATXLENGKAP'])
                        <td>{{$branch['MKLUMATXLENGKAP']}}</td>
                        <td>{{$branch['MKLUMATXLENGKAP_pct']}}</td>
                    @endif
                    @if($status['LUARBIDANG'])
                        <td>{{$branch['LUARBIDANG']}}</td>
                        <td>{{$branch['LUARBIDANG_pct']}}</td>
                    @endif
                    @if($status['TUTUP'])
                        <td>{{$branch['TUTUP']}}</td>
                        <td>{{$branch['TUTUP_pct']}}</td>
                    @endif
                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Jumlah</th>
            <th>{{ $data_final['total']['total'] }}</th>
            @if($status['TERIMA'])
                <th>{{ $data_final['total']['TERIMA'] }}</th>
                <th>100%</th>
            @endif
            @if($status['DALAMSIASATAN'])
                <th>{{ $data_final['total']['DALAMSIASATAN'] }}</th>
                <th>100%</th>
            @endif
            @if($status['SELESAI'])
                <th>{{ $data_final['total']['SELESAI'] }}</th>
                <th>100%</th>
            @endif
            @if($status['AGENSILAIN'])
                <th>{{ $data_final['total']['AGENSILAIN'] }}</th>
                <th>100%</th>
            @endif
            @if($status['TRIBUNAL'])
                <th>{{ $data_final['total']['TRIBUNAL'] }}</th>
                <th>100%</th>
            @endif
            @if($status['PERTANYAAN'])
                <th>{{ $data_final['total']['PERTANYAAN'] }}</th>
                <th>100%</th>
            @endif
            @if($status['MKLUMATXLENGKAP'])
                <th>{{ $data_final['total']['MKLUMATXLENGKAP'] }}</th>
                <th>100%</th>
            @endif
            @if($status['LUARBIDANG'])
                <th>{{ $data_final['total']['LUARBIDANG'] }}</th>
                <th>100%</th>
            @endif
            @if($status['TUTUP'])
                <th>{{ $data_final['total']['TUTUP'] }}</th>
                <th>100%</th>
            @endif
        </tr>
    </tfoot>
</table>
</html>
<?php 
    exit;
    fclose($fp);
?>