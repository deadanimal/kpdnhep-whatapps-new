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
    .form-control[readonly][type="text"] {
        background-color: #ffffff;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>LAPORAN KATEGORI ADUAN</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal','method' => 'GET', 'url'=>'laporanlainlain/kategori']) !!}
                    <div class="form-group" id="date">
                        {{ Form::label('CA_RCVDT_dri', 'Tarikh :', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-8">
                            <div class="input-daterange input-group" id="datepicker">
                                {{ 
                                    Form::text(
                                        'CA_RCVDT_dri',
                                        date('d-m-Y', strtotime($CA_RCVDT_dri)), 
                                        [
                                            'class' => 'form-control input-sm', 
                                            'id' => 'CA_RCVDT_dri',
                                            'readonly' => true,
                                            'placeholder' => 'HH-BB-TTTT'
                                        ]
                                    ) 
                                }}
                                <span class="input-group-addon">hingga</span>
                                {{ 
                                    Form::text(
                                        'CA_RCVDT_lst', 
                                        date('d-m-Y', strtotime($CA_RCVDT_lst)), 
                                        [
                                            'class' => 'form-control input-sm', 
                                            'id' => 'CA_RCVDT_lst',
                                            'readonly' => true,
                                            'placeholder' => 'HH-BB-TTTT'
                                        ]
                                    ) 
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('CA_DEPTCD', 'Bahagian :', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-6">
                            {{ Form::select('CA_DEPTCD', ReportLainlain::GetRef('315', 'semua'), null, ['class' => 'form-control input-sm', 'id' => 'CA_DEPTCD']) }}
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'cari']) }}
                        {{ link_to('laporanlainlain/kategori', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                    @if($cari)
                    <div class="col-sm-12">
                        <div class="form-group" align="center">
                            {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
                            {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                        </div>
                    </div>
                    @endif
                    {!! Form::close() !!}
                </div>

                <div class="table-responsive">
                    <table id="state-table" class="table table-striped table-header-rotated" style="width: 90%">
                        <thead>
                        <h2><center>LAPORAN ADUAN MENGIKUT KATEGORI DARI {{ date('d-m-Y', strtotime($CA_RCVDT_dri)) }} HINGGA {{ date('d-m-Y', strtotime($CA_RCVDT_lst)) }} <br/>  
                                @if ($depart != '')
                                {{ Ref::GetDescr('315',$depart,'ms') }}
                                @else
                                SEMUA BAHAGIAN
                                @endif</center></h2>
                        <tr>
                            <th style="width: 3%">Bil.</th>
                            <th style="width: 20%">Kategori</th>
                            @foreach ($mState as $state)
                            <th class="rotate-45"><div><span>{{ $state->descr }}</span></div></th>
                            @endforeach

                            <th class="rotate-45"><div><span> Jumlah </span></div></th>
                            <th class="rotate-45"><div><span> % </span></div></th>
                        </tr>
                        </thead>
                        @if($cari)
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
                            $percentge =0;
                            $countpercntge=0;
                            
                           ?>
                            <?php $i = 1;?>
                            @foreach ($query as $data)
                           
                            <?php
                           
                            $Gtotal += $data->Bilangan
                            ?>
                        
                            @endforeach
                                  @foreach ($query as $data)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data->descr }}</td>
                                <td>
                                <a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'01']) }}">
                                {{ $data->countstate1 }}
                                </a>
                                </td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'02'])}}">{{ $data->countstate2 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'03']) }}">{{ $data->countstate3 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'04']) }}">{{ $data->countstate4 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'05']) }}">{{ $data->countstate5 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'06']) }}">{{ $data->countstate6 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'07']) }}">{{ $data->countstate7 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'08']) }}">{{ $data->countstate8 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'09']) }}">{{ $data->countstate9 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'10']) }}">{{ $data->countstate10 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'11']) }}">{{ $data->countstate11 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'12']) }}">{{ $data->countstate12 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'13']) }}">{{ $data->countstate13 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'14']) }}">{{ $data->countstate14 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'15']) }}">{{ $data->countstate15 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'16']) }}">{{ $data->countstate16 }}</a></td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,$data->code,'0']) }}">{{ $data->Bilangan }}</a></td>
                                <td>@if($Gtotal != 0)
                                                    {{ $percentge=round((($data->Bilangan*100)/$Gtotal), 2) }}
                                                @else
                                                    0
                                                @endif</td>
                            </tr>
                            <?php
                            $Total01 += $data->countstate1;
                            $Total02 += $data->countstate2;
                            $Total03 += $data->countstate3;
                            $Total04 += $data->countstate4;
                            $Total05 += $data->countstate5;
                            $Total06 += $data->countstate6;
                            $Total07 += $data->countstate7;
                            $Total08 += $data->countstate8;
                            $Total09 += $data->countstate9;
                            $Total10 += $data->countstate10;
                            $Total11 += $data->countstate11;
                            $Total12 += $data->countstate12;
                            $Total13 += $data->countstate13;
                            $Total14 += $data->countstate14;
                            $Total15 += $data->countstate15;
                            $Total16 += $data->countstate16;
                            
                            ?>
                           <?php $countpercntge +=$percentge  ?>
                            @endforeach
                            <tr>
                                <td></td>
                                <td>Jumlah</td>
                                <td>
                                <a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','01']) }}">
                                {{ $Total01 }}
                                </a>
                                </td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','02']) }}">{{ $Total02 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','03']) }}">{{ $Total03 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','04']) }}">{{ $Total04 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','05']) }}">{{ $Total05 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','06']) }}">{{ $Total06 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','07']) }}">{{ $Total07 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','08']) }}">{{ $Total08 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','09']) }}">{{ $Total09 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','10']) }}">{{ $Total10 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','11']) }}">{{ $Total11 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','12']) }}">{{ $Total12 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','13']) }}">{{ $Total13 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','14']) }}">{{ $Total14 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','15']) }}">{{ $Total15 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','16']) }}">{{ $Total16 }}</td>
                                <td><a target="_blank" href="{{ route('kategori1',[$CA_RCVDT_dri->format('d-m-Y'),$CA_RCVDT_lst->format('d-m-Y'),$request->CA_DEPTCD,'0','0']) }}">{{ $Gtotal }}</td>
                                <td>{{ round($countpercntge) }}</td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="20">Jumlah Berdasarkan Status Mengikut Negeri</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Jumlah Dalam Siasatan</td>
                                <?php 
                                    $countdalamsiasatan = 0;
                                    $countsum = 0;
                                    ?>
                                @foreach($qstatus as $value)
                                    <td>{{$value['DALAMSIASATAN']}}</td>
                                    <?php
                                        $countdalamsiasatan += $value['DALAMSIASATAN'];
                                        $countsum += $value['total'];
                                    ?>
                                @endforeach
                                <td>{{$countdalamsiasatan}}</td>
                                <td>{{$countdalamsiasatan != 0? round(($countdalamsiasatan/$countsum)*100, 2) : 0}}</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jumlah Selesai</td>
                                <?php $countselesai = 0 ?>
                                @foreach($qstatus as $value)
                                    <td>{{$value['SELESAI']}}</td>
                                    <?php $countselesai += $value['SELESAI'] ?>
                                @endforeach
                                <td>{{$countselesai}}</td>
                                <td>{{$countselesai != 0? round(($countselesai/$countsum)*100, 2) : 0}}</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Jumlah Tutup</td>
                                <?php $counttutup = 0 ?>
                                @foreach($qstatus as $value)
                                    <td>{{$value['TUTUP']}}</td>
                                    <?php $counttutup += $value['TUTUP'] ?>
                                @endforeach
                                <td>{{$counttutup}}</td>
                                <td>{{$counttutup != 0? round(($counttutup/$countsum)*100, 2) : 0}}</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Jumlah EP</td>
                                <?php $countep = 0 ?>
                                @foreach($qstatusipep as $statusipep)
                                    <td>{{$statusipep['EP']}}</td>
                                    <?php $countep += $statusipep['EP'] ?>
                                @endforeach
                                <td>{{$countep}}</td>
                                <td>{{$countep != 0? round(($countep/$countsum)*100, 2) : 0}}</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Jumlah IP</td>
                                <?php $countip = 0 ?>
                                @foreach($qstatusipep as $statusipep)
                                    <td>{{$statusipep['IP']}}</td>
                                    <?php $countip += $statusipep['IP'] ?>
                                @endforeach
                                <td>{{$countip}}</td>
                                <td>{{$countip != 0? round(($countip/$countsum)*100, 2) : 0}}</td>
                            </tr>
                        </tbody>
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
//        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
</script>
@stop