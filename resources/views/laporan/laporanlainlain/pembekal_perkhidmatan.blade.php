@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportLainlain;
?>

@section('content')
<style>
    .table-header-rotated th.row-header{
        width: auto;
    }

    .table-header-rotated td{
        width: 40px;
        border-top: 1px solid #dddddd;
        border-left: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        border-bottom: 1px solid #dddddd;
        vertical-align: middle;
        text-align: center;
    }

    .table-header-rotated th.rotate-45{
        height: 80px;
        width: 40px;
        min-width: 40px;
        max-width: 40px;
        position: relative;
        vertical-align: bottom;
        padding: 0;
        font-size: 12px;
        line-height: 0.8;
    }

    .table-header-rotated th.rotate-45 > div{
        position: relative;
        top: 0px;
        left: 40px; /* 80 * tan(45) / 2 = 40 where 80 is the height on the cell and 45 is the transform angle*/
        height: 100%;
        -ms-transform:skew(-45deg,0deg);
        -moz-transform:skew(-45deg,0deg);
        -webkit-transform:skew(-45deg,0deg);
        -o-transform:skew(-45deg,0deg);
        transform:skew(-45deg,0deg);
        overflow: hidden;
        border-left: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        border-top: 1px solid #dddddd;
        border-bottom: 1px solid #dddddd;
    }

    .table-header-rotated th.rotate-45 span {
        -ms-transform:skew(45deg,0deg) rotate(315deg);
        -moz-transform:skew(45deg,0deg) rotate(315deg);
        -webkit-transform:skew(45deg,0deg) rotate(315deg);
        -o-transform:skew(45deg,0deg) rotate(315deg);
        transform:skew(45deg,0deg) rotate(315deg);
        position: absolute;
        bottom: 35px; /* 40 cos(45) = 28 with an additional 2px margin*/
        left: -25px; /*Because it looked good, but there is probably a mathematical link here as well*/
        display: inline-block;
        // width: 100%;
        width: 85px; /* 80 / cos(45) - 40 cos (45) = 85 where 80 is the height of the cell, 40 the width of the cell and 45 the transform angle*/
        text-align: left;
        // white-space: nowrap; /*whether to display in one line or not*/
    }
