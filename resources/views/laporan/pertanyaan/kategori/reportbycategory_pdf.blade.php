<style>
    .center {
        text-align: center;
    }
</style>
<table style="width: 100%;">
    <tr><td colspan="15"><center><h3>{{ $titleyear }}</h3></center></td></tr>
</table>
<table class="table table-striped table-bordered table-hover" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
            <th>Bil.</th>
            <th>Kategori</th>
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
            <td class="center"> {{ $i++ }} </td>
            <td> {{ $mSA->descr}}</td>
            <td class="center">{{ $mSA->JAN}}</td>
            <td class="center">{{ $mSA->FEB}}</td>
            <td class="center">{{ $mSA->MAC}}</td>
            <td class="center">{{ $mSA->APR}}</td>
            <td class="center">{{ $mSA->MEI}}</td>
            <td class="center">{{ $mSA->JUN}}</td>
            <td class="center">{{ $mSA->JUL}}</td>
            <td class="center">{{ $mSA->OGO}}</td>
            <td class="center">{{ $mSA->SEP}}</td>
            <td class="center">{{ $mSA->OKT}}</td>
            <td class="center">{{ $mSA->NOV}}</td>
            <td class="center">{{ $mSA->DIS}}</td>
            <td class="center">{{ $mSA->Bilangan}}</td>
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
            <td class="center">{{ $tJan }}</td>
            <td class="center">{{ $tFeb }}</td>
            <td class="center">{{ $tMac }}</td>
            <td class="center">{{ $tApr }}</td>
            <td class="center">{{ $tMei }}</td>
            <td class="center">{{ $tJun }}</td>
            <td class="center">{{ $tJul }}</td>
            <td class="center">{{ $tOgo }}</td>
            <td class="center">{{ $tSep }}</td>
            <td class="center">{{ $tOkt }}</td>
            <td class="center">{{ $tNov }}</td>
            <td class="center">{{ $tDis }}</td>
            <td class="center">{{$ttotal}}</td>
        </tr>
        @endif
    </tbody>
</table>