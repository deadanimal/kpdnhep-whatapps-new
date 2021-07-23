@extends('layouts.main')
<?php
$i = 1;
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Laporan Akta</h2>
                <div class="ibox-content">
                    <div class="row">
                        {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal','method' => 'GET', 'url'=>'laporanlainlain/akta']) !!}
                        <div class="col-sm-12">
                            <div class="form-group" id="date">
                                {{ Form::label('ds', 'Tarikh :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('ds',$ds->format('d-m-Y'), ['class' => 'form-control input-sm', 'id' => 'ds']) }}
                                        <span class="input-group-addon">hingga</span>
                                        {{ Form::text('de', $de->format('d-m-Y'), ['class' => 'form-control input-sm', 'id' => 'de']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('dp', 'Bahagian :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('dp', $department_list, null, ['class' => 'form-control input-sm', 'id' => 'dp']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 ">
                                    <select name="st[]" class="form-control dual_select" multiple>
                                        @foreach ($state_list as $key => $state)
                                            @if(in_array($key, $st))
                                                <option value="{{ $key }}" selected="selected">{{ $state }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $state }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('is_branch', 'Pilihan Paparan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-6">
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" name="is_branch"
                                               value="0" {{ ($is_branch != ''? ($is_branch == '0'? 'checked':''):'checked') }}>
                                        <label for="is_branch"> Negeri </label>
                                    </div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" name="is_branch"
                                               value="1" {{ ($is_branch != ''? ($is_branch == '1'? 'checked':''):'') }}>
                                        <label for="is_branch"> Negeri & Cawangan </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                                    {{ link_to('laporanlainlain/akta', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <h2 style="text-align: center;">
                                        LAPORAN AKTA ADUAN MENGIKUT NEGERI BAGI {{ $ds->format('d-m-Y') }}
                                        HINGGA {{ $de->format('d-m-Y') }} <br/>
                                        {{ $dp_desc }}
                                    </h2>
                                    <table id="state-table"
                                           class="table table-striped table-bordered table-hover dataTables-example"
                                           style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Negeri</th>
                                            <th>Akta Kawalan Bekalan</th>
                                            <th>Akta Perihal Dagangan</th>
                                            <th>Akta Kawalan Harga & Pencatutan</th>
                                            <th>Akta Perlindungan Pengguna</th>
                                            <th>Akta Cakera Optik</th>
                                            <th>Akta Timbang dan Sukat</th>
                                            <th>Akta Jualan Langsung</th>
                                            <th>Akta Sewa Beli</th>
                                            <th>Akta Hakcipta</th>
                                            <th>Jumlah</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($st as $state)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td><b>{{ $state_list[$state] }}</b></td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=AKB' }}">{{ $data_final[$state]['AKB'] }}</a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=APD' }}">{{ $data_final[$state]['APD'] }}</a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=AKH' }}">{{ $data_final[$state]['AKH'] }}</a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=APP' }}"> {{ $data_final[$state]['APP'] }}</a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=ACO' }}"> {{ $data_final[$state]['ACO'] }} </a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=ATS' }}"> {{ $data_final[$state]['ATS'] }} </a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=AJL' }}"> {{ $data_final[$state]['AJL'] }}</a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=ASB' }}"> {{ $data_final[$state]['ASB'] }}</a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd.'st='.$state.'&ac=AHC' }}"> {{ $data_final[$state]['AHC'] }}</a>
                                                </td>
                                                <td><a target="_blank"
                                                       href="{{ $uri_dd .'st='.$state }}"> {{ $data_final[$state]['total'] }}</a>
                                                </td>
                                            </tr>
                                            @if($is_branch == 1)
                                                @foreach($data_final[$state]['branch'] as $key => $stateBranch)
                                                    <tr>
                                                        <td></td>
                                                        <td>{{ $branch_list[$key] }}</td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=AKB&br='.$key }}">{{ $data_final[$state]['branch'][$key]['AKB'] }}</a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=APD&br='.$key }}">{{ $data_final[$state]['branch'][$key]['APD'] }}</a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=AKH&br='.$key }}"> {{ $data_final[$state]['branch'][$key]['AKH'] }}</a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=APP&br='.$key }}"> {{ $data_final[$state]['branch'][$key]['APP'] }}</a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=ACO&br='.$key }}"> {{ $data_final[$state]['branch'][$key]['ACO'] }} </a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=ATS&br='.$key }}"> {{ $data_final[$state]['branch'][$key]['ATS'] }} </a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=AJL&br='.$key }}"> {{ $data_final[$state]['branch'][$key]['AJL'] }} </a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=ASB&br='.$key }}"> {{ $data_final[$state]['branch'][$key]['ASB'] }} </a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'ac=AHC&br='.$key }}"> {{ $data_final[$state]['branch'][$key]['AHC'] }} </a>
                                                        </td>
                                                        <td><a target="_blank"
                                                               href="{{ $uri_dd.'br='.$key }}"> {{ $data_final[$state]['branch'][$key]['total'] }} </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Jumlah</th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=AKB'}}">{{ $data_final['total']['AKB'] }} </a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=APD'}}">{{ $data_final['total']['APD'] }} </a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=AKH'}}">{{ $data_final['total']['AKH'] }} </a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=APP'}}"> {{ $data_final['total']['APP'] }} </a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=ACO'}}"> {{ $data_final['total']['ACO'] }} </a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=ATS'}}"> {{ $data_final['total']['ATS'] }} </a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=AJL'}}">{{ $data_final['total']['AJL'] }} </a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=ASB'}}"> {{ $data_final['total']['ASB'] }} </a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd.'ac=AHC'}}"> {{ $data_final['total']['AHC'] }}</a>
                                            </th>
                                            <th><a target="_blank"
                                                   href="{{ $uri_dd}}">{{ $data_final['total']['total'] }}</a>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
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
      })
      $('#date .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
//        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
      })
    </script>
@stop