@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Aduan Mengikut Status (Tahunan)</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['class' => 'form-horizontal', 'method' =>'GET']) !!}
                        <div class="form-group" id="date">
                            {{ Form::label('CA_RCVDT_YEAR_FROM', 'Tahun Terima', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                <div class="input-group">
                                    {{ Form::select('CA_RCVDT_YEAR_FROM', BandingAduan::GetYearList(), null, ['class' => 'form-control input-sm']) }}
                                    <span class="input-group-addon">hingga</span>
                                    {{ Form::select('CA_RCVDT_YEAR_TO', BandingAduan::GetYearList(), $CA_RCVDT_YEAR_TO, ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('BR_STATECD', 'Negeri', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                {{ Form::select('BR_STATECD', BandingAduan::GetRefList('17', 'semua0'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-5">
                                {{ Form::select('CA_DEPTCD', BandingAduan::GetRefDeptList('315', $BR_STATECD, 'semua'), null, ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name'=>'action']) }}
                            {{ link_to('pembandinganaduan/jumlahaduan', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
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
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <h3 align="center">LAPORAN STATUS BAGI ADUAN YANG TERIMA 
                                    @if(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM < $CA_RCVDT_YEAR_TO))
                                        DARI {{ $CA_RCVDT_YEAR_FROM }} HINGGA {{ $CA_RCVDT_YEAR_TO }}
                                    @elseif(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM == $CA_RCVDT_YEAR_TO))
                                        {{ $CA_RCVDT_YEAR_TO }}
                                    @endif
                                </h3>
                                <h3 align="center">
                                    @if($BR_STATECD != '0')
                                        {{ Ref::GetDescr('17', $BR_STATECD, 'ms') }}
                                    @else
                                        SEMUA NEGERI
                                    @endif
                                </h3>
                                <h3 align="center">
                                    @if($CA_DEPTCD != '0')
                                        {{ Ref::GetDescr('315', $CA_DEPTCD, 'ms') }}
                                    @else
                                        SEMUA BAHAGIAN
                                    @endif
                                </h3>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Status</th>
                                    <!--{{-- @foreach($CA_RCVDT_YEAR_LIST as $year) --}}-->
                                        <!--<th>{{-- $year->year --}}</th>-->
                                    <!--{{-- @endforeach --}}-->
                                    @for($year=$CA_RCVDT_YEAR_FROM; $year<=$CA_RCVDT_YEAR_TO; $year++)
                                        <th>{{ $year }}</th>
                                    @endfor
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counttotal = 0; ?>
                                @foreach ($mRefStatus as $status)
                                    <?php
                                        ${'counttotalstatus'.$status->code} = 0;
                                    ?>
                                    <tr>
                                        <td>{{ $bil++ }}</td>
                                        <td>{{ $status->descr }}</td>
                                        <!--{{-- @foreach($CA_RCVDT_YEAR_LIST as $year) --}}-->
                                            <!--<td>{{-- BandingAduan::jumlahaduanstatustahun($status->code, $year->year) --}}</td>-->
                                        <!--{{-- @endforeach --}}-->
                                        @for($CA_RCVDT_YEAR=$CA_RCVDT_YEAR_FROM; $CA_RCVDT_YEAR<=$CA_RCVDT_YEAR_TO; $CA_RCVDT_YEAR++)
                                            <td>
                                                <!--<a target="_blank" href="{{-- url('pembandinganaduan/jumlahaduan1?CA_INVSTS='.$status->code.'&CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&BR_STATECD='.$BR_STATECD.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                                <a target="_blank" href="{{ route('jumlahaduan1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_RCVDT_YEAR, $CA_DEPTCD, $BR_STATECD, $status->code]) }}">
                                                    <!--{{-- ${'countstatus'.$status->code.'year'.$CA_RCVDT_YEAR} = BandingAduan::jumlahaduanstatustahun($status->code, $CA_RCVDT_YEAR, $BR_STATECD) --}}-->
                                                    {{ ${'countstatus'.$status->code.'year'.$CA_RCVDT_YEAR} = BandingAduan::jumlahaduanstatustahun($status->code, $CA_RCVDT_YEAR, $BR_STATECD, $CA_DEPTCD) }}
                                                </a>
                                            </td>
                                            <?php ${'counttotalstatus'.$status->code} += ${'countstatus'.$status->code.'year'.$CA_RCVDT_YEAR}; ?>
                                        @endfor
                                        <td>
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/jumlahaduan1?CA_INVSTS='.$status->code.'&CA_RCVDT_YEAR_FROM='.$CA_RCVDT_YEAR_FROM.'&CA_RCVDT_YEAR_TO='.$CA_RCVDT_YEAR_TO.'&BR_STATECD='.$BR_STATECD.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                            <a target="_blank" href="{{ route('jumlahaduan1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, 0, $CA_DEPTCD, $BR_STATECD, $status->code]) }}">
                                                {{ ${'counttotalstatus'.$status->code} }}
                                            </a>
                                        </td>
                                        <!--<td>{{ BandingAduan::jumlahaduanstatus($status->code) }}</td>-->
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Jumlah</td>
                                    <!--{{-- @foreach($CA_RCVDT_YEAR_LIST as $year) --}}-->
                                        <!--<td><a target="_blank" href="{{-- route() --}}">{{-- BandingAduan::jumlahaduantahun($year->year) --}}</a></td>-->
                                    <!--{{-- @endforeach --}}-->
                                    @for($CA_RCVDT_YEAR=$CA_RCVDT_YEAR_FROM; $CA_RCVDT_YEAR<=$CA_RCVDT_YEAR_TO; $CA_RCVDT_YEAR++)
                                        <td>
                                            <!--<a target="_blank" href="{{-- url('pembandinganaduan/jumlahaduan1?CA_RCVDT_YEAR='.$CA_RCVDT_YEAR.'&BR_STATECD='.$BR_STATECD.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                            <a target="_blank" href="{{ route('jumlahaduan1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_RCVDT_YEAR, $CA_DEPTCD, $BR_STATECD, '00']) }}">
                                                {{ ${'counttotalyear'.$CA_RCVDT_YEAR} = BandingAduan::jumlahaduantahun($CA_RCVDT_YEAR, $BR_STATECD, $CA_DEPTCD) }}
                                            </a>
                                        </td>
                                        <?php $counttotal += ${'counttotalyear'.$CA_RCVDT_YEAR}; ?>
                                    @endfor
                                    <td>
                                        <!--<a target="_blank" href="{{-- url('pembandinganaduan/jumlahaduan1?CA_RCVDT_YEAR_FROM='.$CA_RCVDT_YEAR_FROM.'&CA_RCVDT_YEAR_TO='.$CA_RCVDT_YEAR_TO.'&BR_STATECD='.$BR_STATECD.'&CA_DEPTCD='.$CA_DEPTCD) --}}">-->
                                        <a target="_blank" href="{{ route('jumlahaduan1', [$CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, 0, $CA_DEPTCD, $BR_STATECD, '00']) }}">
                                            <!--{{-- BandingAduan::jumlahaduanmengikutstatustahunan() --}}-->
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
                    }
                });
            });
        });
    </script>
@stop