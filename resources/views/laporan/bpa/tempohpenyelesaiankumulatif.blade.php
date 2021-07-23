@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\Bpa;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Tempoh Penyelesaian (Kumulatif)</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'bpa/tempoh-penyelesaian-kumulatif']) !!}
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT_YEAR', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                               {{ Form::select('CA_RCVDT_YEAR', Bpa::GetYearList(), $CA_RCVDT_YEAR, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_RCVDT_MONTH_FROM', 'Bulan', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                <div class="input-group">
                                    {{ Form::select('CA_RCVDT_MONTH_FROM', Bpa::GetRefList('206', ''), $CA_RCVDT_MONTH_FROM, ['class' => 'form-control input-sm']) }}
                                    <span class="input-group-addon">hingga</span>
                                    {{ Form::select('CA_RCVDT_MONTH_TO', Bpa::GetRefList('206', ''), $CA_RCVDT_MONTH_TO, ['class' => 'form-control input-sm']) }}
                                </div>
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
                            {{ link_to('bpa/tempoh-penyelesaian-kumulatif', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
                                <h3 align="center">TEMPOH PENYELESAIAN (KUMULATIF)</h3>
                                <h3 align="center">
                                    DARI {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM, 'ms') }} HINGGA {{ Ref::GetDescr('206', $CA_RCVDT_MONTH_TO, 'ms') }} {{ $CA_RCVDT_YEAR }}
                                </h3>
                                <h3 align="center">
                                    {{ $BR_STATECD != '' ? Ref::GetDescr('17', $BR_STATECD, 'ms') : 'SEMUA NEGERI' }}
                                </h3>
                                <h3 align="center">
                                    {{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}
                                </h3>
                                <tr>
                                    <th>Tempoh (hari)</th>
                                    <th>Jumlah Selesai</th>
                                    <th>Jumlah Selesai (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataCount as $key => $cnt)
                                    @if($key !== 'total')
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $cnt }}</td>
                                            <td>{{ $dataPctg[$key] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Jumlah</td>
                                    <td>{{ $dataCount['total'] }}</td>
                                    <td>{{ $dataPctg['total'] }}</td>
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