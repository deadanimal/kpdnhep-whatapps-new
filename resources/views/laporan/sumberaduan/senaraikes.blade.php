@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportYear;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="case_table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%" method ="POST">
                        <thead>
                              <h2>LAPORAN </h2>
                            <tr>
                                <th>Bil.</th>
                                <th>No. Aduan</th>
                                <th> Aduan</th>
                                <th> Nama Pengadu </th>
                                <th> Nama / Alamat Agensi </th>
                                <th> Negeri </th>
                                <th> Cawangan </th>
                                <th> Bahagian </th>
                                <th> Kategori </th>
                                <th> Subkategori </th>
                                <th> Tarikh Penerimaan </th>
                                <th> Tarikh Penugasan </th>
                                <th> Tarikh Selesai </th>
                                <th> Tarikh Penutupan </th>
                                <th> Penyiasat </th>
                                <th> # </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>@section('script_datatable')
<!-- Page-Level Scripts -->
<script type="text/javascript">
    $(function () {
        var oTable = $('#case_table').DataTable({
//            processing: true,
//            serverSide: true,
//            aaSorting: [],
//            bFilter: false,
//            bLengthChange: false,
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
//            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
            rowId: 'id',
            bStateSave: true,
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                infoEmpty: 'Tiada rekod',
                infoFiltered: '(filtered from _MAX_ total records)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{-- url('sumberaduan/get_datatable', $month,$type) --}}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CA_CASEID', name: 'CA_CASEID'},
//                {data: 'holiday_date', name: 'holiday_date'},
//                {data: 'state_code', name: 'state_code'},
//                {data: 'work_code', name: 'work_code'},
//                {data: 'repeat_yearly', name: 'repeat_yearly'},
//                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });

       
    });
</script>
@stop
@stop
