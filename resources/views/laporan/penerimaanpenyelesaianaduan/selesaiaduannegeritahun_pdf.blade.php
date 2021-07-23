<?php
    use App\Ref;
    use App\Laporan\ReportYear;
    use App\Laporan\TerimaSelesaiAduan;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                    <table style="width: 100%;">
                        <tr><td><h3><center>LAPORAN PENYELESAIAN ADUAN MENGIKUT NEGERI</center></h3></td></tr>
                        <tr><td><h3><center>{{ date('d-m-Y', strtotime($CA_RCVDT_FROM)) }} <?php echo $CA_RCVDT_TO != '' ?  'hingga' : '';?> {{ date('d-m-Y', strtotime($CA_RCVDT_TO)) }}</center></h3></td></tr>
                        <tr><td><h3><center><?php echo !empty($CA_DEPTCD) ? Ref::GetDescr('315',$CA_DEPTCD) : 'Semua Bahagian'?></center></h3></td></tr>
                    </table>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" style="width: 100%; border:1px solid; border-collapse: collapse" border="1">
                        <!--<table class="table table-striped table-bordered table-hover" style="width: 100%">-->
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Negeri</th>
                                    <!--<th><1 Hari</th>-->
                                    <th>1 Hari</th>
                                    <th>2-5 Hari</th>
                                    <th>6-10 Hari</th>
                                    <th>11-15 Hari</th>
                                    <th>16-21 Hari</th>
                                    <th>22-31 Hari</th>
                                    <th>32-60 Hari</th>
                                    <th>>60 Hari</th>
                                    <th>Jumlah Keseluruhan Aduan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($BR_STATECD as $state)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ Ref::GetDescr('17',$state) }}</td>
                                    <!--<td><a> {{-- $dataCount[$state]['<1'] --}}</a></td>-->
                                    <td><a> {{ $dataCount[$state]['1'] }}</a></td>
                                    <td><a> {{ $dataCount[$state]['2-5'] }}</a></td>
                                    <td><a> {{ $dataCount[$state]['6-10'] }}</a></td>
                                    <td><a> {{ $dataCount[$state]['11-15'] }}</a></td>
                                    <td><a> {{ $dataCount[$state]['16-21'] }}</a></td>
                                    <td><a> {{ $dataCount[$state]['22-31'] }}</a></td>
                                    <td><a> {{ $dataCount[$state]['32-60'] }}</a></td>
                                    <td><a> {{ $dataCount[$state]['>60'] }}</a></td>
                                    <td><a> {{ $dataCount[$state]['total'] }}</a></td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td><strong>JUMLAH</strong></td>
                                    <!--<td><a> {{-- $dataCount['total']['<1'] --}}</a></td>-->
                                    <td><a> {{ $dataCount['total']['1'] }}</a></td>
                                    <td><a> {{ $dataCount['total']['2-5'] }}</a></td>
                                    <td><a> {{ $dataCount['total']['6-10'] }}</a></td>
                                    <td><a> {{ $dataCount['total']['11-15'] }}</a></td>
                                    <td><a> {{ $dataCount['total']['16-21'] }}</a></td>
                                    <td><a> {{ $dataCount['total']['22-31'] }}</a></td>
                                    <td><a> {{ $dataCount['total']['32-60'] }}</a></td>
                                    <td><a> {{ $dataCount['total']['>60'] }}</a></td>
                                    <td>{{ $dataCount['total']['total'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>