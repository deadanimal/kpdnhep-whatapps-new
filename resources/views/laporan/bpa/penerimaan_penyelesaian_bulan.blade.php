@extends('layouts.main')
<?php

use App\Ref;
use App\Laporan\Bpa;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Penerimaan / Penyelesaian (Bulan)</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'bpa/penerimaan-penyelesaian-bulan']) !!}
                    <div class="form-group">
                        {{ Form::label('CA_RCVDT_YEAR', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::select('CA_RCVDT_YEAR', Bpa::GetYearList(), $CA_RCVDT_YEAR, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('CA_RCVDT_MONTH', 'Bulan', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::select('CA_RCVDT_MONTH', Bpa::GetRefList('206', ''), null, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('BR_STATECD', 'Negeri', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::select('BR_STATECD', Bpa::GetRefList('17', 'semua'), null, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::select('CA_DEPTCD', Bpa::GetRefList('315', 'semua'), null, ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'action']) }}
                        {{ link_to('bpa/penerimaan-penyelesaian-bulan', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                    @if($action != '')
                    <div class="form-group" align="center">
                        {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Versi Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value'=>'1']) }} &nbsp;
                        {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun Versi PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'formtarget' => '_blank', 'value'=>'1']) }}
                    </div>
                    @endif
                    {!! Form::close() !!}
                </div>
                @if($action != '')
                <?php
                // set variable = 0, table 1 - DITERIMA DAN DISELESAIKAN
                $jumlah = 0;
                $terimakerajaan = 0;
                $terimaswasta = 0;
                $terimadalamtindakan = 0;
                $terimajumlah = 0;
                $dalamtindakankerajaan = 0;
                $selesaikerajaan = 0;
                $selesaijumlah = 0;
                $peratusdalamtindakankerajaan = 0;
                $peratusselesaikerajaan = 0;
                $peratusselesaiswasta = 0;
                $peratusjumlah = 0;
                // table 2 - SELESAI BERASAS DAN TIDAK BERASAS
                $selesaiberasaskerajaan = 0;
                $selesaitidakberasaskerajaan = 0;
                $selesaiberasasswasta = 0;
                $selesaiberasasjumlah = 0;
                $peratusselesaiberasaskerajaan = 0;
                $peratusselesaitidakberasaskerajaan = 0;
                $peratusselesaiberasasswasta = 0;
                $peratusselesaiberasasjumlah = 0;
                // table 3 - STATUS PENYELESAIAN ADUAN
                $selesaimuktamad = 0;
                $peratusselesaimuktamad = 0;
                // dapatkan kiraan guna function dalam model
                $senarai = Bpa::penerimaanpenyelesaianbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $BR_STATECD, $CA_DEPTCD)->first();
                if (!empty($senarai)) { // jika data daripada function dalam model ada value
                    // table 1 - DITERIMA DAN DISELESAIKAN
                    $jumlah = $senarai->countcaseid;
//                    $terimakerajaan = $senarai->countcaseid - $senarai->Count4;
                    $terimakerajaan = $senarai->countcaseid;
//                    $terimaswasta = $senarai->Count4;
                    $terimadalamtindakan = $senarai->Count1 + $senarai->Count2;
                    $terimajumlah = $terimakerajaan + $terimaswasta;
                    $dalamtindakankerajaan = $senarai->Count1 + $senarai->Count2;
                    $selesaikerajaan = $senarai->Count3 + $senarai->Count4 + $senarai->Count5 + $senarai->Count6 + $senarai->Count7 + $senarai->Count8 + $senarai->Count9;
                    $selesaijumlah = $selesaikerajaan;
                    $peratusdalamtindakankerajaan = round((($dalamtindakankerajaan / $terimajumlah) * 100), 2);
                    $peratusselesaikerajaan = round((($selesaikerajaan / $terimajumlah) * 100), 2);
                    $peratusselesaiswasta = round((($terimaswasta / $terimajumlah) * 100), 2);
                    $peratusjumlah = $peratusselesaikerajaan + $peratusselesaiswasta;
                    // table 2 - SELESAI BERASAS DAN TIDAK BERASAS
                    $selesaiberasaskerajaan = $senarai->Count3 + $senarai->Count4 + $senarai->Count5 + $senarai->Count6 + $senarai->Count7 + $senarai->Count9;
                    $selesaitidakberasaskerajaan = $senarai->Count8;
                    $selesaiberasasswasta = $senarai->Count4;
//                    $selesaiberasasjumlah = $selesaiberasaskerajaan + $selesaiberasasswasta;
                    $selesaiberasasjumlah = $selesaiberasaskerajaan;
                    $peratusselesaiberasaskerajaan = round((($selesaiberasaskerajaan / $jumlah) * 100), 2);
                    $peratusselesaitidakberasaskerajaan = round((($selesaitidakberasaskerajaan / $jumlah) * 100), 2);
                    $peratusselesaiberasasswasta = round((($selesaiberasasswasta / $jumlah) * 100), 2);
                    $peratusselesaiberasasjumlah = $peratusselesaiberasaskerajaan;
                    // table 3 - STATUS PENYELESAIAN ADUAN
                    $selesaimuktamad = $senarai->Count3 + $senarai->Count4 + $senarai->Count5 + $senarai->Count6 + $senarai->Count7 + $senarai->Count8 + $senarai->Count9;
                    $peratusselesaimuktamad = round((($selesaimuktamad / $jumlah) * 100), 2);
                }
                ?>
                <!--table 1 - DITERIMA DAN DISELESAIKAN-->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>
                        <h3 align="center">JUMLAH ADUAN YANG DITERIMA DAN DISELESAIKAN BAGI
                            {{ Ref::GetDescr('206', $CA_RCVDT_MONTH, 'ms') }} {{ $CA_RCVDT_YEAR }}</h3>
                        <h3 align="center">
                            @if($BR_STATECD != '')
                            {{ Ref::GetDescr('17', $BR_STATECD, 'ms') }}
                            @else
                            SEMUA NEGERI
                            @endif
                        </h3>
                        <h3 align="center">
                            @if($CA_DEPTCD != '')
                            {{ Ref::GetDescr('315', $CA_DEPTCD, 'ms') }}
                            @else
                            SEMUA BAHAGIAN
                            @endif
                        </h3>
                        <tr><th colspan="6" style="text-align: center">Jumlah Aduan</th></tr>
                        <tr>
                            <th>Kementerian</th>
                            <th>Terima</th>
                            <th>Dalam Tindakan</th>
                            <th>%</th>
                            <th>Selesai</th>
                            <th>%</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Agensi Kerajaan</td>
                                <td>{{ $terimakerajaan }}</td>
                                <td>{{ $dalamtindakankerajaan }}</td>
                                <td>{{ $peratusdalamtindakankerajaan }}</td>
                                <td>{{ $selesaikerajaan }}</td>
                                <td>{{ $peratusselesaikerajaan }}</td>
                            </tr>
                            <tr>
                                <td>Agensi Swasta</td>
                                <td>{{-- $terimaswasta --}} - </td>
                                <td> - </td>
                                <td> - </td>
                                <td>{{-- $terimaswasta --}} - </td>
                                <td>{{-- $peratusselesaiswasta --}} - </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Jumlah</td>
                                <td>{{ $terimajumlah }}</td>
                                <td>{{ $terimadalamtindakan }}</td>
                                <td>{{ $peratusdalamtindakankerajaan }}</td>
                                <td>{{ $selesaijumlah }}</td>
                                <td>{{ $peratusjumlah }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!--table 2 - SELESAI BERASAS DAN TIDAK BERASAS-->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>
                        <h3 align="center">JUMLAH ADUAN SELESAI YANG BERASAS DAN LUAR BIDANG KUASA
                        </h3>
                        <tr><th colspan="5" style="text-align: center">Jumlah Aduan</th></tr>
                        <tr>
                            <th>Kementerian</th>
                            <th>Selesai & Berasas</th>
                            <th>%</th>
                            <th>Selesai & Luar Bidang Kuasa</th>
                            <th>%</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Agensi Kerajaan</td>
                                <td>{{ $selesaiberasaskerajaan }}</td>
                                <td>{{ $peratusselesaiberasaskerajaan }}</td>
                                <td>{{ $selesaitidakberasaskerajaan }}</td>
                                <td>{{ $peratusselesaitidakberasaskerajaan }}</td>
                            </tr>
                            <tr>
                                <td>Agensi Swasta</td>
                                <td>{{-- $selesaiberasasswasta --}} - </td>
                                <td>{{-- $peratusselesaiberasasswasta --}} - </td>
                                <td> - </td>
                                <td> - </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Jumlah</td>
                                <td>{{ $selesaiberasasjumlah }}</td>
                                <td>{{ $peratusselesaiberasasjumlah }}</td>
                                <td>{{ $selesaitidakberasaskerajaan }}</td>
                                <td>{{ $peratusselesaitidakberasaskerajaan }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!--table 3 - STATUS PENYELESAIAN ADUAN-->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>
                        <h3 align="center">STATUS PENYELESAIAN ADUAN
                        </h3>
                        <tr>
                            <th>Status Penyelesaian</th>
                            <th>Jumlah Aduan</th>
                            <th>%</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Selesai Muktamad</td>
                                <td>{{ $selesaimuktamad }}</td>
                                <td>{{ $peratusselesaimuktamad }}</td>
                            </tr>
                            <tr>
                                <td>Selesai Dengan Pantauan</td>
                                <td> - </td>
                                <td> - </td>
                            </tr>
                            <tr>
                                <td>Selesai Bersyarat</td>
                                <td> - </td>
                                <td> - </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Jumlah</td>
                                <td>{{ $selesaimuktamad }}</td>
                                <td>{{ $selesaitidakberasaskerajaan }}</td>
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