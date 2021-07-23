@extends('layouts.main')
<?php

use App\Ref;
use App\Laporan\BandingAduan;
?>
@section('content')
<style>
    th, td {
        text-align: center;
        font-size: 11px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Laporan Mengikut Status, Harian, Negeri Dan Kategori Aduan</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class'=>'form-horizontal', 'method'=>'GET', 'url'=>'pembandinganaduan/laporannegeri_bytarikh']) !!}
                    <div class="form-group">
                        {{ Form::label('CA_RCVDT_MONTH', 'Bulan', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::select('CA_RCVDT_MONTH', BandingAduan::GetRefList('206', ''), $CA_RCVDT_MONTH_CURRENT, ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_MONTH']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('CA_RCVDT_YEAR', 'Tahun', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::select('CA_RCVDT_YEAR', BandingAduan::GetYearList(), $CA_RCVDT_YEAR_CURRENT, ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_YEAR']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('BR_STATECD', 'Negeri', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::select('BR_STATECD', BandingAduan::GetRefList('17', 'semua0'), null, ['class' => 'form-control input-sm', 'id' => 'BR_STATECD']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::select('CA_DEPTCD', BandingAduan::GetRefDeptList('315', $BR_STATECD, 'semua'), null, ['class' => 'form-control input-sm', 'id' => 'CA_DEPTCD']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <select name="CA_CMPLCAT[]" class="form-control dual_select" multiple>
                                @foreach ($mRefCategory as $category)
                                    @if(in_array($category->code, $CA_CMPLCAT))
                                        <option value="{{ $category->code }}" selected="selected">{{ $category->descr }}</option>
                                    @else
                                        <option value="{{ $category->code }}">{{ $category->descr }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'action']) }}
                        {{ link_to('pembandinganaduan/laporannegeri_bytarikh', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                    </div>
                    @if($action)
                        <div class="col-sm-12">
                            <div class="form-group" align="center">
                                {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Versi Excel', ['type' => 'submit' , 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }} &nbsp;
                                {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun Versi PDF', ['type' => 'submit' ,'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
                            </div>
                        </div>
                    @endif
                    {!! Form::close() !!}
                </div>
                @if($action != '')
                <table style="width: 100%">
                    <tr><td><center><h3 align="center">
                        LAPORAN MENGIKUT KATEGORI ADUAN
                    </h3></center></td></tr>
                    <tr><td><center><h3>BULAN 
                        {{ $request->get('CA_RCVDT_MONTH') != '' ? Ref::GetDescr('206', $request->get('CA_RCVDT_MONTH'), 'ms') : '' }}
                        TAHUN {{ $request->get('CA_RCVDT_YEAR') }}
                    </h3></center></td></tr>
                    <tr><td><center><h3>
                        {{ $request->get('BR_STATECD') != '0' ? Ref::GetDescr('17', $request->get('BR_STATECD'), 'ms') : 'SEMUA NEGERI' }}
                    </h3></center></td></tr>
                    <tr><td><center><h3>
                        {{ $request->get('CA_DEPTCD') != '0' ? Ref::GetDescr('315', $request->get('CA_DEPTCD'), 'ms') : 'SEMUA BAHAGIAN' }}
                    </h3></center></td></tr>
                </table>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th rowspan="2">Bil.</th>
                                <th rowspan="2">Kategori</th>
                                <th colspan="32">Jumlah Aduan Diterima Mengikut Hari</th>
                            </tr>
                            <tr>
                                @for ($i = 1; $i <= 31; $i++)
                                    <th>{{ $i }}</th>
                                @endfor
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counttotalday1 = 0;
                            $counttotalday2 = 0;
                            $counttotalday3 = 0;
                            $counttotalday4 = 0;
                            $counttotalday5 = 0;
                            $counttotalday6 = 0;
                            $counttotalday7 = 0;
                            $counttotalday8 = 0;
                            $counttotalday9 = 0;
                            $counttotalday10 = 0;
                            $counttotalday11 = 0;
                            $counttotalday12 = 0;
                            $counttotalday13 = 0;
                            $counttotalday14 = 0;
                            $counttotalday15 = 0;
                            $counttotalday16 = 0;
                            $counttotalday17 = 0;
                            $counttotalday18 = 0;
                            $counttotalday19 = 0;
                            $counttotalday20 = 0;
                            $counttotalday21 = 0;
                            $counttotalday22 = 0;
                            $counttotalday23 = 0;
                            $counttotalday24 = 0;
                            $counttotalday25 = 0;
                            $counttotalday26 = 0;
                            $counttotalday27 = 0;
                            $counttotalday28 = 0;
                            $counttotalday29 = 0;
                            $counttotalday30 = 0;
                            $counttotalday31 = 0;
                            $counttotal = 0;
                            ?>
                            @foreach ($caseinfo as $category)
                                <?php ${'counttotalcategory' . $category->CA_CMPLCAT} = 0; ?>
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{-- $category->CA_CMPLCAT --}}
                                        {{ Ref::GetDescr('244', $category->CA_CMPLCAT, 'ms') }}
                                    </td>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <td>
                                            <!--<a target="_blank" href="{{-- route() --}}">-->
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/senarai?BR_STATECD='.$rowstate->BR_STATECD.'&month='.$month.'&CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&CA_RCVDT_MONTH_FROM='.$CA_RCVDT_MONTH_FROM.'&CA_RCVDT_MONTH_TO='.$CA_RCVDT_MONTH_TO.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/laporannegeri_bytarikh1?CA_CMPLCAT='.$category->CA_CMPLCAT.'&day='.$i.'&CA_RCVDT_MONTH='.$request->get('CA_RCVDT_MONTH').'&CA_RCVDT_YEAR='.$request->get('CA_RCVDT_YEAR').'&BR_STATECD='.$request->BR_STATECD.'&CA_DEPTCD='.$request->CA_DEPTCD) --}}">-->
                                            <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, $category->CA_CMPLCAT, $i]) }}">
                                                {{ ${'countday'.$i} = $category->{'count'.$i} }}
                                            </a>
                                        </td>
                                    @endfor
                                    <td>
                                        <!--<a target="_blank" href="{{-- route() --}}">--> 
                                        <!--<a target="_blank" href="{{-- url('pembandinganaduan/laporannegeri_bytarikh1?CA_CMPLCAT='.$category->CA_CMPLCAT.'&CA_RCVDT_MONTH='.$request->get('CA_RCVDT_MONTH').'&CA_RCVDT_YEAR='.$request->get('CA_RCVDT_YEAR').'&BR_STATECD='.$request->BR_STATECD.'&CA_DEPTCD='.$request->CA_DEPTCD) --}}">-->
                                        <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, $category->CA_CMPLCAT, '0']) }}">
                                            {{ $category->countcaseid }}
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                    $counttotalday1 += $category->count1;
                                    $counttotalday2 += $category->count2;
                                    $counttotalday3 += $category->count3;
                                    $counttotalday4 += $category->count4;
                                    $counttotalday5 += $category->count5;
                                    $counttotalday6 += $category->count6;
                                    $counttotalday7 += $category->count7;
                                    $counttotalday8 += $category->count8;
                                    $counttotalday9 += $category->count9;
                                    $counttotalday10 += $category->count10;
                                    $counttotalday11 += $category->count11;
                                    $counttotalday12 += $category->count12;
                                    $counttotalday13 += $category->count13;
                                    $counttotalday14 += $category->count14;
                                    $counttotalday15 += $category->count15;
                                    $counttotalday16 += $category->count16;
                                    $counttotalday17 += $category->count17;
                                    $counttotalday18 += $category->count18;
                                    $counttotalday19 += $category->count19;
                                    $counttotalday20 += $category->count20;
                                    $counttotalday21 += $category->count21;
                                    $counttotalday22 += $category->count22;
                                    $counttotalday23 += $category->count23;
                                    $counttotalday24 += $category->count24;
                                    $counttotalday25 += $category->count25;
                                    $counttotalday26 += $category->count26;
                                    $counttotalday27 += $category->count27;
                                    $counttotalday28 += $category->count28;
                                    $counttotalday29 += $category->count29;
                                    $counttotalday30 += $category->count30;
                                    $counttotalday31 += $category->count31;
                                    $counttotal += $category->countcaseid;
                                ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">Jumlah</td>
                                <td> 
                                    <!--{{-- BandingAduan::jumlahlaporannegeritarikh($i) --}}--> 
                                    <!--<a target="_blank" href="{{-- route() --}}"> {{ $counttotalday1 }} </a>-->
                                    <!--<a target="_blank" href="{{-- url('pembandinganaduan/laporannegeri_bytarikh1?day='.'1'.'&CA_RCVDT_MONTH='.$request->get('CA_RCVDT_MONTH').'&CA_RCVDT_YEAR='.$request->get('CA_RCVDT_YEAR').'&BR_STATECD='.$request->BR_STATECD.'&CA_DEPTCD='.$request->CA_DEPTCD) --}}">--> 
                                    <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '1']) }}">
                                        {{ $counttotalday1 }} 
                                    </a>
                                </td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '2']) }}">{{ $counttotalday2 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '3']) }}">{{ $counttotalday3 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '4']) }}">{{ $counttotalday4 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '5']) }}">{{ $counttotalday5 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '6']) }}">{{ $counttotalday6 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '7']) }}">{{ $counttotalday7 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '8']) }}">{{ $counttotalday8 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '9']) }}">{{ $counttotalday9 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '10']) }}">{{ $counttotalday10 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '11']) }}">{{ $counttotalday11 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '12']) }}">{{ $counttotalday12 }}</a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '13']) }}">{{ $counttotalday13 }}</a></td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '14']) }}"> {{ $counttotalday14 }} </a></td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '15']) }}"> {{ $counttotalday15 }} </a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '16']) }}"> {{ $counttotalday16 }} </a></td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '17']) }}"> {{ $counttotalday17 }} </a></td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '18']) }}"> {{ $counttotalday18 }} </a></td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '19']) }}"> {{ $counttotalday19 }} </a></td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '20']) }}"> {{ $counttotalday20 }} </a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '21']) }}">  {{ $counttotalday21 }} </a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '22']) }}"> {{ $counttotalday22 }} </a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '23']) }}"> {{ $counttotalday23 }} </a> </td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '24']) }}"> {{ $counttotalday24 }} </a></td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '25']) }}"> {{ $counttotalday25 }} </a></td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '26']) }}"> {{ $counttotalday26 }} </a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '27']) }}">  {{ $counttotalday27 }}</a> </td>
                                <td> <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '28']) }}"> {{ $counttotalday28 }} </a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '29']) }}"> {{ $counttotalday29 }} </a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '30']) }}"> {{ $counttotalday30 }} </a></td>
                                <td><a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '31']) }}"> {{ $counttotalday31 }} </a></td>
                                <td>
                                    <!--<a target="_blank" href="{{-- url('pembandinganaduan/laporannegeri_bytarikh1?CA_RCVDT_MONTH='.$request->get('CA_RCVDT_MONTH').'&CA_RCVDT_YEAR='.$request->get('CA_RCVDT_YEAR').'&BR_STATECD='.$request->BR_STATECD.'&CA_DEPTCD='.$request->CA_DEPTCD) --}}">-->
                                    <a target="_blank" href="{{ route('negeribytarikh1', [$request->CA_RCVDT_YEAR, $request->CA_RCVDT_MONTH, $request->BR_STATECD, $request->CA_DEPTCD, '0', '0']) }}">
                                        {{ $counttotal }} 
                                    </a>
                                </td>
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

@section('script_datatable')
<script type="text/javascript">
    $(function () {
        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight: 260,
            showFilterInputs: false,
            infoText: '',
            infoTextEmpty: ''
        });
//        $('.dual_select').bootstrapDualListbox({
//            selectorMinimalHeight: 260
//        });

        $('#BR_STATECD').on('change', function (e) {
            var BR_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('pembandinganaduan/getdeptlistbystate') }}" + "/" + BR_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_DEPTCD"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_DEPTCD"]').append('<option value="' + value + '">' + key + '</option>');
                    });
//                    $('select[name="CA_CMPLCAT[]"]').empty();
                    $('.dual_select').bootstrapDualListbox('refresh');
                    $('#CA_DEPTCD').change();
                }
            });
        });
        $('#CA_DEPTCD').on('change', function (e) {
            var CA_DEPTCD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('pembandinganaduan/getcategorylist') }}" + "/" + CA_DEPTCD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_CMPLCAT[]"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_CMPLCAT[]"]').append('<option value="' + key + '">' + value + '</option>');
                        $('.dual_select').bootstrapDualListbox('refresh');
                    });
                }
            });
        });
    });
</script>
@stop