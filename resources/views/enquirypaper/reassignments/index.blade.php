@extends('layouts.main')
@section('page_title')
    Senarai Penugasan Semula Fail Kes EP
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <h2>@yield('page_title')</h2>
                        <ol class="breadcrumb">
                            <li>{{ link_to('dashboard', 'Dashboard') }}</li>
                            <li>Fail Kes EP</li>
                            <li class="active">
                                <a href="{{ url()->current() }}">
                                    <strong>@yield('page_title')</strong>
                                </a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('enquirypaper.reassignments.table')
    @include('caseenquirypaper.info.show_modal')
@endsection

@section('script_datatable')
    <script type="text/javascript">
        $(document).ready(function(){
            var enquiryPapersTable = $('#enquiry-papers-table').DataTable({
                aaSorting: [],
                bFilter: false,
                processing: true,
                responsive: true,
                serverSide: true,
                // order: [ 1, 'desc' ],
                language: {
                    lengthMenu: 'Paparan _MENU_ rekod',
                    zeroRecords: 'Tiada rekod ditemui',
                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                    infoEmpty: 'Tiada rekod ditemui',
                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                    processing: "<span class=\"font-md\"></span><i class=\"fa fa-spinner fa-spin ml5\"></i>",
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir'
                    },
                    search: 'Carian', 
                    searchPlaceholder: 'Carian'
                },
                ajax: {
                    url: "{{ route('enquirypaper.reassignments.dt') }}",
                    data: function (d) {
                        d.no_kes = $('#search-form #no_kes').val();
                        d.asas_tindakan = $('#search-form #asas_tindakan').val();
                    },
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false },
                    { data: 'no_kes', name: 'no_kes', orderable: false },
                    { data: 'action', name: 'action', width: '1%', searchable: false, orderable: false }
                ]
            });
            $('#search-form').on('submit', function (e) {
                enquiryPapersTable.draw();
                e.preventDefault();
            });
            $('#resetbtn').on('click', function (e) {
                document.getElementById("search-form").reset();
                enquiryPapersTable.draw();
                e.preventDefault();
            });
        });
        function showSummary(id)
        {
            $('#modalCaseEnquiryInfo').modal("show").find("#modalShow")
                .load("{{ url('caseenquirypaper/infos') }}" + "/" + id);
        }
    </script>
@endsection
