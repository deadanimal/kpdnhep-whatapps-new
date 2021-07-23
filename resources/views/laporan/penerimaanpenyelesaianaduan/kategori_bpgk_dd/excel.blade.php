<?php
    use App\Ref;
    $filename = 'Laporan_Mengikut_Status_Dan_Kategori_Aduan_Drilldown_'.date('YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
?>
<html>
<style>
    .text-center {
        text-align : center;
    }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!--<table>-->
<table style="width: 100%; text-align: center">
    <thead>
    <tr>
        <td colspan="12">LAPORAN MENGIKUT STATUS DAN KATEGORI ADUAN</td>
    </tr>
    <tr>
        <td colspan="12">TARIKH TERIMA : DARI {{ date('d-m-Y', strtotime($date_start)) }}</td>
    </tr>
    <tr>
        <td colspan="12">SEHINGGA {{ date('d-m-Y', strtotime($date_end)) }}</td>
    </tr>
    <tr>
        <td colspan="12">
            <!--{{-- $departmentDesc --}} dan-->
            NEGERI : {{ !empty($state) ? Ref::GetDescr('17', $state, 'ms') : 'Semua' }}
        </td>
    </tr>
    <tr>
        <td colspan="12">
            <!--Negeri {{-- $stateDesc --}}-->
            KATEGORI : {{ !empty($subdepartment) ? Ref::GetDescr('244', $subdepartment, 'ms') : 'Semua' }}
        </td>
    </tr>
    </thead>
</table>
<table class="table table-striped table-bordered table-hover" style="width: 100%">
            <thead>
            <tr>
                <th>No.</th>
                <th>No. Aduan</th>
                <th>Aduan</th>
                <th>Nama Pengadu</th>
                <th>Nama Diadu</th>
                <th>Cawangan</th>
                <th>Kategori Aduan</th>
                <th>Tarikh Terima</th>
                <th>Tarikh Penugasan</th>
                <th>Tarikh Selesai</th>
                <th>Tarikh Penutupan</th>
                <th>Penyiasat</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>
            @foreach($data_final as $data)
                <tr>
                    <td>{{ $i }}</td>
                    <td>'{{ $data->CA_CASEID }}</td>
                    <td>{{ implode(' ', array_slice(explode(' ', ucfirst($data->CA_SUMMARY)), 0, 7)).'...' }}</td>
                    <td>{{ $data->CA_NAME }}</td>
                    <td>{{ $data->CA_AGAINSTNM }}</td>
                    <td>{{ $data->BrnCd ? $data->BrnCd->BR_BRNNM : $data->CA_BRNCD }}</td>
                    <td>{{ $data->CmplCat ? $data->CmplCat->descr : $data->CA_CMPLCAT }}</td>
                    <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
                    <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
                    <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
                    <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
                    <td>{{ $data->InvBy ? $data->InvBy->name : $data->CA_INVBY }}</td>
                </tr>
                <?php $i++; ?>
            @endforeach
            </tbody>
        </table>
</html>

<?php 
    exit;
    fclose($fp);
?>