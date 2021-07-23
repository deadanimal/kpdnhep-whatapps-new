@extends('layouts.main')

@section('content')
    <style>
        th, td {
            text-align: left;
            font-size: 12px;
        }
    </style>
    <table style="width: 100%;">
        <tr>
            <td style="text-align: center;"><h2>Senarai Aduan</h2></td>
        </tr>
        <tr>
            <td style="text-align: center;"><h3>LAPORAN AKTA ADUAN MENGIKUT NEGERI BAGI {{ $ds->format('d-m-Y') }}
                    HINGGA {{ $de->format('d-m-Y') }} <br/></h3></td>
        </tr>
        <tr>
            <td style="text-align: center;"><h3>Bahagian: {{ $dp_desc }}</h3></td>
        </tr>
        <tr>
            <td style="text-align: center;"><h3>Negeri: {{ $st_desc }}</h3></td>
        </tr>
        <tr>
            <td style="text-align: center;"><h3>Cawangan: {{ $br_desc }}</h3></td>
        </tr>
        <tr>
            <td style="text-align: center;"><h3>Akta: {{ $ac_desc }}</h3></td>
        </tr>
    </table>
    <div class="form-group" align="center">
        <a target="_blank" href="{!! $uri.'e' !!}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i>
            Muat Turun Excel</a>
        <a target="_blank" href="{!! $uri.'p' !!}" class="btn btn-success btn-sm"><i class="fa fa-file-pdf-o"></i> Muat
            Turun PDF</a>
    </div>
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
                <td><a onclick="ShowSummary('{{ $data->CA_CASEID }}')">{{ $data->CA_CASEID }}</a></td>
                <td>{{ implode(' ', array_slice(explode(' ', ucfirst($data->CA_SUMMARY)), 0, 7)).'...' }}</td>
                <td>{{ $data->CA_NAME }}</td>
                <td>{{ $data->CA_AGAINSTNM }}</td>
                <td>{{ $data->BR_BRNNM }}</td>
                <td>{{ $category_list[$data->CA_CMPLCAT]}}</td>
                <td>{{ $data->CA_RCVDT? date('d-m-Y h:i A', strtotime($data->CA_RCVDT)):'' }}</td>
                <td>{{ $data->CA_ASGDT? date('d-m-Y h:i A', strtotime($data->CA_ASGDT)):'' }}</td>
                <td>{{ $data->CA_COMPLETEDT? date('d-m-Y h:i A', strtotime($data->CA_COMPLETEDT)):'' }}</td>
                <td>{{ $data->CA_CLOSEDT? date('d-m-Y h:i A', strtotime($data->CA_CLOSEDT)):'' }}</td>
                <td>{{ $data->CA_INVBY? $data->name:'' }}</td>
            </tr>
            <?php $i++; ?>
        @endforeach
        </tbody>
    </table>
    <!-- Modal Start -->
    <div id="modal-show-summary" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id='ModalShowSummary'></div>
        </div>
    </div>
    <!-- Modal End -->
@stop

@section('script_datatable')
    <script type="text/javascript">
      function ShowSummary(CASEID) {
        $('#modal-show-summary').modal('show').find('#ModalShowSummary').load("{{ route('tugas.showsummary','') }}" + '/' + CASEID)
      }
    </script>
@stop