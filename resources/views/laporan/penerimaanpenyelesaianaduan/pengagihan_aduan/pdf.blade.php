<style>
    th, td {
        text-align: left;
        font-size: 12px;
    }
</style>
<div class="row">
    <div style="text-align: center;">
        <h3>
            <div style="text-align: center;">
                LAPORAN PINDAH ADUAN MENGIKUT NEGERI BAGI TAHUN {{ $SelectYear }} <br>
                DARI {{ $monthFromDesc }} HINGGA {{ $monthToDesc }}<br>
                {{ $departmentDesc }}
            </div>
        </h3>
    </div>
    <div class="table-responsive">
        <table id="state-table" width="950"
               class="table table-striped table-bordered table-hover dataTables-example"
               style="overflow-wrap: break-word; width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse;"
               border="1">
            <thead>
            <tr>
                <th>Bil.</th>
                <th>Negeri</th>
                <th>Jumlah Aduan</th>
                <th> < 1 hari</th>
                <th> 1 hari</th>
                <th> 2-3 hari</th>
                <th> > 3 hari</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($BR_STATECD as $state)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{$stateList[$state]}}</td>
                    <td>{{ $dataCount[$state]['total'] }}</td>
                    <td>{{ $dataCount[$state]['<1'] }}</td>
                    <td>{{ $dataCount[$state]['1'] }}</td>
                    <td>{{ $dataCount[$state]['2-3'] }}</td>
                    <td>{{ $dataCount[$state]['>3'] }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th><strong>JUMLAH</strong></th>
                <th>{{ $dataCount['total']['total'] }}</th>
                <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['<1'] }}</a></th>
                <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['1'] }}</a></th>
                <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['2-3'] }}</a></th>
                <th><a target="_blank" {{-- href="route()" --}} > {{ $dataCount['total']['>3'] }}</a></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
