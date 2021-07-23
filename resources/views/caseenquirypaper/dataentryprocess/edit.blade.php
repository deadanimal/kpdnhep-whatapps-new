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
                    {{-- <li> --}}
                        {{-- <a href="{{ route('caseenquirypaper.dataentries.edit', $caseEp->id) }}"> --}}
                            {{-- Maklumat Am <span class="label label-success">1</span> --}}
                        {{-- </a> --}}
                    {{-- </li> --}}
                    {{-- @if($process == 2) --}}
                        {{-- <li class="active"> --}}
                            {{-- <a data-toggle="tab">Maklumat OKT <span class="label label-success">2</span></a> --}}
                        {{-- </li> --}}
                    {{-- @else --}}
                        {{-- <li> --}}
                            {{-- <a href="{{ route('caseenquirypaper.dataentries.processes.edit', ['id' => $caseEp->id, 'page' => 2]) }}"> --}}
                                {{-- Maklumat OKT <span class="label label-success">2</span> --}}
                            {{-- </a> --}}
                        {{-- </li> --}}
                    {{-- @endif --}}
                    {{-- @if($process == 3) --}}
                        {{-- <li class="active"> --}}
                            {{-- <a data-toggle="tab">Maklumat Inventori <span class="label label-success">3</span></a> --}}
                        {{-- </li> --}}
                    {{-- @else --}}
                        {{-- <li> --}}
                            {{-- <a href="{{ route('caseenquirypaper.dataentries.processes.edit', ['id' => $caseEp->id, 'page' => 3]) }}"> --}}
                                {{-- Maklumat Inventori <span class="label label-success">3</span> --}}
                            {{-- </a> --}}
                        {{-- </li> --}}
                    {{-- @endif --}}
                {{-- </ul> --}}
                {{-- <div class="tab-content"> --}}
                    {{-- <div class="tab-pane active"> --}}
                        {{-- <div class="panel-body"> --}}
                            {{-- {{ Form::open(['route' => 'caseenquirypaper.dataentries.store']) }}
                                @includeIf('caseenquirypaper.dataentry.form1')
                            {{ Form::close() }} --}}
                            {{-- {{ Form::model($caseEp, ['route' => ['caseenquirypaper.dataentryprocess.update', $caseEp->NO_KES], 'method' => 'put']) }} --}}
                            {{-- {{ Form::model($caseEp, ['route' => ['caseenquirypaper.dataentryprocess.update', $caseEp->id], 'method' => 'put']) }} --}}
                            {{-- route('caseenquirypaper.dataentries.processes.edit', ['id' => $caseEnquiryPaper->id, 'page' => 3]) --}}
                            {{-- {{ Form::model($caseEp, ['route' => ['caseenquirypaper.dataentries.processes.update', $caseEp->id, $process], 'method' => 'put']) }} --}}
                                {{-- @includeIf('caseenquirypaper.dataentryprocess.formpage2') --}}
                                {{-- @includeIf('caseenquirypaper.dataentryprocess.fields') --}}
                                {{-- @if($process == 2) --}}
                                    {{-- @includeIf('caseenquirypaper.dataentryprocess.fieldspage2') --}}
                                {{-- @elseif($process == 3) --}}
                                    {{-- @includeIf('caseenquirypaper.dataentryprocess.fieldspage3') --}}
                                {{-- @endif --}}
                            {{-- {{ Form::close() }} --}}
                        {{-- </div> --}}
                    {{-- </div> --}}
                {{-- </div> --}}
            {{-- </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}
    <div class="row">
        {{ Form::model($caseEp, ['route' => ['caseenquirypaper.dataentries.processes.update', $caseEp->id, $process], 'method' => 'patch']) }}
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@yield('title')</h5>
                </div>
                <div class="ibox-content">
                    @if($process == 2)
                        @includeIf('caseenquirypaper.dataentryprocess.fieldspage2')
                    @elseif($process == 3)
                        @includeIf('caseenquirypaper.dataentryprocess.fieldspage3')
                    @endif
                </div>
                <div class="ibox-footer">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Submit Field -->
                            <div class="form-group col-sm-12 m-b-none text-center">
                                <a href="{{ route('caseenquirypaper.dataentries.edit', [$caseEp]) }}" class="btn btn-success">
                                    <i class="fa fa-chevron-left"></i> Sebelum
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
                    url: "{{ url('caseact/dt') }}",
                    data: function (d) {
                        d.name = $('#name').val()
                        d.icnew = $('#icnew').val()
                        // d.state_cd = $('#state_cd_user').val();
                        // d.state_cd = '{{ Auth::User()->state_cd }}'
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
        });

        function selectCaseEpNo(epno) {
            $.ajax({
                url: "{{ url('caseact/getcaseactdetail') }}" + '/' + epno,
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (key, value) {
                        $('#no_kes').val(key);
                    });
                    $('#case-act-modal').modal('hide');
                }
            })
        }
    </script>
@endsection
