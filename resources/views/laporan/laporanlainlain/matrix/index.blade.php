@extends('layouts.main')
<?php

use App\Ref;
use App\Laporan\ReportLainlain;
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Laporan Matriks</h2>
                <div class="ibox-content">
                    <div class="row">
                        {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal','method' => 'GET', 'url'=>'laporanlainlain/matrix']) !!}
                        <div class="col-sm-12">
                            <div class="form-group" id="date">
                                {{ Form::label('ds', 'Tarikh :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('ds',date('d-m-Y', strtotime($ds)), ['class' => 'form-control input-sm', 'id' => 'ds']) }}
                                        <span class="input-group-addon">hingga</span>
                                        {{ Form::text('de', date('d-m-Y', strtotime($de)), ['class' => 'form-control input-sm', 'id' => 'de']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('ca', 'Kategori :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('ca', $category_list, null, ['class' => 'form-control input-sm', 'id' => 'ca']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('row', 'Baris :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('row', $matrix_type_list, null, ['class' => 'form-control input-sm', 'id' => 'row', 'onChange' => 'getNewSelect("row")']) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12 ">
                                    <select name="row_data[]" class="form-control dual_select" multiple>
                                        {{-- @foreach ($lists as $key => $list) --}}
                                        {{-- <option value="{{ $key }}">{{ $list }}</option> --}}
                                        {{-- @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('col', 'Lajur :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('col', $matrix_type_list, null, ['class' => 'form-control input-sm', 'id' => 'col', 'onChange' => 'getNewSelect("col")']) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12 ">
                                    <select name="col_data[]" class="form-control dual_select" multiple>
                                        {{-- @foreach ($lists as $key => $list) --}}
                                        {{-- <option value="{{ $key }}">{{ $list }}</option> --}}
                                        {{-- @endforeach --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                                {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                                {{ link_to('laporanlainlain/matrix', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                            </div>
                        </div>
                        @if($is_search)
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    <a target="_blank" href="{!! $uri !!}g=e" class="btn btn-success btn-sm"><i
                                                class="fa fa-file-excel-o"></i> Muat Turun Excel</a>
                                    <a target="_blank" href="{!! $uri !!}g=p" class="btn btn-success btn-sm"><i
                                                class="fa fa-file-pdf-o"></i> Muat Turun PDF</a>
                                </div>
                            </div>
                        @endif
                        {!! Form::close() !!}
                    </div>
                    @if($is_search)
                        <div class="table-responsive">
                            <table id="state-table"
                                   class="table table-striped table-bordered table-hover dataTables-example"
                                   style="width: 100%">
                                <thead>
                                <h2>
                                    <div style="text-align: center;">LAPORAN MATRIKS BAGI {{ $de->format('d-m-Y') }}
                                        HINGGA {{ $de->format('d-m-Y') }}
                                    </div>
                                </h2>
                                <tr>
                                    <th>No.</th>
                                    @foreach ($col_data as $data)
                                        <th>{{$col_list[$data]}}</th>
                                    @endforeach
                                    <th> Jumlah</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($row_data as $datum)
                                    <tr>
                                        <td>{{$row_list[$datum]}}</td>
                                        @foreach ($col_data as $data)
                                            <td>
                                                <a target="_blank"
                                                   href="{!! $uri_dd.'row_datum%5B%5D='.$datum.'&col_datum%5B%5D='.$data !!}">{{$data_final[$datum][$data]}}</a>
                                            </td>
                                        @endforeach
                                        <?php $col_array = http_build_query(array('col_datum' => $col_data)); ?>
                                        <td>
                                            <a target="_blank"
                                               href="{!! $uri_dd.$col_array.'&row_datum%5B%5D='.$datum !!}">{{$data_final[$datum]['total']}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($col_data as $data)
                                            <?php $row_array = http_build_query(array('row_datum' => $row_data)); ?>
                                            <td><a target="_blank"
                                                   href="{!! $uri_dd.$row_array.'&col_datum%5B%5D='.$data!!}">{{$data_final['total'][$data]}}</a>
                                            </td>
                                        @endforeach
                                        <td><a target="_blank"
                                               href="{!! $uri_dd.$row_array.'&'.$col_array !!}">{{$data_final['total']['total']}}</a></td>
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
      var dualRow
      var dualCol
      $(function () {
        $('#CA_AGAINST_STATECD').on('change', function (e) {
          var CA_AGAINST_STATECD = $(this).val()
          $.ajax({
            type: 'GET',
            url: "{{ url('laporanlainlain/getcawangan') }}" + '/' + CA_AGAINST_STATECD,
            dataType: 'json',
            success: function (data) {
              $('select[name="CA_BRNCD[]"]').empty()
              $.each(data, function (key, value) {
                $('select[name="CA_BRNCD[]"]').append('<option value="' + key + '">' + value + '</option>')
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
        // dualRow = $('#dual_select_row').bootstrapDualListbox({
        //   selectorMinimalHeight: 260,
        //   showFilterInputs: false,
        //   infoText: '',
        //   infoTextEmpty: 'Sila Pilih Pilihan Baris',
        //   nonSelectedFilter: 'xxx'
        // })
        // dualCol = $('#dual_select_col').bootstrapDualListbox({
        //   selectorMinimalHeight: 260,
        //   showFilterInputs: false,
        //   infoText: '',
        //   infoTextEmpty: 'Sila Pilih Pilihan Baris',
        // })
      })
      $('#date .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
//        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
      })

      function getNewSelect(type) {
        var value = $('#' + type).val()
        var url_request = ''
        // get url
        if (value === 'CA_BRNCD') {
          url_request = 'branch'
        } else if (value === 'CA_INVSTS') {
          url_request = 'status'
        } else if (value === 'CA_RCVTYP') {
          url_request = 'source'
        } else if (value === 'CA_AGAINST_STATECD' || value === 'BR_STATECD') {
          url_request = 'state'
        } else if (value === 'CA_SEXCD') {
          url_request = 'gender'
        } else {
          url_request = ''
        }

        // ajax request to fetch data list
        if (url_request !== '') {
          $.get('/api/xref/' + url_request)
            .then(function (response) {
              // console.log('response', response);
              $('select[name="' + type + '_data[]"]').empty()
              var data = response.data
              // console.log($('select[name="'+value+'_data[]"]'))
              $.each(data, function (key, value) {
                $('select[name="' + type + '_data[]"]').append('<option value="' + key + '">' + value + '</option>')
              })
            }, function (err) {
              console.error('Err:'.err)
              alert('Whoops! Our fisherman smell something fishy in the air. Please contact system admin')
            })
            .done(function () {
              // console.log('all is done then refresh the dual select')
              $('.dual_select').bootstrapDualListbox('refresh')
            })
        } else {
          // clean all those selection
          $('select[name="' + type + '_data[]"]').empty()
          $('.dual_select').bootstrapDualListbox('refresh')
        }
      }
    </script>
@stop