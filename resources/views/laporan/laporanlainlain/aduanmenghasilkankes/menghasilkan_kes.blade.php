@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportYear;
?>
@section('content')
<div class="row">
    <h2>Laporan Aduan Yang Menghasilkan Kes</h2>
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
                    {{ link_to('laporanlainlain/aduan-menghasilkan-kes', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
            <tr><td><center><h3>STATUS SIASATAN ADUAN YANG MENGHASILKAN KES MENGIKUT KATEGORI</h3></center></td></tr>
            <tr><td><center><h3>SALURAN PENERIMAAN</h3></center></td></tr>
            <tr><td><center><h3>TAHUN {{ $year }}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>Sumber Aduan</th>
                        <th>Jumlah Aduan Diterima</th>
                        <th>Aduan Menghasilkan Kes</th>
                        <th>Kes Disiasat Oleh SAS</th>
                        <th>Kes Disiasat Oleh Unit/Negeri/Cawangan</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($dataFinal as $key => $datum)
                    <tr>
                        <td>{{$refCategory[$key]}}</td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.aduanmenghasilkankes1',[$Request->year,$key,1]) }}">{{$datum['diterima']}}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.aduanmenghasilkankes1',[$Request->year,$key,2]) }}">{{$datum['hasil']}}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.aduanmenghasilkankes1',[$Request->year,$key,3]) }}">{{$datum['disiasatsas']}}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.aduanmenghasilkankes1',[$Request->year,$key,4]) }}">{{$datum['disiasatlain']}}</a></td>
                    </tr>
                @endforeach
                @if($Request->cari)
                    <tr style="font-weight:bold">
                        <td>Jumlah</td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.aduanmenghasilkankes1',[$Request->year,'total',1]) }}">{{$dataCounter['diterima']}}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.aduanmenghasilkankes1',[$Request->year,'total',2]) }}">{{$dataCounter['hasil']}}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.aduanmenghasilkankes1',[$Request->year,'total',3]) }}">{{$dataCounter['disiasatsas']}}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.aduanmenghasilkankes1',[$Request->year,'total',4]) }}">{{$dataCounter['disiasatlain']}}</a></td>
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
@endif

@stop

@section('script_datatable')
<script type="text/javascript">
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'STATUS SIASATAN ADUAN YANG MENGHASILKAN KES MENGIKUT KATEGORI<br />SALURAN PENERIMAAN<br />TAHUN ' + <?php echo $Request->year; ?>
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
            name: false,
            colorByPoint: true,
            data: [
                {
                    name: 'Jumlah Aduan Diterima',
                    y: <?php echo $dataCounter['diterima']; ?>
                },
                {
                    name: 'Aduan Menghasilkan Kes',
                    y: <?php echo $dataCounter['hasil']; ?>
                },
                {
                    name: 'Kes Disiasat Oleh SAS',
                    y: <?php echo $dataCounter['disiasatsas']; ?>
                },
                {
                    name: 'Kes Disiasat Oleh Unit/Negeri/Cawangan',
                    y: <?php echo $dataCounter['disiasatlain']; ?>
                }
            ]
        }],
    });
</script>
@stop