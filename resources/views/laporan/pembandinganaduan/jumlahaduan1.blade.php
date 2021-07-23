@extends('layouts.main')
<?php
    use App\Ref;
    use App\Branch;
    use App\Laporan\BandingAduan;
?>
@section('content')
    <div class="ibox-content">
        <h3 align="center">LAPORAN PERBANDINGAN ADUAN MENGIKUT STATUS (TAHUNAN)</h3>
        <center><h3>
            @if(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM < $CA_RCVDT_YEAR_TO))
                DARI {{ $CA_RCVDT_YEAR_FROM }} HINGGA {{ $CA_RCVDT_YEAR_TO }}
            @elseif(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM == $CA_RCVDT_YEAR_TO))
                TAHUN {{ $CA_RCVDT_YEAR_TO }}
            @endif
        </h3></center>
        <h3 align="center">
            {{ Ref::GetDescr('17', $BR_STATECD, 'ms') }}
        </h3>
        <h3 align="center">{{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h3>
        <center><h3>
            {{ $CA_INVSTS != '00' ? 'STATUS ADUAN : ' . Ref::GetDescr('292', $CA_INVSTS, 'ms') : 'SEMUA STATUS ADUAN' }}
        </h3></center>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($query as $senaraiaduan)
                        <tr>
                            <td width="1%">{{ $loop->iteration }}</td>
                            <td width="1%">
                                <a onclick="ShowSummary('{{ $senaraiaduan->CA_CASEID }}')">{{ $senaraiaduan->CA_CASEID }}</a>
                            </td>
                            <td width="1%">
                                {{ implode(' ', array_slice(explode(' ', $senaraiaduan->CA_SUMMARY), 0, 5)).' ...' }}
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