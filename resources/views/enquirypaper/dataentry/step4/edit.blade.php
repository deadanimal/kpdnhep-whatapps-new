@extends('layouts.main')
@section('page_title')
    Daftar Fail Kes EP Baharu ( Pendakwaan )
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
                            <li>
                                {{ link_to_route('caseenquirypaper.dataentries.index', 'Senarai Daftar Fail Kes EP') }}
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
                                    <div class="m-xxs bg-primary p-xs b-r-sm">
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
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::model($enquiryPaperCase, ['route' => ['enquirypaper.dataentry.step4.update', $enquiryPaperCase->id], 'method' => 'patch']) }}
                <div class="ibox-title">
                    <div class="table-responsive">
                        <h5>
                            @yield('page_title') : {{ $enquiryPaperCase->no_kes }}
                        </h5>
                    </div>
                </div>
                <div class="ibox-content">
                    @includeIf('enquirypaper.dataentry.step4.fields')
                </div>
                <div class="ibox-footer">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Submit Field -->
                            <div class="form-group col-sm-12 m-b-none text-center">
                                {{-- <a href="{{ route('enquirypaper.dataentry.loots.index', [$enquiryPaperCase->id])route('enquirypaper.dataentry.index') }}" class="btn btn-default">
                                    Kembali <i class="fa fa-home"></i>
                                </a> --}}
                                <a class="btn btn-success" href="{{ route('enquirypaper.dataentry.loots.index', [$enquiryPaperCase->id]) }}">
                                    <i class="fa fa-chevron-left"></i> Sebelum
                                </a>
                                {{ Form::button('Simpan & Hantar '.' <i class="fa fa-send"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
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