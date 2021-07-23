@extends('layouts.main')
@section('page_title')
    Kemaskini Fail Kes EP (Barang Rampasan)
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
                <div class="ibox-title">
                    <div class="table-responsive">
                        <h5>@yield('page_title')</h5>
                    </div>
                </div>
                <div class="ibox-content">
                    @includeIf('enquirypaper.dataentry.loot.editform')
                </div>
            </div>
        </div>
    </div>
@endsection
