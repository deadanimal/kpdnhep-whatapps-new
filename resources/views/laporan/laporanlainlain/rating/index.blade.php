@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
use App\Laporan\ReportLainlain;
use App\Laporan\ReportYear;
?>

@section('content')
<div class="row">
    <h2>Laporan Rating</h2>
    {!! Form::open(['method' =>'GET', 'class' => 'form-horizontal']) !!}
    <div class="ibox-content" style="padding-bottom: 0px">
        <div class="form-group" style="padding-bottom: 0px">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group">
                    {{ Form::label('option', 'Jenis', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-8">
                    <div class="radio radio-success radio-inline">
                        <input id="RA_TYP1" type="radio" name="RA_TYP" value="1" onclick="check(this.value)" {{ $option != '' ? ($option == '1' ? 'checked' : '') : 'checked' }}>
                        <label for="RA_TYP1"> Aduan </label>
                    </div>
                    <div class="radio radio-success radio-inline">
                        <input id="RA_TYP2" type="radio" name="RA_TYP" value="0" onclick="check(this.value)" {{ $option != '' ? ($option == '0' ? 'checked' : '') : '' }}>
                        <label for="RA_TYP2"> Pertanyaan/Cadangan </label>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('year', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('year', ReportYear::GetByYear(false), date('Y'), ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                <div id="aduan" style="display: {{ ($option != '' ? ($option == '1' ? 'block':'none') : 'block') }}">
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
            </div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group" align="center">
                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                    {{ link_to('laporanlainlain/rating', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
            @if($Request->RA_TYP == 1)
            <tr><td><center><h3>{{ $titlestate }}</h3></center></td></tr>
            <tr><td><center><h3>{{ $titlebrn }}</h3></center></td></tr>
            @endif
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

                        $state = $Request->state == '' ? '0' : $Request->state;
                        $brn = $Request->brn == '' ? '0' : $Request->brn;
                    ?>
                    @foreach ($datas as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <!-- <td>{{-- $data->rate --}}</td> -->
                            <td width="10%"> 
                            <?php if ($data->rate == 1) { ?>
                                <img for="rating1" style="width: 35%" src="{{ url('img/perform3.png') }}" />
                            <?php } elseif ($data->rate == 2) { ?>
                                <img for="rating2" style="width: 35%" src="{{ url('img/perform4.png') }}" />
                            <?php } elseif ($data->rate == 3) { ?>
                                <img for="rating3" style="width: 35%" src="{{ url('img/perform5.png') }}" />
                            <?php }
                            ?></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '1', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->jan }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '2', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->feb }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '3', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->mac }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '4', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->apr }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '5', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->mei }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '6', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->jun }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '7', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->jul }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '8', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->ogo }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '9', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->sep }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '10', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->okt }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '11', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->nov }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '12', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->dis }}</a></td>
                            <td><a target="_blank" href="{{ route('laporanlainlain.rating1', [$Request->year, '0', $state, $brn, $Request->RA_TYP, $data->rate]) }}">{{ $data->Total }}</a></td>
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

    function check(value) {
        if (value == '1') {
            $('#aduan').show();
        } else {
            $('#aduan').hide();
        }
    }
</script>
@stop