@extends('layouts.main')
<?php

use App\Ref;
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
                <h2>Laporan Status Aduan</h2>
                <div class="ibox-content">
                    <div class="row">
                        {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal','method' => 'GET', 'url'=>'laporanlainlain/laporan_negeri_status']) !!}
                        <div class="col-sm-12">
                            <div class="form-group" id="date">
                                {{ Form::label('ds', 'Tarikh :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('ds',date('d-m-Y', strtotime($ds)), [
                                            'class' => 'form-control input-sm', 
                                            'id' => 'ds',
                                            'readonly' => true,
                                            'placeholder' => 'HH-BB-TTTT'
                                        ]) }}
                                        <span class="input-group-addon">hingga</span>
                                        {{ Form::text('de', date('d-m-Y', strtotime($de)), [
                                            'class' => 'form-control input-sm', 
                                            'id' => 'de',
                                            'readonly' => true,
                                            'placeholder' => 'HH-BB-TTTT'
                                        ]) }}
                                    </div>
                                </div>
                            </div>
                            <!-- div class="form-group" -->
                                {{-- Form::label('dp', 'Bahagian :', ['class' => 'col-sm-4 control-label']) --}}
                                <!-- div class="col-sm-6" -->
                                    {{-- Form::select('dp', $department_list, null, ['class' => 'form-control input-sm', 'id' => 'dp']) --}}
                                <!-- /div -->
                            <!-- /div -->
                            <div class="col-sm-6 ">
                                <div class="form-group">
                                    <div class="col-sm-12 ">
                                        <select name="cs[]" class="form-control dual_select" multiple>
                                            @foreach ($case_status_list as $k => $v)
                                                @if(in_array($k, $cs))
                                                    <option value="{{ $k }}" selected="selected">{{ $v }}</option>
                                                @else
                                                    <option value="{{ $k }}">{{ $v }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 ">
                                <div class="form-group">
                                    <div class="col-sm-12 ">
                                        <select name="st[]" class="form-control dual_select" multiple>
                                            @foreach ($state_list as $kk => $vv)
                                                @if(in_array($kk, $st))
                                                    <option value="{{ $kk }}" selected="selected">{{ $vv }}</option>
                                                @else
                                                    <option value="{{ $kk }}">{{ $vv }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 col-sm-push-7">
                                <div class="form-group">
                                    {{ Form::label('br', 'Paparan', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" name="br"
                                               value="0" {{ ($br != ''? ($br == '0'? 'checked':''):'checked') }}>
                                        <label for="br"> Negeri </label>
                                    </div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" name="br"
                                               value="1" {{ ($br != ''? ($br == '1'? 'checked':''):'') }}>
                                        <label for="br"> Cawangan / Bahagian</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                                {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                                {{ link_to('laporanlainlain/laporan_negeri_status', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                            </div>
                        </div>
                        @if($is_search)
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    <!-- <a target="_blank" href="{!! $uri_gen.'&gen=e' !!}"  -->
                                        <!-- class="btn btn-success btn-sm"> -->
                                        <!-- <i class="fa fa-file-excel-o"></i> Muat Turun Excel -->
                                    <!-- </a> -->
                                    <a target="_blank" href="{!! $uri_gen.'&gen=xls' !!}" 
                                        class="btn btn-success btn-sm">
                                        <i class="fa fa-file-excel-o"></i> Muat Turun Excel
                                    </a>
                                    <a target="_blank" href="{!! $uri_gen.'&gen=p' !!}"
                                       class="btn btn-success btn-sm"><i class="fa fa-file-pdf-o"></i> Muat Turun
                                        PDF</a>
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
                                    <div style="text-align: center;">
                                        LAPORAN STATUS ADUAN MENGIKUT NEGERI
                                        BAGI {{ date('d-m-Y', strtotime($ds)) }}
                                        HINGGA {{ date('d-m-Y', strtotime($de)) }} <br/>
                                        {{-- $br != 0 ? Ref::GetDescr('315',$br,'ms') : 'SEMUA BAHAGIAN' --}}
                                    </div>
                                </h2>
                                <tr>
                                    <th rowspan="2">Bil.</th>
                                    <th rowspan="2"> Negeri</th>
                                    <th colspan="19" style="text-align: center">Jumlah Aduan</th>
                                </tr>
                                <tr>
                                    <th> Diterima</th>
                                    @if($status['TERIMA'])
                                        <th> Aduan Baru</th>
                                        <th> %</th>
                                    @endif
                                    @if($status['DALAMSIASATAN'])
                                        <th> Dalam Siasatan</th>
                                        <th> %</th>
                                    @endif
                                    @if($status['SELESAI'])
                                        <th> Diselesaikan</th>
                                        <th> %</th>
                                    @endif
                                    @if($status['AGENSILAIN'])
                                        <th> Agensi KPDNHEP</th>
                                        <th> %</th>
                                    @endif
                                    @if($status['TRIBUNAL'])
                                        <th> Tribunal</th>
                                        <th> %</th>
                                    @endif
                                    @if($status['PERTANYAAN'])
                                        <th> Pertanyaan</th>
                                        <th> %</th>
                                    @endif
                                    @if($status['MKLUMATXLENGKAP'])
                                        <th> Maklumat Tidak Lengkap</th>
                                        <th> %</th>
                                    @endif
                                    @if($status['LUARBIDANG'])
                                        <th> Luar Bidang Kuasa</th>
                                        <th> %</th>
                                    @endif
                                    @if($status['TUTUP'])
                                        <th> Ditutup</th>
                                        <th> %</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @if(count($st) > 0)
                                    @foreach($st as $state)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td> {{ $state_list[$state] }}</td>
                                            <td><a target="_blank"
                                                   href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=total' !!}">{{$data_final[$state]['total']}}</a>
                                            </td>
                                            @if($status['TERIMA'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=TERIMA' !!}">{{$data_final[$state]['TERIMA']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['TERIMA_pct']}}</td>
                                            @endif
                                            @if($status['DALAMSIASATAN'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=DALAMSIASATAN' !!}">{{$data_final[$state]['DALAMSIASATAN']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['DALAMSIASATAN_pct']}}</td>
                                            @endif
                                            @if($status['SELESAI'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=SELESAI' !!}">{{$data_final[$state]['SELESAI']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['SELESAI_pct']}}</td>
                                            @endif
                                            @if($status['AGENSILAIN'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=AGENSILAIN' !!}">{{$data_final[$state]['AGENSILAIN']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['AGENSILAIN_pct']}}</td>
                                            @endif
                                            @if($status['TRIBUNAL'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=TRIBUNAL' !!}">{{$data_final[$state]['TRIBUNAL']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['TRIBUNAL_pct']}}</td>
                                            @endif
                                            @if($status['PERTANYAAN'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=PERTANYAAN' !!}">{{$data_final[$state]['PERTANYAAN']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['PERTANYAAN_pct']}}</td>
                                            @endif
                                            @if($status['MKLUMATXLENGKAP'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=MKLUMATXLENGKAP' !!}">{{$data_final[$state]['MKLUMATXLENGKAP']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['MKLUMATXLENGKAP_pct']}}</td>
                                            @endif
                                            @if($status['LUARBIDANG'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=LUARBIDANG' !!}">{{$data_final[$state]['LUARBIDANG']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['LUARBIDANG_pct']}}</td>
                                            @endif
                                            @if($status['TUTUP'])
                                                <td><a target="_blank"
                                                       href="{!! $uri_dd.'&st_datum='.$state.'&cs_datum=TUTUP' !!}">{{$data_final[$state]['TUTUP']}}</a>
                                                </td>
                                                <td>{{$data_final[$state]['TUTUP_pct']}}</td>
                                            @endif
                                        </tr>
                                        @if($br == 1)
                                            @foreach($data_final[$state]['branch'] as $key => $branch)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $branch_list[$key] }}</td>
                                                    <td>
                                                        <a target="_blank" href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=total' !!}">
                                                            {{$branch['total']}}
                                                        </a>
                                                    </td>
                                                    @if($status['TERIMA'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=TERIMA' !!}">{{$branch['TERIMA']}}</a>
                                                        </td>
                                                        <td>{{$branch['TERIMA_pct']}}</td>
                                                    @endif
                                                    @if($status['DALAMSIASATAN'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=DALAMSIASATAN' !!}">{{$branch['DALAMSIASATAN']}}</a>
                                                        </td>
                                                        <td>{{$branch['DALAMSIASATAN_pct']}}</td>
                                                    @endif
                                                    @if($status['SELESAI'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=SELESAI' !!}">{{$branch['SELESAI']}}</a>
                                                        </td>
                                                        <td>{{$branch['SELESAI_pct']}}</td>
                                                    @endif
                                                    @if($status['AGENSILAIN'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=AGENSILAIN' !!}">{{$branch['AGENSILAIN']}}</a>
                                                        </td>
                                                        <td>{{$branch['AGENSILAIN_pct']}}</td>
                                                    @endif
                                                    @if($status['TRIBUNAL'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=TRIBUNAL' !!}">{{$branch['TRIBUNAL']}}</a>
                                                        </td>
                                                        <td>{{$branch['TRIBUNAL_pct']}}</td>
                                                    @endif
                                                    @if($status['PERTANYAAN'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=PERTANYAAN' !!}">{{$branch['PERTANYAAN']}}</a>
                                                        </td>
                                                        <td>{{$branch['PERTANYAAN_pct']}}</td>
                                                    @endif
                                                    @if($status['MKLUMATXLENGKAP'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=MKLUMATXLENGKAP' !!}">{{$branch['MKLUMATXLENGKAP']}}</a>
                                                        </td>
                                                        <td>{{$branch['MKLUMATXLENGKAP_pct']}}</td>
                                                    @endif
                                                    @if($status['LUARBIDANG'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=LUARBIDANG' !!}">{{$branch['LUARBIDANG']}}</a>
                                                        </td>
                                                        <td>{{$branch['LUARBIDANG_pct']}}</td>
                                                    @endif
                                                    @if($status['TUTUP'])
                                                        <td><a target="_blank"
                                                               href="{!! $uri_dd.'&st_datum='.$state.'&br_datum='.$key.'&cs_datum=TUTUP' !!}">{{$branch['TUTUP']}}</a>
                                                        </td>
                                                        <td>{{$branch['TUTUP_pct']}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Jumlah</th>
                                    <th><a target="_blank"
                                           href="{!! $uri_dd.'&cs_datum=' !!}">{{ $data_final['total']['total'] }} </a>
                                    </th>
                                    @if($status['TERIMA'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&cs_datum=TERIMA' !!}">{{ $data_final['total']['TERIMA'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                    @if($status['DALAMSIASATAN'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&cs_datum=DALAMSIASATAN' !!}">{{ $data_final['total']['DALAMSIASATAN'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                    @if($status['SELESAI'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&cs_datum=SELESAI' !!}">{{ $data_final['total']['SELESAI'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                    @if($status['AGENSILAIN'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&cs_datum=AGENSILAIN' !!}">{{ $data_final['total']['AGENSILAIN'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                    @if($status['TRIBUNAL'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&cs_datum=TRIBUNAL' !!}">{{ $data_final['total']['TRIBUNAL'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                    @if($status['PERTANYAAN'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&cs_datum=PERTANYAAN' !!}">{{ $data_final['total']['PERTANYAAN'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                    @if($status['MKLUMATXLENGKAP'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&cs_datum=MKLUMATXLENGKAP' !!}">{{ $data_final['total']['MKLUMATXLENGKAP'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                    @if($status['LUARBIDANG'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&cs_datum=LUARBIDANG' !!}">{{ $data_final['total']['LUARBIDANG'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                    @if($status['TUTUP'])
                                        <th><a target="_blank"
                                            href="{!! $uri_dd.'&st_datum=&cs_datum=TUTUP' !!}">{{ $data_final['total']['TUTUP'] }} </a>
                                        </th>
                                        <th>100%
                                        </th>
                                    @endif
                                </tr>
                                </tfoot>
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
      $('.dual_select').bootstrapDualListbox({
        selectorMinimalHeight: 260,
        showFilterInputs: false,
        infoText: '',
        infoTextEmpty: ''
      })
    </script>
@stop