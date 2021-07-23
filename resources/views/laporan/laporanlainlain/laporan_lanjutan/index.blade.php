@extends('layouts.main')
<?php

use App\Ref;
use App\Laporan\ReportLainlain;
?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>LAPORAN LANJUTAN ADUAN</h2>
                <div class="ibox-content">
                    <div class="row">
                        {!! Form::open(['url' => 'laporanlainlain/laporanlanjutan', 'id' => 'search-form', 'class'=>'form-horizontal', 'method' => 'GET']) !!}
                        <div class="col-sm-12">

                            <div class="form-group" id="data_5">
                                {{ Form::label('CA_RCVDT', 'Tarikh Dari', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('dateStart',date('d-m-Y', strtotime($dateStart)), ['class' => 'form-control input-sm', 'id' => 'dateStart']) }}
                                        <span class="input-group-addon">hingga</span>
                                        {{ Form::text('dateEnd', date('d-m-Y', strtotime($dateEnd)), ['class' => 'form-control input-sm', 'id' => 'dateEnd']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm12 ">
                                <div class="form-group">
                                    {{ Form::label('CA_AGAINST_STATECD','Negeri', ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('CA_AGAINST_STATECD', ReportLainlain::GetRef('17', 'semua'),null, ['class' => 'form-control input-sm', 'id'=>'CA_AGAINST_STATECD']) }}
                                    </div>
                                </div>
                                <select name="branch[]" class="form-control dual_select" multiple>
                                    @foreach ($branchList as $key => $cat)
                                        @if(in_array($key, $branch))
                                            <option value="{{ $key }}" selected="selected">{{ $cat }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ $cat }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <br/>
                                <div class="form-group">
                                    {{ Form::label('departmentGroup', 'Bahagian', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('departmentGroup', ReportLainlain::GetRef('315', 'semua'), null, ['class' => 'form-control input-sm', 'id' => 'departmentGroup']) }}
                                    </div>
                                </div>
                                <select name="department[]" class="form-control dual_select" multiple>
                                    @foreach ($departmentList as $key => $cat)
                                        @if(in_array($key, $department))
                                            <option value="{{ $key }}" selected="selected">{{ $cat }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ $cat }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="clearfix">
                                <br>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                                {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name' => 'gen', 'value' => 'web']) }}
                                <a class="btn btn-default btn-sm" href="{{ url('laporanlainlain/laporanlanjutan')}}">Semula</a>
                            </div>
                        </div>
                        @if($search)
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'excel']) }}
                                    {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'gen', 'value' => 'pdf', 'formtarget' => '_blank']) }}
                                </div>
                            </div>
                        @endif
                        {!! Form::close() !!}
                    </div>
                    @if($search)
                        <div class="row">
                            <div style="text-align: center;">
                                <h3>
                                    <div style="text-align: center;">LAPORAN LANJUTAN ADUAN BAGI
                                        TEMPOH {{ date('d-m-Y', strtotime($dateStart)) }}
                                        HINGGA {{ date('d-m-Y', strtotime($dateEnd)) }}<br/>
                                        @if ($departmentGroup != '')
                                            {{ Ref::GetDescr('315',$departmentGroup,'ms') }}
                                        @else
                                            SEMUA BAHAGIAN
                                        @endif <br>
                                    </div>
                                </h3>
                                @foreach ($reportFinal as $rf_cawangan => $rf_kategori_data)
                                    @foreach($rf_kategori_data as $rf_kategori => $rf_data)
                                        <p style="text-align: left">Cawangan: {{ $branchList[$rf_cawangan] }}</p>
                                        <p style="text-align: left">Kategori
                                            Aduan: {{ $departmentList[$rf_kategori] }}</p>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover dataTables-example"
                                                   style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th width="1%">Bil.</th>
                                                    <th width="6%">Tarikh Diterima</th>
                                                    <th width="5%">No. Aduan</th>
                                                    <th width="5%">Sumber</th>
                                                    <th width="5%">Peg. Penyiasat</th>
                                                    <th width="5%">Nama Pengadu</th>
                                                    <th width="5%">Alamat Pengadu</th>
                                                    <th width="5%">Nama Diadu</th>
                                                    <th width="10%">Aduan</th>
                                                    <th width="5%">Hasil Siatasan</th>
                                                    <th width="1%">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($rf_data as $key => $datum)
                                                    <tr>
                                                        <td style="vertical-align: top;">{{$key+1}}</td>
                                                        <td style="vertical-align: top;">{{$datum->ca_rcvdt}}</td>
                                                        <td style="vertical-align: top;">{{$datum->ca_caseid}}</td>
                                                        <td style="vertical-align: top;">{{trim($datum->ca_rcvtyp) != '' ? $sourceList[$datum->ca_rcvtyp] : '-'}}</td>
                                                        <td style="vertical-align: top;">{{$datum->name}}</td>
                                                        <td style="vertical-align: top;">{{$datum->ca_name}}</td>
                                                        <td style="vertical-align: top;">{{$datum->ca_addr}} {{$datum->ca_poscd}} {{$datum->ca_distcd}} {{$datum->ca_statecd}}</td>
                                                        <td style="vertical-align: top;">{{$datum->ca_againstnm}}</td>
                                                        <td style="vertical-align: top;">{{$datum->ca_result}}</td>
                                                        <td style="vertical-align: top;">{{$datum->ca_result}}</td>
                                                        <td style="vertical-align: top;">{{$datum->ca_casests}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
@section('script_datatable')
    <script type="text/javascript">
      $(function () {
        $('#CA_AGAINST_STATECD').on('change', function (e) {
          var CA_AGAINST_STATECD = $(this).val()
          $.ajax({
            type: 'GET',
            url: "{{ url('laporanlainlain/getcawangan') }}" + '/' + CA_AGAINST_STATECD + '/' + 0,
            dataType: 'json',
            success: function (data) {
              $('select[name="branch[]"]').empty()
              $.each(data, function (key, value) {
                $('select[name="branch[]"]').append('<option value="' + key + '">' + value + '</option>')
                $('.dual_select').bootstrapDualListbox('refresh')
              })
            }
          })
        })
        $('#departmentGroup').on('change', function (e) {
          var departmentGroup = $(this).val()
          $.ajax({
            type: 'GET',
            url: "{{ url('laporanlainlain/getcatlist') }}" + '/' + departmentGroup,
            dataType: 'json',
            success: function (data) {
              $('select[name="department[]"]').empty()
              $.each(data, function (key, value) {
                $('select[name="department[]"]').append('<option value="' + key + '">' + value + '</option>')
                $('.dual_select').bootstrapDualListbox('refresh')
              })
            }
          })
        })
        $('.dual_select').bootstrapDualListbox({
          selectorMinimalHeight: 240,
          showFilterInputs: false,
          infoText: '',
          infoTextEmpty: ''
        })
        $('#data_5 .input-daterange').datepicker({
          format: 'dd-mm-yyyy',
          keyboardNavigation: false,
          forceParse: false,
          autoclose: true
        })
      })
    </script>
@stop