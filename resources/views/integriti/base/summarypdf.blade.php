<?php
    use App\Ref;
    use App\User;
    use App\Branch;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;
?>
<style>
    table {
        overflow: wrap;
    }
    th, td {
        vertical-align: top;
    }
    .text-align-left {
        text-align: left;
    }
    .font-weight-bold {
        font-weight: bold;
    }
    .pagebreak {
        overflow: hidden;
        page-break-after: always;
    }
</style>
<div class="modal-header">
    <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
    <br/>
    <h4 class="modal-title">@lang('public-case.case.detailcomplaint')</h4>
</div>
<div class="modal-body">
    <div class="row">
        <!--<div class="col-sm-6">-->
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr>
                <th style="width:18%" class="text-align-left">@lang('public-case.case.CA_CASEID')</th>
                <th> : </th>
                <th class="text-align-left" style="width: 30%">{{$model->IN_CASEID}}</th>
                <th class="text-align-left" style="width: 18%">@lang('public-case.case.CA_BRNNM')</th>
                <th> : </th>
                <th class="text-align-left" style="width: 30%"> @if($model->IN_DEPTCD!=''){{Ref::GetDescr('315', $model->IN_DEPTCD, App::getLocale())}} @endif</th>
            </tr>
            <tr>
                <th class="text-align-left">@lang('public-case.case.IN_FILEREF')</th>
                <th> : </th>
                <th class="text-align-left">{{ $model->IN_FILEREF }}</th>
                <th class="text-align-left">@lang('public-case.case.CA_RCVDT')</th>
                <th> : </th>
                <th class="text-align-left">{{Carbon::parse($model->IN_RCVDT)->format('d-m-Y h:i A')}}</th>
            </tr>
            <tr>
                @if($mBukaSemula)
                <th class="text-align-left" style="width: 18%">@lang('public-case.case.CF_CASEID')</th>
                <th> : </th>
                <th class="text-align-left" style="width: 30%">{{ $mBukaSemula->IF_CASEID }}</th>
                @else
                <th></th>
                <th></th>
                <th></th>
                @endif
                <th class="text-align-left">@lang('public-case.case.CA_CREDT')</th>
                <th> : </th>
                <th class="text-align-left">
                    @if($model->IN_CREATED_AT!='')
                    {{Carbon::parse($model->IN_CREATED_AT)->format('d-m-Y h:i A')}}
                    @endif
                </th>
            </tr>
            @if(Auth::user()->user_cat =='1')
                <tr>
                    @if($mGabungAll)
                        <th class="text-align-left">Gabung Aduan</th>
                        <th> : </th>
                        <th class="text-align-left">
                            @foreach ($mGabungAll as $gabung)
                                {{ $gabung->IR_CASEID }}<br />
                            @endforeach
                        </th>
                    @else
                        <th></th>
                        <th></th>
                        <th></th>
                    @endif
                    @if($model->IN_FILEREF)
                        <th class="text-align-left">No. Rujukan Fail</th>
                        <th> : </th>
                        <th class="text-align-left">{{ $model->IN_FILEREF }}</th>
                    @endif
                </tr>
            @endif
        </table>
        <br />
        @if(
            (
                Auth::user()->user_cat == '1'
                &&
                (
                    in_array(Auth::user()->Role->role_code,['800','191'])
                    ||
                    (
                        in_array(Auth::user()->Role->role_code,['192','193'])
                        &&
                        $model->IN_ACCESSIND == '1'
                    )
                )
            )
            ||
            Auth::user()->user_cat == '2'
        )
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style="color: white; ">@lang('public-case.case.CA_CMPBY')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_NAME')</td>
                <td style="width: 1%;">:</td>
                @if(Auth::user()->user_cat =='1')
                    <td colspan="4">{{$model->IN_NAME}}</td>
                @else
                    <td>{{$model->IN_NAME}}</td>
                @endif
            </tr>
            <tr>
                <td >@lang('public-case.case.CA_DOCNO')</td>
                <td> : </td>
                <td>{{$model->IN_DOCNO}}</td>
            </tr>
            @if(Auth::user()->user_cat =='1')
            <tr>
                <td>@lang('public-case.case.CA_RACECD')</td>
                <td> : </td>
                <td>
                    @if($model->bangsa)
                        {{ $model->bangsa->descr }}
                    @else
                        {{ $model->IN_RACECD }}
                    @endif
                </td>
            </tr>
            @endif
            <tr>
                <td>@lang('public-case.case.CA_NATCD')</td>
                <td> : </td>
                <td>
                    @if($model->warganegara)
                        {{ $model->warganegara->descr }}
                    @else
                        {{ $model->IN_NATCD }}
                    @endif
                </td>
            </tr>
            @if(Auth::user()->user_cat =='1')
            <tr>
                <td>@lang('public-case.case.CA_SEXCD')</td>
                <td> : </td>
                <td>
                    @if($model->jantina)
                        {{ $model->jantina->descr }}
                    @else
                        {{ $model->IN_SEXCD }}
                    @endif
                </td>
            </tr>
            @endif
            <tr>
                <td>@lang('public-case.case.CA_ADDR')</td>
                <td> : </td>
                <td style="width: 30%;">{!! nl2br(htmlspecialchars($model->IN_ADDR)) !!}</td>
                @if(Auth::user()->user_cat =='1')
                    <th style="width: 16%;" class="text-align-left">@lang('public-case.case.CA_ADDR') MyIdentity</th>
                    <th> : </th>
                    <th style="width: 34%;" class="text-align-left">
                        {!! 
                            $model->docnopengadu 
                            ? nl2br(htmlspecialchars($model->docnopengadu->myidentity_address)) 
                            : nl2br(htmlspecialchars($model->IN_MYIDENTITY_ADDR)) 
                        !!}
                    </th>
                @endif
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_POSTCD')</td>
                <td> : </td>
                <td>{{$model->IN_POSTCD}}</td>
                @if(Auth::user()->user_cat =='1')
                    <th class="text-align-left">@lang('public-case.case.CA_POSTCD')</th>
                    <th> : </th>
                    <th class="text-align-left">
                        {{ $model->docnopengadu ? $model->docnopengadu->myidentity_postcode : $model->IN_MYIDENTITY_POSTCD }}
                    </th>
                @endif
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_DISTCD')</td>
                <td> : </td>
                <td>
                    {{ $model->daerahpengadu ? $model->daerahpengadu->descr : $model->IN_DISTCD }}
                </td>
                @if(Auth::user()->user_cat =='1')
                    <th class="text-align-left">@lang('public-case.case.CA_DISTCD')</th>
                    <th> : </th>
                    <th class="text-align-left">
                        {{ 
                            $model->docnopengadu ? 
                            ($model->docnopengadu->DaerahMyidentity ? $model->docnopengadu->DaerahMyidentity->descr : $model->docnopengadu->myidentity_distrinct_cd) 
                            : ($model->daerahmyidentity ? $model->daerahmyidentity->descr : $model->IN_MYIDENTITY_DISTCD) 
                        }}
                    </th>
                @endif
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_STATECD')</td>
                <td> : </td>
                <td>
                    {{ $model->negeripengadu ? $model->negeripengadu->descr : $model->IN_STATECD }}
                </td>
                @if(Auth::user()->user_cat =='1')
                    <th class="text-align-left">@lang('public-case.case.CA_STATECD')</th>
                    <th> : </th>
                    <th class="text-align-left">
                        {{ 
                            $model->docnopengadu ? 
                            ($model->docnopengadu->NegeriMyidentity ? $model->docnopengadu->NegeriMyidentity->descr : $model->docnopengadu->myidentity_state_cd) 
                            : ($model->negerimyidentity ? $model->negerimyidentity->descr : $model->CA_MYIDENTITY_STATECD) 
                        }}
                    </th>
                @endif
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_TELNO')</td>
                <td> : </td>
                <td>{{$model->IN_TELNO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_FAXNO')</td>
                <td> : </td>
                <td>{{$model->IN_FAXNO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_MOBILENO')</td>
                <td> : </td>
                <td>{{$model->IN_MOBILENO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_EMAIL')</td>
                <td> : </td>
                <td>{{$model->IN_EMAIL}}</td>
            </tr>
        </table>
        <br />
        @endif
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style="color: white; ">@lang('public-case.case.detailcomplaint')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_RCVTYP')</td>
                <td style="width: 1%;"> : </td>
                <td>
                    @if($model->CaraPenerimaan)
                        {{ $model->CaraPenerimaan->descr }}
                    @else
                        {{ $model->IN_RCVTYP }}
                    @endif
                </td>
            </tr>
            @if(Auth::user()->user_cat =='1')
            <tr>
                <td>@lang('public-case.case.CA_RCVBY')</td>
                <td> : </td>
                <td>
                    {{ $model->rcvby ? $model->rcvby->name : $model->IN_RCVBY }}
                </td>
            </tr>
            <tr>
                <td>Klasifikasi Aduan</td>
                <td> : </td>
                <td>
                    {{ $model->classification ? $model->classification->descr : $model->IN_CLASSIFICATION }}
                </td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_CMPLCAT')</td>
                <td> : </td>
                <td>
                    @if($model->kategori)
                        {{ $model->kategori->descr }}
                    @else
                        {{ $model->IN_CMPLCAT }}
                    @endif
                </td>
            </tr>
            @endif
            <tr>
                <td>@lang('public-case.case.IN_AGAINSTNM')</td>
                <td> : </td>
                <td>{{ $model->IN_AGAINSTNM }}</td>
            </tr>
            <tr>
                <td style="width: 18%;">
                    Jenis Rujukan Berkaitan
                </td>
                <td style="width: 1%;"> : </td>
                <td>
                    @if($model->IN_REFTYPE == 'BGK')
                        Salah Laku (Aduan Kepenggunaan)
                    @elseif($model->IN_REFTYPE == 'TTPM')
                        Salah Laku (TTPM)
                    @elseif($model->IN_REFTYPE == 'OTHER')
                        Salah Laku (Umum)
                    @endif
                </td>
            </tr>
            @if($model->IN_REFTYPE == 'BGK')
            <tr>
                <td>Rujukan Aduan Kepenggunaan</td>
                <td> : </td>
                <td>
                    {{ $model->IN_BGK_CASEID }}
                </td>
            </tr>
            @elseif($model->IN_REFTYPE == 'TTPM')
            <tr>
                <td>No. TTPM</td>
                <td> : </td>
                <td>
                    {{ $model->IN_TTPMNO }}
                </td>
            </tr>
            <tr>
                <td>Jenis Borang TTPM</td>
                <td> : </td>
                <td>
                    @if($model->IN_TTPMFORM == '8')
                        Borang 8 - Award bagi pihak yang menuntut jika penentang tidak hadir
                    @elseif($model->IN_TTPMFORM == '9')
                        Borang 9 - Award dengan persetujuan
                    @elseif($model->IN_TTPMFORM == '10')
                        Borang 10 - Award selepas pendengaran
                    @endif
                </td>
            </tr>
            @elseif($model->IN_REFTYPE == 'OTHER')
            <tr>
                <td>Rujukan Lain</td>
                <td> : </td>
                <td>
                    {{ $model->IN_REFOTHER }}
                </td>
            </tr>
            @endif
            <tr>
                <td>Lokasi PYDA</td>
                <td> : </td>
                <td>
                    @if($model->IN_AGAINSTLOCATION == 'BRN')
                        Bahagian / Cawangan KPDNHEP
                    @elseif($model->IN_AGAINSTLOCATION == 'AGN')
                        Agensi KPDNHEP
                    @endif
                </td>
            </tr>
            @if($model->IN_AGAINSTLOCATION == 'BRN')
            <tr>
                <td>Negeri</td>
                <td> : </td>
                <td>
                    {{ $model->againstbrstatecd ? $model->againstbrstatecd->descr : $model->IN_AGAINST_BRSTATECD }}
                </td>
            </tr>
            <tr>
                <!-- <td style="width: 18%;">
                    @lang('public-case.case.IN_BRNCD')
                </td>
                <td style="width: 1%;"> : </td>
                <td>
                    {{ $model->BrnCd ? $model->BrnCd->BR_BRNNM : $model->IN_BRNCD }}
                </td> -->
                <td>@lang('public-case.case.IN_BRNCD')</td>
                <td> : </td>
                <td>{{ $model->BrnCd ? $model->BrnCd->BR_BRNNM : $model->IN_BRNCD }}</td>
            </tr>
            @elseif($model->IN_AGAINSTLOCATION == 'AGN')
            <tr>
                <td>Agensi KPDNHEP</td>
                <td> : </td>
                <td>
                    {{ $model->agencycd ? $model->agencycd->MI_DESC : $model->IN_AGENCYCD }}
                </td>
            </tr>
            @endif
            <tr>
                <td>@lang('public-case.case.IN_SUMMARY_TITLE')</td>
                <td> : </td>
                <td>{{ $model->IN_SUMMARY_TITLE }}</td>
            </tr>
            <tr>
                <td >@lang('public-case.case.IN_SUMMARY')</td>
                <td> : </td>
                <td>{!! nl2br(htmlspecialchars($model->IN_SUMMARY)) !!}</td>
            </tr>
            <tr>
                <td>@lang('public-case.attachment.lampiranlabel')</td>
                <td> : </td>
                <td>
                    <?php $t = 1; ?>
                    @foreach ($lampiranaduan as $pic)
                    <!-- {{-- '<a href='.Storage::disk('bahanpath')->url(rawurlencode($pic->IC_PATH.$pic->IC_DOCNAME)).' target="_blank">'.$t++.') '.$pic->IC_DOCFULLNAME.'</a><br />' --}} -->
                    <!-- {{-- '<a href='.Storage::disk('integritibahanpath')->url(rawurlencode($pic->IC_PATH.$pic->IC_DOCNAME)).' target="_blank">'.$t++.') '.$pic->IC_DOCFULLNAME.'</a><br />' --}} -->
                    {!! $t++.') '.$pic->IC_DOCFULLNAME.'<br />' !!}
                    @endforeach
                </td>
            </tr>
        </table>
        <br />
        @if(Auth::user()->user_cat =='1')
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="6" style="color: white; ">Penugasan</th></tr>
            <tr>
                <td style="width: 18%"> Penugasan Aduan Oleh</td>
                <td>:</td>
                <td style="width: 30%">
                    {{ count($model->asgby) == '1' ? $model->asgby->name : $model->IN_ASGBY }}
                </td>
                <td style="width: 16%;">Tarikh Penugasan</td>
                <td> : </td>
                <td style="width: 34%">{{$model->IN_ASGDT != ''? Carbon::parse($model->IN_ASGDT)->format('d-m-Y h:i A'):''}}</td>
            </tr>
            <tr>
                <td>Penyiasat</td>
                <td> : </td>
                <td>
                    {{ count($model->invby) == '1' ? $model->invby->name : $model->IN_INVBY }}
                </td>
                <td>Tarikh Selesai </td>
                <td> : </td>
                <td>{{$model->IN_COMPLETEDT != ''? Carbon::parse($model->IN_COMPLETEDT)->format('d-m-Y h:i A'):''}}</td>
            </tr>
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style=" color: white; ">Siasatan</th></tr>
            <tr>
                <td style="width: 18%">Ditutup Oleh</td>
                <td style="width: 1%"> : </td>
                <td>
                    {{ count($model->closeby) ? $model->closeby->name : $model->IN_CLOSEBY }}
                </td>
            </tr>
            <tr>
                <td>Tarikh Ditutup</td>
                <td> : </td>
                <td>
                    {{ $model->IN_CLOSEDT ? Carbon::parse($model->IN_CLOSEDT)->format('d-m-Y h:i A') : '' }}
                </td>
            </tr>
        </table>
        <br/>
        @endif

        @if(Auth::user()->user_cat == '1')
            @if($model->IN_SSP =='YES')
            <table style="width: 100%; " class="table-bordered table-stripped">
                <tr style="background: #115272;"><th colspan="6" style="color:white">Wujud Kes</th></tr>
                <tr>
                    <th> Bil </th>
                    <th> No. IP </th>
                    <th> No. EP </th>
                    <th> Akta </th>
                    <th> Jenis Kesalahan </th>
                </tr>
                <?php $modelcount = 1; ?>
                @if(count($kes) > 0)
                    @foreach($kes as $keslist)
                        <tr>
                            <td>{{ $modelcount++ }}</td>
                            <td>{{ $keslist->IT_IPNO }}</td>
                            <td>{{ $keslist->IT_IPNO }}</td>
                            <td>{{ Ref::GetDescr('713',$keslist->IT_AKTA,App::getLocale()) }}</td>
                            <td>{{ Ref::GetDescr('714',$keslist->IT_SUBAKTA,App::getLocale()) }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
            <br/>
            @endif
        @endif
        @if(Auth::user()->user_cat =='1')
            @if (count($saranan) > 0)
            <table style="width: 100%; " class="table-bordered table-stripped">
                <tr style="background: #115272;"><th colspan="6" style=" color: white; ">Saranan</th></tr>
                <?php $sarananCount = 1; ?>
                @foreach($saranan as $sarananList)
                <tr>
                    <td> {{ $sarananCount++ }}. </td>
                    <td>{{ $sarananList->ID_DESC }}</td>
                </tr>
                @endforeach
            </table>
            <br/>
            @endif
        @endif
        @if(Auth::user()->user_cat =='1')
            @if ($model->IN_RESULT)
            <table style="width: 100%; " class="table-bordered table-stripped">
                <tr style="background: #115272;"><th colspan="6" style="color: white; ">@lang('public-case.case.CA_RESULT')</th></tr>
                <tr>
                    <td style="width: 18%;">@lang('public-case.case.CA_RESULT')</td>
                    <td style="width: 1%;"> : </td>
                    <td>{{ $model->IN_RESULT }}</td>
                </tr>
            </table>
            <br/>
            @endif
        @endif
        @if ($model->IN_ANSWER != NULL && (in_array($model->IN_IPSTS, ['04','05','09']) || in_array($model->IN_INVSTS, ['04','05','06','07','08'])))
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="3" style="color: white;">@lang('public-case.case.CA_ANSWER')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_ANSWER')</td>
                <td style="width: 1%;"> : </td>
                <td>{{ $model->IN_ANSWER }}</td>
            </tr>
        </table>
        <br/>
        @endif
        @if (Auth::user()->user_cat == '1')
            @if (count($lampiransiasatan) > 0)
            <table style="width: 100%; " class="table-bordered table-stripped">
                <tr style="background: #115272;"><th colspan="6" style="color: white; ">@lang('public-case.case.CA_EVIDENCE')</th></tr>
                <?php $s = 1; ?>
                @foreach($lampiransiasatan as $bukti)
                <tr>
                    <td>{{ $s++ }}</td>
                    <td>{{ $bukti->IC_DOCFULLNAME }}</td>
                    <td>{!! '<a href='.Storage::disk('integritibahanpath')->url($bukti->IC_PATH.$bukti->IC_DOCNAME).' target="_blank">'.$bukti->IC_DOCFULLNAME.'</a>' !!}</td>
                </tr>
                @endforeach
            </table>
            <br />
            @endif
        @endif
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="8" style=" color: white; ">@lang('public-case.case.transactionsm')</th></tr>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($transaksi as $sorotan)
                    <tr>
                        <td style="width: 5%"> {{ $i++ }} </td>
                        <td>
                            @if($sorotan->ID_INVSTS!='') 
                                @if(($sorotan->ID_ACTTYPE=='CLS')&&($sorotan->ID_INVSTS=='02'))
                                    <strong> PENUGASAN SEMULA </strong>
                                @elseif($sorotan->ID_DESC=='Aduan Dibuka Semula') 
                                    <strong> BUKA SEMULA </strong>
                                @elseif(($sorotan->ID_ACTTYPE=='KICK')&&($sorotan->ID_INVSTS=='02'))
                                    <strong> PENYIASATAN DITOLAK </strong>
                                @else 
                                    <strong>
                                    @if (in_array($sorotan->ID_INVSTS,['01','02','04','05','06','07','08','011','012']) && $sorotan->ID_IPSTS == NULL)
                                        {{ strtoupper(Ref::GetDescr('1334',$sorotan->ID_INVSTS,App::getLocale())) }}
                                    @elseif (in_array($sorotan->ID_IPSTS,['03','04','05','09']))
                                        {{ strtoupper(Ref::GetDescr('1370',$sorotan->ID_IPSTS,App::getLocale())) }}
                                    @endif
                                    </strong>
                                @endif
                            @elseif($sorotan->ID_ACTTYPE=='UPDATEINFO')
                                <strong> KEMASKINI MAKLUMAT ADUAN </strong>
                            @endif
                            @if(Auth::user()->user_cat =='1')
                                <!-- STATUS ADUAN -->
                                @if (in_array($sorotan->ID_INVSTS,['01','02','04','05','06','07','08','011','012']) && $sorotan->ID_IPSTS == NULL)
                                    @if (count($sorotan->createdby) == '0' || $sorotan->createdby->user_cat =='2') 
                                        <!-- {{-- @lang('public-case.case.BY') --}} -->
                                        <!-- <strong>Online Web </strong> -->
                                    @elseif  ($sorotan->createdby->user_cat =='1') 
                                        <!-- {{-- @lang('public-case.case.BY') --}} -->
                                        Oleh
                                        <strong>
                                            {{ count($sorotan->createdby) ? $sorotan->createdby->name : '' }}
                                        </strong>
                                    @endif
                                <!-- STATUS PENYIASATAN -->
                                @elseif (in_array($sorotan->ID_IPSTS,['03','04','05','09']))
                                    @if (count($sorotan->createdby) == '0' || $sorotan->createdby->user_cat =='2') 
                                        <!-- {{-- @lang('public-case.case.BY') --}} -->
                                        <!-- <strong>Online Web </strong> -->
                                    @elseif  ($sorotan->createdby->user_cat =='1') 
                                        <!-- {{-- @lang('public-case.case.BY') --}} -->
                                        Oleh
                                        <strong>
                                            {{ count($sorotan->createdby) ? $sorotan->createdby->name : '' }}
                                        </strong>
                                    @endif
                                @elseif($sorotan->ID_ACTTYPE=='UPDATEINFO')
                                    Oleh
                                    <strong>
                                        {{ $sorotan->createdby ? $sorotan->createdby->name : '' }}
                                    </strong>
                                @endif
                                @if($sorotan->ID_ACTTO != '' && !in_array($sorotan->ID_INVSTS, array('04','05','08','00'), true))
                                    Kepada Pegawai
                                    <strong>
                                        {{ count($sorotan->actto) ? $sorotan->actto->name:''}}
                                    </strong>
                                @endif
                            @endif
                            <!-- {{-- @lang('public-case.case.AT') --}} -->
                            Pada
                            <strong>
                                {{ 
                                    $sorotan->ID_CREATED_AT 
                                    ? Carbon::parse($sorotan->ID_CREATED_AT)->format('d-m-Y h:i A') 
                                    : '' 
                                }}
                            </strong>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style="color: white;">@lang('public-case.case.CD_DOCATTCHID_PUBLIC')</th></tr>
            <tr>
                <th>Bil.</th>
                @if(
                        (
                            Auth::user()->user_cat == '1'
                            &&
                            (
                                in_array(Auth::user()->Role->role_code,['800','191'])
                                ||
                                (
                                    in_array(Auth::user()->Role->role_code,['192','193'])
                                    &&
                                    $model->IN_ACCESSIND == '1'
                                )
                            )
                        )
                        ||
                        Auth::user()->user_cat == '2'
                    )
                <th>@lang('public-case.case.CD_DOCATTCHID_PUB')</th>
                @endif
                @if(Auth::user()->user_cat =='1')
                <th>@lang('public-case.case.CD_DOCATTCHID_ADM')</th>
                @endif
            </tr>
            <?php $i = 1; ?>
            @foreach ($transaksi as $sorotan)
            @if($sorotan->ID_INVSTS!=10)
                @if($sorotan->ID_DOCATTCHID_PUBLIC != '' || $sorotan->ID_DOCATTCHID_ADMIN != '')
                <tr>
                    <td> {{ $i++ }} </td>
                    @if(
                        (
                            Auth::user()->user_cat == '1'
                            &&
                            (
                                in_array(Auth::user()->Role->role_code,['800','191'])
                                ||
                                (
                                    in_array(Auth::user()->Role->role_code,['192','193'])
                                    &&
                                    $model->IN_ACCESSIND == '1'
                                )
                            )
                        )
                        ||
                        Auth::user()->user_cat == '2'
                    )
                    <td>
                        @if($sorotan->ID_DOCATTCHID_PUBLIC != '')
                        <!--Surat Kepada Pengadu :-->
                        {!! $sorotan->docattachidpublic->file_name !!}
                        @endif
                    </td>
                    @endif
                    @if(Auth::user()->user_cat =='1')
                    <td> 
                        @if($sorotan->ID_DOCATTCHID_ADMIN != '')
                        <!--Surat Kepada Pegawai :--> 
                        {!! $sorotan->docattachidadmin->file_name !!}
                        @endif
                    </td>
                    @endif
                </tr>
                @endif
            @endif
            @endforeach
        </table>
        <br/>
        <!--</div>-->
        <!--<div class="col-sm-6">-->

        <!--</div>-->
    </div>
</div>
