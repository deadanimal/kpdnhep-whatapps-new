
<?php

use App\Ref;
use App\Laporan\ReportLainlain;
?>


<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="state-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%; font-size: 10px;" border="1">
                        <thead>
                        <h2><center>LAPORAN STATUS ADUAN MENGIKUT NEGERI BAGI {{ date('d-m-Y', strtotime($CA_RCVDT_dri)) }} HINGGA {{ date('d-m-Y', strtotime($CA_RCVDT_lst)) }} <br/>  
                                @if ($depart != '')
                                {{ Ref::GetDescr('315',$depart,'ms') }}
                                @else
                                SEMUA BAHAGIAN
                                @endif</center></h2>
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
                                        <td>{{$reportFinal[$state]['TERIMA']}}</td>
                                        @if($status['TERIMA'])
                                            <td>{{$reportFinal[$state]['TERIMA']}}</td>
                                            <td>{{$reportFinal[$state]['TERIMA_pct']}}</td>
                                        @endif
                                        @if($status['DALAMSIASATAN'])
                                            <td>{{$reportFinal[$state]['DALAMSIASATAN']}}</td>
                                            <td>{{$reportFinal[$state]['DALAMSIASATAN_pct']}}</td>
                                        @endif
                                        @if($status['SELESAI'])
                                            <td>{{$reportFinal[$state]['SELESAI']}}</td>
                                            <td>{{$reportFinal[$state]['SELESAI_pct']}}</td>
                                        @endif
                                        @if($status['AGENSILAIN'])
                                            <td>{{$reportFinal[$state]['AGENSILAIN']}}</td>
                                            <td>{{$reportFinal[$state]['AGENSILAIN_pct']}}</td>
                                        @endif
                                        @if($status['TRIBUNAL'])
                                            <td>{{$reportFinal[$state]['TRIBUNAL']}}</td>
                                            <td>{{$reportFinal[$state]['TRIBUNAL_pct']}}</td>
                                        @endif
                                        @if($status['PERTANYAAN'])
                                            <td>{{$reportFinal[$state]['PERTANYAAN']}}</td>
                                            <td>{{$reportFinal[$state]['PERTANYAAN_pct']}}</td>
                                        @endif
                                        @if($status['MKLUMATXLENGKAP'])
                                            <td>{{$reportFinal[$state]['MKLUMATXLENGKAP']}}</td>
                                            <td>{{$reportFinal[$state]['MKLUMATXLENGKAP_pct']}}</td>
                                        @endif
                                        @if($status['LUARBIDANG'])
                                            <td>{{$reportFinal[$state]['LUARBIDANG']}}</td>
                                            <td>{{$reportFinal[$state]['LUARBIDANG_pct']}}</td>
                                        @endif
                                        @if($status['TUTUP'])
                                            <td>{{$reportFinal[$state]['TUTUP']}}</td>
                                            <td>{{$reportFinal[$state]['TUTUP_pct']}}</td>
                                        @endif
                                    </tr>
                                    @if($CA_BRNCD == 1)
                                        @foreach($reportFinal[$state]['branch'] as $key => $branch)
                                            <tr>
                                                <td></td>
                                                <td>{{ $key }}</td>
                                                <td>{{$branch['TERIMA']}}</td>
                                                @if($status['TERIMA'])
                                                    <td>{{$branch['TERIMA']}}</td>
                                                    <td>{{$branch['TERIMA_pct']}}</td>
                                                @endif
                                                @if($status['DALAMSIASATAN'])
                                                    <td>{{$branch['DALAMSIASATAN']}}</td>
                                                    <td>{{$branch['DALAMSIASATAN_pct']}}</td>
                                                @endif
                                                @if($status['SELESAI'])
                                                    <td>{{$branch['SELESAI']}}</td>
                                                    <td>{{$branch['SELESAI_pct']}}</td>
                                                @endif
                                                @if($status['AGENSILAIN'])
                                                    <td>{{$branch['AGENSILAIN']}}</td>
                                                    <td>{{$branch['AGENSILAIN_pct']}}</td>
                                                @endif
                                                @if($status['TRIBUNAL'])
                                                    <td>{{$branch['TRIBUNAL']}}</td>
                                                    <td>{{$branch['TRIBUNAL_pct']}}</td>
                                                @endif
                                                @if($status['PERTANYAAN'])
                                                    <td>{{$branch['PERTANYAAN']}}</td>
                                                    <td>{{$branch['PERTANYAAN_pct']}}</td>
                                                @endif
                                                @if($status['MKLUMATXLENGKAP'])
                                                    <td>{{$branch['MKLUMATXLENGKAP']}}</td>
                                                    <td>{{$branch['MKLUMATXLENGKAP_pct']}}</td>
                                                @endif
                                                @if($status['LUARBIDANG'])
                                                    <td>{{$branch['LUARBIDANG']}}</td>
                                                    <td>{{$branch['LUARBIDANG_pct']}}</td>
                                                @endif
                                                @if($status['TUTUP'])
                                                    <td>{{$branch['TUTUP']}}</td>
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
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['TERIMA'] }} </a></th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['TERIMA'] }} </a></th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['DALAMSIASATAN'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['SELESAI'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['AGENSILAIN'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['TRIBUNAL'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['PERTANYAAN'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['MKLUMATXLENGKAP'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['LUARBIDANG'] }} </a>
                                    </th>
                                    <th>100%
                                    </th>
                                    <th><a target="_blank"
                                           href="{{-- route('',[]) --}}">{{ $reportFinal['total']['TUTUP'] }} </a></th>
                                    <th>100%
                                    </th>

                                </tr>
                                </tfoot>
                            </table>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
