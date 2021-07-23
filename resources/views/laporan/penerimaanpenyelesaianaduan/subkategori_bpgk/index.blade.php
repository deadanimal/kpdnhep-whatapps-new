@extends('layouts.main')
<?php
use App\Ref;
use App\Laporan\TerimaSelesaiAduan;
?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>LAPORAN MENGIKUT STATUS DAN SUBKATEGORI ADUAN</h2>
                <div class="ibox-content">
                    <div class="row">
                        {!! Form::open(['url' => 'penerimaanpenyelesaianaduan/laporan_subkategori_BPGK', 'id' => 'search-form', 'class'=>'form-horizontal', 'method' => 'GET']) !!}
                        <div class="form-group" id="data_5">
                            {{ Form::label('date', 'Tarikh (Terima) : ', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                <div class="input-daterange input-group" id="datepicker">
                                    {{ Form::text('dateStart', date('d-m-Y', strtotime($dateStart)), ['class' => 'form-control input-sm', 'id' => 'dateStart', 'onkeypress' => "return false", 'onpaste' => "return false"]) }}
                                    <span class="input-group-addon">hingga</span>
                                    {{ Form::text('dateEnd', date('d-m-Y', strtotime($dateEnd)), ['class' => 'form-control input-sm', 'id' => 'dateEnd', 'onkeypress' => "return false", 'onpaste' => "return false"]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('state', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('state', TerimaSelesaiAduan::GetRef('17', 'semua'), $user->state_cd, ['class' => 'form-control input-sm', 'id' => 'state']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="ibox">
                                        <select name="branch[]" class="form-control dual_select" multiple>
                                            @foreach ($branchSelectList as $branch)
                                                @if(in_array($branch->BR_BRNCD, $brancharray))
                                                    <option value="{{ $branch->BR_BRNCD }}" selected="selected">{{ $branch->BR_BRNNM }}</option>
                                                @else
                                                    <option value="{{ $branch->BR_BRNCD }}">{{ $branch->BR_BRNNM }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                {{ Form::label('kategori', 'Kategori', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::select('kategori', TerimaSelesaiAduan::GetRef('244', 'sp'), null, ['class' => 'form-control input-sm', 'id' => 'kategori']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                                {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                                &nbsp;
                                <a class="btn btn-default btn-sm"
                                   href="{{ url('penerimaanpenyelesaianaduan/laporan_subkategori_BPGK')}}"><i
                                            class="fa fa-recycle"> Semula</i></a>

                            </div>
                        </div>
                        @if($search)
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    <a target="_blank"
                                       href="/penerimaanpenyelesaianaduan/laporan_subkategori_BPGK?dateStart={!! $dateStart->format('d-m-Y') !!}&dateEnd={!! $dateEnd->format('d-m-Y') !!}&state={!! $state !!}&{!! $branchUri !!}&kategori={!! $kategori !!}&gen=excel"
                                       class="btn btn-success btn-sm">
                                        <i class="fa fa-file-excel-o"></i> Muat Turun Versi Excel
                                    </a>
                                    <a target="_blank" 
                                       href="/penerimaanpenyelesaianaduan/laporan_subkategori_BPGK?dateStart={!! $dateStart->format('d-m-Y') !!}&dateEnd={!! $dateEnd->format('d-m-Y') !!}&state={!! $state !!}&{!! $branchUri !!}&kategori={!! $kategori !!}&gen=pdf"
                                       class="btn btn-success btn-sm">
                                        <i class="fa fa-file-excel-o"></i> Muat Turun Versi PDF
                                    </a>
                                </div>
                            </div>
                        @endif
                        {!! Form::close() !!}
                    </div>
                    @if($search)
                        <div class="row">
                            <div style="text-align: center;">
                                <h3>
                                    <div style="text-align: center;">
                                        LAPORAN MENGIKUT STATUS DAN SUBKATEGORI ADUAN BAGI
                                        TAHUN {{ date('d-m-Y', strtotime($dateStart)) }}
                                        SEHINGGA {{ date('d-m-Y', strtotime($dateEnd)) }}<br>
                                        {{ $kategori != null ? Ref::GetDescr('244', $kategori) : '' }} dan
                                        Negeri {{ $state != null ? Ref::GetDescr('17', $state) : '' }}
                                    </div>
                                </h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example"
                                       style="width: 100%">
                                    <thead>
<!--                                        <tr>
                                            <th rowspan="3">Bil.</th>
                                            <th rowspan="3">Kategori Aduan</th>
                                            <th colspan="12">Status Aduan</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2">Diterima</th>
                                            <th rowspan="2">Belum Diagih</th>
                                            <th rowspan="2">Dalam Siasatan</th>
                                            <th colspan="7">Tindakan Aduan</th>
                                            <th rowspan="2">Belum Selesai</th>
                                        </tr>
                                        <tr>
                                            <th>Diselesaikan</th>
                                            <th>Ditutup</th>
                                            <th>Agensi KPDNKK</th>
                                            <th>Tribunal</th>
                                            <th>Pertanyaan</th>
                                            <th>Maklumat Tidak Lengkap</th>
                                            <th>Luar Bidang Kuasa</th>
                                        </tr>-->
                                        <tr>
                                            <th rowspan="2">Bil.</th>
                                            <th rowspan="2">Subkategori Aduan</th>
                                            <th rowspan="2">Diterima</th>
                                            <th colspan="3">Belum Selesai</th>
                                            <th colspan="6">Tindakan Aduan</th>
                                        </tr>
                                        <tr>
                                            <th>Aduan Baru</th>
                                            <th>Dalam Siasatan</th>
                                            <th>Maklumat Tidak Lengkap</th>
                                            <th>Diselesaikan</th>
                                            <th>Ditutup</th>
                                            <th>Agensi KPDNKK</th>
                                            <th>Tribunal</th>
                                            <th>Pertanyaan</th>
                                            <th>Luar Bidang Kuasa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1; ?>
                                    @foreach($dataFinal as $key => $datum)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$subdepartmentList[$key]}}</td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is[]=0&is[]=1&is[]=2&is[]=3&is[]=4&is[]=5&is[]=6&is[]=7&is[]=8&is[]=9&is[]=11&is[]=12">{{$datum['total']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is[]=0&is[]=1">{{$datum['belum agih']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is=2">{{$datum['dalam siasatan']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is=7">{{$datum['maklumat tak lengkap']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is[]=3&is[]=12">{{$datum['selesai']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is[]=9&is[]=11">{{$datum['tutup']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=2&is=4">{{$datum['agensi lain']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is=5">{{$datum['tribunal']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is=6">{{$datum['pertanyaan']}}</a></td>
                                            <td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=&is=8">{{$datum['luar bidang']}}</a></td>
                                            <!--<td><a target="_blank" href="laporan_subkategori_BPGK/dd?{!! $dd_uri !!}&sd={!! $key !!}&cs=3&is=">{{-- $datum['belum selesai'] --}}</a></td>-->
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfooter>
                                        <tr>
                                            <th></th>
                                            <th>Jumlah</th>
                                            <th>{{$dataCounter['total']}}</th>
                                            <th>{{$dataCounter['belum agih']}}</th>
                                            <th>{{$dataCounter['dalam siasatan']}}</th>
                                            <th>{{$dataCounter['maklumat tak lengkap']}}</th>
                                            <th>{{$dataCounter['selesai']}}</th>
                                            <th>{{$dataCounter['tutup']}}</th>
                                            <th>{{$dataCounter['agensi lain']}}</th>
                                            <th>{{$dataCounter['tribunal']}}</th>
                                            <th>{{$dataCounter['pertanyaan']}}</th>
                                            <th>{{$dataCounter['luar bidang']}}</th>
                                            <!--<th>{{-- $dataCounter['belum selesai'] --}}</th>-->
                                        </tr>
                                    </tfooter>
                                </table>
                            </div>
                        </div>
                        <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
                </div>
                @endif
            </div>
        </div>
    </div>
@stop
@section('script_datatable')
    <script type="text/javascript">
      $(function () {
        console.log(dataCounter)

        $('#state').on('change', function (e) {
          var CA_AGAINST_STATECD = $(this).val()
          $.ajax({
            type: 'GET',
            url: "{{ url('penerimaanpenyelesaianaduan/getbranchlist') }}" + '/' + CA_AGAINST_STATECD,
            dataType: 'json',
            success: function (data) {
              $('select[name="branch[]"]').empty()
              $.each(data, function (key, value) {
                $('select[name="branch[]"]').append('<option value="' + key + '">' + value + '</option>')
                $('.dual_select').bootstrapDualListbox('refresh')
              })
            }
          })

          $.ajax({
            type: 'GET',
            url: "{{ url('penerimaanpenyelesaianaduan/getdeptlistbystate') }}" + '/' + CA_AGAINST_STATECD,
            dataType: 'json',
            success: function (data) {
              $('select[name="CA_DEPTCD"]').empty()
              $.each(data, function (key, value) {
                $('select[name="CA_DEPTCD"]').append('<option value="' + value + '">' + key + '</option>')
              })
              $('select[name="CA_CMPLCAT[]"]').empty()
              $('.dual_select').bootstrapDualListbox('refresh')
            }
          })
        })


        $('#department').on('change', function (e) {
          var CA_DEPTCD = $(this).val()
          $.ajax({
            type: 'GET',
            url: "{{ url('penerimaanpenyelesaianaduan/getcategorylist') }}" + '/' + CA_DEPTCD,
            dataType: 'json',
            success: function (data) {
              $('select[name="subdepartment[]"]').empty()
              $.each(data, function (key, value) {
                $('select[name="subdepartment[]"]').append('<option value="' + key + '">' + value + '</option>')
                $('.dual_select').bootstrapDualListbox('refresh')
              })
            }
          })
        })
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
            text: 'Laporan Mengikut Status dan Kategori'
          },
          subtitle: {
//        text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
          },
          xAxis: {
            type: 'category',
//            categories: [ 'Selesai', 'Belum Agih', 'Dalam Siastan', 'Diselesaikan', 'Ditutup', 'Agensi Lain', 'Tirbunal', 'Pertanyaan', 'Maklumat Tidak Lengkap', 'Luar Bidang Kuasa' ],
            categories: [ 'Aduan Baru', 'Dalam Siasatan', 'Maklumat Tidak Lengkap', 'Diselesaikan', 'Ditutup', 'Agensi KPDNKK', 'Tribunal', 'Pertanyaan', 'Luar Bidang Kuasa' ],
            labels: {
              rotation: -45,
              style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
              }
            }
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Jumlah aduan'
            }
          },
          legend: {
            enabled: false
          },
          tooltip: {
//            pointFormat: '<b>{point.y:.1f}</b>'
            pointFormat: '<b>{point.y:.f}</b>'
          },
          series: [ {
            data: dataCounter
          } ]
        })
      })
      $('#data_5 .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
      })
    </script>
@stop