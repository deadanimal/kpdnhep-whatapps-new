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
                    {{ link_to('sas-report/menghasilkan-kes', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
            <tr><td><center><h3>TAHUN {{ $Request->year }}</h3></center></td></tr>
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
                    <?php 
                        $total1 = 0;
                        $total2 = 0;
                    ?>
                    @if($Request->cari)
                    <?php 
                        $total1 = 0;
                        $total2 = 0;
                    ?>
                        @foreach ($result as $key => $value)
                        <tr>
                            <td>{{ $key }}</td>
                            <?php 
                            $total3 = 0; 
                            $total4 = 0; 
                            $i = 1;
                            ?>
                            @foreach($value as $key => $val)
                            <td><a target="_blank" href="{{ route('sasreport.menghasilkankes1',[$Request->year,$val->code,$i]) }}">{{ $val->bilangankes }}</a></td>
                                <?php 
                                if($key == 0)
                                $total3 += $val->bilangankes;
                                elseif($key == 1)
                                $total4 += $val->bilangankes;
                                $i++;
                                ?>
                            @endforeach
                            <?php
                            $i = 1;
                            $total1 += $total3;
                            $total2 += $total4;
                            ?>
                            <td><a target="_blank" href="{{ route('sasreport.menghasilkankes1',[$Request->year,$val->code,3]) }}">0</a></td>
                            <td><a target="_blank" href="{{ route('sasreport.menghasilkankes1',[$Request->year,$val->code,4]) }}">0</a></td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>Jumlah</td>
                            <td><a target="_blank" href="{{ route('sasreport.menghasilkankes1',[$Request->year,0,1]) }}">{{ $total1 }}</a></td>
                            <td><a target="_blank" href="{{ route('sasreport.menghasilkankes1',[$Request->year,0,2]) }}">{{ $total2 }}</a></td>
                            <td><a target="_blank" href="{{ route('sasreport.menghasilkankes1',[$Request->year,0,3]) }}">{{ 0 }}</a></td>
                            <td><a target="_blank" href="{{ route('sasreport.menghasilkankes1',[$Request->year,0,4]) }}">{{ 0 }}</a></td>
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
                    y: <?php echo $total1; ?>
                },
                {
                    name: 'Aduan Menghasilkan Kes',
                    y: <?php echo $total2; ?>
                },
                {
                    name: 'Kes Disiasat Oleh SAS',
                    y: 0
                },
                {
                    name: 'Kes Disiasat Oleh Unit/Negeri/Cawangan',
                    y: 0
                }
            ]
        }],
    });
</script>
@stop