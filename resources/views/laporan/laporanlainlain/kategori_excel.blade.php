@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportLainlain;
?>

@section('content')
<?php
$filename = 'Raw-Data.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
?>
<style>
    th, td {
        text-align: center;
        font-size: 12px;
    }
    </style>
<table style="width: 100%;">
    <tr><td colspan="20"><center><h3>LAPORAN ADUAN MENGIKUT KATEGORI</h3></center></td></tr>
    <tr><td colspan="20"><center><h3>DARI {{ $request->get('CA_RCVDT_dri') }} HINGGA {{ $request->get('CA_RCVDT_lst') }}</h3></center></td></tr>
</table>
              
                 <table id="state-table" class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
                        <thead>
                        <tr>
                            <th >Bil.</th>
                            <th>Kategori</th>
                            @foreach ($mState as $state)
                            <th >{{ $state->descr }}</th>
                            @endforeach

                            <th > Jumlah </th>
                            <th > % </th>
                        </tr>
                        </thead>
                        <tbody>
                              @if($cari)
                            <?php
                            $Total01 = 0;
                            $Total02 = 0;
                            $Total03 = 0;
                            $Total04 = 0;
                            $Total05 = 0;
                            $Total06 = 0;
                            $Total07 = 0;
                            $Total08 = 0;
                            $Total09 = 0;
                            $Total10 = 0;
                            $Total11 = 0;
                            $Total12 = 0;
                            $Total13 = 0;
                            $Total14 = 0;
                            $Total15 = 0;
                            $Total16 = 0;
                            $Gtotal = 0;
                            $percentge =0;
                            $countpercntge=0;
                            
                           ?>
                            <?php $i = 1;?>
                            @foreach ($query as $data)
                           
                            <?php
                           
                            $Gtotal += $data->Bilangan
                            ?>
                        
                            @endforeach
                                  @foreach ($query as $data)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data->descr }}</td>
                                <td>{{ $data->countstate1 }}</td>
                                <td>{{ $data->countstate2 }}</td>
                                <td>{{ $data->countstate3 }}</td>
                                <td>{{ $data->countstate4 }}</td>
                                <td>{{ $data->countstate5 }}</td>
                                <td>{{ $data->countstate6 }}</td>
                                <td>{{ $data->countstate7 }}</td>
                                <td>{{ $data->countstate8 }}</td>
                                <td>{{ $data->countstate9 }}</td>
                                <td>{{ $data->countstate10 }}</td>
                                <td>{{ $data->countstate11 }}</td>
                                <td>{{ $data->countstate12 }}</td>
                                <td>{{ $data->countstate13 }}</td>
                                <td>{{ $data->countstate14 }}</td>
                                <td>{{ $data->countstate15 }}</td>
                                <td>{{ $data->countstate16 }}</td>
                                <td>{{ $data->Bilangan }}</td>
                                <td>@if($Gtotal != 0)
                                                    {{ $percentge=round((($data->Bilangan*100)/$Gtotal), 2) }}
                                                @else
                                                    0
                                                @endif</td>
                            </tr>
                            <?php
                            $Total01 += $data->countstate1;
                            $Total02 += $data->countstate2;
                            $Total03 += $data->countstate3;
                            $Total04 += $data->countstate4;
                            $Total05 += $data->countstate5;
                            $Total06 += $data->countstate6;
                            $Total07 += $data->countstate7;
                            $Total08 += $data->countstate8;
                            $Total09 += $data->countstate9;
                            $Total10 += $data->countstate10;
                            $Total11 += $data->countstate11;
                            $Total12 += $data->countstate12;
                            $Total13 += $data->countstate13;
                            $Total14 += $data->countstate14;
                            $Total15 += $data->countstate15;
                            $Total16 += $data->countstate16;
                            
                            ?>
                           <?php $countpercntge +=$percentge  ?>
                            @endforeach
                            <tr>
                                <td></td>
                                <td>Jumlah</td>
                                <td>{{ $Total01 }}</td>
                                <td>{{ $Total02 }}</td>
                                <td>{{ $Total03 }}</td>
                                <td>{{ $Total04 }}</td>
                                <td>{{ $Total05 }}</td>
                                <td>{{ $Total06 }}</td>
                                <td>{{ $Total07 }}</td>
                                <td>{{ $Total08 }}</td>
                                <td>{{ $Total09 }}</td>
                                <td>{{ $Total10 }}</td>
                                <td>{{ $Total11 }}</td>
                                <td>{{ $Total12 }}</td>
                                <td>{{ $Total13 }}</td>
                                <td>{{ $Total14 }}</td>
                                <td>{{ $Total15 }}</td>
                                <td>{{ $Total16 }}</td>
                                <td>{{ $Gtotal }}</td>
                                <td>{{ round($countpercntge) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
<?php 
exit;
fclose($fp);
?>
@stop

