
<table style="width: 100%;">
    <tr><td colspan="15"><center><h3>{{ $titleyear }}</h3></center></td></tr>
    <tr><td colspan="15"><center><h3>{{ $titledepart }}</h3></center></td></tr>
</table>
<table class="table table-striped table-header-rotated" style="width: 100%; font-size: 10px;" border="1">
    <thead>
        <tr>
            <th>Bil.</th>
            <th>Cara Penerimaan</th>
            @foreach ($SenaraiNegeri as $Negeri)
            <th>{{ $Negeri->descr }}</th>
            @endforeach
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @if($cari)
        <?php
        $Total01 = 0;
        $Total02 = 0;
        $Total03 = 0;
        $Total04 = 0;
        $Total05 = 0;
        $Total06 = 0;
        $Total07 = 0;
        $Total08 = 0;
        $Total09 = 0;
        $Total10 = 0;
        $Total11 = 0;
        $Total12 = 0;
        $Total13 = 0;
        $Total14 = 0;
        $Total15 = 0;
        $Total16 = 0;
        $Gtotal = 0;
       ?>
        <?php $i = 1;?>
        @foreach ($query as $data)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $data->descr }}</td>
            <td>{{ $data->KOD01 }}</td>
            <td>{{ $data->KOD02 }}</td>
            <td>{{ $data->KOD03 }}</td>
            <td>{{ $data->KOD04 }}</td>
            <td>{{ $data->KOD05 }}</td>
            <td>{{ $data->KOD06 }}</td>
            <td>{{ $data->KOD07 }}</td>
            <td>{{ $data->KOD08 }}</td>
            <td>{{ $data->KOD09 }}</td>
            <td>{{ $data->KOD10 }}</td>
            <td>{{ $data->KOD11 }}</td>
            <td>{{ $data->KOD12 }}</td>
            <td>{{ $data->KOD13 }}</td>
            <td>{{ $data->KOD14 }}</td>
            <td>{{ $data->KOD15 }}</td>
            <td>{{ $data->KOD16 }}</td>
            <td>{{ $data->Bilangan }}</td>
        </tr>
        <?php
        $Total01 += $data->KOD01;
        $Total02 += $data->KOD02;
        $Total03 += $data->KOD03;
        $Total04 += $data->KOD04;
        $Total05 += $data->KOD05;
        $Total06 += $data->KOD06;
        $Total07 += $data->KOD07;
        $Total08 += $data->KOD08;
        $Total09 += $data->KOD09;
        $Total10 += $data->KOD10;
        $Total11 += $data->KOD11;
        $Total12 += $data->KOD12;
        $Total13 += $data->KOD13;
        $Total14 += $data->KOD14;
        $Total15 += $data->KOD15;
        $Total16 += $data->KOD16;
        $Gtotal += $data->Bilangan
        ?>
        @endforeach
        <tr>
            <td></td>
            <td>Jumlah</td>
            <td>{{ $Total01 }}</a></td>
            <td>{{ $Total02 }}</td>
            <td>{{ $Total03 }}</td>
            <td>{{ $Total04 }}</td>
            <td>{{ $Total05 }}</td>
            <td>{{ $Total06 }}</td>
            <td>{{ $Total07 }}</td>
            <td>{{ $Total08 }}</td>
            <td>{{ $Total09 }}</td>
            <td>{{ $Total10 }}</td>
            <td>{{ $Total11 }}</td>
            <td>{{ $Total12 }}</td>
            <td>{{ $Total13 }}</td>
            <td>{{ $Total14 }}</td>
            <td>{{ $Total15 }}</td>
            <td>{{ $Total16 }}</td>
            <td>{{ $Gtotal }}</td>
        </tr>
        @endif
    </tbody>
</table>