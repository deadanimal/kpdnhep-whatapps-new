@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;
?>

@section('content')
    <style>
        .form-control.input-sm[readonly][type="text"] {
            background-color: #ffffff;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>LAPORAN ADUAN MENGIKUT PEGAWAI PENYIASAT BAGI SEMUA BAHAGIAN</h2>
                <div class="ibox-content">
                    <div class="row">
                        {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal','method' => 'GET', 'url'=>'laporanlainlain/laporan_pegawai']) !!}
                        <div class="form-group" id="date">
                            {{ Form::label('CA_RCVDT', 'Tarikh :', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                <div class="input-daterange input-group" id="datepicker">
                                    {{ 
                                        Form::text(
                                            'dateStart', 
                                            $dateStart->format('d-m-Y'), 
                                            [
                                                'class' => 'form-control input-sm', 
                                                'id' => 'dateStart',
                                                'readonly' => true,
                                                'placeholder' => 'HH-BB-TTTT'
                                            ]
                                        ) 
                                    }}
                                    <span class="input-group-addon">hingga</span>
                                    {{ 
                                        Form::text(
                                            'dateEnd', 
                                            $dateEnd->format('d-m-Y'), 
                                            [
                                                'class' => 'form-control input-sm', 
                                                'id' => 'dateEnd',
                                                'readonly' => true,
                                                'placeholder' => 'HH-BB-TTTT'
                                            ]
                                        ) 
                                    }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('state','Negeri', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::select('state', $stateList, old('state'), ['class' => 'form-control input-sm', 'id' => 'state']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('branch','Cawangan', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::select('branch',(($isLimitByState || $branch != '') ? $branchList : ['' => 'SEMUA']), old('branch'), ['class' => 'form-control input-sm', 'id' => 'branch']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('pegawai','Senarai Pegawai', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::select('pegawai', [], old('pegawai'), ['class' => 'form-control input-sm select2', 'id' => 'pegawai']) }}
                            </div>
                        </div>
                         
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'gen', 'value'=>'search']) }}
                            {{ link_to('laporanlainlain/laporan_pegawai', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                        @if($search)
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    {{-- Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'excel']) --}}
                                    {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'excelxls', 'formtarget' => '_blank']) }}
                                    {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'pdf', 'formtarget' => '_blank']) }}
                                </div>
                            </div>
                        @endif
                        {!! Form::close() !!}
                    </div>
                     @if($search)
                    <div class="table-responsive">
                        <table id="state-table"
                               class="table table-striped table-bordered table-hover dataTables-example"
                               style="width: 100%">
                           
                                <thead>
                                <h3>Cawangan:{{ $branchName }}<br/>
                                    Tarikh:{{ $dateStart->format('d-m-Y') }} hingga {{ $dateEnd->format('d-m-Y') }}
                                </h3>
                                <br>
                                <br>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Nama Pegawai Penyiasat</th>
                                    <th>Unit</th>
                                    <th>Selesai</th>
                                    <th>Belum Selesai</th>
                                    <th>Jumlah Aduan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total=0;
                                @endphp
                                @if(count($dataFinal) > 0)
                                @foreach ($dataFinal as $key => $datum)
                                    <tr>
                                        <td> {{ $key+1 }}</td>
                                        <td> {{ $datum->investigator_name }}</td>
                                        <td> {{ $datum->branch_name }}</td>
                                        <td>{{ $datum->investigation_done }}</td>
                                        <td>{{ $datum->investigation_not_finished }}</td>
                                        <td>
                                            <a target="_blank"
                                               href="/laporanlainlain/pegawai1?ds={!! $dateStart->format('d-m-Y') !!}&de={!! $dateEnd->format('d-m-Y') !!}&s={!! $state !!}&d={!! $branch !!}&i={!! $datum->investigator_id !!}&g=w">
                                               {{ $datum->total }}
                                           </a>
                                        </td>
                                    </tr>
                                    @php
                                        $total += $datum->total;
                                    @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan='6'>Tiada Rekod</td>
                                </tr>
                                @endif
                                </tbody>
                                <tfooter>
                                    <tr>
                                       {{--  <th>
                                            <a target="_blank"
                                               href="{{ route('pegawai1',[$dateStart->format('d-m-Y'), $dateEnd->format('d-m-Y'), $branch, '0', $state]) }}"> {{$total}}</a>
                                        </th> --}}
                                    </tr>
                                </tfooter>
                        </table>
                          </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('script_datatable')
    <script type="text/javascript">
      $('#date .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
//        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
      })
 $(".select2").select2();
      $('#state').on('change', function (e) {
        var state_cd = $(this).val()
        $.get('/api/xref/branch?st=' + state_cd + '&ph=1&pt=SEMUA')
          .then(function (response) {
            $('select[name="branch"]').empty()
            $.each(response.data, function (key, value) {
              $('select[name="branch"]').append('<option value="' + key + '">' + value + '</option>')
            })
          }, function (err) {
            console.error(err)
          })

          $.get('/api/xref/investigator?st=' + state_cd)
          .then(function (response) {
            $('select[name="pegawai"]').empty()
            $.each(response.data, function (key, value) {
                console.log(key,value)
              $('select[name="pegawai"]').append('<option value="' + key + '">' + value + '</option>')
            })
          }, function (err) {
            console.error(err)
          })
      })
    </script>
@stop
