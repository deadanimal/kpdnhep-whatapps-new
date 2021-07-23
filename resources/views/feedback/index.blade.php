@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-push-1">
            <div class="row m-b">
                <div class="col-md-8 message">
                    <div class="row">
                        <div class="col-sm-4 col-xs-6">
                            <i class="fa fa-whatsapp text-center" style="font-size: 120px; color:#54C861"></i>
                            <h3>Whatsapp</h3>
                        </div>
                        <div class="col-sm-8 col-xs-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h4>Senarai Maklumat</h4>
                                    <ul>
                                        <li><a class="nav-link text-center"
                                               href="{{route('whatsapp.all.index')}}">Semua</a>
                                        </li>
                                        <li><a class="nav-link text-center" href="{{route('whatsapp.index')}}">Aktif</a>
                                        </li>
                                        <li><a class="nav-link text-center" href="{{route('whatsapp.mytask.index')}}">My
                                                Task</a></li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <a class="nav-link text-center" href="{{route('whatsapp.create')}}">Cipta (Backlog)</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <h4>Laporan</h4>
                                    <ul>
                                        <li><a class="nav-link text-center"
                                               href="{{route('laporan.feedback.r1.index')}}">Bulanan</a>
                                        </li>
                                        <li><a class="nav-link text-center"
                                               href="{{route('laporan.feedback.r2.index')}}">Pegawai</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-b">
                <div class="col-md-8 message">
                    <div class="row">
                        <div class="col-sm-4 col-xs-6s">
                            <i class="fa fa-telegram text-center" style="font-size: 120px; color:#0189d0"></i>
                            <h3>Telegram</h3>
                        </div>
                        <div class="col-sm-8 col-xs-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h4>Senarai Maklumat</h4>
                                    <ul>
{{--                                        <li><a class="nav-link text-center"--}}
{{--                                               href="{{route('telegram.all.index')}}">Semua</a>--}}
{{--                                        </li>--}}
                                        <li><a class="nav-link text-center" href="{{route('telegram.index')}}">Aktif</a>
                                        </li>
                                        <li><a class="nav-link text-center" href="{{route('telegram.mytask.index')}}">My
                                                Task</a></li>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <h4>Laporan</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(auth()->user()->role->role_code == 800 || auth()->user()->role->role_code == 120)
                <div class="row m-b">
                    <div class="col-md-8 message">
                        <div class="row">
                            <div class="col-sm-4 col-xs-6s">
                                <i class="fa fa-envelope text-center" style="font-size: 120px; color:#AB1F37"></i>
                                <h3>Email</h3>
                            </div>
                            <div class="col-sm-8 col-xs-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4>Senarai Maklumat</h4>
                                        <ul>
                                            <li>
                                                <a class="nav-link text-center" href="{{route('email.all.index')}}">Semua</a>
                                            </li>
                                            <li>
                                                <a class="nav-link text-center"
                                                   href="{{route('email.index')}}">Aktif</a>
                                            </li>
                                            <li>
                                                <a class="nav-link text-center" href="{{route('email.mytask.index')}}">My
                                                    Task</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4>Laporan</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-b">
                    <div class="col-md-8 message">
                        <div class="row">
                            <div class="col-sm-4 col-xs-6s">
                                <i class="fa fa-instagram text-center" style="font-size: 120px; color:#CCC"></i>
                                <h3>Instagram</h3>
                            </div>
                            <div class="col-sm-8 col-xs-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4>Senarai Maklumat</h4>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4>Laporan</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-b">
                    <div class="col-md-8 message">
                        <div class="row">
                            <div class="col-sm-4 col-xs-6s">
                                <i class="fa fa-twitter text-center" style="font-size: 120px; color:#CCC"></i>
                                <h3>Twitter</h3>
                            </div>
                            <div class="col-sm-8 col-xs-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4>Senarai Maklumat</h4>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4>Laporan</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-b">
                    <div class="col-md-8 message">
                        <div class="row">
                            <div class="col-sm-4 col-xs-6s">
                                <i class="fa fa-facebook text-center" style="font-size: 120px; color:#CCC"></i>
                                <h3>Facebook</h3>
                            </div>
                            <div class="col-sm-8 col-xs-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4>Senarai Maklumat</h4>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4>Laporan</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop