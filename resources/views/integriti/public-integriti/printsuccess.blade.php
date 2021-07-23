<?php
    use App\Branch;
?>
<style>
    th, td {
        vertical-align: top;
    }
</style>
<table border="1" style="width: 100%;">
    <tr>
        <!-- <td style="width: 20%"> -->
            <!-- <center><img src="assets/images/kpdnkk-128x112.png" width="6%"></center> -->
        <!-- </td> -->
        <td style="width: 20%; text-align:center;">
            <!-- <center> -->
            <!-- <img src="assets/images/kpdnkk-128x112.png" width="6%"> -->
            <img src="{{ url('/img/jata.png') }}" width="60" height="60">
            <!-- </center> -->
        </td>
        <td style="text-align:center;">
            <h3 class="font-bold no-margins">
                <!-- @lang('public-case.success.receivenote') -->
                {{Auth::user()->lang == 'ms' ? 'Aduan Integriti anda telah diterima.' : 'Your integrity complaint has been received.'}}
            </h3>
            <h1 class="m-xs">
                @lang('public-case.case.CA_CASEID') : <strong>{{ $model->IN_CASEID }}</strong>
            </h1>
            <!-- <small>@lang('public-case.success.emailnotify')  -->
                <!-- <b>{{ Auth::user()->email }}</b> -->
            <!-- </small> -->
        </td>
        <!-- <td style="width: 20%"> -->
            <!-- <center><img src="{{ url('/img/logo2_0.png') }}" width="18%"></center> -->
        <!-- </td> -->
        <td style="width: 20%; text-align:center;">
            <!-- <center> -->
            <!-- <img src="{{ url('/img/logo2_0.png') }}" width="18%"> -->
            <img src="{{url('img/Kpdnkk.jpg')}}" width="60" height="60">
            <!-- </center> -->
        </td>
    </tr>
</table>
<br />
<table style="width: 100%;">
    <tr>
        <td style="width: 30%;">
            {{Auth::user()->lang == 'ms' ? 'Nama Pegawai Yang Diadu (PYDA)' : 'Name of Claimed Officer'}}</td>
        <td style="width: 1%;"> : </td>
        <td>{{ $model->IN_AGAINSTNM }}</td>
    </tr>
    <tr>
        <td>
            {{Auth::user()->lang == 'ms' ? 'Jenis Rujukan Berkaitan' : 'Type Of Related Reference'}}
        </td>
        <td> : </td>
        <td>
            @if($model->IN_REFTYPE == 'BGK')
                {{ Auth::user()->lang == 'ms' ? 'Aduan Kepenggunaan' : 'Consumer Complaint' }}
            @elseif($model->IN_REFTYPE == 'TTPM')
                {{ Auth::user()->lang == 'ms' ? 'No. TTPM' : 'TTPM No.' }}
            @elseif($model->IN_REFTYPE == 'OTHER')
                {{ Auth::user()->lang == 'ms' ? 'Lain-lain' : 'Others' }}
            @endif
        </td>
    </tr>
    @if($model->IN_REFTYPE == 'BGK')
    <tr>
        <td>
        {{Auth::user()->lang == 'ms' ? 'Aduan Kepenggunaan' : 'Consumer Complaint'}}
        </td>
        <td> : </td>
        <td>{{ $model->IN_BGK_CASEID }}</td>
    </tr>
    @elseif($model->IN_REFTYPE == 'TTPM')
    <tr>
        <td>
        {{Auth::user()->lang == 'ms' ? 'No. TTPM' : 'TTPM No.'}}
        </td>
        <td> : </td>
        <td>{{ $model->IN_TTPMNO }}</td>
    </tr>
    @elseif($model->IN_REFTYPE == 'OTHER')
    <tr>
        <td>
        {{Auth::user()->lang == 'ms' ? 'Lain-lain' : 'Others'}}
        </td>
        <td> : </td>
        <td>{{ $model->IN_REFOTHER }}</td>
    </tr>
    @endif
    <tr>
        <td>
        {{Auth::user()->lang == 'ms' ? 'Lokasi PYDA' : 'Officer Location'}}
        </td>
        <td> : </td>
        <td>
            @if($model->IN_AGAINSTLOCATION == 'BRN')
                {{ Auth::user()->lang == 'ms' ? 'Bahagian / Cawangan KPDNHEP' : 'KPDNHEP Department / Branch' }}
            @elseif($model->IN_AGAINSTLOCATION == 'AGN')
                {{ Auth::user()->lang == 'ms' ? 'Agensi KPDNHEP' : 'KPDNHEP Agencies' }}
            @endif
        </td>
    </tr>
    @if($model->IN_AGAINSTLOCATION == 'BRN')
    <tr>
        <td>{{ Auth::user()->lang == 'ms' ? 'Negeri' : 'State' }}</td>
        <td> : </td>
        <td>
            {{ $model->againstbrstatecd ? $model->againstbrstatecd->descr : $model->IN_AGAINST_BRSTATECD }}
        </td>
    </tr>
    <tr>
        <td>
            <!-- Bahagian / Cawangan -->
            @lang('public-case.case.IN_BRNCD')
        </td>
        <td> : </td>
        <td>
            <!-- {{-- !empty($model->IN_BRNCD) ? Branch::GetBranchName($model->IN_BRNCD) : '' --}} -->
            {{ $model->BrnCd ? $model->BrnCd->BR_BRNNM : $model->IN_BRNCD }}
            </td>
    </tr>
    @elseif($model->IN_AGAINSTLOCATION == 'AGN')
    <tr>
        <td>{{ Auth::user()->lang == 'ms' ? 'Agensi KPDNHEP' : 'KPDNHEP Agencies' }}</td>
        <td> : </td>
        <td>
            {{ $model->agencycd ? $model->agencycd->MI_DESC : $model->IN_AGENCYCD }}
        </td>
    </tr>
    @endif
    <tr>
        <td>{{ Auth::user()->lang == 'ms' ? 'Tajuk Aduan' : 'Complaint Title' }}</td>
        <td> : </td>
        <td>{{ $model->IN_SUMMARY_TITLE }}</td>
    </tr>
    <tr>
        <td>@lang('public-case.case.CA_SUMMARY')</td>
        <td> : </td>
        <td>
            <!--{{-- $model->IN_SUMMARY != '' ? $model->IN_SUMMARY : '' --}}-->
            {!! nl2br(htmlspecialchars($model->IN_SUMMARY)) !!}
        </td>
    </tr>
</table>