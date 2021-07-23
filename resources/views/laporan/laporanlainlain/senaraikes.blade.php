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
                    <table id="cat_table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%" method ="POST">
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
        $(function() {
            var oTable = $('#cat_table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
//                order: [ 6, 'desc' ],
                pageLength: 50,
                pagingType: "full_numbers",
                dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
                language: {
                    lengthMenu: 'Memaparkan _MENU_ rekod',
                    zeroRecords: 'Tiada rekod ditemui',
                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                    infoEmpty: 'Tiada rekod ditemui',
                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir',
                    },
                },
                ajax: {
                    url: "{{ url('laporanlainlain/get_datatable') }}",
                    data: function (d) {
//                        d.CA_CASEID = $('#CA_CASEID').val();
//                        d.CA_SUMMARY = $('#CA_SUMMARY').val();
//                        d.CA_NAME = $('#CA_NAME').val();
//                        d.CA_RCVDT = $('#CA_RCVDT').val();
//                        d.CA_INVSTS = $('#CA_INVSTS').val();
//                        d.CA_CASESTS = $('#CA_CASESTS').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                    {data: 'CA_CASEID', name: 'CA_CASEID'},
                    {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                    {data: 'CA_NAME', name: 'CA_NAME'},
                    {data: 'CA_AGAINSTADD', name: 'CA_AGAINSTADD'},
                    {data: 'CA_STATECD', name: 'CA_STATECD'},
                    {data: 'CA_BRNCD', name: 'CA_BRNCD'},
                    {data: 'CA_DEPTCD', name: 'CA_DEPTCD'},
                    {data: 'CA_CMPLCAT', name: 'CA_CMPLCAT'},
                    {data: 'CA_CMPLCD', name: 'CA_CMPLCD'},
                    {data: 'CA_RCVDT', name: 'CA_RCVDT'},
                    {data: 'CA_INVDT', name: 'CA_INVDT'},
                    {data: 'CA_COMPLETEDT', name: 'CA_COMPLETEDT'},
                    {data: 'CA_CLOSEDT', name: 'CA_CLOSEDT'},
                    {data: 'CA_INVBY', name: 'CA_INVBY'},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ],
                buttons: [
//                    {extend: 'copy'},
//                    {extend: 'csv'},
                    {extend: 'excel'},
//                    {extend: 'pdf'},
                    {extend: 'print',customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    }
                ]
            });
        });
       
    
</script>
@stop
@stop
