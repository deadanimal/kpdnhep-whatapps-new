<?php

use App\Ref;
use App\User;
use App\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">@lang('public-case.case.CA_INVBY_INFO')</h4>
</div>
<div class="modal-body">
    <div class="row">
        <table style="width: 100%;">
            <tr style="background: #115272; color: white; "><th colspan="6">@lang('public-case.case.CA_INVBY_INFO')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_INVBY')</td>
                <td style="width: 1%;"> : </td>
                <td>{{ $model->name }}</td>
            </tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_EMAIL')</td>
                <td style="width: 1%;"> : </td>
                <td>{{ $model->email }}</td>
            </tr>
            @if(Auth::user()->user_cat =='1')
                <tr>
                    <td style="width: 18%;">No. Telefon (Bimbit)</td>
                    <td style="width: 1%;"> : </td>
                    <td>{{ $model->mobile_no }}</td>
                </tr>
            @endif
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_INVBY_TELNO')</td>
                <td style="width: 1%;"> : </td>
                <td>{{ !empty($model->office_no) ? $model->office_no : ($model->Cawangan ? $model->Cawangan->BR_TELNO : '') }}</td>
            </tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_BRNCD')</td>
                <td style="width: 1%;"> : </td>
                <td>{{ $model->brn_cd != ''? $model->Cawangan->BR_BRNNM:'' }}</td>
            </tr>
        </table>
    </div>
</div>
