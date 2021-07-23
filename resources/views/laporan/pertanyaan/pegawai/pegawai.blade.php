@extends('layouts.main')
<?php
    use App\Ref;
    use App\Branch;
    use App\Laporan\ReportYear;
?>

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Laporan Pertanyaan / Cadangan - Pegawai (Dijawab Oleh)</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['id' => 'search-form','method' =>'GET','url'=>'laporanpertanyaan/reportpegawai', 'class' => 'form-horizontal']) !!}
                    <div class="col-sm-offset-3 col-sm-6">
                        <div class="form-group">
                            {{ Form::label('year', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::select('year', ReportYear::GetByYear(true),date('Y'), ['class' => 'form-control input-sm' , 'id' => 'year']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('state','Negeri', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::select('state', ReportYear::GetRef('17', 'semua'),null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('brn', 'Cawangan', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-8">
                                @if($Request->has('state'))
                                    {{ Form::select('brn', Branch::GetListAlternative(true,'-- SILA PILIH --','',$Request->get('state')), $Request->get('brn'), ['class' => 'form-control input-sm', 'id' => 'brn']) }}
                                @else
                                    {{ Form::select('brn', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn']) }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                            {{ link_to('laporanpertanyaan/reportpegawai', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group" align="center">
                            @if($cari)
                            {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                            {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                            @endif
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <table style="width: 100%;">
                    <tr><td><center><h3>{{ $titleyear }}</h3></center></td></tr>
                    <tr><td><center><h3>{{ $titlestate }}</h3></center></td></tr>
                    <tr><td><center><h3>{{ $titlebrn }}</h3></center></td></tr>
                </table>
                <div class="table-responsive">
                    <table id="year-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>Nama</th>
                                <th>Negeri</th>
                                <th>Cawangan</th>
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
                           @if($cari)
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
                            <?php $i = 1; ?>
                            @foreach ($query as $data)
                            <tr>
                                <?php 
                                    $statename = Ref::GetDescr(17,$data->BR_STATECD);
                                ?>
                                <td> {{ $i++ }} </td>
                                <td> {{ $data->name}}</td>
                                <td> {{ $statename }} </td>
                                <td> {{ $data->BR_BRNNM }} </td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,1,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->JAN}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,2,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->FEB}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,3,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->MAC}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,4,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->APR}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,5,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->MEI}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,6,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->JUN}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,7,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->JUL}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,8,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->OGO}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,9,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->SEP}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,10,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->OKT}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,11,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->NOV}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,12,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->DIS}}</a></td>
                                <td><a target="_blank" href="{{ route('reportpegawai1',[$Request->year,0,$data->id,$data->BR_STATECD,$data->BR_BRNCD]) }}">{{ $data->Bilangan}}</a></td>
                            </tr>
                            <?php
                            $tJan += $data->JAN;
                            $tFeb += $data->FEB;
                            $tMac += $data->MAC;
                            $tApr += $data->APR;
                            $tMei += $data->MEI;
                            $tJun += $data->JUN;
                            $tJul += $data->JUL;
                            $tOgo += $data->OGO;
                            $tSep += $data->SEP;
                            $tOkt += $data->OKT;
                            $tNov += $data->NOV;
                            $tDis += $data->DIS;
                            $ttotal += $data->Bilangan
                            ?>
                            @endforeach
      
                            <!-- <tr>
                                <td colspan="2"><center>Jumlah</center></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,1]) --}}">{{-- $tJan --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,2]) --}}">{{-- $tFeb --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,3]) --}}">{{-- $tMac --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,4]) --}}">{{-- $tApr --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,5]) --}}">{{-- $tMei --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,6]) --}}">{{-- $tJun --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,7]) --}}">{{-- $tJul --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,8]) --}}">{{-- $tOgo --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,9]) --}}">{{-- $tSep --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,10]) --}}">{{-- $tOkt --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,11]) --}}">{{-- $tNov --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,12]) --}}">{{-- $tDis --}}</a></td>
                                <td><a target="_blank" href="{{-- route('reportpegawai1',[$Request->year,0,0]) --}}">{{--$ttotal--}}</a></td>
                            </tr> -->
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
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