</style>
<div class="row">
    <div class="ibox float-e-margins">
        <h2>LAPORAN PEMBEKAL PERKHIDMATAN</h2>
        <div class="ibox-content">
            {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal','method' => 'GET', 'url'=>'laporanlainlain/pembekalperkhidmatan']) !!}
                <div class="form-group" id="date">
                    {{ Form::label('CA_RCVDT', 'Tarikh', ['class' => 'col-sm-4 control-label']) }}
                    <div class="col-sm-8">
                        <div class="input-daterange input-group" id="datepicker">
                            {{ Form::text('CA_RCVDT_FROM', '', ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_FROM']) }}
                            <span class="input-group-addon">hingga</span>
                            {{ Form::text('CA_RCVDT_TO', '', ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_TO']) }}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('option', 'Pilihan Paparan', ['class' => 'col-sm-4 control-label']) }}
                    <div class="col-sm-8">
                        <div class="radio radio-success radio-inline">
                            <input id="option1" type="radio" name="option" value="1" {{ $option != '' ? ($option == '1' ? 'checked' : '') : '' }}>
                            <label for="option1"> Rekod Sahaja </label>
                        </div>
                        <div class="radio radio-success radio-inline">
                            <input id="option0" type="radio" name="option" value="0" {{ $option != '' ? ($option == '0' ? 'checked' : '') : 'checked' }}>
                            <label for="option0"> Semua </label>
                        </div>
                    </div>
                </div>
                <div class="form-group" align="center">
                    {{ Form::submit(' Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'search']) }}
                    {{ link_to('laporanlainlain/pembekalperkhidmatan', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                </div>
                @if($search)
                <div class="form-group" align="center">
                    {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value'=>'1']) }}
                    {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value'=>'1', 'formtarget' => '_blank']) }}
                </div>
                @endif
            {!! Form::close() !!}
        </div>
        <br />
        
        <div class="ibox-content">
            <div class="table-responsive">
                <table style="width: 100%">
                    <tr><td><center><h3>LAPORAN PEMBEKAL PERKHIDMATAN</h3></center></td></tr>
                    @if($search)
                    <tr><td><center><h3>DARI {{ $Request->get('CA_RCVDT_FROM') }} HINGGA {{ $Request->get('CA_RCVDT_TO') }}</h3></center></td></tr>
                    @endif
                </table>
                <table class="table table-striped table-header-rotated" style="width: 90%">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach($ListState as $State)
                            <th class="rotate-45"><div><span>{{ $State->descr }}</span></div></th>
                            @endforeach
                            <th class="rotate-45"><div><span>Jumlah</span></div></th>
                        </tr>
                    </thead>
                    @if($search)
                    <tbody>
                        <?php
                        $Total01 = 0;
                        $Total02 = 0;
                        $Total03 = 0;
                        $Total04 = 0;
                        $Total05 = 0;
                        $Total06 = 0;
                        $Total07 = 0;
                        $Total08 = 0;
                        $Total09 = 0;
                        $Total10 = 0;
                        $Total11 = 0;
                        $Total12 = 0;
                        $Total13 = 0;
                        $Total14 = 0;
                        $Total15 = 0;
                        $Total16 = 0;
                        $Gtotal = 0;
                       ?>
                        @foreach($datas as $key => $data)
                        <tr>
                            <td>{{ $data->descr }}</td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'01']) }}">{{ $data->kod_01 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'02']) }}">{{ $data->kod_02 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'03']) }}">{{ $data->kod_03 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'04']) }}">{{ $data->kod_04 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'05']) }}">{{ $data->kod_05 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'06']) }}">{{ $data->kod_06 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'07']) }}">{{ $data->kod_07 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'08']) }}">{{ $data->kod_08 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'09']) }}">{{ $data->kod_09 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'10']) }}">{{ $data->kod_10 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'11']) }}">{{ $data->kod_11 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'12']) }}">{{ $data->kod_12 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'13']) }}">{{ $data->kod_13 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'14']) }}">{{ $data->kod_14 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'15']) }}">{{ $data->kod_15 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'16']) }}">{{ $data->kod_16 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,$data->code,'0']) }}">{{ $data->Bilangan }}</a></td>
                        </tr>
                        <?php
                        $Total01 += $data->kod_01;
                        $Total02 += $data->kod_02;
                        $Total03 += $data->kod_03;
                        $Total04 += $data->kod_04;
                        $Total05 += $data->kod_05;
                        $Total06 += $data->kod_06;
                        $Total07 += $data->kod_07;
                        $Total08 += $data->kod_08;
                        $Total09 += $data->kod_09;
                        $Total10 += $data->kod_10;
                        $Total11 += $data->kod_11;
                        $Total12 += $data->kod_12;
                        $Total13 += $data->kod_13;
                        $Total14 += $data->kod_14;
                        $Total15 += $data->kod_15;
                        $Total16 += $data->kod_16;
                        $Gtotal += $data->Bilangan
                        ?>
                        @endforeach
                        <tr>
                            <td>Jumlah</td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','01']) }}">{{ $Total01 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','02']) }}">{{ $Total02 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','03']) }}">{{ $Total03 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','04']) }}">{{ $Total04 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','05']) }}">{{ $Total05 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','06']) }}">{{ $Total06 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','07']) }}">{{ $Total07 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','08']) }}">{{ $Total08 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','09']) }}">{{ $Total09 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','10']) }}">{{ $Total10 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','11']) }}">{{ $Total11 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','12']) }}">{{ $Total12 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','13']) }}">{{ $Total13 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','14']) }}">{{ $Total14 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','15']) }}">{{ $Total15 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','16']) }}">{{ $Total16 }}</a></td>
                            <td><a target="_blank" href="{{ route('pembekalperkhidmatan1',[$Request->CA_RCVDT_FROM,$Request->CA_RCVDT_TO,'0','0']) }}">{{ $Gtotal }}</a></td>
                        </tr>
                    </tbody>
                    @endif
                </table>
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
    });
</script>
@stop