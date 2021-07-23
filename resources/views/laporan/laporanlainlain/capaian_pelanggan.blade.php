@extends('layouts.main')
<?php

use App\Ref;
use App\Laporan\ReportLainlain;
?>

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Laporan Pencapaian Piagam Pelanggan Bahagian Gerakan Penggunaan</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal','method' => 'GET', 'url'=>'laporanlainlain/capaian_pelanggan']) !!}
                    <div class="col-sm-8">
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::select('year', ReportLainlain::GetByYear(true),$tahun, ['class' => 'form-control input-sm' , 'id' => 'year']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('mMonth', 'Bulan', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                <div class="input-group">
                                    {{ Form::select('Month_from', ReportLainlain::GetMonth('206', ''), $Month_from, ['class' => 'form-control input-sm']) }}
                                    <span class="input-group-addon">hingga</span>
                                    {{ Form::select('Month_to', ReportLainlain::GetMonth('206', ''), $Month_to, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                                {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                                {{ link_to('laporanlainlain/capaian_pelanggan', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                            </div>
                        </div>
                           @if($cari)
                        <div class="col-sm-12">
                        <div class="form-group" align="center">
                            {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                            {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                        </div>
                    </div>
                    @endif
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="state-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                        <h2><center>Laporan Pencapaian Piagam Pelanggan Bahagian Gerakan Kepenggunaan Bagi Bulan 
                                @if(($Month_from) && ($Month_to) && ($Month_from < $Month_to))
                                Dari {{ Ref::GetDescr('206', $Month_from, 'ms') }} 
                                Hingga {{ Ref::GetDescr('206', $Month_to, 'ms') }} 
                                @elseif(($Month_from == $Month_to) && ($Month_from!='')&&($Month_to!=''))
                                {{ Ref::GetDescr('206', $Month_from, 'ms') }}
                                @endif
                                {{ $tahun }}</center></h2>

                        <tr>
                           
                            <th rowspan="3">Standard Piagam Pelanggan</th>
                        </tr>
                        <tr>
                            <th colspan="2">Menetapi Tempoh Masa/Standard Piagam Pelanggan</th>
                            <th colspan="2">Belum Mencapai Standard Piagam Pelanggan </th>
                            <th colspan="2">Melebihi Tempoh Masa</th>
                            <th colspan ="2">Jumlah Perkhidmatan</th>
                        </tr>
                        <tr>
                            <th>Jumlah Menetapi Standard</th>
                            <th> % Menetapi Standard</th>
                            <th>Jumlah Belum capai/Dalam Proses</th>
                            <th> % Belum capai/Dalam Proses</th>
                            <th>Jumlah Melebihi Standard</th>
                            <th>% Melebihi Tempoh Standard</th>
                            <th colspan="2"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counttetapstandard=0;
                            $countblumstandard=0;
                            $countlebihstandard=0;
                            ?>
                            <?php $i = 1; ?>
                            @foreach ($data as $Capaian)
                            <tr>
                            
                                <td align="center"> (a)Akuan penerimaan dan maklumbalas aduan yang diterima kepdada pengadu dalam tempoh tiga (3) hari berkerja bagi aduan yang dikemukan secara bertulis memalui surat atau emel,malalui panggilan telefon atau hadir sendiri
                                    - Ambil daripada jumlah aduan ke BPA yang diterima dlm setahun </td>
                                <td align=center class="td"><br>{{ $Capaian->Counta }}</td>
                                <td align=center class="td"><br>{{ round($Capaian->Counta/$Capaian->Counta*100,2)  }} % </td>
                                <td align=center class="td"><br> - </td>
                                <td align=center class="td"><br> - </td>
                                <td align=center class="td"><br> - </td>
                                <td align=center class="td"><br> - </td>
                            
                                <td align=center class="td"><br>{{ $Capaian->Counta }} </td>
                            </tr>
                            <?php $lebih = $Capaian->Counta - $Capaian->COUNT - $Capaian->Count1; ?>
                            <tr>
                                    
                                <td align="center"> (b) Penyelesaian aduan daripada pengadu dalam tempoh 21 hari berkerja
                                    - Tempoh penyelesaian aduan BPA</td>
                                <td align=center class="td"><br>{{ $Capaian->COUNT }}</td>
                                <td align=center class="td"><br> {{ round($Capaian->COUNT/$Capaian->Counta*100,2)  }}% </td>
                                <td align=center class="td"><br>{{ $Capaian->Count1 }}</td>
                                <td align=center class="td"><br> {{ round($Capaian->Count1/$Capaian->Counta*100,2)  }} %</td>
                                <td align=center class="td"><br> {{ $lebih }} </td>
                                <td align=center class="td"><br>{{ round($lebih/$Capaian->Counta*100,2)  }} % </td>
                                <td align=center class="td"><br> {{ $Capaian->Counta }}</td>
                                
                                
                            </tr>
                            <?php
                            $counttetapstandard +=$Capaian->COUNT;
                            $countblumstandard +=$Capaian->Count1;
                            $countlebihstandard +=$lebih;
                            ?>
                              @endforeach
                        </tbody>
                    </table>
                </div>
                   @if($cari != '')
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
     Highcharts.setOptions({
            lang: {
                numericSymbols: null
            }
        });
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Laporan Pencapaian Piagam Pelanggan Bahagian Gerakan Kepenggunaan '
            },
            subtitle: {
                text: 'Bulan Dari: <?php echo $Month_from ?> Hingga: <?php echo $Month_to ?>'
            },
            xAxis: {
                crosshair: true,
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Pencapaian Piagam Pelanggan'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{series.name}</span><table>',
                pointFormat: '<tr><td style="color:{point.color};padding:0">{point.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            series: [{
                name: 'Jumlah Piagam Pelanggan',
                colorByPoint: true,
                data: [
//                    ['Jumlah Menetapi Standard (A) ', <?php // echo $Capaian->Counta ?>],
                    ['Jumlah Menetapi Standard (B) ', <?php echo $counttetapstandard ?>],
                    ['Jumlah Belum Capai/Dalam Proses', <?php echo $countblumstandard ?>],
                    ['Jumlah Melebihi Standard', <?php echo $countlebihstandard ?>],
                
                
                ]
            }],
            credits: {
                enabled: false
            }
        });
</script>
@stop