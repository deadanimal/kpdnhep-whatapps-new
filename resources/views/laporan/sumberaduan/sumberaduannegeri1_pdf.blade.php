   
<table style="width: 100%;  font-size: 14px; text-align: center">
        <tr><td><center><h3>LAPORAN CARA PENERIMAAN</h3></center></td></tr>
        <tr><td><center><h3>TAHUN {{ $year }}</h3></center></td></tr>
        <tr><td><center><h3> BAGI NEGERI {{ $titlestate }}</h3></center></td></tr>
        <tr><td><center><h3>{{ $titledept }}</h3></center></td></tr>
    </table>
   <table class="table table-striped table-bordered table-hover" style="width: 100%; font-size: 20px; border:1px solid; border-collapse: collapse" border="1">

        <thead>
            <tr>
                <th>No.</th>
                <th>No. Aduan</th>
                <th>Aduan </th>
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
            @foreach($query as $data)
            <tr>
                <td>{{ $i }}</td>
                <td><a onclick="ShowSummary('{{ $data->CA_CASEID }}')">{{ $data->CA_CASEID }}</a></td>
                <td>{{ $data->CA_SUMMARY }}</td>
                <td>{{ $data->CA_NAME }}</td>
                <td>{{ $data->CA_AGAINSTNM }}</td>
                <td>
                    <!--{{-- $data->BrnCd->BR_BRNNM --}}-->
                    {{ $data->CA_BRNCD ? (count($data->BrnCd) == '1' ? $data->BrnCd->BR_BRNNM : $data->CA_BRNCD) : '' }}
                </td>
                <td>
                    <!--{{-- $data->CmplCat->descr --}}-->
                    {{ $data->CA_CMPLCAT ? (count($data->CmplCat) == '1' ? $data->CmplCat->descr : $data->CA_CMPLCAT) : '' }}
                </td>
                <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
                <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
                <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
                <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
                <td>
                    <!--{{-- $data->CA_INVBY? $data->InvBy->name:'' --}}-->
                    {{ $data->CA_INVBY ? (count($data->InvBy) == '1' ? $data->InvBy->name : $data->CA_INVBY) : '' }}
                </td>
            </tr>
            <?php $i++; ?>
            @endforeach
        </tbody>
    </table>