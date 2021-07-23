<?php
    use App\Ref;
?>
<table style="width: 100%;">
    <tr><td><center><h6>
        Laporan Statistik Aduan Menghasilkan Kes Mengikut Negeri & Cawangan
    </h6></center></td></tr>
    <tr><td><center><h6>
        Tarikh Penerimaan Aduan : Dari
        {{ $datestart->format('d-m-Y') }} 
        Hingga 
        {{ $dateend->format('d-m-Y') }}
    </h6></center></td></tr>
    <tr><td><center><h6>NEGERI : {{ $statedesc }}</h6></center></td></tr>
</table>
<table class="table table-bordered" style="width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse" border="1">
    <thead>
        <tr>
            <th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">No. Aduan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Aduan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Nama Pengadu</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Nama Diadu</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Cawangan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Kategori Aduan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Terima</th>
            <!-- <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Penugasan</th> -->
            <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Selesai</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Penutupan</th>
            <th style="border: 1px solid #000; background: #f3f3f3;">Penyiasat</th>
        </tr>
    </thead>
    <tbody>
        <?php // $i = 1; ?>
        @foreach($que as $key => $data)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $data->CA_CASEID }}</td>
            <td>{{ implode(' ', array_slice(explode(' ', ucfirst($data->CA_SUMMARY)), 0, 7)).'...' }}</td>
            <td>{{ $data->CA_NAME }}</td>
            <td>{{ $data->CA_AGAINSTNM }}</td>
            <td>
                <!-- {{-- $data->BrnCd ? $data->BrnCd->BR_BRNNM : $data->CA_BRNCD --}} -->
                {{ $data->BR_BRNNM }}
            </td>
            <td>{{ $data->CmplCat ? $data->CmplCat->descr : $data->CA_CMPLCAT }}</td>
            <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
            <!-- <td>{{-- $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' --}}</td> -->
            <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
            <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
            <td>{{ $data->InvBy ? $data->InvBy->name : $data->CA_INVBY }}</td>
        </tr>
        <?php // $i++; ?>
        @endforeach
    </tbody>
</table>