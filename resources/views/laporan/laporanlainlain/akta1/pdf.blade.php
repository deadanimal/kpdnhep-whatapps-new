<style>
    th, td {
        text-align: left;
        font-size: 12px;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td style="text-align: left;"><h2>Senarai Aduan</h2></td>
    </tr>
    <tr>
        <td style="text-align: left;"><h3>LAPORAN AKTA ADUAN MENGIKUT NEGERI</h3></td>
    </tr>
    <tr>
        <td style="text-align: left;"><p><b>Tarikh:</b> {{ $ds->format('d-m-Y') }} HINGGA {{ $de->format('d-m-Y') }}</p></td>
    </tr>
    <tr>
        <td style="text-align: left;"><p><b>Bahagian:</b> {{ $dp_desc }}</p></td>
    </tr>
    <tr>
        <td style="text-align: left;"><p><b>Negeri:</b> {{ $st_desc }}</p></td>
    </tr>
    <tr>
        <td style="text-align: left;"><p><b>Cawangan:</b> {{ $br_desc }}</p></td>
    </tr>
    <tr>
        <td style="text-align: left;"><p><b>Akta:</b> {{ $ac_desc }}</p></td>
    </tr>
</table>
<br>
<table class="table table-striped table-bordered table-hover"
       style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
    <tr>
        <th style="border: 1px solid #000; background: #f3f3f3;">No.</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">No. Aduan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Aduan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Nama Pengadu</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Nama Diadu</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Cawangan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Kategori Aduan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Terima</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Penugasan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Selesai</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Penutupan</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Penyiasat</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    @foreach($data_final as $data)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $data->CA_CASEID }}</td>
            <td>{{ implode(' ', array_slice(explode(' ', ucfirst($data->CA_SUMMARY)), 0, 7)).'...' }}</td>
            <td>{{ $data->CA_NAME }}</td>
            <td>{{ $data->CA_AGAINSTNM }}</td>
            <td>{{ $data->BR_BRNNM }}</td>
            <td>{{ $category_list[$data->CA_CMPLCAT]}}</td>
            <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
            <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
            <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
            <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
            <td>{{ $data->CA_INVBY? $data->name:'' }}</td>
        </tr>
        <?php $i++; ?>
    @endforeach
    </tbody>
</table>
<table>
    <tr>
        {{--<td>Dijana pada {{date('d-m-Y H:i:s')}} oleh {{ Auth::user()->name }}</td>--}}
    </tr>
</table>