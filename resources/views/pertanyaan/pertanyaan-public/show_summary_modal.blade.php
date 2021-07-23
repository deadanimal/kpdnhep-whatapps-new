<?php
use App\Ref;
use App\User;
use App\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">@lang('pertanyaan.table_header.enquiry.info')</h4>
</div>
<div class="modal-body">
    <div class="row">
        <!--<div class="col-sm-6">-->
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr>
                <th>@lang('pertanyaan.table_header.enquiry.askid')</th>
                <th> : </th>
                <th>{{ $model->AS_ASKID }}</th>
                <th>@lang('pertanyaan.form_label.brncd')</th>
                <th> : </th>
                <th>{{ Branch::GetBranchName($model->AS_BRNCD) }}</th>

            </tr>
            <tr>
                <th>@lang('pertanyaan.form_label.credt')</th>
                <th> : </th>
                <th>{{Carbon::parse($model->AS_CREDT)->format('d-m-Y h:i A')}}</th>
                <th>@lang('pertanyaan.table_header.enquiry.acceptdate')</th>
                <th> : </th>
                <th>{{Carbon::parse($model->AS_RCVDT)->format('d-m-Y h:i A')}}</th>

            </tr>
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="3">@lang('pertanyaan.tab.enquirysm')</th></tr>
            <tr>
                <td style="width: 26%;">@lang('pertanyaan.form_label.completeby')</td>
                <td style="width: 1%;"> : </td>
                <td>{{ $model->AS_COMPLETEBY != ''? $model->CompleteBy->name:'' }}</td>
            </tr>
            <tr>
                <td style="width: 18%;">@lang('pertanyaan.tab.enquirysm')</td>
                <td style="width: 1%;"> : </td>
                <td>{{ $model->AS_SUMMARY }}</td>
            </tr>
            <tr>
                <td>@lang('pertanyaan.table_header.enquiry.answer')</td>
                <td> : </td>
                <td>{{ $model->AS_ANSWER }}</td>
            </tr>
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="3">@lang('pertanyaan.table_header.enquiry.askbydetail')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('pertanyaan.form_label.name')</td>
                <td style="width: 1%;">:</td>
                <td>{{ $model->user->name }}</td>
            </tr>
            <tr>
                <td>@lang('pertanyaan.form_label.docno')</td>
                <td> : </td>
                <td>{{ $model->user->icnew }}</td>
            </tr>
            <tr>
                <td>@lang('pertanyaan.form_label.natcd')</td>
                <td> : </td>
                <td>{{ $model->user->citizen != ''? Ref::GetDescr('947',$model->user->citizen):'' }}</td>
            </tr>
<!--            <tr>
                <td>Jantina</td>
                <td> : </td>
                <td>{{ $model->user->gender != ''? Ref::GetDescr('202',$model->CA_SEXCD,'ms'): '' }}</td>
            </tr>-->
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="3">@lang('pertanyaan.table_header.transaction.transaction_info')</th></tr>
            <tr>
                <th>Bil.</th>
                <th>@lang('pertanyaan.table_header.enquiry.asksts')</th>
                <th>@lang('pertanyaan.table_header.transaction.transaction_date')</th>
            </tr>

            <tbody>
                <?php $i=1; ?>
                @foreach ($trnsksi as $sorotan)
                <tr>
                    <td><center>{{ $i++ }} </center></td>
        <td><center>{{ $sorotan->AD_ASKSTS !=''? Ref::GetDescr('1061',$sorotan->AD_ASKSTS,App::getLocale()):'' }}</center></td>
        <td><center>{{ Carbon::parse($sorotan->AD_CREDT)->format('d-m-Y h:i A') }}</center></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br />
        <a href="{{route('pertanyaan.printsummary', $model->AS_ASKID)}}" target="_blank" class="btn btn-success btn-sm">@lang('pertanyaan.form_label.print')</a>
        <!--</div>-->
        <!--<div class="col-sm-6">-->
            
        <!--</div>-->
    </div>
</div>
