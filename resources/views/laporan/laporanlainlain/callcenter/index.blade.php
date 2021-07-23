@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;
use App\Laporan\ReportYear;
?>

@section('content')
<div class="row">
    <h2>Laporan Call Center</h2>
    {!! Form::open(['method' =>'GET', 'class' => 'form-horizontal']) !!}
    <div class="ibox-content" style="padding-bottom: 0px">
        <div class="form-group" style="padding-bottom: 0px">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group">
                    {{ Form::label('year', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('year', ReportYear::GetByYear(false), date('Y'), ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                    {{ link_to('laporanlainlain/callcenter', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    @if($Request->cari)
                    {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                    {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>Bil.</th>
                        <th>Nama</th>
                        <th> Jan</th>
                        <th> Feb </th>
                        <th> Mac </th>
                        <th> Apr </th>
                        <th> Mei </th>
                        <th> Jun </th>
                        <th> Jul </th>
                        <th> Ogo </th>
                        <th> Sep </th>
                        <th> Okt </th>
                        <th> Nov </th>
                        <th> Dis </th>
                        <th> Jumlah </th>
                    </tr>
                </thead>
                <tbody>
                    @if($Request->cari)
                    <?php
                    $i = 1;
                    $tJan=0;
                    $tFeb=0;
                    $tMac=0;
                    $tApr=0;
                    $tMei=0;
                    $tJun=0;
                    $tJul=0;
                    $tOgo=0;
                    $tSep=0;
                    $tOkt=0;
                    $tNov=0;
                    $tDis=0;
                    $ttotal=0;
                    ?>
                    @endif
                    @foreach ($datas as $data)
                    <tr>
                        <td> {{ $i++ }} </td>
                        <td> {{ $data->name }} </td>
                        <td>
                            <!--<a target="_blank" href="{{-- route('sasreport.carapenerimaan1',[$Request->year,$data->id,1]) --}}">-->
                            <a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 1, $data->id]) }}">
                                {{ $data->JAN }}
                            </a>
                        </td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 2, $data->id]) }}">{{ $data->FEB }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 3, $data->id]) }}">{{ $data->MAC }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 4, $data->id]) }}">{{ $data->APR }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 5, $data->id]) }}">{{ $data->MEI }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 6, $data->id]) }}">{{ $data->JUN }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 7, $data->id]) }}">{{ $data->JUL }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 8, $data->id]) }}">{{ $data->OGO }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 9, $data->id]) }}">{{ $data->SEP }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 10, $data->id]) }}">{{ $data->OKT }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 11, $data->id]) }}">{{ $data->NOV }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 12, $data->id]) }}">{{ $data->DIS }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.callcenter1',[$Request->year, 0, $data->id]) }}">{{ $data->Total }}</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
