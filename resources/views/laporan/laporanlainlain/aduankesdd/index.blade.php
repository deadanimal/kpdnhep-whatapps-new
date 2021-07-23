@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <h2>Laporan Statistik Aduan Menghasilkan Kes Mengikut Negeri & Cawangan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/dashboard">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Laporan Aduan Menghasilkan Kes</strong>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Laporan</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <!-- <div class="row"> -->
                    <div class="text-center">
                        <a target="_blank"
                            href=
                            "/aduankes/dd?datestart={{ $datestart }}&dateend={{ $dateend }}&state={{ $state }}&branch={{ $branch }}&case={{ $case }}&gen=excel"
                            class="btn btn-primary btn-rounded">
                            <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Muat Turun Versi Excel
                        </a>
                        &nbsp;
                        <a target="_blank"
                            href=
                            "/aduankes/dd?datestart={{ $datestart }}&dateend={{ $dateend }}&state={{ $state }}&branch={{ $branch }}&case={{ $case }}&gen=pdf"
                            class="btn btn-danger btn-rounded btn-outline">
                            <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Muat Turun Versi PDF
                        </a>
                    </div>
                <!-- </div> -->
                <h3 class="text-center">
                    Laporan Statistik Aduan Menghasilkan Kes Mengikut Negeri & Cawangan
                </h3>
                <h3 class="text-center">
                    Tarikh Penerimaan Aduan : Dari 
                    {{ $datestart->format('d-m-Y') }} 
                    Hingga 
                    {{ $dateend->format('d-m-Y') }}
                </h3>
                <h3 class="text-center">
                    Negeri : 
                    {{ $statedesc }}
                </h3>
                <!-- <div class="row"> -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" style="width: 100%" id="aduankestable">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>No. Aduan</th>
                                    <th>Aduan</th>
                                    <th>Nama Pengadu</th>
                                    <th>Nama Diadu</th>
                                    <th>Cawangan</th>
                                    <th>Kategori Aduan</th>
                                    <th>Tarikh Terima</th>
                                    <!-- <th>Tarikh Penugasan</th> -->
                                    <th>Tarikh Selesai</th>
                                    <th>Tarikh Penutupan</th>
                                    <th>Penyiasat</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Start -->
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
<div id="modal-show-invby" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowInvBy'></div>
    </div>
</div>
<!-- Modal End -->
@stop

@section('script_datatable')
    <script type="text/javascript">
        function ShowSummary(CASEID)
        {
            $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
        }

        function ShowInvBy(id)
        {
            $('#modal-show-invby').modal("show").find("#ModalShowInvBy").load("{{ route('carian.showinvby','') }}" + "/" + id);
        }

        $(function() {
            var aduankestable = $('#aduankestable').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
                // order: [ 6, 'desc' ],
                pageLength: 50,
                pagingType: "full_numbers",
                // dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12'p>>",
                language: {
                    lengthMenu: 'Memaparkan _MENU_ rekod',
                    zeroRecords: 'Tiada rekod ditemui',
                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                    infoEmpty: 'Tiada rekod ditemui',
                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                    // infoFiltered: '',
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir',
                    },
                },
                ajax: {
                    url: "{{ url('aduankes/dddatatable') }}" 
                        + "?" + "datestart=" + "{{ $datestart }}"
                        + "&" + "dateend=" + "{{ $dateend }}"
                        + "&" + "state=" + "{{ $state }}"
                        + "&" + "branch=" + "{{ $branch }}"
                        + "&" + "case=" + "{{ $case }}"
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                    {
                        data: 'CA_CASEID', render: function (data, type) {
                            return type === 'export' ? "' " + data : data;
                        }, 
                        name: 'CA_CASEID', orderable: false
                    },
                    {data: 'CA_SUMMARY', name: 'CA_SUMMARY', orderable: false},
                    {data: 'CA_NAME', name: 'CA_NAME', orderable: false},
                    {data: 'CA_AGAINSTNM', name: 'CA_AGAINSTNM', orderable: false},
                    {data: 'BR_BRNNM', name: 'BR_BRNNM', orderable: false},
                    {data: 'CA_CMPLCAT', name: 'CA_CMPLCAT', orderable: false},
                    {data: 'CA_RCVDT', name: 'CA_RCVDT', orderable: false},
                    {data: 'CA_COMPLETEDT', name: 'CA_COMPLETEDT', orderable: false},
                    {data: 'CA_CLOSEDT', name: 'CA_CLOSEDT', orderable: false},
                    {data: 'CA_INVBY', name: 'CA_INVBY', orderable: false},
                ]
            });
        });
    </script>
@stop