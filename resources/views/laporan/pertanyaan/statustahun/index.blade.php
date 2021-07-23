@extends('layouts.main')
<?php
    use App\Laporan\Pertanyaan;
?>
@section('content')
    <div class="row">
        <h2>Laporan Pertanyaan / Cadangan</h2>
        {!! Form::open(['method' =>'GET', 'class' => 'form-horizontal']) !!}
            <div class="ibox-content" style="padding-bottom: 0px">
                <div class="form-group" style="padding-bottom: 0px">
                    <div class="col-sm-4 col-sm-offset-4">
                        <div class="form-group">
                            {{ Form::label('year', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-10">
                                {{ Form::select('year', Pertanyaan::GetAskInfoByYear(false), date('Y'), ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                            {{ link_to('laporanpertanyaan/statustahun', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-group" align="center">
                            @if($request->cari)
                                {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                                {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
    @if($request->cari)
        <div class="row">
            <div class="ibox-content" style="padding-bottom: 0px">
                <table style="width: 100%;">
                    <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
                </table>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th> Bil. </th>
                                <th> Status </th>
                                <th> Jan </th>
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
                            <?php
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
                            @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->descr }}</td>
                                    <td>
                                        <a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '1', $data->code]) }}">
                                            {{ $data->jan }}
                                        </a>
                                    </td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '2', $data->code]) }}">{{ $data->feb }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '3', $data->code]) }}">{{ $data->mac }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '4', $data->code]) }}">{{ $data->apr }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '5', $data->code]) }}">{{ $data->mei }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '6', $data->code]) }}">{{ $data->jun }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '7', $data->code]) }}">{{ $data->jul }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '8', $data->code]) }}">{{ $data->ogo }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '9', $data->code]) }}">{{ $data->sep }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '10', $data->code]) }}">{{ $data->okt }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '11', $data->code]) }}">{{ $data->nov }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '12', $data->code]) }}">{{ $data->dis }}</a></td>
                                    <td><a target="_blank" href="{{ route('laporanpertanyaan.statustahun1', [$request->year, '0', $data->code]) }}">{{ $data->Total }}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@stop
