<?php
    use App\Ref;
    use App\Branch;
    use App\Laporan\BandingAduan;
    $filename = 'Laporan Tindakan Pertama '.date('YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                    <table style="width: 100%;">
                        <tr><td colspan="9"><h3><center>Laporan Tindakan Pertama - Bahagian Gerakan Kepenggunaan (BGK)</center></h3></td></tr>
                        <tr><td colspan="9"><h3><center>
                            Tarikh Penerimaan Aduan : {{ date('d-m-Y', strtotime($datestart)) }} 
                            {{ $dateend != '' ?  'Hingga' : '' }}
                            {{ date('d-m-Y', strtotime($dateend)) }}
                        </center></h3></td></tr>
                        <tr><td colspan="9"><h3><center>
                            Bahagian : {{ !empty($department) 
                            ? Ref::GetDescr('315', $department) 
                            : 'Semua Bahagian' }}
                        </center></h3></td></tr>
                        <tr><td colspan="9"><h3><center>
                            Negeri : {{ !empty($state) 
                            ? Ref::GetDescr('17', $state) 
                            : 'Semua Negeri' }}
                        </center></h3></td></tr>
                    </table>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" style="width: 100%" border="1">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Bil.</th>
                                    <th style="text-align: center">Status Aduan</th>
                                    <th style="text-align: center">1 Hari</th>
                                    <th style="text-align: center">2 - 3 Hari</th>
                                    <th style="text-align: center">4 - 7 Hari</th>
                                    <th style="text-align: center">8 - 14 Hari</th>
                                    <th style="text-align: center">15 - 21 Hari</th>
                                    <th style="text-align: center">> 21 Hari</th>
                                    <th style="text-align: center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($statuses as $key => $datum)
                                    <tr>
                                        <td style="text-align: center">{{ $i++ }}</td>
                                        <td>
                                            {{ $datum != '' ? Ref::GetDescr('292', $datum) : '' }}
                                        </td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['1'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['2-3'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['4-7'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['8-14'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['15-21'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['>21'] }}</td>
                                        <td style="text-align: center">{{ $dataCount[$datum]['total'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="text-align: center"><strong>Jumlah</strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['1'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['2-3'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['4-7'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['8-14'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['15-21'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['>21'] }}<strong></td>
                                    <td style="text-align: center"><strong>{{ $dataCount['total']['total'] }}<strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    exit;
    fclose($fp);
?>
            