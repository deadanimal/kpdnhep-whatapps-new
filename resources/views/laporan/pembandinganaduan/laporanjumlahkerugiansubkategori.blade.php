@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
    use App\Laporan\TerimaSelesaiAduan;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Laporan Jumlah Kerugian Mengikut Bulan & Subkategori Aduan</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'pembandinganaduan/laporanjumlahkerugiansubkategori']) !!}
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT_YEAR', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-6">
                               {{ Form::select('CA_RCVDT_YEAR', BandingAduan::GetYearList(), $CA_RCVDT_YEAR, ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_YEAR']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_CMPLCAT', 'Kategori', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::select('CA_CMPLCAT', TerimaSelesaiAduan::GetRef('244', 'semua'), null, ['class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT']) }}
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'action']) }}
                            {{ link_to('pembandinganaduan/laporanjumlahkerugiansubkategori', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                            @if($action)
                            </br>
                            {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Versi Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value'=>'1']) }} 
                            {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun Versi PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value'=>'1', 'formtarget' => '_blank']) }}
                            @endif
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 100%">
                        @if($action != '')
                            <thead>
                                <h3 align="center">LAPORAN JUMLAH KERUGIAN MENGIKUT BULAN & SUBKATEGORI ADUAN</h3>
                                <h3 align="center">
                                    {{ $CA_RCVDT_YEAR }}
                                </h3>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Subkategori Aduan</th>
                                    <th>JAN</th>
                                    <th>FEB</th>
                                    <th>MAC</th>
                                    <th>APR</th>
                                    <th>MEI</th>
                                    <th>JUN</th>
                                    <th>JUL</th>
                                    <th>OGO</th>
                                    <th>SEP</th>
                                    <th>OKT</th>
                                    <th>NOV</th>
                                    <th>DIS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $countmonth1total = 0;
                                    $countmonth2total = 0;
                                    $countmonth3total = 0;
                                    $countmonth4total = 0;
                                    $countmonth5total = 0;
                                    $countmonth6total = 0;
                                    $countmonth7total = 0;
                                    $countmonth8total = 0;
                                    $countmonth9total = 0;
                                    $countmonth10total = 0;
                                    $countmonth11total = 0;
                                    $countmonth12total = 0;
                                ?>
                                @foreach ($dataFinal as $key => $value)
                                    <tr>
                                        <td>{{ $bil++ }}</td>
                                        <td>{{ $subcategoryList[$key] }}</td>
                                        @foreach ($mRefMonth as $month)
                                            <td>{{ number_format($value[$month->code],2) }}</td>
                                        @endforeach
                                    </tr>
                                    <?php
                                        $countmonth1total += $value['1'];
                                        $countmonth2total += $value['2'];
                                        $countmonth3total += $value['3'];
                                        $countmonth4total += $value['4'];
                                        $countmonth5total += $value['5'];
                                        $countmonth6total += $value['6'];
                                        $countmonth7total += $value['7'];
                                        $countmonth8total += $value['8'];
                                        $countmonth9total += $value['9'];
                                        $countmonth10total += $value['10'];
                                        $countmonth11total += $value['11'];
                                        $countmonth12total += $value['12'];
                                    ?>
                                @endforeach
                            </tbody>
                            <tfooter>
                                <tr style="font-weight:bold">
                                    <td></td>
                                    <td>Jumlah</td>
                                    @foreach ($mRefMonth as $month)
                                        <td>{{ number_format(${'countmonth'.$month->code.'total'},2) }}</td>
                                    @endforeach
                                </tr>
                            </tfooter>
                        @endif
                    </table>
                </div>
                @if($action != '')
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
@section('script_datatable')
    <script type="text/javascript">
        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight: 260,
            showFilterInputs: false,
            infoText: '',
            infoTextEmpty: ''
        });
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
                text: 'Laporan Jumlah Kerugian Mengikut Bulan & Subkategori Aduan'
            },
            subtitle: {
                text: 'Tahun: <?php echo $CA_RCVDT_YEAR ?>'
            },
            xAxis: {
                crosshair: true,
                type: 'category',
                title: {
                    text: 'Bulan'
                }
            },
            yAxis: {
                title: {
                    text: 'Jumlah Kerugian (RM)'
                }
            },
            legend: {
                enabled: false
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
                name: 'Bulan',
                colorByPoint: true,
                data: [
                    ['JANUARI', <?php echo $countmonth1total ?>],
                    ['FEBRUARI', <?php echo $countmonth2total ?>],
                    ['MAC', <?php echo $countmonth3total?>],
                    ['APRIL', <?php echo $countmonth4total ?>],
                    ['MEI', <?php echo $countmonth5total ?>],
                    ['JUN', <?php echo $countmonth6total ?>],
                    ['JULAI', <?php echo $countmonth7total ?>],
                    ['OGOS', <?php echo $countmonth8total ?>],
                    ['SEPTEMBER', <?php echo $countmonth9total ?>],
                    ['OKTOBER', <?php echo $countmonth10total ?>],
                    ['NOVEMBER', <?php echo $countmonth11total ?>],
                    ['DISEMBER', <?php echo $countmonth12total ?>]
                ]
            }],
            credits: {
                enabled: false
            }
        });
    </script>
@stop