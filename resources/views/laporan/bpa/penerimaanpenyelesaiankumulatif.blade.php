@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\Bpa;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Penerimaan / Penyelesaian (Kumulatif) </h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'bpa/penerimaan-penyelesaian-kumulatif']) !!}
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT_YEAR', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                               {{ Form::select('CA_RCVDT_YEAR', Bpa::GetYearList(), $CA_RCVDT_YEAR, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                {{ Form::select('CA_DEPTCD', Bpa::GetRefDeptList('315', 'semua'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'action']) }}
                            {{ link_to('bpa/penerimaan-penyelesaian-kumulatif', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                        @if($action != '')
                            <div class="form-group" align="center">
                               {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                                {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                            </div>
                        @endif
                    {!! Form::close() !!}
                </div>
                @if($action != '')
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <h3 align="center">JUMLAH ADUAN YANG DITERIMA DAN DISELESAIKAN BAGI TAHUN {{ $CA_RCVDT_YEAR }}</h3>
                                <h3 align="center">
                                    {{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}
                                </h3>
                                <tr><th colspan="7" style="text-align: center">Jumlah Aduan</th></tr>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Agensi</th>
                                    <th>Diterima</th>
                                    <th>Dalam Tindakan</th>
                                    <th>%</th>
                                    <th>Selesai</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $jumlahterima = 0;
                                    $jumlahterimadalamtindakan = 0;
                                    $jumlahterimaselesaikerajaan = 0;
                                    $jumlahperatusdalamtindakankerajaan = 0;
                                    $jumlahperatusselesaikerajaan = 0;
                                @endphp
                                @foreach($senarai as $senaraidata)
                                    @php
                                        $jumlahterima += $senaraidata->countcaseid;
                                        $jumlahterimadalamtindakan += $senaraidata->Count1 + $senaraidata->Count2;
                                        $jumlahterimaselesaikerajaan += $senaraidata->Count3 + $senaraidata->Count4 + $senaraidata->Count5 + $senaraidata->Count6 + $senaraidata->Count7 + $senaraidata->Count8 + $senaraidata->Count9;
                                    @endphp
                                @endforeach
                                @foreach($senarai as $senaraidata)
                                    @php
                                        $terimakerajaan = $senaraidata->countcaseid - $senaraidata->Count4;
                                        $terimaswasta = $senaraidata->Count4;
                                        $dalamtindakankerajaan = $senaraidata->Count1 + $senaraidata->Count2;
                                        $peratusdalamtindakankerajaan = round((($dalamtindakankerajaan / $jumlahterima) * 100), 2);
                                        $jumlahperatusdalamtindakankerajaan += $peratusdalamtindakankerajaan;
                                        $selesaikerajaan = $senaraidata->Count3 + $senaraidata->Count4 + $senaraidata->Count5 + $senaraidata->Count6 + $senaraidata->Count7 + $senaraidata->Count8 + $senaraidata->Count9;
                                        $peratusselesaikerajaan = round((($selesaikerajaan / $jumlahterima) * 100), 2);
                                        $jumlahperatusselesaikerajaan += $peratusselesaikerajaan;
                                    @endphp
                                    <tr>
                                        <td rowspan="2">{{ $senaraidata->month }}</td>
                                        <td>Kerajaan</td>
                                        <td>{{ $terimakerajaan }}</td>
                                        <td>{{ $dalamtindakankerajaan }}</td>
                                        <td>{{ $peratusdalamtindakankerajaan }}</td>
                                        <td>{{ $selesaikerajaan }}</td>
                                        <td>{{ $peratusselesaikerajaan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Swasta</td>
                                        <td>{{ $terimaswasta }}</td>
                                        <td> - </td>
                                        <td> - </td>
                                        <td> - </td>
                                        <td> - </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Jumlah</td>
                                    <td>{{ $jumlahterima }}</td>
                                    <td>{{ $jumlahterimadalamtindakan }}</td>
                                    <td>{{ $jumlahperatusdalamtindakankerajaan }}</td>
                                    <td>{{ $jumlahterimaselesaikerajaan }}</td>
                                    <td>{{ $jumlahperatusselesaikerajaan }}</td>
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