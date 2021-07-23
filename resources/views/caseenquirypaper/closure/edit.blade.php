@extends('layouts.main')

@section('title')
Penutupan Fail Kes EP
@endsection

@section('content')
    <style> 
        textarea {
            resize: vertical;
        }
    </style>
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
                {{ Form::model($enquiryPaperCase, ['route' => ['caseenquirypaper.closures.update', $enquiryPaperCase->id], 'method' => 'patch']) }}
                    <div class="ibox-content">
                        @includeIf('caseenquirypaper.closure.fields')
                    </div>
                    <div class="ibox-footer">
                        <div class="row">
                            <div class="table-responsive">
                                <!-- Submit Field -->
                                <div class="form-group col-lg-12 text-center">
                                    <a href="{{ url('caseenquirypaper/closures') }}" class="btn btn-default">Kembali <i class="fa fa-home"></i></a>
                                    {{ Form::button('Simpan '.' <i class="fa fa-save"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @includeIf('caseenquirypaper.info.show_modal')
    @includeIf('caseenquirypaper.closure.modal_officer')
@endsection

@section('script_datatable')
    <script type="text/javascript">
        function showSummary(id)
        {
            $('#modalCaseEnquiryInfo').modal("show").find("#modalShow")
                .load("{{ url('caseenquirypaper/infos') }}" + "/" + id);
        }

        $(document).ready(function(){
            $('.input-group.date').datepicker({
                autoclose: true,
                calendarWeeks: true,
                forceParse: false,
                format: 'dd-mm-yyyy',
                keyboardNavigation: false,
                todayBtn: "linked",
                todayHighlight: true,
                weekStart: 1,
            });
            var oTable = $('#modal-close-user-table').DataTable({
                processing: true,
                serverSide: true,
                // bFilter: false,
                aaSorting: [],
                // pagingType: "full_numbers",
                dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
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
                },
                ajax: {
                    {{-- url: "{{ url('tutup/getdatatableuser') }}", --}}
                    url: '{{ url("enquirypaper/users") }}',
                    data: function (d) {
                        // d.name = $('#name').val();
                        // d.icnew = $('#icnew').val();
                        // // d.state_cd = $('#state_cd').val();
                        // // d.brn_cd = $('#brn_cd').val();
                        // d.state_cd = '{{ Auth::User()->state_cd }}';
                        // d.brn_cd = '{{ Auth::User()->brn_cd }}';
                        // d.role_cd = $('#role_cd').val();
                        d.close_by_user_name_search = $('#search-form input[name=close_by_user_name_search]').val();
                        d.close_by_user_icnumber = $('#search-form input[name=close_by_user_icnumber]').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                    {data: 'username', name: 'username'},
                    {data: 'name', name: 'name'},
                    {data: 'state_cd', name: 'state_cd'},
                    {data: 'brn_cd', name: 'brn_cd'},
                    // {data: 'count_case', name: 'count_case'},
                    // {data: 'role.role_code', name: 'role.role_code'},
                    // {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
                    {data: 'close_by_action', name: 'close_by_action', searchable: false, orderable: false, width: '1%'}
                ],
            });
            $('#buttonresetform').on('click', function (e) {
                document.getElementById("search-form").reset();
                oTable.draw();
                e.preventDefault();
            });

            $('#search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });
        });
        function myFunction(id) {
            $.ajax({
                url: "{{ url('tutup/getuserdetail') }}" + "/" + id,
                dataType: "json",
                success: function (data) {
                    $.each(data, function (value, key) {
                        $('input[name="close_by_user_id"]').val(key);
                        $('input[name="close_by_user_name"]').val(value);
                    });
                    $('#modalOfficer').modal('hide');
                }
            });
        };

        function selectCloseByUser(id)
        {
            $.ajax({
                url: "{{ url('enquirypaper/users') }}" + "/" + id,
                dataType: "json",
                success: function (data) {
                    $.each(data, function (key, value) {
                        $('input[name=close_by_user_name]').val(key);
                        $('input[name=close_by_user_id]').val(value);
                    });
                    $('#modalOfficer').modal('hide');
                }
            });
        };
    </script>
@endsection
