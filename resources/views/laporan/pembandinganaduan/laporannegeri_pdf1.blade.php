<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
<style>
    h5, td {
        text-align: center;
    }
</style>
<!--<title>Laporan Perbandingan Aduan Mengikut Negeri (Bulanan)</title>-->
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <h5 align="center">LAPORAN PERBANDINGAN ADUAN MENGIKUT NEGERI (BULANAN)</h5>
                    <h5 align="center">
                        @if($CA_RCVDT_MONTH)
                            BULAN {{ Ref::GetDescr('206', $CA_RCVDT_MONTH, 'ms') }}
                        @endif
                        TAHUN {{ $CA_RCVDT_YEAR }}
                    </h5>
                    <h5 align="center">
                        {{ Ref::GetDescr('17', $BR_STATECD, 'ms') }}
                    </h5>
                    <h5 align="center">{{ $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD, 'ms') : 'SEMUA BAHAGIAN' }}</h5>
                    <table id="senaraitable" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%; border:1px solid; border-collapse: collapse; font-size: 11px;" border="1">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>No. Aduan</th>
                                    <th>Keterangan Aduan</th>
                                    <th>Nama Pengadu</th>
                                    <th>Nama Diadu</th>
                                    <th>Negeri</th>
                                    <th>Kategori</th>
                                    <th>Tarikh Penerimaan</th>
                                    <!--<th width="1%">Penyiasat</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($query as $senaraiaduan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a onclick="ShowSummary('{{ $senaraiaduan->CA_CASEID }}')">{{ $senaraiaduan->CA_CASEID }}</a>
                                        </td>
                                        <td>
                                            {{ implode(' ', array_slice(explode(' ', $senaraiaduan->CA_SUMMARY), 0, 5)).' ...' }}
                                            {{-- substr($senaraiaduan->CA_SUMMARY, 0, 50).'...' --}}
                                        </td>
                                        <td>{{ $senaraiaduan->CA_NAME }} </td>
                                        <td>{{ $senaraiaduan->CA_AGAINSTNM }} </td>
                                        <td>
                                            {{ $senaraiaduan->BR_STATECD != '' ? Ref::GetDescr('17', $senaraiaduan->BR_STATECD, 'ms') : '' }}
                                        </td>
                                        <td>
                                            {{ $senaraiaduan->CA_CMPLCAT != '' ? Ref::GetDescr('244', $senaraiaduan->CA_CMPLCAT, 'ms') : '' }}
                                        </td>
                                        <td>
                                            {{ $senaraiaduan->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($senaraiaduan->CA_RCVDT)) : '' }}
                                        </td>
<!--                                        <td width="1%">
                                            {{-- $senaraiaduan->CA_INVBY != '' ? BandingAduan::getUserName($senaraiaduan->CA_INVBY) : '' --}}
                                        </td>-->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>