@extends('layouts.main')
<?php

use App\Ref;
use App\Laporan\ReportLainlain;
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Laporan Status Aduan</h2>
                <div class="ibox-content">
                    <div class="row">
                        {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal','method' => 'GET', 'url'=>'laporanlainlain/laporan_negeri_status']) !!}
                        <div class="col-sm-12">
                            <div class="form-group" id="date">
                                {{ Form::label('CA_RCVDT_dri', 'Tarikh :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('CA_RCVDT_dri',date('d-m-Y', strtotime($CA_RCVDT_start)), ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_dri']) }}
                                        <span class="input-group-addon">hingga</span>
                                        {{ Form::text('CA_RCVDT_lst', date('d-m-Y', strtotime($CA_RCVDT_end)), ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_lst']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('CA_DEPTCD', 'Bahagian :', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('CA_DEPTCD', $departmentList, null, ['class' => 'form-control input-sm', 'id' => 'CA_DEPTCD']) }}
                                </div>
                            </div>
                            <div class="col-sm-6 ">
                                <div class="form-group">
                                    <div class="col-sm-12 ">
                                        <select name="CA_CASESTS[]" class="form-control dual_select" multiple>
                                            @foreach ($caseStatusList as $k => $v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 ">
                                <div class="form-group">
                                    <div class="col-sm-12 ">
                                        <select name="BR_STATECD[]" class="form-control dual_select" multiple>
                                            @foreach ($stateList as $kk => $vv)
                                                <option value="{{ $kk }}">{{ $vv }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 col-sm-push-8">
                                <div class="form-group">
                                    {{ Form::label('CA_BRNCD', 'Cawangan', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" name="CA_BRNCD"
                                               value="0" {{ ($CA_BRNCD != ''? ($CA_BRNCD == '0'? 'checked':''):'checked') }}>
                                        <label for="CA_BRNCD"> Negeri </label>
                                    </div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" name="CA_BRNCD"
                                               value="1" {{ ($CA_BRNCD != ''? ($CA_BRNCD == '1'? 'checked':''):'') }}>
                                        <label for="CA_BRNCD"> Cawangan </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                                {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm',  'name'=>'cari']) }}
                                {{ link_to('laporanlainlain/laporan_negeri_status', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                            </div>
                        </div>
                        @if($search)
                            <div class="col-sm-12">
                                <div class="form-group" align="center">
                                    <a target="_blank" href="{!! $uri.'&gen=e' !!}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Muat Turun Excel</a>
                                    <a target="_blank" href="{!! $uri.'&gen=p' !!}" class="btn btn-success btn-sm"><i class="fa fa-file-pdf-o"></i> Muat Turun PDF</a>
                                    {{--{{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}--}}
                                    {{--{{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}--}}
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
                                <h2>
                                    <div style="text-align: center;">
                                        LAPORAN STATUS ADUAN MENGIKUT NEGERI
                                        BAGI {{ date('d-m-Y', strtotime($CA_RCVDT_start)) }}
                                        HINGGA {{ date('d-m-Y', strtotime($CA_RCVDT_end)) }} <br/>
                                        {{ $CA_BRNCD != 0 ? Ref::GetDescr('315',$CA_BRNCD,'ms') : 'SEMUA BAHAGIAN' }}
                                    </div>
                                </h2>
                                <tr>
                                    <th rowspan="2">Bil.</th>
                                    <th rowspan="2"> Negeri</th>
                                    <th colspan="19" style="text-align: center">Jumlah Aduan</th>
                                </tr>
                                <tr>
                                    <th> Diterima</th>
                                    <th> Belum Bermula</th>
                                    <th> %</th>
                                    <th> Dalam Siasatan</th>
                                    <th> %</th>
                                    <th> Diselesaikan</th>
                                    <th> %</th>
                                    <th> Agensi Lain</th>
                                    <th> %</th>
                                    <th> Tribunal</th>
                                    <th> %</th>
                                    <th> Pertanyaan</th>
                                    <th> %</th>
                                    <th> Maklumat Tidak Lengkap</th>
                                    <th> %</th>
                                    <th> Luar Bidang Kuasa</th>
                                    <th> %</th>
                                    <th> Ditutup</th>
                                    <th> %</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($BR_STATECD as $state)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td> {{ $stateList[$state] }}</td>
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['TERIMA']}}</a></td>
                                        @if($status['TERIMA'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['TERIMA']}}</a></td>
                                            <td>{{$reportFinal[$state]['TERIMA_pct']}}</td>
                                        @endif
                                        @if($status['DALAMSIASATAN'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['DALAMSIASATAN']}}</a></td>
                                            <td>{{$reportFinal[$state]['DALAMSIASATAN_pct']}}</td>
                                        @endif
                                        @if($status['SELESAI'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['SELESAI']}}</a></td>
                                            <td>{{$reportFinal[$state]['SELESAI_pct']}}</td>
                                        @endif
                                        @if($status['AGENSILAIN'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['AGENSILAIN']}}</a></td>
                                            <td>{{$reportFinal[$state]['AGENSILAIN_pct']}}</td>
                                        @endif
                                        @if($status['TRIBUNAL'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['TRIBUNAL']}}</a></td>
                                            <td>{{$reportFinal[$state]['TRIBUNAL_pct']}}</td>
                                        @endif
                                        @if($status['PERTANYAAN'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['PERTANYAAN']}}</a></td>
                                            <td>{{$reportFinal[$state]['PERTANYAAN_pct']}}</td>
                                        @endif
                                        @if($status['MKLUMATXLENGKAP'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['MKLUMATXLENGKAP']}}</a></td>
                                            <td>{{$reportFinal[$state]['MKLUMATXLENGKAP_pct']}}</td>
                                        @endif
                                        @if($status['LUARBIDANG'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['LUARBIDANG']}}</a></td>
                                            <td>{{$reportFinal[$state]['LUARBIDANG_pct']}}</td>
                                        @endif
                                        @if($status['TUTUP'])
                                        <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$reportFinal[$state]['TUTUP']}}</a></td>
                                            <td>{{$reportFinal[$state]['TUTUP_pct']}}</td>
                                        @endif
                                    </tr>
                                    @if($CA_BRNCD == 1)
                                        @foreach($reportFinal[$state]['branch'] as $key => $branch)
                                            <tr>
                                                <td></td>
                                                <td>{{ $branchList[$key] }}</td>
                                                <td>{{$branch['TERIMA']}}</td>
                                                @if($status['TERIMA'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['TERIMA']}}</a></td>
                                                    <td>{{$branch['TERIMA_pct']}}</td>
                                                @endif
                                                @if($status['DALAMSIASATAN'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['DALAMSIASATAN']}}</a></td>
                                                    <td>{{$branch['DALAMSIASATAN_pct']}}</td>
                                                @endif
                                                @if($status['SELESAI'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['SELESAI']}}</a></td>
                                                    <td>{{$branch['SELESAI_pct']}}</td>
                                                @endif
                                                @if($status['AGENSILAIN'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['AGENSILAIN']}}</a></td>
                                                    <td>{{$branch['AGENSILAIN_pct']}}</td>
                                                @endif
                                                @if($status['TRIBUNAL'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['TRIBUNAL']}}</a></td>
                                                    <td>{{$branch['TRIBUNAL_pct']}}</td>
                                                @endif
                                                @if($status['PERTANYAAN'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['PERTANYAAN']}}</a></td>
                                                    <td>{{$branch['PERTANYAAN_pct']}}</td>
                                                @endif
                                                @if($status['MKLUMATXLENGKAP'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['MKLUMATXLENGKAP']}}</a></td>
                                                    <td>{{$branch['MKLUMATXLENGKAP_pct']}}</td>
                                                @endif
                                                @if($status['LUARBIDANG'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['LUARBIDANG']}}</a></td>
                                                    <td>{{$branch['LUARBIDANG_pct']}}</td>
                                                @endif
                                                @if($status['TUTUP'])
                                                <td><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{$branch['TUTUP']}}</a></td>
                                                    <td>{{$branch['TUTUP_pct']}}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Jumlah</th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['TERIMA'] }} </a></th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['TERIMA'] }} </a></th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['DALAMSIASATAN'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['SELESAI'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['AGENSILAIN'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['TRIBUNAL'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['PERTANYAAN'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['MKLUMATXLENGKAP'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['LUARBIDANG'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank" href="{{-- route('laporan_negeri_status1',[]) --}}">{{ $reportFinal['total']['TUTUP'] }} </a></th>
                                    <th>100%
                                    </th>

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