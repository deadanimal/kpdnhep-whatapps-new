@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportYear;
?>

@section('content')
<style>
    .table-header-rotated th.row-header{
        width: auto;
    }

    .table-header-rotated td{
        width: 40px;
        border-top: 1px solid #dddddd;
        border-left: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        border-bottom: 1px solid #dddddd;
        vertical-align: middle;
        text-align: center;
    }

    .table-header-rotated th.rotate-45{
        height: 80px;
        width: 40px;
        min-width: 40px;
        max-width: 40px;
        position: relative;
        vertical-align: bottom;
        padding: 0;
        font-size: 12px;
        line-height: 0.8;
    }

    .table-header-rotated th.rotate-45 > div{
        position: relative;
        top: 0px;
        left: 40px; /* 80 * tan(45) / 2 = 40 where 80 is the height on the cell and 45 is the transform angle*/
        height: 100%;
        -ms-transform:skew(-45deg,0deg);
        -moz-transform:skew(-45deg,0deg);
        -webkit-transform:skew(-45deg,0deg);
        -o-transform:skew(-45deg,0deg);
        transform:skew(-45deg,0deg);
        overflow: hidden;
        border-left: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        border-top: 1px solid #dddddd;
        border-bottom: 1px solid #dddddd;
    }

    .table-header-rotated th.rotate-45 span {
        -ms-transform:skew(45deg,0deg) rotate(315deg);
        -moz-transform:skew(45deg,0deg) rotate(315deg);
        -webkit-transform:skew(45deg,0deg) rotate(315deg);
        -o-transform:skew(45deg,0deg) rotate(315deg);
        transform:skew(45deg,0deg) rotate(315deg);
        position: absolute;
        bottom: 35px; /* 40 cos(45) = 28 with an additional 2px margin*/
        left: -25px; /*Because it looked good, but there is probably a mathematical link here as well*/
        display: inline-block;
        // width: 100%;
        width:85px; /* 80 / cos(45) - 40 cos (45) = 85 where 80 is the height of the cell, 40 the width of the cell and 45 the transform angle*/
        text-align: left;
        // white-space: nowrap; /*whether to display in one line or not*/
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>LAPORAN CARA PENERIMAAN MENGIKUT NEGERI</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['id' => 'search-form','method' =>'GET','url'=>'sumberaduan/sumberaduannegeri', 'class' => 'form-horizontal']) !!}
                    <div class="col-sm-offset-3 col-sm-6">
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-10">
                               {{ Form::select('year', ReportYear::GetByYear(true), date('Y'), ['class' => 'form-control input-sm' , 'id' => 'year']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-10">
                                {{ Form::select('CA_DEPTCD', ReportYear::GetRef('315', 'semua'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                         <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                            {{ link_to('sumberaduan/sumberaduannegeri', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
                
                <div class="table-responsive">
                    <table class="table table-striped table-header-rotated" style="width: 90%">
                        <thead>
                            <tr>
                                <th style="width: 3%">Bil.</th>
                                <th style="width: 20%">Cara Penerimaan</th>
                                @foreach ($SenaraiNegeri as $Negeri)
                                <th class="rotate-45"><div><span>{{ $Negeri->descr }}</span></div></th>
                                @endforeach
                                <th class="rotate-45"><div><span>Jumlah</span></div></th>
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
                           ?>
                            <?php $i = 1;?>
                            @foreach ($query as $data)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data->descr }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'01']) }}">{{ $data->KOD01 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'02']) }}">{{ $data->KOD02 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'03']) }}">{{ $data->KOD03 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'04']) }}">{{ $data->KOD04 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'05']) }}">{{ $data->KOD05 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'06']) }}">{{ $data->KOD06 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'07']) }}">{{ $data->KOD07 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'08']) }}">{{ $data->KOD08 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'09']) }}">{{ $data->KOD09 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'10']) }}">{{ $data->KOD10 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'11']) }}">{{ $data->KOD11 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'12']) }}">{{ $data->KOD12 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'13']) }}">{{ $data->KOD13 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'14']) }}">{{ $data->KOD14 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'15']) }}">{{ $data->KOD15 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'16']) }}">{{ $data->KOD16 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,$data->code,'0']) }}">{{ $data->Bilangan }}</a></td>
                            </tr>
                            <?php
                            $Total01 += $data->KOD01;
                            $Total02 += $data->KOD02;
                            $Total03 += $data->KOD03;
                            $Total04 += $data->KOD04;
                            $Total05 += $data->KOD05;
                            $Total06 += $data->KOD06;
                            $Total07 += $data->KOD07;
                            $Total08 += $data->KOD08;
                            $Total09 += $data->KOD09;
                            $Total10 += $data->KOD10;
                            $Total11 += $data->KOD11;
                            $Total12 += $data->KOD12;
                            $Total13 += $data->KOD13;
                            $Total14 += $data->KOD14;
                            $Total15 += $data->KOD15;
                            $Total16 += $data->KOD16;
                            $Gtotal += $data->Bilangan
                            ?>
                           
                            @endforeach
                            <tr>
                                <td></td>
                                <td>Jumlah</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','01']) }}">{{ $Total01 }}</a></td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','02']) }}">{{ $Total02 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','03']) }}">{{ $Total03 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','04']) }}">{{ $Total04 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','05']) }}">{{ $Total05 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','06']) }}">{{ $Total06 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','07']) }}">{{ $Total07 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','08']) }}">{{ $Total08 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','09']) }}">{{ $Total09 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','10']) }}">{{ $Total10 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','11']) }}">{{ $Total11 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','12']) }}">{{ $Total12 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','13']) }}">{{ $Total13 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','14']) }}">{{ $Total14 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','15']) }}">{{ $Total15 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','16']) }}">{{ $Total16 }}</td>
                                <td><a target="_blank" href="{{ route('sumberaduannegeri1',[$Request->year,$Request->CA_DEPTCD,'0','0']) }}">{{ $Gtotal }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
               
                </div>
                       @if($cari !='')
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
@section('script_datatable')
   <script type="text/javascript">
        Highcharts.chart('container', {
            chart: {
            type: 'column'
        },
        title: {
            text: 'Laporan Cara Penerimaan'
        },
        subtitle: {
//            text: 'Click the columns to view versions. Source: <a href="http://netmarketshare.com">netmarketshare.com</a>.'
        },
        xAxis: {
            type: 'Cara Penerimaan'
        },
        yAxis: {
            title: {
                text: 'Jumlah Menngikut Cara Penerimaan'
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
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> daripada Jumlah Keseluruhan <br/>'
        },

        series: [{
            name: 'Cara Penerimaan',
            colorByPoint: true,
            data: <?php echo $datachart; ?>
        }],
        });
        
    </script>
@stop