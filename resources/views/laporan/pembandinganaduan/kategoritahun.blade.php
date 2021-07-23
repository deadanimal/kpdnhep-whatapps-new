@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Aduan Mengikut Kategori (Tahunan)</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'pembandinganaduan/kategoritahun']) !!}
                        <div class="form-group" id="year">
                            {{ Form::label('CA_RCVDT_YEAR_FROM', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                <div class="input-group">
                                    {{ Form::select('CA_RCVDT_YEAR_FROM', BandingAduan::GetYearList(), null, ['class' => 'form-control input-sm']) }}
                                    <span class="input-group-addon">hingga</span>
                                    {{ Form::select('CA_RCVDT_YEAR_TO', BandingAduan::GetYearList(), $CA_RCVDT_YEAR_CURRENT, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_STATECD', 'Negeri', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                {{ Form::select('CA_STATECD', BandingAduan::GetRefList('17', 'semua0'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                <!--{{-- Form::select('CA_DEPTCD', BandingAduan::GetRefList('315', 'semua'), null, ['class' => 'form-control input-sm']) --}}-->
                                {{ Form::select('CA_DEPTCD', BandingAduan::GetRefDeptList('315', $CA_STATECD, 'semua'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'search']) }}
                            {{ link_to('pembandinganaduan/kategoritahun', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                        @if($search != '')
                            <div class="form-group" align="center">
                                {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Versi Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value'=>'1']) }} &nbsp;
                                {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun Versi PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value'=>'1', 'formtarget' => '_blank']) }}
                            </div>
                        @endif
                    {!! Form::close() !!}
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 100%">
                        @if($search != '')
                            <h3 align="center">
                                LAPORAN KATEGORI TAHUNAN 
                                @if(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM < $CA_RCVDT_YEAR_TO))
                                    DARI {{ $CA_RCVDT_YEAR_FROM }} HINGGA {{ $CA_RCVDT_YEAR_TO }}
                                @elseif(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM == $CA_RCVDT_YEAR_TO))
                                    {{ $CA_RCVDT_YEAR_TO }}
                                @endif
                            </h3>
                            <h3 align="center">
                                @if($CA_STATECD != '0')
                                    {{ Ref::GetDescr('17', $CA_STATECD, 'ms') }}
                                @else
                                    SEMUA NEGERI
                                @endif
                            </h3>
                            <h3 align="center">
                                @if($CA_DEPTCD != '0')
                                    {{ Ref::GetDescr('315', $CA_DEPTCD, 'ms') }}
                                @else
                                    SEMUA BAHAGIAN
                                @endif
                            </h3>
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Kategori</th>
                                    <!--{{-- @foreach($CA_RCVDT_YEAR as $YEAR) --}}-->
                                        <!--<th>{{-- $YEAR->year --}}</th>-->
                                    <!--{{-- @endforeach --}}-->
                                    @for($year=$CA_RCVDT_YEAR_FROM; $year<=$CA_RCVDT_YEAR_TO; $year++)
                                        <th>{{ $year }}</th>
                                    @endfor
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--{{-- @foreach($mRefCategory as $category) --}}-->
                                @foreach($datas as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->descr }}</td>
                                        <!--{{-- @foreach($CA_RCVDT_YEAR as $YEAR) --}}-->
                                            <!--<td><a target="_blank" href="{{-- route() --}}">{{-- BandingAduan::jumlahkategoritahun($category->code, $YEAR->year, $CA_STATECD, $CA_DEPTCD) --}}</a></td>-->
                                        <!--{{-- @endforeach --}}-->
                                        @for($CA_RCVDT_YEAR=$CA_RCVDT_YEAR_FROM; $CA_RCVDT_YEAR<=$CA_RCVDT_YEAR_TO; $CA_RCVDT_YEAR++)
                                            <td>
                                                <!--<a target="_blank" href="">-->
                                                <!--<a target="_blank" href="{{-- url('pembandinganaduan/kategoritahun1?CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&CA_STATECD='.$CA_STATECD.'&CA_DEPTCD='.$CA_DEPTCD.'&CA_CMPLCAT='.$category->code) --}}">-->
                                                <a target="_blank" href="{{ route('kategoritahun1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_RCVDT_YEAR, $CA_DEPTCD, $CA_STATECD, $category->code]) }}">
                                                    {{ BandingAduan::jumlahkategoritahun($category->code, $CA_RCVDT_YEAR, $CA_STATECD, $CA_DEPTCD) }}
                                                </a>
                                            </td>
                                        @endfor
                                        <td>
                                            <!--<a target="_blank" href="{{-- route() --}}">-->
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/kategoritahun1?CA_RCVDT_YEAR_FROM='.$CA_RCVDT_YEAR_FROM.'&CA_RCVDT_YEAR_TO='.$CA_RCVDT_YEAR_TO.'&CA_STATECD='.$CA_STATECD.'&CA_DEPTCD='.$CA_DEPTCD.'&CA_CMPLCAT='.$category->code) --}}">-->
                                            <a target="_blank" href="{{ route('kategoritahun1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, 0, $CA_DEPTCD, $CA_STATECD, $category->code]) }}">
                                                <!--{{-- BandingAduan::jumlahkategoritahunsemuatahun($CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $category->code, $CA_STATECD, $CA_DEPTCD) --}}-->
                                                {{ $category->countcaseid }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Jumlah</td>
                                    <!--{{-- @foreach($CA_RCVDT_YEAR as $YEAR) --}}-->
                                    <!--<td><a target="_blank" href="{{-- route() --}}">{{-- BandingAduan::jumlahkategoritahunsemuakategori($YEAR->year, $CA_STATECD, $CA_DEPTCD) --}}</a></td>-->
                                    <!--{{-- @endforeach --}}-->
                                    @for($CA_RCVDT_YEAR=$CA_RCVDT_YEAR_FROM; $CA_RCVDT_YEAR<=$CA_RCVDT_YEAR_TO; $CA_RCVDT_YEAR++)
                                        <td>
                                            <!--<a target="_blank">-->
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/kategoritahun1?CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&CA_STATECD='.$CA_STATECD.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                            <a target="_blank" href="{{ route('kategoritahun1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_RCVDT_YEAR, $CA_DEPTCD, $CA_STATECD, '0']) }}">
                                                {{ BandingAduan::jumlahkategoritahunsemuakategori($CA_RCVDT_YEAR, $CA_STATECD, $CA_DEPTCD) }}
                                            </a>
                                        </td>
                                    @endfor
                                    <td>
                                        <!--<a target="_blank" href="{{-- route() --}}">-->
                                        <!--<a target="_blank" href="{{-- url('pembandinganaduan/kategoritahun1?CA_RCVDT_YEAR_FROM='.$CA_RCVDT_YEAR_FROM.'&CA_RCVDT_YEAR_TO='.$CA_RCVDT_YEAR_TO.'&CA_STATECD='.$CA_STATECD.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                        <a target="_blank" href="{{ route('kategoritahun1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, '0', $CA_DEPTCD, $CA_STATECD, '0']) }}">
                                        <!--<a target="_blank" href="{{-- route('jumlahaduan1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, '0', $CA_DEPTCD, $BR_STATECD, '00']) --}}">-->
                                            {{ BandingAduan::jumlahkategoritahunsemua($CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_STATECD, $CA_DEPTCD) }}
                                        </a>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@if($search != '')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div id="container" style="min-width: 400px; min-height: 500px; margin: 0 auto"></div>
            </div>
        </div>
    </div>
</div>
@endif
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
                text: 'Laporan Perbandingan Aduan Mengikut Kategori (Tahunan)'
            },
            subtitle: {
                text: 'Tahun: <?php 
                    if(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM < $CA_RCVDT_YEAR_TO)){
                        echo 'Dari ' . $CA_RCVDT_YEAR_FROM . ' Hingga ' . $CA_RCVDT_YEAR_TO;
                    }
                    elseif(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM == $CA_RCVDT_YEAR_TO)){
                        echo $CA_RCVDT_YEAR_TO ;
                    }
                ?>'
            },
            xAxis: {
                crosshair: true,
                type: 'category',
                title: {
                    text: 'Kategori'
                }
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Jumlah Aduan'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<table>',
                pointFormat: '<tr><td style="color:{point.color};padding:0">{point.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            series: [{
                data: <?php echo $datachart; ?>,
                colorByPoint: true
            }],
            credits: {
                enabled: false
            }
        });
    </script>
@stop