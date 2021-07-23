@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\Bpa;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Cara Penerimaan Aduan (Bulan)</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'bpa/sumber-penerimaan-bulan']) !!}
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
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                {{ Form::select('CA_DEPTCD', Bpa::GetRefList('315', 'semua'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('BR_STATECD', 'Negeri', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                               {{ Form::select('BR_STATECD', Bpa::GetRefList('17', 'semua'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'action']) }}
                            {{ link_to('bpa/sumber-penerimaan-bulan', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
                                <h3 align="center">LAPORAN ADUAN MENGIKUT CARA PENERIMAAN</h3>
                                <h3 align="center">{{ Ref::GetDescr('206', $CA_RCVDT_MONTH, 'ms') }} {{ $CA_RCVDT_YEAR }}</h3>
                                <h3 align="center">
                                    {{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}
                                </h3>
                                <h3 align="center">
                                    {{ $BR_STATECD != '' ? Ref::GetDescr('17', $BR_STATECD, 'ms') : 'SEMUA NEGERI' }}
                                </h3>
                                <tr>
                                    <th>Cara Penerimaan</th>
                                    <th>Jumlah Aduan</th>
                                    <th>% Aduan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $counttotal = 0;
                                    $percenttotal = 0;
                                ?>
                                @foreach($refsumberaduan as $sumberaduan)
                                    <?php
                                        $sumberpenerimaanbulancount = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, $sumberaduan->code)->first();
                                        if(!empty($sumberpenerimaanbulancount)){
                                            $counttotal += $sumberpenerimaanbulancount->countcaseid;
                                        }
                                    ?>
                                @endforeach
                                <?php
//                                    $sumberpenerimaanbulancountempty = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, '')->first();
//                                    if(!empty($sumberpenerimaanbulancountempty)){
//                                        $counttotal += $sumberpenerimaanbulancountempty->countcaseid;
//                                    }
                                ?>
                                @foreach($refsumberaduan as $sumberaduan)
                                    <?php
                                        $sumberpenerimaanbulan = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, $sumberaduan->code)->first();
                                        if(!empty($sumberpenerimaanbulan)){
                                    ?>
                                        <tr>
                                            <td>{{ $sumberaduan->descr }}</td>
                                            <td>{{ $sumberpenerimaanbulan->countcaseid }}</td>
                                            <td>{{ $percent = round((($sumberpenerimaanbulan->countcaseid/$counttotal)*100), 2) }}</td>
                                        </tr>
                                    <?php 
                                            $percenttotal += $percent;
                                        }
                                    ?>
                                @endforeach
                                <?php
//                                    $sumberpenerimaanbulanempty = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, '')->first();
//                                    if(!empty($sumberpenerimaanbulanempty)){
                                ?>
<!--                                    <tr>
                                        <td>Lain-Lain</td>
                                        <td>{{-- $sumberpenerimaanbulanempty->countcaseid --}}</td>
                                        <td>{{-- $percent = round((($sumberpenerimaanbulanempty->countcaseid/$counttotal)*100), 2) --}}</td>
                                    </tr>-->
                                <?php 
//                                        $percenttotal += $percent;
//                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Jumlah</td>
                                    <td>{{ $counttotal }}</td>
                                    <td>{{ round($percenttotal) }}</td>
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