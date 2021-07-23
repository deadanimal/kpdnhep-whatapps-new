@extends('layouts.main')
@php
    use App\Ref;
    use App\Laporan\TerimaSelesaiAduan;
@endphp
@section('content')
    <style>
        .form-control[readonly][type="text"] {
            background-color: #ffffff;
        }
    </style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <h2>Laporan Tindakan Pertama - Bahagian Gerakan Kepenggunaan (BGK)</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="dashboard">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Laporan Tindakan Pertama</strong>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Carian</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-10">
                        {{ Form::open([
                            'url' => 'firstaction', 
                            'id' => 'search-form', 
                            'class'=>'form-horizontal', 
                            'method' => 'GET'
                        ]) }}
                            <div class="form-group" id="data_5">
                                {{ Form::label('date', 'Tarikh Penerimaan Aduan', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text(
                                            'datestart', 
                                            date('d-m-Y', strtotime($datestart)), 
                                            [
                                                'class' => 'form-control', 
                                                'id' => 'datestart', 
                                                'readonly' => true,
                                                'placeholder' => 'HH-BB-TTTT'
                                            ]
                                        ) }}
                                        <span class="input-group-addon">Hingga</span>
                                        {{ Form::text(
                                            'dateend', 
                                            date('d-m-Y', strtotime($dateend)), 
                                            [
                                                'class' => 'form-control', 
                                                'id' => 'dateend', 
                                                'readonly' => true,
                                                'placeholder' => 'HH-BB-TTTT'
                                            ]
                                        ) }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('department', 'Bahagian', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('department', TerimaSelesaiAduan::GetRef('315', 'semua'), null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('state', 'Negeri', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    {{ Form::select('state', TerimaSelesaiAduan::GetRef('17', 'semua'), '0', ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <div class="text-center">
                                        <a class="btn btn-outline btn-success btn-rounded" href="{{ url('firstaction') }}">
                                            <i class="fa fa-refresh"></i>&nbsp;&nbsp;Semula
                                        </a>
                                        &nbsp;
                                        {{ Form::button(
                                            '<i class="fa fa-search"></i>&nbsp;&nbsp;Carian', 
                                            [
                                                'type' => 'submit', 
                                                'class' => 'btn btn-success btn-rounded', 
                                            ]
                                        ) }}
                                    </div>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                    <div class="col-lg-1"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@if($var['search'])
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Laporan</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="text-center">
                        <a target="_blank"
                            href="firstaction?datestart={{ $datestart }}&dateend={{ $dateend }}&department={{ $department }}&state={{ $state }}&gen=excel"
                            class="btn btn-primary btn-rounded">
                            <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Muat Turun Versi Excel
                        </a>
                        &nbsp;
                        <a target="_blank"
                            href="firstaction?datestart={{ $datestart }}&dateend={{ $dateend }}&department={{ $department }}&state={{ $state }}&gen=pdf"
                            class="btn btn-danger btn-rounded btn-outline">
                            <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Muat Turun Versi PDF
                        </a>
                    </div>
                </div>
                <div class="row">
                    <table style="width: 100%;">
                        <tr><td><h3><center>Laporan Tindakan Pertama</center></h3></td></tr>
                        <tr><td><h3><center>
                            Tarikh Penerimaan Aduan : {{ date('d-m-Y', strtotime($datestart)) }} 
                            {{ $dateend != '' ?  'Hingga' : '' }}
                            {{ date('d-m-Y', strtotime($dateend)) }}
                        </center></h3></td></tr>
                        <tr><td><h3><center>
                            Bahagian : {{ !empty($department) 
                            ? Ref::GetDescr('315', $department) 
                            : 'Semua Bahagian' }}
                        </center></h3></td></tr>
                        <tr><td><h3><center>
                            Negeri : {{ !empty($state) 
                            ? Ref::GetDescr('17', $state) 
                            : 'Semua Negeri' }}
                        </center></h3></td></tr>
                    </table>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Bil.</th>
                                    <th style="text-align: center">Status Aduan</th>
                                    <th style="text-align: center">1 Hari</th>
                                    <th style="text-align: center">2 - 3 Hari</th>
                                    <th style="text-align: center">4 - 7 Hari</th>
                                    <th style="text-align: center">8 - 14 Hari</th>
                                    <th style="text-align: center">15 - 21 Hari</th>
                                    <th style="text-align: center">> 21 Hari</th>
                                    <th style="text-align: center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($statuses as $key => $datum)
                                    <tr>
                                        <td style="text-align: center">{{ $i++ }}</td>
                                        <td>
                                            {{ $datum != '' ? Ref::GetDescr('292', $datum) : '' }}
                                        </td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['1'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['2-3'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['4-7'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['8-14'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['15-21'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['>21'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td style="text-align: center"><strong>Jumlah</strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['1'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['2-3'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['4-7'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['8-14'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['15-21'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['>21'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['total'] }}<strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@stop
@section('script_datatable')
<script type="text/javascript">
    $('#data_5 .input-daterange').datepicker({
        autoclose: true,
        calendarWeeks: true,
        forceParse: false,
        keyboardNavigation: false,
        todayBtn: "linked",
        todayHighlight: true,
        weekStart: 1,
        format: 'dd-mm-yyyy',
        startDate: '01-04-2018',
        endDate: '31-12-yyyy'
    });
</script>
@stop