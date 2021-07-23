<?php
use App\Ref;
use App\Aduan\PublicCase;
use App\Aduan\PublicCaseDoc;
?>

<table border="1" style="width: 100%;">
    <tr>
        <td align="center">
            <h3 class="font-bold no-margins">
                @lang('pertanyaan.title.successreceive')
            </h3>
            <h1 class="m-xs">@lang('pertanyaan.title.success') <strong>{{ $ASKSEID }}</strong></h1>
        </td>
    </tr>
</table>
<br />
<table border="0" style="width: 100%;">
    <tr>
        <td>@lang('pertanyaan.form_label.docno')</td>
        <td>: {{ $model->AS_DOCNO }}</td>
    </tr>
    <tr>
        <td>@lang('pertanyaan.form_label.name')</td>
        <td>: {{ $model->AS_NAME }}</td>
    </tr>
    <tr>
        <td>@lang('pertanyaan.table_header.enquiry.acceptdate')</td>
        <td>: {{ $model->AS_RCVDT }}</td>
    </tr>
    <tr>
        <td>@lang('pertanyaan.form_label.brncd')</td>
        <td>: {{ $branch->BR_BRNNM }}</td>
    </tr>
    <tr>
        <td>@lang('pertanyaan.form_label.statecd')</td>
        <td>: {{ $state->descr }}</td>
    </tr>
    <tr>
        <td>@lang('pertanyaan.form_label.summary')</td>
        <td>: {!! $model->AS_SUMMARY !!}</td>
    </tr>
</table>