@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportYear;
?>
@section('content')

<div class="row">
    <h2>Laporan Cara Penerimaan SAS</h2>
    {!! Form::open(['method' =>'GET', 'class' => 'form-horizontal']) !!}
    <div class="ibox-content" style="padding-bottom: 0px">
        <div class="form-group" style="padding-bottom: 0px">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group">
                    {{ Form::label('year', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('year', ReportYear::GetByYear(false), date('Y'), ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                    {{ link_to('sas-report/cara-penerimaan', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    @if($Request->cari)
                    {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                    {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
            <!--<tr><td><center><h3>{{-- $titledepart --}}</h3></center></td></tr>-->
            <!--<tr><td><center><h3>{{-- $titlestate --}}</h3></center></td></tr>-->
        </table>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" style="width: 100%">
            <thead>
                <tr>
                    <th>Bil.</th>
                    <th>Cara Penerimaan</th>
                    <th> Jan</th>
                    <th> Feb </th>
                    <th> Mac </th>
                    <th> Apr </th>
                    <th> Mei </th>
                    <th> Jun </th>
                    <th> Jul </th>
                    <th> Ogo </th>
                    <th> Sep </th>
                    <th> Okt </th>
                    <th> Nov </th>
                    <th> Dis </th>
                    <th> Jumlah </th>
                </tr>
            </thead>

            <tbody>
               @if($Request->cari)
               <?php
                $tJan=0;
                $tFeb=0;
                $tMac=0;
                $tApr=0;
                $tMei=0;
                $tJun=0;
                $tJul=0;
                $tOgo=0;
                $tSep=0;
                $tOkt=0;
                $tNov=0;
                $tDis=0;
                $ttotal=0;
               ?>
                <?php $i = 1; ?>
                @foreach ($query as $mSA)
                <tr>
                    <td> {{ $i++ }} </td>
                    <td> {{ $mSA->descr}}</td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,1]) }}">{{ $mSA->JAN}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,2]) }}">{{ $mSA->FEB}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,3]) }}">{{ $mSA->MAC}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,4]) }}">{{ $mSA->APR}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,5]) }}">{{ $mSA->MEI}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,6]) }}">{{ $mSA->JUN}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,7]) }}">{{ $mSA->JUL}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,8]) }}">{{ $mSA->OGO}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,9]) }}">{{ $mSA->SEP}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,10]) }}">{{ $mSA->OKT}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,11]) }}">{{ $mSA->NOV}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,12]) }}">{{ $mSA->DIS}}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,$mSA->code,0]) }}">{{ $mSA->Bilangan}}</a></td>
                </tr>
                <?php
                $tJan += $mSA->JAN;
                $tFeb += $mSA->FEB;
                $tMac += $mSA->MAC;
                $tApr += $mSA->APR;
                $tMei += $mSA->MEI;
                $tJun += $mSA->JUN;
                $tJul += $mSA->JUL;
                $tOgo += $mSA->OGO;
                $tSep += $mSA->SEP;
                $tOkt += $mSA->OKT;
                $tNov += $mSA->NOV;
                $tDis += $mSA->DIS;
                $ttotal += $mSA->Bilangan
                ?>
                @endforeach

                <tr>
                    <td colspan="2"><center>Jumlah</center></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,1]) }}">{{ $tJan }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,2]) }}">{{ $tFeb }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,3]) }}">{{ $tMac }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,4]) }}">{{ $tApr }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,5]) }}">{{ $tMei }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,6]) }}">{{ $tJun }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,7]) }}">{{ $tJul }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,8]) }}">{{ $tOgo }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,9]) }}">{{ $tSep }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,10]) }}">{{ $tOkt }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,11]) }}">{{ $tNov }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,12]) }}">{{ $tDis }}</a></td>
                    <td><a target="_blank" href="{{ route('sasreport.carapenerimaan1',[$Request->year,0,0]) }}">{{$ttotal}}</a></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    </div>
</div>

@if($Request->cari)
<br />
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
<br />
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
@endif

@stop

@section('script_datatable')
   <script type="text/javascript">
        Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'JUMLAH KESELURUHAN ADUAN MENGIKUT BULAN YANG DITERIMA<br />TAHUN ' + <?php echo $Request->year; ?>
        },
        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mac',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Ogo',
                'Sep',
                'Okt',
                'Nov',
                'Dis',
                'Jumlah'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah Aduan'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: <?php echo $datachart1; ?>
    });
    
    Highcharts.chart('container1', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'JUMLAH KESELURUHAN ADUAN MENGIKUT CARA PENERIMAAN SAS<br />TAHUN ' + <?php echo $Request->year; ?>
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Jumlah Aduan'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
        },

        series: [{
            name: 'Cara Penerimaan',
            colorByPoint: true,
            data: <?php echo $datachart2; ?>
        }],
    });
        
    </script>
@stop