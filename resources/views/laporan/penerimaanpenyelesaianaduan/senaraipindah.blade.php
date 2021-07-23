@extends('layouts.main')
<?php
use App\Ref;
use App\Laporan\ReportYear;
use App\Laporan\TerimaSelesaiAduan;
?>
@section('content')
<div class="ibox-content">
    <!--<h2>Laporan Aduan (Senarai)</h2>-->
    <h3 align="center"><center>LAPORAN PINDAH ADUAN MENGIKUT NEGERI BAGI TAHUN {{ $CA_RCVDT_YEAR }}
                                DARI <?php echo $CA_RCVDT_MONTH_FROM != '' ? Ref::GetDescr('206', $CA_RCVDT_MONTH_FROM) : '' ?> HINGGA 
                                <?php echo $CA_RCVDT_MONTH_TO != '' ? Ref::GetDescr('206', $CA_RCVDT_MONTH_TO) : '' ?><br>
                            <?php echo $CA_DEPTCD != '' ?  Ref::GetDescr('315',$CA_DEPTCD) : 'Semua Bahagian'?> </center></h3>
{!! Form::open(['method' => 'GET']) !!}
<div class="form-group" align="center">
    {{ Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) }}
    {{ Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) }}
</div>
{!! Form::close() !!}
    <div class="table-responsive">
        <table id="senaraitable" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%; font-size: 11px;">
            <thead>
                <tr>
                    <th width="1%">Bil.</th>
                    <th width="1%">No. Aduan</th>
                    <th width="1%">Keterangan Aduan</th>
                    <th width="1%">Nama Pengadu</th>
                    <th width="1%">Nama Diadu</th>
                    <th width="1%">Negeri</th>
                    <th width="1%">Kategori</th>
                    <th width="1%">Tarikh Penerimaan</th>
                    <!--<th width="1%">Penyiasat</th>-->
                </tr>
            </thead>
            <tbody>
                @foreach($lists as $senaraiaduan)
                <tr>
                    <td width="3px">{{ $i ++ }}</td>
                    <!--<td width="1%">{{-- $senaraiaduan->CA_CASEID --}}</td>-->
                    <td width="1%">
                            <a onclick="ShowSummary('{{ $senaraiaduan->CA_CASEID }}')">{!! $senaraiaduan->CA_CASEID !!}</a>
                        </td>
                        <td width="1%">
                            {{ implode(' ', array_slice(explode(' ', $senaraiaduan->CA_SUMMARY), 0, 5)).' ...' }}
                            {{-- substr($senaraiaduan->CA_SUMMARY, 0, 50).'...' --}}
                        </td>
                        <td width="1%">{{ $senaraiaduan->CA_NAME }} </td>
                        <td width="1%">{{ $senaraiaduan->CA_AGAINSTNM }} </td>
                        <td width="1%">
                            {{ $senaraiaduan->BR_STATECD != '' ? Ref::GetDescr('17', $senaraiaduan->BR_STATECD, 'ms') : '' }}
                        </td>
                        <td width="1%">
                            {{ $senaraiaduan->CA_CMPLCAT != '' ? Ref::GetDescr('244', $senaraiaduan->CA_CMPLCAT, 'ms') : '' }}
                        </td>
                        <td width="1%">
                            {{ $senaraiaduan->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($senaraiaduan->CA_RCVDT)) : '' }}
                        </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
@stop
@section('script_datatable')
    <script type="text/javascript">
        function ShowSummary(CASEID){
            $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
        }
    </script>
@stop
