@extends('layouts.main')

@section('title')
Senarai Fail Kes EP
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
                    </div>
                </div>
                <div class="ibox-content">
                    {{ Form::open(['id' => 'search-form']) }}
                        <div class="row">
                            <div class="col-md-1 col-lg-4"></div>
                            <div class="col-md-10 col-lg-4">
                                <div class="form-group">
                                    {{ Form::label('no_kes', 'Nombor Fail Kes EP:', ['class' => 'control-label']) }}
                                    {{ Form::text('no_kes', null, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('asas_tindakan', 'Asas Tindakan:', ['class' => 'control-label']) }}
                                    {{ Form::select('asas_tindakan', $collections['asasTindakan'], null, ['class' => 'form-control', 'placeholder' => '-- SEMUA --']) }}
                                </div>
                                <div class="form-group text-center">
                                    {{-- <a href="{{ url()->current() }}" class='btn btn-rounded btn-success btn-outline'>
                                        <i class="fa fa-refresh"></i>&nbsp;Semula
                                    </a> --}}
                                    {{ Form::reset('Semula', ['class' => 'btn btn-rounded btn-success btn-outline', 'id' => 'resetbtn']) }}
                                    {{ Form::button(
                                        '<i class="fa fa-search"></i>&nbsp;Carian',
                                        [
                                            'type' => 'submit',
                                            'class' => 'btn btn-rounded btn-success'
                                        ]
                                    ) }}
                                </div>
                            </div>
                            <div class="col-md-1 col-lg-4"></div>
                        </div>
                    {{ Form::close() }}
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="enquiry-papers-table" style="width: 100%">
                            <thead>
                                @include('caseenquirypaper.info.table_header')
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    url: "{{ route('caseenquirypaper.infos.dt') }}",
                    data: function (d) {
                        // d.date_start = $('#search-form #date_start').val();
                        // d.date_end = $('#search-form #date_end').val();
                        d.no_kes = $('#search-form #no_kes').val();
                        d.asas_tindakan = $('#search-form #asas_tindakan').val();
                        // d.search_ind = $('#search-form #search_ind').val();
                    },
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false },
                    { data: 'no_kes', name: 'no_kes', orderable: false },
                    { data: 'asas_tindakan', name: 'asas_tindakan', orderable: false },
                    { data: 'tkh_kejadian', name: 'tkh_kejadian', orderable: false },
                    { data: 'kod_negeri', name: 'kod_negeri', orderable: false },
                    { data: 'kod_cawangan', name: 'kod_cawangan', orderable: false },
                    { data: 'akta', name: 'akta', orderable: false },
                    // { data: 'action', name: 'action', width: '10%', searchable: false, orderable: false }
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