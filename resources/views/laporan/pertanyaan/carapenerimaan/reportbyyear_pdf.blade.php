<table style="width: 100%;">
    <tr><td colspan="15"><center><h3>{{ $titleyear }}</h3></center></td></tr>
</table>
<table class="table table-striped table-bordered table-hover" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
            <th>Bil.</th>
            <th>Cara Penerimaan</th>
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
       @if($cari)
       <?php
        $tJan=0;
        $tFeb=0;
        $tMac=0;
        $tApr=0;
        $tMei=0;
        $tJun=0;
        $tJul=0;
        $tOgo=0;
        $tSep=0;
        $tOkt=0;
        $tNov=0;
        $tDis=0;
        $ttotal=0;
       ?>
        <?php $i = 1; ?>
        @foreach ($query as $mSA)
        <tr>
            <td> {{ $i++ }} </td>
            <td> {{ $mSA->descr}}</td>
            <td>{{ $mSA->JAN}}</td>
            <td>{{ $mSA->FEB}}</td>
            <td>{{ $mSA->MAC}}</td>
            <td>{{ $mSA->APR}}</td>
            <td>{{ $mSA->MEI}}</td>
            <td>{{ $mSA->JUN}}</td>
            <td>{{ $mSA->JUL}}</td>
            <td>{{ $mSA->OGO}}</td>
            <td>{{ $mSA->SEP}}</td>
            <td>{{ $mSA->OKT}}</td>
            <td>{{ $mSA->NOV}}</td>
            <td>{{ $mSA->DIS}}</td>
            <td>{{ $mSA->Bilangan}}</td>
        </tr>
        <?php
        $tJan += $mSA->JAN;
        $tFeb += $mSA->FEB;
        $tMac += $mSA->MAC;
        $tApr += $mSA->APR;
        $tMei += $mSA->MEI;
        $tJun += $mSA->JUN;
        $tJul += $mSA->JUL;
        $tOgo += $mSA->OGO;
        $tSep += $mSA->SEP;
        $tOkt += $mSA->OKT;
        $tNov += $mSA->NOV;
        $tDis += $mSA->DIS;
        $ttotal += $mSA->Bilangan
        ?>
        @endforeach

        <tr>
            <td colspan="2"><center>Jumlah</center></td>
            <td>{{ $tJan }}</td>
            <td>{{ $tFeb }}</td>
            <td>{{ $tMac }}</td>
            <td>{{ $tApr }}</td>
            <td>{{ $tMei }}</td>
            <td>{{ $tJun }}</td>
            <td>{{ $tJul }}</td>
            <td>{{ $tOgo }}</td>
            <td>{{ $tSep }}</td>
            <td>{{ $tOkt }}</td>
            <td>{{ $tNov }}</td>
            <td>{{ $tDis }}</td>
            <td>{{$ttotal}}</td>
        </tr>
        @endif
    </tbody>
</table>