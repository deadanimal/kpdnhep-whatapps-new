@extends('layouts.main')

@section('title')
Daftar Fail Kes EP Baharu
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
                            <li>
                                {{ link_to_route('caseenquirypaper.dataentries.index', 'Senarai Daftar Fail Kes EP') }}
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
    {{-- <div class="row"> --}}
        {{-- <div class="col-lg-12"> --}}
            {{-- <div class="tabs-container"> --}}
                {{-- <ul class="nav nav-tabs"> --}}
                    {{-- <li class="active"> --}}
                        {{-- <a data-toggle="tab"> --}}
                            {{-- Maklumat Am <span class="label label-success">1</span> --}}
                        {{-- </a> --}}
                    {{-- </li> --}}
                    {{-- <li> --}}
                        {{-- <a> --}}
                            {{-- Maklumat OKT <span class="label label-success">2</span> --}}
                        {{-- </a> --}}
                    {{-- </li> --}}
                    {{-- <li><a>Maklumat Inventori <span class="label label-success">3</span></a></li> --}}
                {{-- </ul> --}}
                {{-- <div class="tab-content"> --}}
                    {{-- <div class="tab-pane active"> --}}
                        {{-- <div class="panel-body"> --}}
                            {{-- Form::open(['route' => 'caseenquirypaper.dataentries.store']) --}}
                                {{-- @includeIf('caseenquirypaper.dataentry.form1') --}}
                                {{-- @includeIf('caseenquirypaper.dataentry.fields') --}}
                            {{-- Form::close() --}}
                        {{-- </div> --}}
                    {{-- </div> --}}
                {{-- </div> --}}
            {{-- </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}
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
                                <div class="m-xxs bg-muted p-xs b-r-sm">
                                    <span class="number">2.</span> Maklumat OKT
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{ Form::open(['route' => 'caseenquirypaper.dataentries.store']) }}
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@yield('title')</h5>
                </div>
                <div class="ibox-content">
                    @includeIf('caseenquirypaper.dataentry.fields')
                </div>
                <div class="ibox-footer">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Submit Field -->
                            <div class="form-group col-sm-12 m-b-none text-center">
                                <a href="{{ url('caseenquirypaper/dataentries') }}" class="btn btn-default">
                                    Kembali <i class="fa fa-home"></i>
                                </a>
                                {{ Form::button(
                                    'Simpan & Seterusnya'.' <i class="fa fa-chevron-right"></i>',
                                    [
                                        'type' => 'submit',
                                        'class' => 'btn btn-success'
                                    ]
                                ) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
    @includeIf('caseenquirypaper.dataentry.caseactmodal')
@endsection

@section('script_datatable')
    <script type="text/javascript">
        $(document).ready(function(){
            $('select[name="kod_negeri"]').on('change', function (e) {
                var kod_negeri = $(this).val();
                if(kod_negeri){
                    $.ajax({
                        type:'GET',
                        url:"{{ url('user/getbrnlist') }}" + "/" + kod_negeri,
                        dataType: "json",
                        success:function(data){
                            $('select[name="kod_cawangan"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="kod_cawangan"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="kod_cawangan"]').empty();
                    $('select[name="kod_cawangan"]').append('<option>-- SILA PILIH --</option>');
                    $('select[name="kod_cawangan"]').trigger('change');
                }
            });

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

            $('#case-act-modal-button').on('click', function () {
                $('#case-act-modal').modal();
            });

            var caseActTable = $('#ep-table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
                pagingType: 'full_numbers',
                // dom: '<\'row\'<\'col-sm-6\'i><\'col-sm-6\'<\'pull-right\'l>>>' +
                //     '<\'row\'<\'col-sm-12\'tr>>' +
                //     '<\'row\'<\'col-sm-12\'p>>',
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
                        last: 'Terakhir'
                    }
                },
                ajax: {
                    url: "{{ url('caseacts/dt') }}",
                    data: function (d) {
                        d.CT_CASEID = $('#search-form input[name=CT_CASEID]').val();
                        d.CT_EPNO = $('#search-form input[name=CT_EPNO]').val();
                        // d.name = $('#name').val()
                        // d.icnew = $('#icnew').val()
                        // d.state_cd = $('#state_cd_user').val();
                        // d.state_cd = '{{-- Auth::User()->state_cd --}}'
                        // d.brn_cd = $('#brn_cd').val();
                        {{-- d.brn_cd = '{{ Auth::User()->brn_cd }}' --}}
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'id', width: '5%', searchable: false, orderable: false },
                    { data: 'CT_CASEID', name: 'CT_CASEID' },
                    { data: 'CT_EPNO', name: 'CT_EPNO' },
                    { data: 'CT_AKTA', name: 'CT_AKTA' },
                    { data: 'CT_SUBAKTA', name: 'CT_SUBAKTA' },
                    { data: 'action', name: 'action', searchable: false, orderable: false, width: '8%' }
                ]
            });

            $('#buttonresetform').on('click', function (e) {
                document.getElementById("search-form").reset();
                caseActTable.draw();
                caseActTable.page('first');
                caseActTable.state.clear();
                e.preventDefault();
            });

            $('#search-form').on('submit', function(e) {
                caseActTable.draw();
                e.preventDefault();
            });
        });

        $('select[name="asas_tindakan"]').on('change', function () {
            switch ($(this).val()) {
                case 'ADUAN':
                    $('#complaint_case_number_field').show();
                    break;
                default:
                    $('#complaint_case_number_field').hide();
                    break;
            };
        });

        function selectCaseEpNo(epno) {
            $.ajax({
                // url: "{{-- url('caseact/getcaseactdetail') --}}" + '/' + epno,
                url: "{{ url('caseacts') }}" + '/' + epno,
                dataType: 'json',
                success: function (data) {
                    console.log(data)
                    // $.each(data, function (key, value) {
                    //     $('#complaint_case_no').val(key);
                    // });
                    $('#no_kes').val(data.CT_EPNO);
                    $('#complaint_case_number').val(data.CT_CASEID);
                    $('#case-act-modal').modal('hide');
                },
                error: function (data) {
                    console.log(data)
                },
                complete: function (data) {
                    console.log(data)
                }
            })
        };
    </script>
@endsection
