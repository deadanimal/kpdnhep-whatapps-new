@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;
use App\Laporan\ReportYear;
use App\Laporan\BandingAduan;
?>

@section('content')
<div class="row">
    <h2>Laporan EzStar</h2>
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
                <div class="form-group">
                    {{ Form::label('month', 'Bulan', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('month', BandingAduan::GetRefList('206', 'sp'), null, ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('state', 'Negeri', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('state', Ref::GetList('17'), null, ['class' => 'form-control input-sm', 'id' => 'state']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('brn', 'Cawangan', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        @if($Request->has('state'))
                            {{ Form::select('brn', Branch::GetListAlternative(true,'-- SILA PILIH --','',$Request->get('state')), $Request->get('brn'), ['class' => 'form-control input-sm', 'id' => 'brn']) }}
                        @else
                            {{ Form::select('brn', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn']) }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                    {{ link_to('laporanlainlain/ezstar', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
@if($Request->cari)
<div class="row">
    <div class="ibox-content" style="padding-bottom: 0px">
        <table style="width: 100%;">
            <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
            <tr><td><center><h3>{{ $titlemonth }}</h3></center></td></tr>
            <tr><td><center><h3>{{ $titlestate }}</h3></center></td></tr>
            <tr><td><center><h3>{{ $titlebrn }}</h3></center></td></tr>
        </table>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>Bil.</th>
                        <th style="width: 20%;">Nama</th>
                        <th style="width: 20%;">Negeri</th>
                        <th style="width: 20%;">Cawangan</th>
                        @php
                            $status = ReportLainlain::EzStarGetStatus();
                        @endphp
                        @foreach($status as $stat)
                            <th style="width: 9%;">{{ $stat->descr }}</th>
                        @endforeach
                        <th style="width: 8%;">Jumlah</th>
                        
                    </tr>
                </thead>
                <tbody>
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
                    
                    $month = $Request->month == '' ? '0' : $Request->month;
                    $state = $Request->state == '' ? '0' : $Request->state;
                    $brn = $Request->brn == '' ? '0' : $Request->brn;
                    ?>
                    @foreach ($datas as $data)
                    
                    <tr>
                        <?php 
                            $statename = Ref::GetDescr(17,$data->BR_STATECD);
                        ?>
                        <td>{{ $i++ }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $statename }}</td>
                        <td>{{ $data->BR_BRNNM }}</td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 2]) }}">{{ $data->code2 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 3]) }}">{{ $data->code3 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 4]) }}">{{ $data->code4 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 5]) }}">{{ $data->code5 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 7]) }}">{{ $data->code7 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 8]) }}">{{ $data->code8 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 9]) }}">{{ $data->code9 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 0]) }}">{{ $data->code0 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 11]) }}">{{ $data->code11 }}</a></td>
                        <td><a target="_blank" href="{{ route('laporanlainlain.ezstar1', [$Request->year, $month, $state, $brn, $data->id, 99]) }}">{{ $data->code2 + $data->code3 + $data->code4 + $data->code5 + $data->code7 + $data->code8 + $data->code9 + $data->code0 + $data->code11 }}</a></td>
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
    $(function () {

        $('#state').on('change', function (e) {
            var state_cd = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('user/getbrnlist') }}" + "/" + state_cd,
                dataType: "json",
                success:function(data){
                    $('select[name="brn"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="brn"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            }); 
        });
        
    });
</script>
@stop