@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Senarai Aduan Pengguna Awam</h2>
            <div class="ibox-content">
                <table id="complaint-public-table" class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                        <tr>
                            <th width="1%">Bil.</th>
                            <th width="1%">No. Aduan</th>
                            <th width="1%">Keterangan Aduan</th>
                            <th width="1%">Nama Pengadu</th>
                            <th width="1%">Nama Diadu</th>
                            <th width="1%">Kategori</th>
                            <th width="1%">Tarikh Penerimaan</th>
                            <th width="1%">Penyiasat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($complaintlist as $senaraiaduan)
                            <tr>
                                <td width="1%">{{ $loop->iteration }}</td>
                                <td width="1%">
                                    <a onclick="ShowSummary('{{ $senaraiaduan->CA_CASEID }}')">{{ $senaraiaduan->CA_CASEID }}</a>
                                </td>
                                <td width="1%">
                                    {{ implode(' ', array_slice(explode(' ', $senaraiaduan->CA_SUMMARY), 0, 5)).' ...' }}
                                    {{-- substr($senaraiaduan->CA_SUMMARY, 0, 50).'...' --}}
                                </td>
                                <td width="1%">{{ $senaraiaduan->CA_NAME }} </td>
                                <td width="1%">{{ $senaraiaduan->CA_AGAINSTNM }} </td>
                                <td width="1%">
                                    {{ $senaraiaduan->CA_CMPLCAT != '' ? Ref::GetDescr('244', $senaraiaduan->CA_CMPLCAT, 'ms') : '' }}
                                </td>
                                <td width="1%">
                                    {{ $senaraiaduan->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($senaraiaduan->CA_RCVDT)) : '' }}
                                </td>
                                <td width="1%">
                                    {{ $senaraiaduan->CA_INVBY != '' ? BandingAduan::getUserName($senaraiaduan->CA_INVBY) : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div align="center">
                    {{ link_to('publicuser', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                </div>
            </div>
        </div>
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