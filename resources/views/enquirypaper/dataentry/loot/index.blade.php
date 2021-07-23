@extends('layouts.main')
@section('page_title')
Daftar Fail Kes EP (Barang Rampasan)
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <h2>@yield('page_title')</h2>
                        <ol class="breadcrumb">
                            <li>
                                {{ link_to('dashboard', 'Dashboard') }}
                            </li>
                            <li>
                                <a>Fail Kes EP</a>
                            </li>
                            <li>
                                <a href="{{ route('caseenquirypaper.dataentries.index') }}">
                                    Senarai Daftar Fail Kes EP
                                </a>
                            </li>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="row">
                        <div class="table-responsive">
                            <div class="col-lg-3">
                                <div class="m-xxs bg-primary p-xs b-r-sm">
                                    <span class="number">1.</span> Maklumat Am
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="m-xxs bg-primary p-xs b-r-sm">
                                    <span class="number">2.</span> Maklumat OKT
                                </div>
                            </div>
                            @if(!in_array($enquiryPaperCase->asas_tindakan, ['ADUAN']))
                                <div class="col-lg-3">
                                    <div class="m-xxs bg-primary p-xs b-r-sm">
                                        <span class="number">3.</span> Maklumat Barang Rampasan
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="m-xxs bg-muted p-xs b-r-sm">
                                        <span class="number">4.</span> Maklumat Pendakwaan
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('enquirypaper.dataentry.loot.table')
    @includeIf('enquirypaper.dataentry.loot.createmodal')
    @includeIf('enquirypaper.dataentry.loot.editmodal')
@endsection
@section('script_datatable')
    <script type="text/javascript">
        $(document).ready(function(){
            var oTable = $('#dataentry-loot-table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [],
                // bFilter: false,
                // responsive: true,
                ajax: {
                    url: '{{ route('enquirypaper.dataentry.loots.dt', [$enquiryPaperCaseId]) }}',
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false },
                    { data: 'no_kes', name: 'no_kes' },
                    { data: 'kesbrg_id', name: 'kesbrg_id' },
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],
                language: {
                    lengthMenu: "@lang('datatable.lengthMenu')",
                    zeroRecords: "@lang('datatable.infoEmpty')",
                    info: "@lang('datatable.info')",
                    infoEmpty: "@lang('datatable.infoEmpty')",
                    infoFiltered: "(@lang('datatable.infoFiltered'))",
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: "@lang('datatable.first')",
                        last: "@lang('datatable.last')",
                    },
                    processing: "<span class=\"font-md\"></span><i class=\"fa fa-spinner fa-spin ml5\"></i>",
                    search: "@lang('button.search')",
                    searchPlaceholder: "@lang('button.search')",
                },
            });
            $('.dataTables_filter input').addClass('form-control');
        });

        function lootCreateModal(enquiryPaperCaseId)
        {
            $('#dataentryLootCreateModal').modal("show").find("#lootCreateForm")
                .load("{{ url('enquirypaper/dataentry') }}" + "/" + enquiryPaperCaseId + "/loots/create?generate=form");
        }

        function lootEditModal(enquiryPaperCaseId, enquiryPaperCaseLootId)
        {
            $('#dataentryLootEditModal').modal("show").find("#lootEditForm")
                .load("{{ url('enquirypaper/dataentry') }}" + "/" + enquiryPaperCaseId + "/loots/" + enquiryPaperCaseLootId + "/edit?generate=form");
        }
    </script>
@endsection
