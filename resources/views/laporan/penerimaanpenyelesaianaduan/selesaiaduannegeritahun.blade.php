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
                                        <option value="{{ $negeri->code }}">{{ $negeri->descr }}</option>
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
                        <tr><td><h3><center><?php echo $CA_DEPTCD != '' ?  Ref::GetDescr('315',$CA_DEPTCD) : 'Semua Bahagian'?></center></h3></td></tr>
                    </table>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Negeri</th>
                                    <th>1 Hari</th>
                                    <th>2-5 Hari</th>
                                    <th>6-10 Hari</th>
                                    <th>11-15 Hari</th>
                                    <th>16-21 Hari</th>
                                    <th>22-30 Hari</th>
                                    <th>31-60 Hari</th>
                                    <th>>60 Hari</th>
                                    <th>Jumlah Keseluruhan Aduan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $countCOUNT = 0;
                                $countCount25 = 0;
                                $countCount610 = 0;
                                $countCount1115 = 0;
                                $countCount1621 = 0;
                                $countCount2230 = 0;
                                $countCount3160 = 0;
                                $countCount60 = 0;
                                $TotalCount = 0;?>
                                @foreach($SenaraiTempohAduan as $senaraiSelesai)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ Ref::GetDescr('17',$senaraiSelesai->BR_STATECD) }}</td>
                                    <td><a target="_blank"
                                               href="{{ route('senaraiselesai',['0', $state, $SelectYear, $MonthFrom, $MonthTo, $CA_DEPTCD]) }}"> {{ $senaraiSelesai->COUNT }}</a></td>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $senaraiSelesai->Count25 }}</a></td>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $senaraiSelesai->Count610 }} </a></td>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $senaraiSelesai->Count1115 }} </a></td>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $senaraiSelesai->Count1621 }} </a></td>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $senaraiSelesai->Count2230 }}</a></td>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $senaraiSelesai->Count3160 }}</a></td>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $senaraiSelesai->Count60 }} </a></td>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $senaraiSelesai->TOTAL_CASE }}</a></td>
                                </tr>
                                <?php
                                    $count++;
                                    $countCOUNT += $senaraiSelesai->COUNT;
                                    $countCount25 += $senaraiSelesai->Count25;
                                    $countCount610 += $senaraiSelesai->Count610;
                                    $countCount1115 += $senaraiSelesai->Count1115;
                                    $countCount1621 += $senaraiSelesai->Count1621;
                                    $countCount2230 += $senaraiSelesai->Count2230;
                                    $countCount3160 += $senaraiSelesai->Count3160;
                                    $countCount60 += $senaraiSelesai->Count60;
                                ?>
                                @endforeach
                                <tr>
                                    <td colspan="2" right>Jumlah</td>
                                    <td> <a target="_blank" href="{{-- route() --}}"> {{ $countCOUNT }} </a></td>
                                    <td> <a target="_blank" href="{{-- route() --}}"> {{ $countCount25 }} </a></td>
                                    <td> <a target="_blank" href="{{-- route() --}}"> {{ $countCount610 }} </a></td>
                                    <td> <a target="_blank" href="{{-- route() --}}"> {{ $countCount1115 }} </a></td>
                                    <td> <a target="_blank" href="{{-- route() --}}"> {{ $countCount1621 }} </a></td>
                                    <td> <a target="_blank" href="{{-- route() --}}"> {{ $countCount2230 }} </a></td>
                                    <td> <a target="_blank" href="{{-- route() --}}"> {{ $countCount3160 }} </a></td>
                                    <td> <a target="_blank" href="{{-- route() --}}"> {{ $countCount60 }} </a></td>
                                    <?php $TotalCount = $countCOUNT+$countCount25+$countCount610+$countCount1115+
                                                        $countCount1621+$countCount2230+$countCount3160+$countCount60; ?>
                                    <td><a target="_blank" href="{{-- route() --}}"> {{ $TotalCount }} </a> </td>
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