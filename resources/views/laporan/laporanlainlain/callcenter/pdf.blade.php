<table style="width: 100%;">
    <tr><td colspan="15"><center><h3>{{ $titleyear }}</h3></center></td></tr>
</table>
<table class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
            <th>Bil.</th>
            <th>Nama</th>
            <th> Jan</th>
            <th> Feb </th>
            <th> Mac </th>
            <th> Apr </th>
            <th> Mei </th>
            <th> Jun </th>
            <th> Jul </th>
            <th> Ogo </th>
            <th> Sep </th>
            <th> Okt </th>
            <th> Nov </th>
            <th> Dis </th>
            <th> Jumlah </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $tJan = 0;
        $tFeb = 0;
        $tMac = 0;
        $tApr = 0;
        $tMei = 0;
        $tJun = 0;
        $tJul = 0;
        $tOgo = 0;
        $tSep = 0;
        $tOkt = 0;
        $tNov = 0;
        $tDis = 0;
        $ttotal = 0;
        ?>
        @foreach ($datas as $data)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $data->name }}</td>
            <td>{{ $data->JAN}}</td>
            <td>{{ $data->FEB}}</td>
            <td>{{ $data->MAC}}</td>
            <td>{{ $data->APR}}</td>
            <td>{{ $data->MEI}}</td>
            <td>{{ $data->JUN}}</td>
            <td>{{ $data->JUL}}</td>
            <td>{{ $data->OGO}}</td>
            <td>{{ $data->SEP}}</td>
            <td>{{ $data->OKT}}</td>
            <td>{{ $data->NOV}}</td>
            <td>{{ $data->DIS}}</td>
            <td>{{ $data->Total}}</td>
        </tr>
        @endforeach
    </tbody>
</table>