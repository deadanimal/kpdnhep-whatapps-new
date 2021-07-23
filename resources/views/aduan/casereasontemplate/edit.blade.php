@extends('layouts.main')

@section('title')
Kemaskini Templat Alasan Aduan
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
                                Aduan Kepenggunaan
                            </li>
                            <li>
                                {{ link_to('casereasontemplates', 'Senarai Templat Alasan Aduan') }}
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
                <div class="ibox-content">
                    <div class="table-responsive">
                        {{ Form::model($caseReasonTemplate, ['route' => ['casereasontemplates.update', $caseReasonTemplate->id], 'method' => 'put']) }}
                            @include('aduan.casereasontemplate.form')
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
