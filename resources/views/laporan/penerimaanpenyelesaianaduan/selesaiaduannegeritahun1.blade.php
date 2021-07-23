@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportYear;
    use App\Laporan\TerimaSelesaiAduan;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>LAPORAN PENYELESAIAN ADUAN MENGIKUT NEGERI</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['url' => 'penerimaanpenyelesaianaduan/selesaiaduannegeritahun', 'id' => 'search-form', 'class'=>'form-horizontal', 'method' => 'GET']) !!}
                    <div class="col-sm-offset-2 col-sm-8">
                        <div class="form-group" id="data_5">
                            {{ Form::label('CA_RCVDT', 'Tarikh Dari', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-10">
                                <div class="col-sm-12 input-daterange input-group" id="datepicker">
                                    <input type="text" class="input-sm form-control" value="{{ date('d-m-Y', strtotime($CA_RCVDT_FROM)) }}"name="CA_RCVDT_FROM"/>
                                    <span class="input-group-addon">hingga</span>
                                    <input type="text" class="input-sm form-control" value="{{ date('d-m-Y', strtotime($CA_RCVDT_TO)) }}" name="CA_RCVDT_TO"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-10">
                                {{ Form::select('CA_DEPTCD', TerimaSelesaiAduan::GetRef('315', 'semua'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('BR_STATECD','Negeri', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-10">
                                <!--<div class="ibox">-->
                                    <select name="BR_STATECD[]" class="form-control dual_select" multiple>
                                        @foreach ($mNegeriList as $negeri)
                                        @if(in_array($negeri->code, $BR_STATECD))
                                            <option value="{{ $negeri->code }}" selected="selected">{{ $negeri->descr }}</option>
                                        @else
                                            <option value="{{ $negeri->code }}">{{ $negeri->descr }}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                            <a class="btn btn-default btn-sm" href="{{ url('penerimaanpenyelesaianaduan/selesaiaduannegeritahun')}}">Semula</a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group" align="center">
                            {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                            {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="row">
                    <table style="width: 100%;">
                        <tr><td><h3><center>LAPORAN PENYELESAIAN ADUAN MENGIKUT NEGERI</center></h3></td></tr>
                        <tr><td><h3><center>{{ date('d-m-Y', strtotime($CA_RCVDT_FROM)) }} <?php echo $CA_RCVDT_TO != '' ?  'hingga' : '';?> {{ date('d-m-Y', strtotime($CA_RCVDT_TO)) }}</center></h3></td></tr>
                        <tr><td><h3><center><?php echo !empty($CA_DEPTCD) ? Ref::GetDescr('315',$CA_DEPTCD) : 'Semua Bahagian'?></center></h3></td></tr>
                    </table>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Negeri</th>
                                    <!--<th><1 Hari</th>-->
                                    <th>1 Hari</th>
                                    <th>2-5 Hari</th>
                                    <th>6-10 Hari</th>
                                    <th>11-15 Hari</th>
                                    <th>16-21 Hari</th>
                                    <th>22-31 Hari</th>
                                    <th>32-60 Hari</th>
                                    <th>>60 Hari</th>
                                    <th>Jumlah Keseluruhan Aduan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($BR_STATECD as $state)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ Ref::GetDescr('17',$state) }}</td>
                                    <!--<td><a target="_blank" href="{{-- url('penerimaanpenyelesaianaduan/senaraiselesai/0/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.($CA_DEPTCD != '0' ? $CA_DEPTCD : '0')) --}}" > {{-- $dataCount[$state]['<1'] --}}</a></td>-->
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesai/1/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount[$state]['1'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesai/25/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount[$state]['2-5'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesai/610/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount[$state]['6-10'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesai/1115/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount[$state]['11-15'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesai/1621/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount[$state]['16-21'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesai/2231/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount[$state]['22-31'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesai/3260/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount[$state]['32-60'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesai/60/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount[$state]['>60'] }}</a></td>
                                    <!--<td><a target="_blank" href="{{-- url('penerimaanpenyelesaianaduan/senaraiselesaimengikutnegeri/'.$state.'/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) --}}" > {{-- $dataCount[$state]['total'] --}}</a></td>-->
                                    <td> {{ $dataCount[$state]['total'] }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td><strong>JUMLAH</strong></td>
                                    <!--<td><a target="_blank" href="{{-- url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/0/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) --}}" > {{-- $dataCount['total']['<1'] --}}</a></td>-->
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/1/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount['total']['1'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/25/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount['total']['2-5'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/610/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount['total']['6-10'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/1115/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount['total']['11-15'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/1621/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount['total']['16-21'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/2231/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount['total']['22-31'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/3260/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount['total']['32-60'] }}</a></td>
                                    <td><a target="_blank" href="{{ url('penerimaanpenyelesaianaduan/senaraiselesaimengikuttempoh/60/'.$CA_RCVDT_FROM.'/'.$CA_RCVDT_TO.'/'.$CA_DEPTCD) }}" > {{ $dataCount['total']['>60'] }}</a></td>
                                    <td>{{ $dataCount['total']['total'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script_datatable')
<script type="text/javascript">
    $('#data_5 .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });

    $('.dual_select').bootstrapDualListbox({
        selectorMinimalHeight: 260,
        showFilterInputs: false,
        infoText: '',
        infoTextEmpty: ''
    });

    $(document).ready(function(){
        $('.dual').bootstrapDualListbox({
            selectorMinimalHeight: 160,
            infoText:false,
            filterPlaceHolder : 'Carian'
        });
    });
</script>

@stop