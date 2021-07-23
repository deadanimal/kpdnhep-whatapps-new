@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Laporan Perbandingan Aduan Mengikut Negeri (Bulanan)</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'pembandinganaduan/laporannegeri']) !!}
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT_YEAR', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                               {{ Form::select('CA_RCVDT_YEAR', BandingAduan::GetYearList(), $CA_RCVDT_YEAR, ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_YEAR']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT_MONTH', 'Bulan', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                <div class="input-group">
                                    {{ Form::select('CA_RCVDT_MONTH_FROM', BandingAduan::GetRefList('206', ''), $CA_RCVDT_MONTH_FROM, ['class' => 'form-control input-sm']) }}
                                    <span class="input-group-addon">hingga</span>
                                    {{ Form::select('CA_RCVDT_MONTH_TO', BandingAduan::GetRefList('206', ''), $CA_RCVDT_MONTH_TO, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                {{ Form::select('CA_DEPTCD', BandingAduan::GetRefDeptList('315', '0', 'semua'), null, ['class' => 'form-control input-sm', 'id' => 'CA_DEPTCD']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <select name="BR_STATECD[]" class="form-control dual_select" multiple>
                                    @foreach ($mRefNegeri as $negeri)
                                        @if(in_array($negeri->code, $BR_STATECD))
                                            <option value="{{ $negeri->code }}" selected="selected">{{ $negeri->descr }}</option>
                                        @else
                                            <option value="{{ $negeri->code }}">{{ $negeri->descr }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'action']) }}
                            {{ link_to('pembandinganaduan/laporannegeri', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
                                <h3 align="center">LAPORAN PERBANDINGAN ADUAN MENGIKUT NEGERI (BULANAN)</h3>
                                <h3 align="center">
                                    @if(($CA_RCVDT_MONTH_FROM) && ($CA_RCVDT_MONTH_TO) && ($CA_RCVDT_MONTH_FROM < $CA_RCVDT_MONTH_TO))
                                        DARI {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }} 
                                        HINGGA {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_TO, 'ms') }} 
                                    @elseif(($CA_RCVDT_MONTH_FROM == $CA_RCVDT_MONTH_TO) && ($CA_RCVDT_MONTH_FROM!='')&&($CA_RCVDT_MONTH_TO!=''))
                                        {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }}
                                    @endif
                                    {{ $CA_RCVDT_YEAR }}
                                </h3>
                                <h3 align="center">{{ $CA_DEPTCD != '0' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h3>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Negeri</th>
<!--                                    <th> Jan</th>
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
                                    <th> Dis </th>-->
                                    <!--{{-- @foreach ($mRefMonth as $month) --}}-->
                                        <!--<th>{{-- $month->descr --}}</th>-->
                                    <!--{{-- @endforeach --}}-->
                                    @for($mRefMonth=$CA_RCVDT_MONTH_FROM; $mRefMonth<=$CA_RCVDT_MONTH_TO; $mRefMonth++)
                                        <th title="{{ Ref::GetDescr('206', $mRefMonth, 'ms') }}">
                                            @if($mRefMonth == '1')
                                                JAN
                                            @elseif($mRefMonth == '2')
                                                FEB
                                            @elseif($mRefMonth == '4')
                                                APR
                                            @elseif($mRefMonth == '7')
                                                JUL
                                            @elseif($mRefMonth == '8')
                                                OGO
                                            @elseif($mRefMonth == '9')
                                                SEP
                                            @elseif($mRefMonth == '10')
                                                OKT
                                            @elseif($mRefMonth == '11')
                                                NOV
                                            @elseif($mRefMonth == '12')
                                                DIS
                                            @else
                                                {{ Ref::GetDescr('206', $mRefMonth, 'ms') }}
                                            @endif
                                        </th>
                                    @endfor
                                    <th>Jumlah</th>
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
                                    $countcaseidtotal = 0;
                                ?>
                                @foreach ($laporannegeribulan as $rowstate)
                                    <tr>
                                        <td>{{ $bil++ }}</td>
                                        <td>{{ Ref::GetDescr('17', $rowstate->BR_STATECD, 'ms') }}</td>
                                        @for($month=$CA_RCVDT_MONTH_FROM; $month<=$CA_RCVDT_MONTH_TO; $month++)
                                        <td>
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/senarai?BR_STATECD='.$rowstate->BR_STATECD.'&month='.$month.'&CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&CA_RCVDT_MONTH_FROM='.$CA_RCVDT_MONTH_FROM.'&CA_RCVDT_MONTH_TO='.$CA_RCVDT_MONTH_TO.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                            <a target="_blank" href="{{ route('laporannegeri1', [$CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $month, $CA_DEPTCD, $rowstate->BR_STATECD]) }}">
                                                <!--{{-- $rowstate->countmonth1 --}}-->
                                                {{ $rowstate->{'countmonth'.$month} }}
                                            </a>
                                        </td>
                                        @endfor
                                        <!--                                    
                                        <td>{{-- $rowstate->countmonth1 --}}</td>
                                        <td>{{-- $rowstate->countmonth2 --}}</td>
                                        <td>{{-- $rowstate->countmonth3 --}}</td>
                                        <td>{{-- $rowstate->countmonth4 --}}</td>
                                        <td>{{-- $rowstate->countmonth5 --}}</td>
                                        <td>{{-- $rowstate->countmonth6 --}}</td>
                                        <td>{{-- $rowstate->countmonth7 --}}</td>
                                        <td>{{-- $rowstate->countmonth8 --}}</td>
                                        <td>{{-- $rowstate->countmonth9 --}}</td>
                                        <td>{{-- $rowstate->countmonth10 --}}</td>
                                        <td>{{-- $rowstate->countmonth11 --}}</td>
                                        <td>{{-- $rowstate->countmonth12 --}}</td>
                                        -->
                                        <td>
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/senarai?BR_STATECD='.$rowstate->BR_STATECD.'&CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&CA_RCVDT_MONTH_FROM='.$CA_RCVDT_MONTH_FROM.'&CA_RCVDT_MONTH_TO='.$CA_RCVDT_MONTH_TO.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                            <a target="_blank" href="{{ route('laporannegeri1', [$CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, '0', $CA_DEPTCD, $rowstate->BR_STATECD]) }}">
                                                {{ $rowstate->countcaseid }}
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                        $countmonth1total += $rowstate->countmonth1;
                                        $countmonth2total += $rowstate->countmonth2;
                                        $countmonth3total += $rowstate->countmonth3;
                                        $countmonth4total += $rowstate->countmonth4;
                                        $countmonth5total += $rowstate->countmonth5;
                                        $countmonth6total += $rowstate->countmonth6;
                                        $countmonth7total += $rowstate->countmonth7;
                                        $countmonth8total += $rowstate->countmonth8;
                                        $countmonth9total += $rowstate->countmonth9;
                                        $countmonth10total += $rowstate->countmonth10;
                                        $countmonth11total += $rowstate->countmonth11;
                                        $countmonth12total += $rowstate->countmonth12;
                                        $countcaseidtotal += $rowstate->countcaseid;
                                    ?>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Jumlah</td>
                                    @for($month=$CA_RCVDT_MONTH_FROM; $month<=$CA_RCVDT_MONTH_TO; $month++)
                                        <td>
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/senarai?month='.$month.'&CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&CA_RCVDT_MONTH_FROM='.$CA_RCVDT_MONTH_FROM.'&CA_RCVDT_MONTH_TO='.$CA_RCVDT_MONTH_TO.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                            <a target="_blank" href="{{ route('laporannegeri1', [$CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $month, $CA_DEPTCD, '0']) }}">
                                                {{ ${'countmonth'.$month.'total'} }}
                                            </a>
                                        </td>
                                    @endfor
<!--                                    <td>{{-- $countmonth1total --}}</td>
                                    <td>{{-- $countmonth2total --}}</td>
                                    <td>{{-- $countmonth3total --}}</td>
                                    <td>{{-- $countmonth4total --}}</td>
                                    <td>{{-- $countmonth5total --}}</td>
                                    <td>{{-- $countmonth6total --}}</td>
                                    <td>{{-- $countmonth7total --}}</td>
                                    <td>{{-- $countmonth8total --}}</td>
                                    <td>{{-- $countmonth9total --}}</td>
                                    <td>{{-- $countmonth10total --}}</td>
                                    <td>{{-- $countmonth11total --}}</td>
                                    <td>{{-- $countmonth12total --}}</td>-->
                                    <td>
                                        <!--<a target="_blank" href="{{-- url('pembandinganaduan/senarai?CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&CA_RCVDT_MONTH_FROM='.$CA_RCVDT_MONTH_FROM.'&CA_RCVDT_MONTH_TO='.$CA_RCVDT_MONTH_TO.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                        <a target="_blank" href="{{ route('laporannegeri1', [$CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, '0', $CA_DEPTCD, '0']) }}">
                                            {{ $countcaseidtotal }}
                                        </a>
                                    </td>
                                </tr>
                            </tfoot>
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
                text: 'Laporan Perbandingan Aduan Mengikut Negeri (Bulanan)'
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
                    text: 'Jumlah Aduan'
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