@extends('layouts.main')

@section('title')
Senarai Daftar Fail Kes EP
@endsection

@section('content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <h2>@yield('title')</h2>
                        <ol class="breadcrumb">
                            <li>{{ link_to('dashboard', 'Dashboard') }}</li>
                            <li>Fail Kes EP</li>
                            <li class="active">
                                <a href="{{ url()->current() }}">
                                    <strong>@yield('title')</strong>
                                </a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="table-responsive">
                        <h5>@yield('title')</h5>
                        <div class="ibox-tools">
                            <a href="{{ route('caseenquirypaper.dataentries.create') }}" class="btn btn-primary" style="color:#FFF;">
                                <i class="fa fa-plus"></i> Fail Kes EP
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="enquiry-papers-table" style="width: 100%">
                            <thead>
                                @includeIf('caseenquirypaper.dataentry.table_header')
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('caseenquirypaper.info.show_modal')
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
                    url: "{{ url('caseenquirypaper/dataentries/dt') }}",
                    data: function (d) {
                        d.date_start = $('#search-form #date_start').val();
                        d.date_end = $('#search-form #date_end').val();
                        d.no_kes = $('#search-form #NO_KES').val();
                        d.search_ind = $('#search-form #search_ind').val();
                    },
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false },
                    { data: 'no_kes', name: 'no_kes' },
                    // { data: 'ASAS_TINDAKAN', name: 'ASAS_TINDAKAN' },
                    // { data: 'TKH_KEJADIAN', name: 'TKH_KEJADIAN' },
                    { data: 'action', name: 'action', width: '10%', searchable: false, orderable: false }
                ]
            });
            $('#search-form').on('submit', function (e) {
                $('#search-form #search_ind').val(1);
                epAssignmentsTable.draw();
                e.preventDefault();
            });
        });
        function showSummary(id) {
            // alert(id);
            $('#modalCaseEnquiryInfo').modal("show").find("#modalShow")
                .load("{{ url('caseenquirypaper/infos') }}" + "/" + id);
        }
    </script>
@endsection