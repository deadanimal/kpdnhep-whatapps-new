@extends('layouts.main')
@section('page_title')
    Penugasan Semula Fail Kes EP
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
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::model($enquiryPaperCase, ['route' => ['enquirypaper.reassignments.update', $enquiryPaperCase->id], 'method' => 'patch']) }}
                <div class="ibox-title">
                    <div class="table-responsive">
                        <h5>
                            @yield('page_title') : {{ $enquiryPaperCase->no_kes }}
                        </h5>
                    </div>
                </div>
                <div class="ibox-content">
                    @include('enquirypaper.reassignments.fields')
                </div>
                <div class="ibox-footer">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Submit Field -->
                            <div class="form-group col-sm-12 m-b-none text-center">
                                <a href="{{ route('enquirypaper.reassignments.index') }}" class="btn btn-default">
                                    Kembali <i class="fa fa-home"></i>
                                </a>
                                {{ Form::button('Simpan '.' <i class="fa fa-save"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @include('caseenquirypaper.info.show_modal')
    @include('enquirypaper.reassignments.modal_io')
    @include('enquirypaper.reassignments.modal_aio')
@endsection

@section('script_datatable')
    <script type="text/javascript">
        function showSummary(id)
        {
            $('#modalCaseEnquiryInfo').modal("show").find("#modalShow")
                .load("{{ url('caseenquirypaper/infos') }}" + "/" + id);
        }

        var ioTable = $('#modal-io-table').DataTable({
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
            processing: true,
            serverSide: true,
            aaSorting: [],
            ajax: {
                url: '{{ url("enquirypaper/users") }}',
                data: function (d) {
                    d.io_name = $('#io-search-form input[name=io_name]').val();
                    d.io_icnumber = $('#io-search-form input[name=io_icnumber]').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', searchable: false, orderable: false, width: '1%'},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'io_action', name: 'io_action', searchable: false, orderable: false, width: '1%'}
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
            }
        });

        $('#buttonresetform').on('click', function (e) {
            document.getElementById("io-search-form").reset();
            ioTable.draw();
            ioTable.page('first');
            ioTable.state.clear();
            e.preventDefault();
        });

        $('#io-search-form').on('submit', function(e) {
            ioTable.draw();
            e.preventDefault();
        });

        function selectIo(id)
        {
            $.ajax({
                url: "{{ url('enquirypaper/users') }}" + "/" + id,
                dataType: "json",
                success: function (data) {
                    $.each(data, function (key, value) {
                        $('input[name=pegawai_penyiasat_io]').val(key);
                        $('input[name=io_user_id]').val(value);
                    });
                    $('#modalIo').modal('hide');
                }
            });
        };
    </script>
@endsection
