@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Laporan Kategori Aduan</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'pembandinganaduan/kategori']) !!}
                        <div class="form-group" id="date">
                            {{ Form::label('CA_RCVDT_FROM', 'Tarikh', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-6">
                                <div class="input-daterange input-group" id="datepicker">
                                    {{ Form::text('CA_RCVDT_FROM', '', ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_FROM']) }}
                                    <span class="input-group-addon">hingga</span>
                                    {{ Form::text('CA_RCVDT_TO', '', ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_TO']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                {{ Form::select('CA_DEPTCD', BandingAduan::GetRefList('315', 'semua'), null, ['class' => 'form-control input-sm', 'id' => 'CA_DEPTCD']) }}
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'action']) }}
                            {{ link_to('pembandinganaduan/kategori', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="table-responsive">
                    <table id="kategori-table" class="table table-striped table-bordered table-hover" style="width: 100%">
                        @if($action != '')
                            <thead>
                                <h3 align="center">LAPORAN ADUAN MENGIKUT KATEGORI DARI {{ $CA_RCVDT_FROM }} HINGGA {{ $CA_RCVDT_TO }}</h3>
                                <h3 align="center">
                                    @if($CA_DEPTCD != '')
                                        {{ Ref::GetDescr('315', $CA_DEPTCD, 'ms') }}
                                    @else
                                        SEMUA BAHAGIAN
                                    @endif
                                </h3>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Kategori</th>
                                    @foreach ($mRefNegeri as $mNegeri)
                                        <th>{{ $mNegeri->descr }}</th>
                                    @endforeach
                                    <th>Jumlah</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $countstate1total = 0;
                                    $countstate2total = 0;
                                    $countstate3total = 0;
                                    $countstate4total = 0;
                                    $countstate5total = 0;
                                    $countstate6total = 0;
                                    $countstate7total = 0;
                                    $countstate8total = 0;
                                    $countstate9total = 0;
                                    $countstate10total = 0;
                                    $countstate11total = 0;
                                    $countstate12total = 0;
                                    $countstate13total = 0;
                                    $countstate14total = 0;
                                    $countstate15total = 0;
                                    $countstate16total = 0;
                                    $counttotalall = 0;
                                    $percenttotal = 0;
                                ?>
                                @foreach ($mRefKategori as $mRefData)
                                    <?php 
                                        $bandingcount = BandingAduan::kategorinegeri($CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD, $mRefData->code)->first();
                                        if(!empty($bandingcount)){
                                            $counttotalall += $bandingcount->countcaseid;
                                        }
                                    ?>
                                @endforeach
                                @foreach ($mRefKategori as $mRefData)
                                    <?php 
                                        $banding = BandingAduan::kategorinegeri($CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD, $mRefData->code)->first();
                                        if(!empty($banding)){
                                    ?>
                                    <tr>
                                        <td>{{ $bil++ }}</td>
                                        <td>{{ $mRefData->descr }}</td>
                                        <td>{{ $banding->countstate1 }}</td>
                                        <td>{{ $banding->countstate2 }}</td>
                                        <td>{{ $banding->countstate3 }}</td>
                                        <td>{{ $banding->countstate4 }}</td>
                                        <td>{{ $banding->countstate5 }}</td>
                                        <td>{{ $banding->countstate6 }}</td>
                                        <td>{{ $banding->countstate7 }}</td>
                                        <td>{{ $banding->countstate8 }}</td>
                                        <td>{{ $banding->countstate9 }}</td>
                                        <td>{{ $banding->countstate10 }}</td>
                                        <td>{{ $banding->countstate11 }}</td>
                                        <td>{{ $banding->countstate12 }}</td>
                                        <td>{{ $banding->countstate13 }}</td>
                                        <td>{{ $banding->countstate14 }}</td>
                                        <td>{{ $banding->countstate15 }}</td>
                                        <td>{{ $banding->countstate16 }}</td>
                                        <td>{{ $banding->countcaseid }}</td>
                                        <td>
                                            <?php
                                                if ($counttotalall == 0){
                                                    $percent = 0;
                                                } else if ($counttotalall > 0){
                                                    $percent = round(($banding->countcaseid/$counttotalall)*100,2);
                                                }
                                                $percenttotal += $percent;
                                            ?>
                                            {{ $percent }}
                                        </td>
                                    </tr>
                                    <?php 
                                            $countstate1total += $banding->countstate1;
                                            $countstate2total += $banding->countstate2;
                                            $countstate3total += $banding->countstate3;
                                            $countstate4total += $banding->countstate4;
                                            $countstate5total += $banding->countstate5;
                                            $countstate6total += $banding->countstate6;
                                            $countstate7total += $banding->countstate7;
                                            $countstate8total += $banding->countstate8;
                                            $countstate9total += $banding->countstate9;
                                            $countstate10total += $banding->countstate10;
                                            $countstate11total += $banding->countstate11;
                                            $countstate12total += $banding->countstate12;
                                            $countstate13total += $banding->countstate13;
                                            $countstate14total += $banding->countstate14;
                                            $countstate15total += $banding->countstate15;
                                            $countstate16total += $banding->countstate16;
                                        }
                                    ?>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <?php
//                                    $countstate1percent = ($countstate1total/$counttotalall)*100;
                                ?>
                                <tr>
                                    <td colspan="2">Jumlah</td>
                                    <td>{{ $countstate1total }}</td>
                                    <td>{{ $countstate2total }}</td>
                                    <td>{{ $countstate3total }}</td>
                                    <td>{{ $countstate4total }}</td>
                                    <td>{{ $countstate5total }}</td>
                                    <td>{{ $countstate6total }}</td>
                                    <td>{{ $countstate7total }}</td>
                                    <td>{{ $countstate8total }}</td>
                                    <td>{{ $countstate9total }}</td>
                                    <td>{{ $countstate10total }}</td>
                                    <td>{{ $countstate11total }}</td>
                                    <td>{{ $countstate12total }}</td>
                                    <td>{{ $countstate13total }}</td>
                                    <td>{{ $countstate14total }}</td>
                                    <td>{{ $countstate15total }}</td>
                                    <td>{{ $countstate16total }}</td>
                                    <td>{{ $counttotalall }}</td>
                                    <td>{{ round($percenttotal) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
    $('#date .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
</script>
@stop