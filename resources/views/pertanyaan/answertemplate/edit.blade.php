@extends('layouts.main')

@section('title')
Kemaskini Templat Jawapan Pertanyaan 
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
                            <li>
                                {{ link_to('dashboard', 'Dashboard') }}
                            </li>
                            <li>
                                Pertanyaan
                            </li>
                            <li>
                                {{ link_to('askanswertemplate', 'Senarai Templat Jawapan Pertanyaan') }}
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
                        {{ Form::model($askanswertemplate, ['route' => ['askanswertemplate.update', $askanswertemplate->id], 'method' => 'put']) }}
                            @include('pertanyaan.answertemplate.form')
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
