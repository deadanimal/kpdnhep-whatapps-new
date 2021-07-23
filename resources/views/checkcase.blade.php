@extends('layouts.main_portal')
<?php
?>
@section('content')
    <style>
        th, td {
            padding: 5px;
        }
    </style>
    <div class="ibox floating-container">
        <div class="ibox-content" style="padding: 0px;">
            <div class="container mt-4 mb-4 shadow-sm p-3 mb-5 bg-white rounded checkcase"
                 style="background-color: white; border: 1px solid #ccc;">
                @if($caseinfo)
                    <div class="table-responsive">
                        <table style="width: 80%" align="center">
                            <thead>
                            <tr>
                                <th colspan="2" style="text-align: center">@lang('public-case.case.checkcase')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 50%;"><b>@lang('public-case.case.CA_RCVDT')</b>
                                    : {{ $caseinfo->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($caseinfo->CA_RCVDT)) : '' }}
                                </td>
                                <td style="width: 50%;"><b>@lang('public-case.case.CA_CASEID')</b>
                                    : {{ $caseinfo->CA_CASEID }}</td>
                            </tr>
                            <!--                        <tr>
                            <td><b>@lang('public-case.case.CA_CASEID')</b></td>
                            <td>: {{ $caseinfo->CA_CASEID }}</td>
                        </tr>-->
                            <tr>
                                <td colspan="2" style="text-align: center; background-color: #446CB3; color: white; font-weight: bold">
                                    @lang('public-case.case.complainantinfo')
                                </td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-profile.profail.name')</b></td>
                                <td>: {{ $caseinfo->CA_NAME }}</td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-profile.profail.icnew')</b></td>
                                <td>: {{ $caseinfo->CA_DOCNO }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center; background-color: #446CB3; color: white; font-weight: bold">
                                    @lang('public-case.case.complaintinfo')
                                </td>
                            </tr>
                            <!--                        <tr>
                            <td><b>@lang('public-case.case.CA_CASEID')</b></td>
                            <td>: {{ $caseinfo->CA_CASEID }}</td>
                        </tr>-->
                            <tr>
                                <td><b>@lang('public-case.case.CA_CMPLCAT')</b></td>
                                <td>:
                                    {{-- Ref::GetDescr('244', $caseinfo->CA_CMPLCAT, app()->getLocale()) --}}
                                    {{ $caseinfo->CmplCat ? $caseinfo->CmplCat->descr : '' }}
                                </td>
                            <!--{{-- app()->getLocale() == 'en' ? $mainmenu->menu_txt_en : $mainmenu->menu_txt --}}-->
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_BRNCD')</b></td>
                                <td>:
                                    {{-- Branch::GetBranchName($caseinfo->CA_BRNCD) --}}
                                    {{ $caseinfo->BrnCd ? $caseinfo->BrnCd->BR_BRNNM : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_AGAINST_TELNO')</b></td>
                                <td>:
                                    {{-- $caseinfo->BR_TELNO --}}
                                    {{ $caseinfo->BrnCd ? $caseinfo->BrnCd->BR_TELNO : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_AGAINST_EMAIL')</b></td>
                                <td>:
                                    {{-- $caseinfo->BR_EMAIL --}}
                                    {{ $caseinfo->BrnCd ? $caseinfo->BrnCd->BR_EMAIL : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Status</b></td>
                                <td>:
                                    {{-- Ref::GetDescr('292', $caseinfo->CA_INVSTS, app()->getLocale()) --}}
                                    {{ $caseinfo->StatusAduan ? $caseinfo->StatusAduan->descr : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_INVBY')</b></td>
                                <td>: {{ $INVBY }}</td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_AGAINSTNM')</b></td>
                                <td>: {{ $caseinfo->CA_AGAINSTNM }}</td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_AGAINSTADD')</b></td>
                                <td>:
                                    {{-- $caseinfo->CA_AGAINSTADD != '' ? $caseinfo->CA_AGAINSTADD : '' --}}
                                    {!! nl2br(htmlspecialchars($caseinfo->CA_AGAINSTADD)) !!}
                                </td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_AGAINST_POSTCD')</b></td>
                                <td>: {{ $caseinfo->CA_AGAINST_POSTCD != '' ? $caseinfo->CA_AGAINST_POSTCD : '' }}</td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_AGAINST_DISTCD')</b></td>
                                <td>:
                                    {{-- $caseinfo->CA_AGAINST_DISTCD != '' ? Ref::GetDescr('18', $caseinfo->CA_AGAINST_DISTCD, 'ms') : '' --}}
                                    {{ $caseinfo->againstdistcd ? $caseinfo->againstdistcd->descr : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_AGAINST_STATECD')</b></td>
                                <td>:
                                    {{-- $caseinfo->CA_AGAINST_STATECD != '' ? Ref::GetDescr('17', $caseinfo->CA_AGAINST_STATECD, 'ms') : '' --}}
                                    {{ $caseinfo->againststatecd ? $caseinfo->againststatecd->descr : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_SUMMARY')</b></td>
                                <td>:
                                    {{-- $caseinfo->CA_SUMMARY != '' ? $caseinfo->CA_SUMMARY : '' --}}
                                    {!! nl2br(htmlspecialchars($caseinfo->CA_SUMMARY)) !!}
                                </td>
                            </tr>
                            <tr>
                                <td><b>@lang('public-case.case.CA_ANSWER')</b></td>
                                <td>:
                                    {{-- $caseinfo->CA_ANSWER --}}
                                    {!! nl2br(htmlspecialchars($caseinfo->CA_ANSWER)) !!}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"
                                    style="text-align: center; background-color: #446CB3; color: white; font-weight: bold">
                                    @lang('public-case.case.CD_DOCATTCHID_PUBLIC')
                                </td>
                            </tr>
                            @if(isset($case_details))
                                @foreach ($case_details as $key => $value)
                                    <tr>
                                        <td>
                                            {!! '<a href='.Storage::disk('letter')->url($value->SuratPublic->file_name_sys).' target="_blank">'.$value->SuratPublic->file_name.'</a>' !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div align="center" style="padding-top: 20px; padding-bottom: 50px">
                            <a class="btn btn-primary btn-sm" href="{{ url('kepenggunaan') }}">@lang('button.back')</a>
                        </div>
                    </div>
                    <br/>
                @elseif(!$caseinfo)
                    <div align="center">
                        <p>@lang('portal.validation.norecordcse')</p>
                    </div>
                    <div align="center" style="padding-top: 20px; padding-bottom: 50px">
                        <a class="btn btn-primary btn-sm" href="{{ url('kepenggunaan') }}">@lang('button.back')</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
