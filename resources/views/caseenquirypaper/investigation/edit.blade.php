@extends('layouts.main')

@section('title')
Penyiasatan Fail Kes EP
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
                {{ Form::model($enquiryPaperCase, ['route' => ['caseenquirypaper.investigations.update', $enquiryPaperCase->id], 'method' => 'patch']) }}
                <div class="ibox-content">
                    @include('caseenquirypaper.investigation.fields')
                </div>
                <div class="ibox-footer">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Submit Field -->
                            <div class="form-group col-lg-12 text-center">
                                <a href="{{ url('caseenquirypaper/investigations') }}" class="btn btn-default">Kembali <i class="fa fa-home"></i></a>
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
        });
    </script>
@endsection