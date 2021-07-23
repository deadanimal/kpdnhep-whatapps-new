@extends('layouts.main')

@section('title')
Senarai Templat Jawapan Pertanyaan
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <h2>@yield('title')</h2>
                        <ol class="breadcrumb">
                            <li>
                                {{ link_to('dashboard', 'Dashboard') }}
                            </li>
                            <li>
                                Pertanyaan
                            </li>
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
                    <h5>Senarai</h5>
                    <a class="btn btn-success pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('askanswertemplate.create') }}">
                        <i class="fa fa-plus"></i> Templat
                    </a>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table style="width: 100%" id="askanswertemplate-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <th>Kategori</th> --}}
                                    <th>Kod</th>
                                    <th>Tajuk</th>
                                    <th>Penerangan</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_datatable')
    <script type="text/javascript">
        $(function () {
            var askanswerTemplateTable = $('#askanswertemplate-table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [],
                pagingType: 'full_numbers',
                pageLength: 50,
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
                    url: "{{ url('askanswertemplate/dt') }}",
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false },
                    // { data: 'category', name: 'category' },
                    { data: 'code', name: 'code' },
                    { data: 'title', name: 'title' },
                    { data: 'body', name: 'body' },
                    // { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', width: '10%', searchable: false, orderable: false }
                ]
            });
        });
    </script>
@endsection
