<?php
    $filename = 'Laporan Aduan Menghasilkan Kes '.date('YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<table>
    <tr><td colspan="11"><center><h3>
        Laporan Statistik Aduan Menghasilkan Kes Mengikut Negeri & Cawangan
    </h3></center></td></tr>
    <tr><td colspan="11"><center><h3>
        Tarikh Penerimaan Aduan : Dari
        {{ $datestart->format('d-m-Y') }} 
        Hingga 
        {{ $dateend->format('d-m-Y') }}
    </h3></center></td></tr>
    <tr><td colspan="11"><center><h3>NEGERI : {{ $statedesc }}</h3></center></td></tr>
</table>
<table class="table table-striped table-bordered table-hover" border="1">
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
            <td>
                <!-- {{-- $i --}} -->
                {{ $key+1 }}
            </td>
            <td>'{{ $data->CA_CASEID }}</td>
            <td>
                <!-- {{-- implode(' ', array_slice(explode(' ', ucfirst($data->CA_SUMMARY)), 0, 7)).'...' --}} -->
                {{ $data->CA_SUMMARY }}
            </td>
            <td>{{ $data->CA_NAME }}</td>
            <td>{{ $data->CA_AGAINSTNM }}</td>
            <td>
                <!-- {{-- $data->BrnCd ? $data->BrnCd->BR_BRNNM : $data->CA_BRNCD --}} -->
                {{ $data->BR_BRNNM }}
            </td>
            <td>{{ $data->CmplCat ? $data->CmplCat->descr : $data->CA_CMPLCAT }}</td>
            <td>{{ $data->CA_RCVDT ? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)) : '' }}</td>
            <!-- <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td> -->
            <td>{{ $data->CA_COMPLETEDT ? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)) : '' }}</td>
            <td>{{ $data->CA_CLOSEDT ? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)) : '' }}</td>
            <td>{{ $data->InvBy ? $data->InvBy->name : $data->CA_INVBY }}</td>
        </tr>
        <?php // $i++; ?>
        @endforeach
    </tbody>
</table>
<?php 
    exit;
    fclose($fp);
?>