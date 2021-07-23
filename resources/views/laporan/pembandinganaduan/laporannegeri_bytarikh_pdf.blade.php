<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
<style>
    .text-align-center{
        text-align: center;
    }
</style>
<table style="width: 100%">
    <tr><td colspan="34"><center><h3 align="center">
        LAPORAN MENGIKUT KATEGORI ADUAN
    </h3></center></td></tr>
    <tr><td colspan="34"><center><h3>BULAN 
        {{ $request->get('CA_RCVDT_MONTH') != '' ? Ref::GetDescr('206', $request->get('CA_RCVDT_MONTH'), 'ms') : '' }}
        TAHUN {{ $request->get('CA_RCVDT_YEAR') }}
    </h3></center></td></tr>
    <tr><td colspan="34"><center><h3>
        {{ $request->get('BR_STATECD') != '0' ? Ref::GetDescr('17', $request->get('BR_STATECD'), 'ms') : 'SEMUA NEGERI' }}
    </h3></center></td></tr>
    <tr><td colspan="34"><center><h3>
        {{ $request->get('CA_DEPTCD') != '0' ? Ref::GetDescr('315', $request->get('CA_DEPTCD'), 'ms') : 'SEMUA BAHAGIAN' }}
    </h3></center></td></tr>
</table>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" style="width: 100%; border:1px solid; border-collapse: collapse" border="1">
        <thead>
            <tr>
                <th rowspan="2">Bil.</th>
                <th rowspan="2">Kategori</th>
                <th colspan="32">Jumlah Aduan Diterima Mengikut Hari</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 31; $i++)
                    <th>{{ $i }}</th>
                @endfor
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $counttotalday1 = 0;
                $counttotalday2 = 0;
                $counttotalday3 = 0;
                $counttotalday4 = 0;
                $counttotalday5 = 0;
                $counttotalday6 = 0;
                $counttotalday7 = 0;
                $counttotalday8 = 0;
                $counttotalday9 = 0;
                $counttotalday10 = 0;
                $counttotalday11 = 0;
                $counttotalday12 = 0;
                $counttotalday13 = 0;
                $counttotalday14 = 0;
                $counttotalday15 = 0;
                $counttotalday16 = 0;
                $counttotalday17 = 0;
                $counttotalday18 = 0;
                $counttotalday19 = 0;
                $counttotalday20 = 0;
                $counttotalday21 = 0;
                $counttotalday22 = 0;
                $counttotalday23 = 0;
                $counttotalday24 = 0;
                $counttotalday25 = 0;
                $counttotalday26 = 0;
                $counttotalday27 = 0;
                $counttotalday28 = 0;
                $counttotalday29 = 0;
                $counttotalday30 = 0;
                $counttotalday31 = 0;
                $counttotal = 0;
            ?>
            @foreach ($caseinfo as $category)
                <?php ${'counttotalcategory' . $category->CA_CMPLCAT} = 0; ?>
                <tr>
                    <td class="text-align-center">{{ $loop->iteration }}</td>
                    <td>{{ Ref::GetDescr('244', $category->CA_CMPLCAT, 'ms') }}</td>
                    @for ($i = 1; $i <= 31; $i++)
                        <td class="text-align-center">{{ ${'countday'.$i} = $category->{'count'.$i} }}</td>
                    @endfor
                    <td class="text-align-center">{{ $category->countcaseid }}</td>
                </tr>
                <?php
                $counttotalday1 += $category->count1;
                $counttotalday2 += $category->count2;
                $counttotalday3 += $category->count3;
                $counttotalday4 += $category->count4;
                $counttotalday5 += $category->count5;
                $counttotalday6 += $category->count6;
                $counttotalday7 += $category->count7;
                $counttotalday8 += $category->count8;
                $counttotalday9 += $category->count9;
                $counttotalday10 += $category->count10;
                $counttotalday11 += $category->count11;
                $counttotalday12 += $category->count12;
                $counttotalday13 += $category->count13;
                $counttotalday14 += $category->count14;
                $counttotalday15 += $category->count15;
                $counttotalday16 += $category->count16;
                $counttotalday17 += $category->count17;
                $counttotalday18 += $category->count18;
                $counttotalday19 += $category->count19;
                $counttotalday20 += $category->count20;
                $counttotalday21 += $category->count21;
                $counttotalday22 += $category->count22;
                $counttotalday23 += $category->count23;
                $counttotalday24 += $category->count24;
                $counttotalday25 += $category->count25;
                $counttotalday26 += $category->count26;
                $counttotalday27 += $category->count27;
                $counttotalday28 += $category->count28;
                $counttotalday29 += $category->count29;
                $counttotalday30 += $category->count30;
                $counttotalday31 += $category->count31;
                $counttotal += $category->countcaseid;
                ?>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-align-center">Jumlah</td>
                <td class="text-align-center">{{ $counttotalday1 }}</td>
                <td class="text-align-center">{{ $counttotalday2 }}</td>
                <td class="text-align-center">{{ $counttotalday3 }}</td>
                <td class="text-align-center">{{ $counttotalday4 }}</td>
                <td class="text-align-center">{{ $counttotalday5 }}</td>
                <td class="text-align-center">{{ $counttotalday6 }}</td>
                <td class="text-align-center">{{ $counttotalday7 }}</td>
                <td class="text-align-center">{{ $counttotalday8 }}</td>
                <td class="text-align-center">{{ $counttotalday9 }}</td>
                <td class="text-align-center">{{ $counttotalday10 }}</td>
                <td class="text-align-center">{{ $counttotalday11 }}</td>
                <td class="text-align-center">{{ $counttotalday12 }}</td>
                <td class="text-align-center">{{ $counttotalday13 }}</td>
                <td class="text-align-center">{{ $counttotalday14 }}</td>
                <td class="text-align-center">{{ $counttotalday15 }}</td>
                <td class="text-align-center">{{ $counttotalday16 }}</td>
                <td class="text-align-center">{{ $counttotalday17 }}</td>
                <td class="text-align-center">{{ $counttotalday18 }}</td>
                <td class="text-align-center">{{ $counttotalday19 }}</td>
                <td class="text-align-center">{{ $counttotalday20 }}</td>
                <td class="text-align-center">{{ $counttotalday21 }}</td>
                <td class="text-align-center">{{ $counttotalday22 }}</td>
                <td class="text-align-center">{{ $counttotalday23 }}</td>
                <td class="text-align-center">{{ $counttotalday24 }}</td>
                <td class="text-align-center">{{ $counttotalday25 }}</td>
                <td class="text-align-center">{{ $counttotalday26 }}</td>
                <td class="text-align-center">{{ $counttotalday27 }}</td>
                <td class="text-align-center">{{ $counttotalday28 }}</td>
                <td class="text-align-center">{{ $counttotalday29 }}</td>
                <td class="text-align-center">{{ $counttotalday30 }}</td>
                <td class="text-align-center">{{ $counttotalday31 }}</td>
                <td class="text-align-center">{{ $counttotal }}</td>
            </tr>
        </tfoot>
    </table>
</div>