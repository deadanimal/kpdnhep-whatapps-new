<table style="width: 100%;">
    <tr><td><center><h3>STATUS SIASATAN ADUAN YANG MENGHASILKAN KES MENGIKUT KATEGORI</h3></center></td></tr>
    <tr><td><center><h3>SALURAN PENERIMAAN</h3></center></td></tr>
    <tr><td><center><h3>TAHUN {{ $Request->year }}</h3></center></td></tr>
</table>
<table class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
            <th>Sumber Aduan</th>
            <th>Jumlah Aduan Diterima</th>
            <th>Aduan Menghasilkan Kes</th>
            <th>Kes Disiasat Oleh SAS</th>
            <th>Kes Disiasat Oleh Unit/Negeri/Cawangan</th>
        </tr>
    </thead>
    <tbody>
    @foreach($dataFinal as $key => $datum)
        <tr>
            <td>{{$refCategory[$key]}}</td>
            <td>{{$datum['diterima']}}</td>
            <td>{{$datum['hasil']}}</td>
            <td>{{$datum['disiasatsas']}}</td>
            <td>{{$datum['disiasatlain']}}</td>
        </tr>
    @endforeach
        <tr style="font-weight:bold">
            <td>Jumlah</td>
            <td>{{$dataCounter['diterima']}}</td>
            <td>{{$dataCounter['hasil']}}</td>
            <td>{{$dataCounter['disiasatsas']}}</td>
            <td>{{$dataCounter['disiasatlain']}}</td>
        </tr>
    </tbody>
</table>