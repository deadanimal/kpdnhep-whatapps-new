@extends('layouts.main')
<?php
    use App\Laporan\TerimaSelesaiAduan;
?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Tempoh Pindah Aduan</h2>
                <div class="ibox-content">
                    <div class="row">
                        {!! Form::open(['url' => 'penerimaanpenyelesaianaduan/pengagihanaduan', 'id' => 'search-form', 'class'=>'form-horizontal', 'method' => 'GET']) !!}
                        <div class="row">
                            <div class="col-md-12" align="center">
                                <div class="form-group">
                                    {{ Form::label('CA_RCVDT_YEAR', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-3">
                                        {{ Form::select('CA_RCVDT_YEAR', TerimaSelesaiAduan::GetByYear(true), $SelectYear, ['class' => 'form-control input-sm ' , 'id' => 'year']) }}
                                    </div>
                                    {{ Form::label('CA_RCVDT_MONTH', 'Bulan', ['class' => 'col-sm-1 control-label']) }}
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            {{ Form::select('CA_RCVDT_MONTH_FROM', TerimaSelesaiAduan::GetMonth(), null, ['class' => 'form-control input-sm']) }}
                                            <span class="input-group-addon">hingga</span>
                                            {{ Form::select('CA_RCVDT_MONTH_TO', TerimaSelesaiAduan::GetMonth(), null, ['class' => 'form-control input-sm']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_DEPTCD', TerimaSelesaiAduan::GetRef('315', 'semua'), null, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('BR_STATECD','Negeri', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    <div class="ibox">
                                        <select name="BR_STATECD[]" class="form-control dual_select" multiple>
                                            @foreach ($mNegeriList as $negeri)
                                                @if(in_array($negeri->code, $BR_STATECD))
                                                    <option value="{{ $negeri->code }}"
                                                            selected="selected">{{ $negeri->descr }}</option>
                                                @else
                                                    <option value="{{ $negeri->code }}">{{ $negeri->descr }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'value' => 'btnCarian', 'name' => 'btnCarian', 'name'=>'gen', 'value'=>'web']) }}
                            <!--<a href="{{-- url('penerimaanpenyelesaianaduan\printexcel\$Parameter') --}}" name="excel" class = 'btn btn-success btn-sm' data-toggle="tooltip" data-placement="right">Muat Turun Excel</a>-->
                                <a class="btn btn-default btn-sm"
                                   href="{{ url('penerimaanpenyelesaianaduan/pengagihanaduan')}}"> <i
                                            class="fa fa-recycle"> Semula </i></a>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                                {{ Form::button('<i class="fa fa-file-excel-o"></i>'.'  Muat Turun Versi Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value'=>'excel','url' => 'penerimaanpenyelesaianaduan/pengagihanaduan']) }}
                                &nbsp;
                                {{ Form::button('<i class="fa fa-file-pdf-o"></i>'.' Muat Turun Versi PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value'=>'pdf', 'formtarget' => '_blank']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="row">
                        <div style="text-align: center;"><h3></h3></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example"
                                   style="width: 100%">
                                <thead>
                                <h3>
                                    <div style="text-align: center;">
                                        <p>
                                            LAPORAN PINDAH ADUAN MENGIKUT NEGERI BAGI
                                            TAHUN {{ $SelectYear }}
                                        </p>
                                        <p>
                                            DARI {{ $monthFromDesc }}
                                            HINGGA
                                            {{ $monthToDesc }}
                                        </p>
                                        {{ $departmentDesc }}
                                    </div>
                                </h3>
                                <br>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Negeri</th>
                                    <th>Jumlah Aduan</th>
                                    <th> < 1 hari</th>
                                    <th> 1 hari</th>
                                    <th> 2-3 hari</th>
                                    <th> > 3 hari</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($BR_STATECD as $state)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{$stateList[$state]}}</td>
                                        <td>{{ $dataCount[$state]['total'] }}</td>
                                        <td><a target="_blank" href="{{ route('senaraipindah',['0', $state, $SelectYear, $MonthFrom, $MonthTo, $CA_DEPTCD]) }}">{{ $dataCount[$state]['<1'] }}</a></td>
                                        <td><a target="_blank" href="{{ route('senaraipindah',['1', $state, $SelectYear, $MonthFrom, $MonthTo, $CA_DEPTCD]) }}">{{ $dataCount[$state]['1'] }}</a></td>
                                        <td><a target="_blank" href="{{ route('senaraipindah',['23', $state, $SelectYear, $MonthFrom, $MonthTo, $CA_DEPTCD]) }}">{{ $dataCount[$state]['2-3'] }}</a></td>
                                        <td><a target="_blank" href="{{ route('senaraipindah',['3', $state, $SelectYear, $MonthFrom, $MonthTo, $CA_DEPTCD]) }}">{{ $dataCount[$state]['>3'] }}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th><strong>JUMLAH</strong></th>
                                    <th>{{ $dataCount['total']['total'] }}</th>
                                    <th>
                                        <a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['<1'] }}</a>
                                    </th>
                                    <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['1'] }}</a>
                                    </th>
                                    <th>
                                        <a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['2-3'] }}</a>
                                    </th>
                                    <th>
                                        <a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['>3'] }}</a>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>
            </div>
        </div>
    </div>
    <?php // dd($dataimplode);?>
@stop
@section('script_datatable')
    <script type="text/javascript">
      console.log(data)
      console.log(categories)

      $('.dual_select').bootstrapDualListbox({
        selectorMinimalHeight: 260,
        showFilterInputs: false,
        infoText: '',
        infoTextEmpty: ''
      })
      Highcharts.chart('container', {
        chart: {
          type: 'column'
        },
        title: {
          text: 'Laporan Pindah Aduan Mengikut Negeri'
        },
        subtitle: {
//        text: 'Source: WorldClimate.com'
        },
        xAxis: {
          categories: categories,
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
          '<td style="padding:0"><b>{point.y}</b></td></tr>',
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
        series: data
      })
    </script>
@stop