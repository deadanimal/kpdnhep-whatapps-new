<?php
use App\Ref;
use App\Aduan\PublicCase;
use App\Aduan\PublicCaseDoc;
?>

<table border="1" style="width: 100%;">
    <tr>
        <td style="width: 20%">
            <center>
                <!-- img src="assets/images/kpdnkk-128x112.png" width="6%" -->
                <img src="{{ url('/img/Kpdnkk.jpg') }}" width="7%">
            </center>
        </td>
        <td align="center">
            <h3 class="font-bold no-margins">
                @lang('public-case.success.receivenote')
            </h3>
            <h1 class="m-xs">@lang('public-case.case.CA_CASEID') <strong>{{ $model->CA_CASEID }}</strong></h1>
            <small>@lang('public-case.success.emailnotify') <b>{{ Auth::user()->email }}</b></small>
        </td>
        <td style="width: 20%"><center><img src="{{ url('/img/logo2_0.png') }}" width="18%"></center></td>
    </tr>
</table>
<br />
<table border="0" style="width: 100%;">
    <tr>
        <td>@lang('public-case.case.CA_CMPLCAT')</td>
        <td>: {{ $model->CA_CMPLCAT != ''? Ref::GetDescr('244', $model->CA_CMPLCAT, Auth::user()->lang) : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_CMPLCD')</td>
        <td>: {{ $model->CA_CMPLCD != ''? Ref::GetDescr('634', $model->CA_CMPLCD, Auth::user()->lang) : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_ONLINECMPL_AMOUNT')</td>
        <td>: {{ number_format($model->CA_ONLINECMPL_AMOUNT, 2) }}</td>
    </tr>
    @if($model->CA_CMPLKEYWORD != '')
    <tr>
        <td>@lang('public-case.case.CA_CMPLKEYWORD')</td>
        <td>: {{ $model->CA_CMPLKEYWORD != ''? Ref::GetDescr('1051', $model->CA_CMPLKEYWORD, Auth::user()->lang):'' }}</td>
    </tr>
    @endif
    @if($model->CA_CMPLCAT == 'BPGK 19')
    <tr>
        <td>@lang('public-case.case.CA_ONLINECMPL_PROVIDER')</td>
        <td>: {{ $model->CA_ONLINECMPL_PROVIDER != ''? Ref::GetDescr('1091', $model->CA_ONLINECMPL_PROVIDER, Auth::user()->lang) : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_ONLINECMPL_URL')</td>
        <td>: {{ $model->CA_ONLINECMPL_URL != ''? $model->CA_ONLINECMPL_URL : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_ONLINECMPL_ACCNO')</td>
        <td>: {{ $model->CA_ONLINECMPL_ACCNO != ''? $model->CA_ONLINECMPL_ACCNO : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_ONLINECMPL_IND')</td>
        <td>: {{ $model->CA_ONLINECMPL_IND != ''? Ref::GetDescr('1065', $model->CA_ONLINECMPL_IND, Auth::user()->lang) : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_ONLINECMPL_CASENO')</td>
        <td>: {{ $model->CA_ONLINECMPL_CASENO != ''? $model->CA_ONLINECMPL_CASENO : '' }}</td>
    </tr>
    @endif
    <tr>
        <td>@lang('public-case.case.CA_AGAINSTNM')</td>
        <td>: {{ $model->CA_AGAINSTNM != ''? $model->CA_AGAINSTNM : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_AGAINST_TELNO')</td>
        <td>: {{ $model->CA_AGAINST_TELNO != ''? $model->CA_AGAINST_TELNO : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_AGAINST_MOBILENO')</td>
        <td>: {{ $model->CA_AGAINST_MOBILENO != ''? $model->CA_AGAINST_MOBILENO : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_AGAINST_EMAIL')</td>
        <td>: {{ $model->CA_AGAINST_EMAIL != ''? $model->CA_AGAINST_EMAIL : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_AGAINST_FAXNO')</td>
        <td>: {{ $model->CA_AGAINST_FAXNO != ''? $model->CA_AGAINST_FAXNO : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_AGAINSTADD')</td>
        <td>: {{ $model->CA_AGAINSTADD != ''? $model->CA_AGAINSTADD : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_AGAINST_POSTCD')</td>
        <td>: {{ $model->CA_AGAINST_POSTCD != ''? $model->CA_AGAINST_POSTCD : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_AGAINST_STATECD')</td>
        <td>: {{ $model->CA_AGAINST_STATECD != ''? Ref::GetDescr('17', $model->CA_AGAINST_STATECD) : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_AGAINST_DISTCD')</td>
        <td>: {{ $model->CA_AGAINST_DISTCD != '' ? Ref::GetDescr('18', $model->CA_AGAINST_DISTCD, Auth::user()->lang) : '' }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_SUMMARY')</td>
        <td>: {!! $model->CA_SUMMARY != '' ? $model->CA_SUMMARY : '' !!}</td>
    </tr>
</table>