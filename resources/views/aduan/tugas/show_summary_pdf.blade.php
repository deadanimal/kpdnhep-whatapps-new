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
                <th class="text-align-left">@lang('public-case.case.CA_CASEID')</th>
                <th> : </th>
                <th class="text-align-left">{{ $model->CA_CASEID ? $model->CA_CASEID : '' }}</th>
                <th class="text-align-left">@lang('public-case.case.CA_BRNNM')</th>
                <th> : </th>
                <th class="text-align-left"> @if($model->CA_BRNCD!=''){{Branch::GetBranchName($model->CA_BRNCD)}} @endif</th>

            </tr>
            <tr>
                <th class="text-align-left">@lang('public-case.case.CA_CREDT')</th>
                <th> : </th>
                <th class="text-align-left">
                    @if($model->CA_CREDT!='')
                    {{Carbon::parse($model->CA_CREDT)->format('d-m-Y h:i A')}}
                    @endif
                </th>
                <th class="text-align-left">@lang('public-case.case.CA_RCVDT')</th>
                <th> : </th>
                <th class="text-align-left">{{Carbon::parse($model->CA_RCVDT)->format('d-m-Y h:i A')}}</th>

            </tr>
            @if($mBukaSemula)
            <tr>
                <th class="text-align-left">@lang('public-case.case.CF_CASEID')</th>
                <th> : </th>
                <th class="text-align-left">{{ $mBukaSemula->CF_CASEID }}</th>
            </tr>
            @endif
            @if(Auth::user()->user_cat =='1')
                <tr>
                    @if($mGabungAll)
                        <th class="text-align-left">Gabung Aduan</th>
                        <th> : </th>
                        <th class="text-align-left">
                            @foreach ($mGabungAll as $gabung)
                                {{ $gabung->CR_CASEID }}<br />
                            @endforeach
                        </th>
                    @else
                        <th></th>
                        <th></th>
                        <th></th>
                    @endif
                    @if($model->CA_FILEREF)
                        <th class="text-align-left">No. Rujukan Fail</th>
                        <th> : </th>
                        <th class="text-align-left">{{ $model->CA_FILEREF }}</th>
                    @endif
                </tr>
            @endif
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6"  style="color:white;">@lang('public-case.case.trxcomplaint')</th></tr>
            <tr>
                <td style="width: 18%; ">@lang('public-case.case.CA_RCVTYP')</td>
                <td style="width: 1%;"> : </td>
                <td>@if($model->CA_RCVTYP!=''){{Ref::GetDescr('259',$model->CA_RCVTYP,App::getLocale())}}@endif</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_RCVBY')</td>
                <td> : </td>
                <td>
                    <!--{{-- $model->CA_RCVBY != ''? ($model->CA_RCVBY == 'Sistem Online'? $model->CA_RCVBY:$model->namaPenerima->name):'' --}}-->
                    {{ !empty($model->namaPenerima) ? $model->namaPenerima->name : $model->CA_RCVBY }}
                </td>
            </tr>
            <tr>
                <td>@lang('public-case.case.trxcomplaint')</td>
                <td> : </td>
                <td>{{$model->CA_SUMMARY}}</td>
            </tr>
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style="color: white; ">@lang('public-case.case.CA_CMPBY')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_NAME')</td>
                <td style="width: 1%;">:</td>
                @if(Auth::user()->user_cat =='1')
                    <td colspan="4">{{$model->CA_NAME}}</td>
                @else
                    <td>{{$model->CA_NAME}}</td>
                @endif
            </tr>
            <tr>
                <td >@lang('public-case.case.CA_DOCNO')</td>
                <td> : </td>
                <td>{{$model->CA_DOCNO}}</td>
            </tr>
            @if(Auth::user()->user_cat =='1')
            <tr>
                <td>@lang('public-case.case.CA_RACECD')</td>
                <td> : </td>
                <td>@if($model->CA_RACECD!=''){{Ref::GetDescr('580',$model->CA_RACECD)}}@endif</td>
            </tr>
            @endif
            <tr>
                <td>@lang('public-case.case.CA_NATCD')</td>
                <td> : </td>
                <td>@if($model->CA_NATCD!=''){{Ref::GetDescr('947',$model->CA_NATCD)}}@endif</td>
            </tr>
            @if(Auth::user()->user_cat =='1')
            <tr>
                <td>@lang('public-case.case.CA_SEXCD')</td>
                <td> : </td>
                <td>@if($model->CA_SEXCD!='')
                    {{Ref::GetDescr('202',$model->CA_SEXCD,App::getLocale())}}@endif</td>
            </tr>
            @endif
            <tr>
                <td>@lang('public-case.case.CA_ADDR')</td>
                <td> : </td>
                <td style="width: 30%;">{{$model->CA_ADDR}}</td>
                @if(Auth::user()->user_cat =='1')
                    <th style="width: 16%;" class="text-align-left">@lang('public-case.case.CA_ADDR') MyIdentity</th>
                    <th> : </th>
                    <th style="width: 34%;" class="text-align-left">
                        {{ $model->docnopengadu ? $model->docnopengadu->myidentity_address : $model->CA_MYIDENTITY_ADDR }}
                    </th>
                @endif
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_POSTCD')</td>
                <td> : </td>
                <td>{{$model->CA_POSCD}}</td>
                @if(Auth::user()->user_cat =='1')
                    <th class="text-align-left">@lang('public-case.case.CA_POSTCD')</th>
                    <th> : </th>
                    <th class="text-align-left">
                        {{ $model->docnopengadu ? $model->docnopengadu->myidentity_postcode : $model->CA_MYIDENTITY_POSCD }}
                    </th>
                @endif
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_DISTCD')</td>
                <td> : </td>
                <td>
                    @if($model->CA_DISTCD!='' || $model->CA_DISTCD!='0' || $model->CA_DISTCD!=NULL)
                        {{ Ref::GetDescr('18',$model->CA_DISTCD,App::getLocale()) }}
                    @endif
                </td>
                @if(Auth::user()->user_cat =='1')
                    <th class="text-align-left">@lang('public-case.case.CA_DISTCD')</th>
                    <th> : </th>
                    <th class="text-align-left">
                        {{ 
                            $model->docnopengadu ? 
                            ($model->docnopengadu->DaerahMyidentity ? $model->docnopengadu->DaerahMyidentity->descr : $model->docnopengadu->myidentity_distrinct_cd) 
                            : ($model->daerahmyidentity ? $model->daerahmyidentity->descr : $model->CA_MYIDENTITY_DISTCD) 
                        }}
                    </th>
                @endif
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_STATECD')</td>
                <td> : </td>
                <td>
                    @if($model->CA_STATECD!='' || $model->CA_STATECD!='0' || $model->CA_STATECD!=NULL)
                        {{ Ref::GetDescr('17',$model->CA_STATECD,App::getLocale()) }}
                    @endif
                </td>
                @if(Auth::user()->user_cat =='1')
                    <th class="text-align-left">@lang('public-case.case.CA_STATECD')</th>
                    <th> : </th>
                    <th class="text-align-left">
                        {{-- $model->docnopengadu ? ($model->docnopengadu->NegeriMyidentity ? $model->docnopengadu->NegeriMyidentity->descr : $model->docnopengadu->myidentity_state_cd) : '' --}}
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
                <td>{{$model->CA_TELNO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_FAXNO')</td>
                <td> : </td>
                <td>{{$model->CA_FAXNO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_MOBILENO')</td>
                <td> : </td>
                <td>{{$model->CA_MOBILENO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_EMAIL')</td>
                <td> : </td>
                <td>{{$model->CA_EMAIL}}</td>
            </tr>
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style="color: white; ">@lang('public-case.case.detailcomplaint')</th></tr>
            <!-- <tr> -->
                <!-- <td style="width: 18%;">@lang('public-case.case.CA_AGAINSTNM')</td> -->
                <!-- <td style="width: 1%;"> : </td> -->
                <!-- <td>{{$model->CA_AGAINSTNM}}</td> -->
            <!-- </tr> -->
            <tr>
                <td style="width: 18%;">Kategori</td>
                <td style="width: 1%;"> : </td>
                <td>@if($model->CA_CMPLCAT!=''){{Ref::GetDescr('244',$model->CA_CMPLCAT,App::getLocale())}}@endif</td>
            </tr>
            <tr>
                <td>Subkategori</td>
                <td> : </td>
                <td>{{ $model->CA_CMPLCD != ''? ($model->CA_CMPLCD == '0'? 'Tiada':Ref::GetDescr('634',$model->CA_CMPLCD,App::getLocale())):'' }}</td>
            </tr>
            @if($model->CA_CMPLCAT=='BPGK 19')
            <tr>
                <td>Pembekal Perkhidmatan</td>
                <td> : </td>
                <td>@if($model->CA_ONLINECMPL_PROVIDER!=''){{Ref::GetDescr('1091',$model->CA_ONLINECMPL_PROVIDER,App::getLocale())}}@endif</td>
            </tr>
            <tr>
                <td>Laman Atas Talian/URL</td>
                <td> : </td>
                <td>{{$model->CA_ONLINECMPL_URL}}</td>
            </tr>
            <tr>
                <td>Nama Bank</td>
                <td> : </td>
                <td>@if($model->CA_ONLINECMPL_BANKCD!=''){{Ref::GetDescr('1106',$model->CA_ONLINECMPL_BANKCD,App::getLocale())}}@endif</td>
            </tr>
            <tr>
                <td>No. Akaun Bank / No. Transaksi FPX</td>
                <td> : </td>
                <td>{{$model->CA_ONLINECMPL_ACCNO}}</td>
            </tr>
            <tr>
                <td>No. Aduan Rujukan</td>
                <td> : </td>
                <td>@if($model->CA_ONLINECMPL_IND!='' || $model->CA_ONLINECMPL_IND!=NULL){{$model->CA_ONLINECMPL_CASENO}}@endif</td>
            </tr>
            @elseif(($model->CA_CMPLCAT == 'BPGK 01') || ($model->CA_CMPLCAT == 'BPGK 03')) 
            <tr>
                <td>Jenis Barangan</td>
                <td> : </td>
                <td>@if($model->CA_CMPLKEYWORD!=''){{Ref::GetDescr('1051',$model->CA_CMPLKEYWORD,App::getLocale())}}@endif</td>
            </tr>
            @elseif($model->CA_CMPLCAT=='BPGK 08')
            <tr>
                <td>Penuntut/Penentang</td>
                <td> : </td>
                <td>{{$model->CA_TTPMTYP}}</td>
            </tr>
            <tr>
                <td>No TTPM</td>
                <td> : </td>
                <td>{{$model->CA_TTPMNO}}</td>
            </tr>
            @endif
            <tr>
                <td>Jumlah Kerugian (RM)</td>
                <td> : </td>
                <td>{{$model->CA_ONLINECMPL_AMOUNT}}</td>
            </tr>
            <tr>
                <td>Jenis Premis</td>
                <td> : </td>
                <td>@if($model->CA_AGAINST_PREMISE!=''){{ Ref::GetDescr('221',$model->CA_AGAINST_PREMISE,App::getLocale())}}@endif</td>
            </tr>
            <tr>
                <td>Nama Premis</td>
                <td> : </td>
                <td>{{ $model->CA_AGAINSTNM}}</td>
            </tr>
            <tr>
                <td >@lang('public-case.case.CA_AGAINSTADD')</td>
                <td> : </td>
                <td>{{$model->CA_AGAINSTADD}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_AGAINST_POSTCD')</td>
                <td> : </td>
                <td>{{$model->CA_AGAINST_POSTCD}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_AGAINST_DISTCD')</td>
                <td> : </td>
                <td>
                    @if($model->CA_AGAINST_DISTCD!='' || $model->CA_AGAINST_DISTCD!='0' || $model->CA_AGAINST_DISTCD!=NULL)
                        {{ Ref::GetDescr('18',$model->CA_AGAINST_DISTCD,App::getLocale()) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_AGAINST_STATECD')</td>
                <td> : </td>
                <td>
                    @if($model->CA_AGAINST_STATECD!='' || $model->CA_AGAINST_STATECD!='0' || $model->CA_AGAINST_STATECD!=NULL)
                        {{ Ref::GetDescr('17',$model->CA_AGAINST_STATECD,App::getLocale()) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_AGAINST_TELNO')</td>
                <td> : </td>
                <td>{{$model->CA_AGAINST_TELNO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_AGAINST_FAXNO')</td>
                <td> : </td>
                <td>{{$model->CA_AGAINST_FAXNO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_AGAINST_MOBILENO')</td>
                <td> : </td>
                <td>{{$model->CA_AGAINST_MOBILENO}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.case.CA_AGAINST_EMAIL')</td>
                <td> : </td>
                <td>{{$model->CA_AGAINST_EMAIL}}</td>
            </tr>
            <tr>
                <td>@lang('public-case.attachment.lampiranlabel')</td>
                <td> : </td>

                <td>
                    <?php $t = 1; ?>

                    @foreach ($img as $pic)
                    {!! '<a href='.Storage::disk('bahanpath')->url($pic->CC_PATH.$pic->CC_IMG).' target="_blank">'.$t++.') '.$pic->CC_IMG_NAME.'</a><br />' !!}
                    @endforeach
                </td>
            </tr>
        </table>
        <br />
        @if(Auth::user()->user_cat =='1')
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="6" style="color: white; ">Penugasan</th></tr>
            <tr>
                <td style="width: 18%;"> Penugasan Aduan Oleh</td>
                <td style="width: 1%;">:</td>
                <td>
                    <!--@if($model->CA_ASGBY!=''){{-- $model->namaPemberiTugas->name --}}@endif-->
                    {{ !empty($model->namaPemberiTugas) ? $model->namaPemberiTugas->name : $model->CA_ASGBY }}
                </td>
                <td>Tarikh Penugasan</td>
                <td> : </td>
                <td>{{$model->CA_ASGDT != ''? Carbon::parse($model->CA_ASGDT)->format('d-m-Y h:i A'):''}}</td>

            </tr>
            <tr>
                <td>Penyiasat</td>
                <td> : </td>
                <td>
                    <!--@if($model->CA_INVBY!=''){{-- $model->namapenyiasat->name --}}@endif-->
                    {{ !empty($model->namapenyiasat) ? $model->namapenyiasat->name : $model->CA_INVBY }}
                </td>
                <td>Tarikh Selesai </td>
                <td> : </td>
                <td>{{$model->CA_COMPLETEDT != ''? Carbon::parse($model->CA_COMPLETEDT)->format('d-m-Y h:i A'):''}}</td>
            </tr>
        </table>
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style=" color: white; ">Siasatan</th></tr>
            <tr>
                <td style="width: 18%;">Status Aduan</td>
                <td style="width: 1%;"> : </td>
                <td>@if($model->CA_INVSTS!=''){{Ref::GetDescr('292',$model->CA_INVSTS,App::getLocale())}}@endif</td>
            </tr>
            <tr>
                <td>Ditutup Oleh</td>
                <td> : </td>
                <td>{{ !empty($model->closeby) ? $model->closeby->name : $model->CA_CLOSEBY }}</td>
            </tr>
            <tr>
                <td>Tarikh Ditutup</td>
                <td> : </td>
                <td>{{ $model->CA_CLOSEDT ? Carbon::parse($model->CA_CLOSEDT)->format('d-m-Y h:i A') : '' }}</td>
            </tr>
            <tr>
                <td>Kawasan Kes</td>
                <td> : </td>
                <td>{{ Ref::GetDescr('712',$model->CA_AREACD,App::getLocale()) }}</td>
            </tr>
            <!-- <tr>
                <td>Wujud Kes</td>
                <td> : </td>
                <td>{{ $model->CA_SSP}}</td>
            </tr>
            <tr>
                <td>No. IP</td>
                <td> : </td>
                <td>{{ $model->CA_IPNO}}</td>
            </tr>
            <tr>
                <td>Akta</td>
                <td> : </td>
                <td>{{ Ref::GetDescr('713',$model->CA_AKTA,App::getLocale()) }}</td>
            </tr>
            <tr>
                <td>Kod Akta</td>
                <td> : </td>
                <td>{{ Ref::GetDescr('714',$model->CA_SUBAKTA,App::getLocale())}}</td>
            </tr> -->
        </table>
        <!--<br/>-->    
        @endif

        @if(Auth::user()->user_cat == '1')
            @if($model->CA_SSP =='YES')
            <table style="width: 100%;" class="table-bordered table-stripped">
                <tr style="background: #115272; color: white;">
                    <th colspan="6">Wujud Kes</th>
                </tr>
                <tr>
                    <th>Bil</th>
                    <th> No Kertas Siasatan / EP </th>
                    <th> Akta </th>
                    <th> Jenis Kesalahan </th>
                </tr>
                <?php $modelcount = 1; ?>
                @if(count($kes) > 0)
                    @foreach($kes as $keslist)
                        <tr>
                            <td> {{ $modelcount++ }} </td>
                            <td>
                                {{ !empty($keslist->CT_IPNO) ? $keslist->CT_IPNO : (!empty($keslist->CT_EPNO) ? $keslist->CT_EPNO : '') }}
                            </td>
                            <td>{{ Ref::GetDescr('713',$keslist->CT_AKTA,App::getLocale()) }}</td>
                            <td>{{ Ref::GetDescr('714',$keslist->CT_SUBAKTA,App::getLocale()) }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
            @endif
        @endif
        <!--<br/>-->
        <pagebreak></pagebreak>
        @if(Auth::user()->user_cat =='1')
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style=" color: white; ">Saranan</th></tr>
            <?php $sarananCount = 1; ?>
            @foreach($saranan as $sarananList)
            <tr>
                <td> {{ $sarananCount++ }}. </td>
                <td>{{ $sarananList->CD_DESC }}</td>
            </tr>
            @endforeach
        </table>
        <br>
        @if($model->CA_RESULT)
        <table style="width: 100%; " class="table-bordered table-stripped">

            <tr style="background: #115272;"><th colspan="6" style="color: white; ">@lang('public-case.case.CA_RESULT')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_RESULT')</td>
                <td style="width: 1%;"> : </td>

                <td>{{ $model->CA_RESULT }}</td>
            </tr>
        </table>
        <br>
        @endif
        @if ($model->CA_ANSWER != NULL)
        <table style="width: 100%; ">
            <tr style="background: #115272; color: white; "><th colspan="3" style="color: white;">@lang('public-case.case.CA_ANSWER')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_ANSWER')</td>
                <td style="width: 1%;"> : </td>
                <td>{{ $model->CA_ANSWER }}</td>
            </tr>
        </table>
        <br/>
        @endif
        @if(count($buktisiasatan) > 0)
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="6" style="color: white; ">@lang('public-case.case.CA_EVIDENCE')</th></tr>
            <?php $s = 1; ?>
            @foreach($buktisiasatan as $bukti)
            <tr>
                <td> {{ $s++ }} </td>
                <td>{{ $bukti->CC_IMG_NAME }}</td>
                <td>{!! '<a href='.Storage::disk('bahanpath')->url($bukti->CC_PATH.$bukti->CC_IMG).' target="_blank">'.$bukti->CC_IMG_NAME.'</a>' !!}</td>
            </tr>
            @endforeach
        </table>
        @endif
        @else
        <table style="width: 100%; " class="table-bordered table-stripped">

            <tr style="background: #115272; "><th colspan="6" style=" color: white; ">@lang('public-case.case.CA_ANSWER')</th></tr>
            <tr>
                <td style="width: 18%;">@lang('public-case.case.CA_ANSWER')</td>
                <td style="width: 1%;"> : </td>

                <td>{{ $model->CA_ANSWER }}</td>
            </tr>
        </table>
        @endif
        <br />
        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272;"><th colspan="8" style=" color: white; ">@lang('public-case.case.transactionsm')</th></tr>
<!--            <tr>
                
                <th>Status</th>
                @if(Auth::user()->user_cat =='1')
                <th>Daripada</th>
                <th>Kepada</th>
                @endif
                <th>Saranan</th>
                <th>Tarikh Transaksi</th>
               
            
            </tr>-->

            <tbody>
                <?php $i = 1; ?>
                @foreach ($trnsksi as $sorotan)
                    <tr>
                        <td style="width: 5%"> {{ $i++ }} </td>
                        <td>
                            @if($sorotan->CD_INVSTS!='') 
                                @if(($sorotan->CD_ACTTYPE=='CLS')&&($sorotan->CD_INVSTS==2)) 
                                    PENUGASAN SEMULA
                                @elseif($sorotan->CD_DESC=='Aduan Dibuka Semula') 
                                    BUKA SEMULA 
                                @else 
                                    {{ Ref::GetDescr('292',$sorotan->CD_INVSTS,App::getLocale()) }} 
                                @endif
                            @endif
                            @if(Auth::user()->user_cat =='1')
                                {{-- @if(($sorotan->CD_INVSTS==1)||($sorotan->CD_INVSTS==2 )||($sorotan->CD_INVSTS==3 )||($sorotan->CD_INVSTS==4 )||($sorotan->CD_INVSTS==5 )||($sorotan->CD_INVSTS==6 )||($sorotan->CD_INVSTS==7 )||($sorotan->CD_INVSTS==8)||($sorotan->CD_INVSTS==9 )||($sorotan->CD_INVSTS==0)) --}}
                                @if(in_array($sorotan->CD_INVSTS, ['0','1','2','3','4','5','6','7','8','9','11']))
                                    @if (empty($sorotan->UserCreate) || $sorotan->UserCreate->user_cat =='2')
                                        @lang('public-case.case.BY') <strong>Online Web </strong>
                                    @elseif  ($sorotan->UserCreate->user_cat =='1') 
                                        @lang('public-case.case.BY') <strong>{{!empty($sorotan->UserCreate) ? $sorotan->UserCreate->name:''}}</strong>
                                        @if(!empty($sorotan->brncdfrom) || !empty($sorotan->UserCreate->Cawangan))
                                        ( CAWANGAN <strong>
                                            {{
                                                !empty($sorotan->brncdfrom)
                                                ? $sorotan->brncdfrom->BR_BRNNM
                                                : (
                                                    !empty($sorotan->UserCreate->Cawangan)
                                                    ? $sorotan->UserCreate->Cawangan->BR_BRNNM
                                                    : ''
                                                )
                                            }}
                                        </strong> )
                                        @endif
                                    @endif
                                @endif
                                @if($sorotan->CD_ACTTO != '' && !in_array($sorotan->CD_INVSTS, array('4','5','8','0'), true))
                                    KEPADA PEGAWAI <strong>{{ !empty($sorotan->UserKepada) ? $sorotan->UserKepada->name:''}}</strong>
                                    ( CAWANGAN <strong>
                                        {{ !empty($sorotan->UserKepada->Cawangan) ? $sorotan->UserKepada->Cawangan->BR_BRNNM:''}}
                                    </strong> )
                                @elseif(in_array($sorotan->CD_INVSTS, ['0'], true))
                                    @if(!empty($sorotan->brncdto) || !empty($sorotan->UserKepada->Cawangan))
                                    KEPADA CAWANGAN <strong>
                                        {{
                                            !empty($sorotan->brncdto)
                                            ? $sorotan->brncdto->BR_BRNNM
                                            : (
                                                !empty($sorotan->UserKepada->Cawangan)
                                                ? $sorotan->UserKepada->Cawangan->BR_BRNNM
                                                : ''
                                            )
                                        }}
                                    </strong>
                                    @endif
                                @endif
                            @endif
                            @lang('public-case.case.AT')
                            @if($sorotan->CD_CREDT!='')
                                {{ Carbon::parse($sorotan->CD_CREDT)->format('d-m-Y h:i A') }}
                            @endif
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
                <th>@lang('public-case.case.CD_DOCATTCHID_PUB')</th>
                @if(Auth::user()->user_cat =='1')
                <th>@lang('public-case.case.CD_DOCATTCHID_ADM')</th>
                @endif
            </tr>
            <?php $i = 1; ?>
            @foreach ($trnsksi as $sorotan)
            @if($sorotan->CD_INVSTS!=10)
                @if($sorotan->CD_DOCATTCHID_PUBLIC != '' || $sorotan->CD_DOCATTCHID_ADMIN != '')
                <tr>
                    <td> {{ $i++ }} </td>
                    <td>
                        @if($sorotan->CD_DOCATTCHID_PUBLIC != '')
                        <!--Surat Kepada Pengadu :-->
                        {!! $sorotan->SuratPublic->file_name !!}
                        @endif
                    </td>
                    @if(Auth::user()->user_cat =='1')
                    <td> 
                        @if($sorotan->CD_DOCATTCHID_ADMIN != '')
                        <!--Surat Kepada Pegawai :--> 
                        {!! $sorotan->SuratAdmin->file_name !!}
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
