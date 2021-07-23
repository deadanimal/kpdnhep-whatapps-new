<table class="table table-striped table-bordered table-hover" style="width: 100%">
    <thead>
    <tr>
        <th width="25%">Nama Pegawai</th>
        <th width="10%">Jumlah Maklumbalas (Tutup)</th>
        <th width="10%">Jumlah Aduan Dicipta</th>
        <th width="10%">Jumlah Keseluruhan</th>
        <th width="10%">Peratusan (%)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data_final as $datum)
        <tr>
            <td>{{$datum['name']}}</td>
            <td>{{$datum['close']}}</td>
            <td>{{$datum['ticket']}}</td>
            <td>{{$datum['total']}}</td>
            <td>{{$datum['pct']}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr style="background-color: lightblue">
        <th>Jumlah</th>
        @foreach($data_final_total_by_type as $datum)
            <th>{{$datum}}</th>
        @endforeach
        <th>100.00</th>
    </tr>
    </tfoot>
</table>