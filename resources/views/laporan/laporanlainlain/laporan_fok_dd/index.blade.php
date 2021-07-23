@extends('layouts.main')
<?php
    use App\Ref;
?>
@section('content')
    <style>
        th, td {
            text-align: left;
            font-size: 12px;
        }
    </style>
    <div class="table-responsive">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;">
                    <h3>LAPORAN ADUAN OLEH PENGGUNA "FRIENDS OF KPDNHEP" (FOK)</h3>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <h3>
                        TARIKH PENERIMAAN ADUAN : DARI 
                        {{ \Carbon\Carbon::parse($datestart)->format('d-m-Y') }}
                        HINGGA 
                        {{ \Carbon\Carbon::parse($dateend)->format('d-m-Y') }}
                    </h3>
                </td>
            </tr>
            @if($docno)
            <tr>
                <td style="text-align: center;">
                    <!-- <h3>NEGERI : {{ !empty($state) ? Ref::GetDescr('17', $state, 'ms') : 'Semua' }}</h3> -->
                    <h3>NO. KAD PENGENALAN / PASPORT : {{ $docno }}</h3>
                </td>
            </tr>
            @endif
            <tr>
                <td style="text-align: center;">
                    <!-- <h3>KATEGORI : {{ !empty($subdepartment) ? Ref::GetDescr('244', $subdepartment, 'ms') : 'Semua' }}</h3> -->
                </td>
            </tr>
            {!! Form::open(['method' => 'GET']) !!}
                <div class="form-group" align="center">
                    <!-- {{-- Form::button('<i class="fa fa-file-excel-o"></i> Muat Turun Excel', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'excel', 'value' => '1']) --}} -->
                    <!-- {{-- Form::button('<i class="fa fa-file-pdf-o"></i> Muat Turun PDF', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'name'=>'pdf', 'value' => '1', 'formtarget' => '_blank']) --}} -->
                    <!-- <div class="form-group" align="center"> -->
                    <a target="_blank"
                        href="/senaraipenggunafok/dd?datestart={{ $datestart }}&dateend={{ $dateend }}&docno={{ $docno }}&gen=pdf"
                        class="btn btn-success">
                        <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Muat Turun Versi PDF
                    </a>
                    <!-- </div> -->
                </div>
            {!! Form::close() !!}
        </table>
        <table class="table table-striped table-bordered table-hover" style="width: 100%">
            <thead>
                <tr>
                    <th>Bil.</th>
                    <th>No. Aduan</th>
                    <th>Aduan</th>
                    <th>Nama Pengadu</th>
                    <th>Nama Diadu</th>
                    <th>Cawangan</th>
                    <th>Kategori Aduan</th>
                    <th>Tarikh Terima</th>
                    <th>Tarikh Penugasan</th>
                    <th>Tarikh Selesai</th>
                    <th>Tarikh Penutupan</th>
                    <th>Penyiasat</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach($query as $data)
                    <tr>
                        <td>{{ $i }}</td>
                        <td><a onclick="ShowSummary('{{ $data->CA_CASEID }}')">{{ $data->CA_CASEID }}</a></td>
                        <td>{{ implode(' ', array_slice(explode(' ', ucfirst($data->CA_SUMMARY)), 0, 7)).'...' }}</td>
                        <td>{{ $data->CA_NAME }}</td>
                        <td>{{ $data->CA_AGAINSTNM }}</td>
                        <td>{{ $data->BrnCd ? $data->BrnCd->BR_BRNNM : $data->CA_BRNCD }}</td>
                        <td>{{ $data->CmplCat ? $data->CmplCat->descr : $data->CA_CMPLCAT }}</td>
                        <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
                        <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
                        <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
                        <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
                        <td>{{ $data->InvBy ? $data->InvBy->name : $data->CA_INVBY }}</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Modal Start -->
    <div id="modal-show-summary" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id='ModalShowSummary'></div>
        </div>
    </div>
    <!-- Modal End -->
@stop

@section('script_datatable')
    <script type="text/javascript">
        function ShowSummary(CASEID) {
            $('#modal-show-summary').modal('show').find('#ModalShowSummary').load("{{ route('tugas.showsummary','') }}" + '/' + CASEID)
        }
    </script>
@stop