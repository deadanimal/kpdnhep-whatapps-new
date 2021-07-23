@extends('layouts.main_portal')
<?php
?>
@section('content')
    <style>
        th, td {
            padding: 5px;
            vertical-align: top;
        }
    </style>
    <div class="ibox floating-container">
        <div class="ibox-content" style="padding: 0px;">
            <div class="container mt-4 mb-4 shadow-sm p-3 mb-5 bg-white rounded checkcase"
                 style="background-color: white; border: 1px solid #ccc;">
                @if($model)
                    <div class="table-responsive">
                        <table style="width: 50%" align="center">
                            <thead>
                            <!-- <tr>
                            <th colspan="2" style="text-align: center">
                            @lang('public-case.case.checkcase')
                                    </th>
                                </tr> -->
                            </thead>
                            <tbody>
                            <!-- <tr>
                            <td style="width: 50%;">
                                <b>@lang('public-case.case.CA_CASEID')</b>
                                 : {{ $model->IN_CASEID }}
                                    </td>
                                    <td style="width: 50%;">
                                        <b>@lang('public-case.case.CA_RCVDT')</b>
                                 : 
                                {{ $model->IN_RCVDT != '' ? date('d-m-Y h:i A', strtotime($model->IN_RCVDT)) : '' }}
                                    </td>
                                </tr> -->
                            <!--                        <tr>
                            <td><b>@lang('public-case.case.CA_CASEID')</b></td>
                            <td>: {{-- $model->CA_CASEID --}}</td>
                        </tr>-->
                            <!-- <tr>
                            <td colspan="2" style="text-align: center; background-color: #446CB3; color: white; font-weight: bold">@lang('public-case.case.complainantinfo')</td>
                        </tr> -->
                            <!-- <tr>
                            <td><b>@lang('public-profile.profail.name')</b></td>
                            <td>: {{ $model->CA_NAME }}</td>
                        </tr> -->
                            <!-- <tr>
                            <td><b>@lang('public-profile.profail.icnew')</b></td>
                            <td>: {{ $model->CA_DOCNO }}</td>
                        </tr> -->
                            <tr>
                                <td colspan="3"
                                    style="text-align: center; background-color: #446CB3; color: white; font-weight: bold">
                                <!-- @lang('public-case.case.complaintinfo') -->
                                    {{ app()->getLocale() == 'ms' ? 'Semakan Maklumat Aduan Integriti' : 'Integriti Complaint Status' }}
                                </td>
                            </tr>
                            <!-- <tr>
                            <td style="width: 50%;">
                                <b>@lang('public-case.case.CA_CASEID')</b>
                                 : {{ $model->IN_CASEID }}
                                    </td>
                                    <td style="width: 50%;">
                                        <b>@lang('public-case.case.CA_RCVDT')</b>
                                 : 
                                {{ $model->IN_RCVDT != '' ? date('d-m-Y h:i A', strtotime($model->IN_RCVDT)) : '' }}
                                    </td>
                                </tr> -->
                            <!--                        <tr>
                            <td><b>@lang('public-case.case.CA_CASEID')</b></td>
                            <td>: {{ $model->CA_CASEID }}</td>
                        </tr>-->
                            <tr>
                                <td style="width: 35%;">
                                    <b>@lang('public-case.case.CA_CASEID')</b>
                                </td>
                                <td style="width: 1%;">
                                    :
                                </td>
                                <td style="width: 60%;">
                                    {{ $model->IN_CASEID }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>@lang('public-case.case.CA_RCVDT')</b>
                                </td>
                                <td>:</td>
                                <td>
                                    {{ $model->IN_RCVDT != '' ? date('d-m-Y h:i A', strtotime($model->IN_RCVDT)) : '' }}
                                </td>
                            </tr>
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_CMPLCAT')</b></td> -->
                            <!-- <td>: {{-- Ref::GetDescr('244', $model->CA_CMPLCAT, app()->getLocale()) --}}</td> -->
                            <!--{{-- app()->getLocale() == 'en' ? $mainmenu->menu_txt_en : $mainmenu->menu_txt --}}-->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_BRNCD')</b></td> -->
                            <!-- <td>: {{-- Branch::GetBranchName($model->CA_BRNCD) --}}</td> -->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_AGAINST_TELNO')</b></td> -->
                            <!-- <td>: {{ $model->BR_TELNO }}</td> -->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_AGAINST_EMAIL')</b></td> -->
                            <!-- <td>: {{ $model->BR_EMAIL }}</td> -->
                            <!-- </tr> -->
                            <tr>
                                <td><b>{{ app()->getLocale() == 'ms' ? 'Status Aduan' : 'Complaint Status' }}</b></td>
                                <td>:
                                <!-- {{-- Ref::GetDescr('292', $model->CA_INVSTS, app()->getLocale()) --}} -->
                                </td>
                                <td>
                                    {{ $model->StatusAduan ? $model->StatusAduan->descr : $model->IN_INVSTS }}
                                </td>
                            </tr>
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_INVBY')</b></td> -->
                            <!-- <td>: {{-- $INVBY --}}</td> -->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_AGAINSTNM')</b></td> -->
                            <!-- <td>: {{-- $model->CA_AGAINSTNM --}}</td> -->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_AGAINSTADD')</b></td> -->
                            <!-- <td>:  -->
                            <!-- {{-- $model->CA_AGAINSTADD != '' ? $model->CA_AGAINSTADD : '' --}} -->
                            <!-- </td> -->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_AGAINST_POSTCD')</b></td> -->
                            <!-- <td>: {{-- $model->CA_AGAINST_POSTCD != '' ? $model->CA_AGAINST_POSTCD : '' --}}</td> -->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_AGAINST_DISTCD')</b></td> -->
                            <!-- <td>: {{-- $model->CA_AGAINST_DISTCD != '' ? Ref::GetDescr('18', $model->CA_AGAINST_DISTCD, 'ms') : '' --}}</td> -->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_AGAINST_STATECD')</b></td> -->
                            <!-- <td>: {{-- $model->CA_AGAINST_STATECD != '' ? Ref::GetDescr('17', $model->CA_AGAINST_STATECD, 'ms') : '' --}}</td> -->
                            <!-- </tr> -->
                            <!-- <tr> -->
                            <!-- <td><b>@lang('public-case.case.CA_SUMMARY')</b></td> -->
                            <!-- <td>: {{-- $model->CA_SUMMARY != '' ? $model->CA_SUMMARY : '' --}}</td> -->
                            <!-- </tr> -->
                            <tr>
                                <td><b>@lang('public-case.case.CA_ANSWER')</b></td>
                                <td>:
                                </td>
                                <td>
                                <!-- {{ $model->IN_ANSWER }} -->
                                    {!! nl2br(htmlspecialchars($model->IN_ANSWER)) !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div align="center" style="padding-top: 20px; padding-bottom: 50px">
                            <a class="btn btn-primary btn-sm" href="{{ url('integriti') }}">@lang('button.back')</a>
                        </div>
                    </div>
                    <br/>
                @elseif(!$model)
                    <div align="center">
                        <p>@lang('portal.validation.norecordcse')</p>
                    </div>
                    <div align="center" style="padding-top: 20px; padding-bottom: 50px">
                        <a class="btn btn-primary btn-sm" href="{{ url('integriti') }}">@lang('button.back')</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
