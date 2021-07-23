@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;
use App\Laporan\ReportYear;
?>

@section('content')
<div class="row">
    <h2>Laporan Integriti - Statistik Aduan Mengikut Kategori</h2>
    {!! Form::open(['method' =>'GET', 'class' => 'form-horizontal']) !!}
    <div class="ibox-content" style="padding-bottom: 0px">
        <div class="form-group" style="padding-bottom: 0px">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" id="data_5">
                    {{ Form::label('date', 'Tarikh (Terima) : ', ['class' => 'col-sm-4 control-label']) }}
                    <div class="col-sm-8">
                        <div class="input-daterange input-group" id="datepicker">
                            {{ Form::text('DATE_FROM',date('d-m-Y', strtotime($DATE_FROM)), ['class' => 'form-control input-sm', 'id' => 'DATE_FROM']) }}
                            <span class="input-group-addon">hingga</span>
                            {{ Form::text('DATE_TO', date('d-m-Y', strtotime($DATE_TO)), ['class' => 'form-control input-sm', 'id' => 'DATE_TO']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                    {{ link_to('laporanintegriti/statistikaduanmengikutkategori', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
            <tr><td><center><h3>Laporan Integriti - Statistik Aduan Mengikut Kategori dari {{date('d-m-Y', strtotime($DATE_FROM))}} hingga {{date('d-m-Y', strtotime($DATE_TO))}}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th> Bil. </th>
                        <th> Kategori Aduan </th>
                        <th> Jumlah </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $key => $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $categoryList[$key] }}</td>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@stop
@section('script_datatable')
<script type="text/javascript">
    $('#data_5 .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    })
</script>
@stop