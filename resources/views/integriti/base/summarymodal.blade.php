<?php
    use App\Ref;
    use App\User;
    use App\Branch;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;
?>
<style>
    th, td {
        padding: 3px;
        vertical-align: top;
    }
    p.word-break {
        word-break: break-all;
    }
    table{
        width: 100%;
    }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">@lang('public-case.case.detailcomplaint')</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="table-responsive">
            <table>
                <tr>
                    <th style="width:30%">@lang('public-case.case.CA_CASEID')</th>
                    <th style="width:1%"> : </th>
                    <th style="width:19%">{{ $model->IN_CASEID }}</th>
                    <th style="width:15%">@lang('public-case.case.CA_BRNNM')</th>
                    <th style="width:1%"> : </th>
                    <th style="width:34%"> 
                        <!-- {{-- @if($model->IN_BRNCD!='') --}} -->
                            <!-- {{-- Branch::GetBranchName($model->IN_BRNCD) --}}  -->
                        <!-- {{-- @endif --}} -->
                        {{ $model->DeptCd ? $model->DeptCd->descr : $model->IN_DEPTCD }}
                    </th>
                </tr>
                <tr>
                    <!-- {{-- @if($model->IN_FILEREF) --}} -->
                    <th>@lang('public-case.case.IN_FILEREF')</th>
                    <th> : </th>
                    <th>{{ $model->IN_FILEREF }}</th>
                    <!-- {{-- @endif --}} -->
                    <th>@lang('public-case.case.CA_RCVDT')</th>
                    <th> : </th>
                    <th>
                        @if(!empty($model->IN_RCVDT))
                            {{ Carbon::parse($model->IN_RCVDT)->format('d-m-Y h:i A') }}
                        @endif
                    </th>
                </tr>
                <tr>
                    @if($mBukaSemula)
                        <th>@lang('public-case.case.CF_CASEID')</th>
                        <th> : </th>
                        <th>{{ $mBukaSemula->IF_CASEID }}</th>
                    @else
                        <th></th>
                        <th></th>
                        <th></th>
                    @endif
                    <th>@lang('public-case.case.CA_CREDT')</th>
                    <th> : </th>
                    <th>
                        @if(!empty($model->IN_CREATED_AT))
                            {{ Carbon::parse($model->IN_CREATED_AT)->format('d-m-Y h:i A') }}
                        @endif
                    </th>
                </tr>
                @if(Auth::user()->user_cat =='1')
                    <tr>
                        @if($mGabungAll)
                            <th>Gabung Aduan</th>
                            <th> : </th>
                            <th>
                                @foreach ($mGabungAll as $gabung)
                                    {{ $gabung->IR_CASEID }}<br />
                                @endforeach
                            </th>
                        @else
                            <th></th>
                            <th></th>
                            <th></th>
                        @endif
                        
                    </tr>
                @endif
            </table>
        </div>
        <br />
        <!-- <div class="table-responsive"> -->
            <!-- <table> -->
                <!-- <tr style="background: #115272; color: white; "> -->
                    <!-- <th colspan="3">@lang('public-case.case.trxcomplaint')</th> -->
                <!-- </tr> -->
                <!-- {{-- @if(Auth::user()->user_cat =='1') --}} -->
                <!-- <tr> -->
                    <!-- <td>@lang('public-case.case.CA_RCVBY')</td> -->
                    <!-- <td> : </td> -->
                    <!-- <td>{{ count($model->namaPenerima) == '1'? $model->namaPenerima->name:$model->IN_RCVBY }}</td> -->
                <!-- </tr> -->
                <!-- {{-- @endif --}} -->
            <!-- </table> -->
        <!-- </div> -->
        <!-- <br /> -->
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
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; ">
                    <th colspan="6">@lang('public-case.case.CA_CMPBY')</th>
                </tr>
                <!-- <tr> -->
                    <!-- <td colspan="3"> -->
                        <!-- <div class="checkbox checkbox-primary"> -->
                            <!-- <input id="IN_SECRETFLAG" type="checkbox" name="IN_SECRETFLAG" disabled {{ $model->IN_SECRETFLAG == "1"? 'checked':'' }}> -->
                            <!-- <label for="IN_SECRETFLAG"> -->
                                <!-- <b> -->
                                    <!-- Saya ingin merahsiakan maklumat sulit (maklumat, identiti, alamat, pekerjaan dan yang berkaitan dengan pemberi maklumat) -->
                                <!-- </b> -->
                            <!-- </label> -->
                        <!-- </div> -->
                    <!-- </td> -->
                    <!-- <td> : </td>
                    <td>{{$model->CA_DOCNO}}</td> -->
                <!-- </tr> -->
                <tr>
                    <td style="width: 18%;">@lang('public-case.case.CA_NAME')</td>
                    <td style="width: 1%;">:</td>
                    @if(Auth::user()->user_cat =='1')
                        <td colspan="4">{{ $model->IN_NAME }}</td>
                    @else
                        <td>{{ $model->IN_NAME }}</td>
                    @endif
                </tr>
                <tr>
                    <td>@lang('public-case.case.CA_DOCNO')</td>
                    <td> : </td>
                    <td>{{ $model->IN_DOCNO }}</td>
                </tr>
                @if(Auth::user()->user_cat =='1')
                <tr>
                    <td>@lang('public-case.case.CA_RACECD')</td>
                    <td> : </td>
                    <td>
                        <!-- {{-- @if($model->CA_RACECD!='') --}} -->
                        @if($model->bangsa)
                            <!-- {{-- Ref::GetDescr('580',$model->CA_RACECD) --}} -->
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
                        <!-- {{-- @if($model->CA_NATCD!='') --}} -->
                        @if($model->warganegara)
                            <!-- {{-- Ref::GetDescr('947',$model->CA_NATCD) --}} -->
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
                        <!-- {{-- @if($model->CA_SEXCD!='') --}} -->
                        @if($model->jantina)
                            <!-- {{-- Ref::GetDescr('202',$model->CA_SEXCD,App::getLocale()) --}} -->
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
                    <td>
                        <!--<p class="word-break">-->
                            <!--{{-- $model->CA_ADDR --}}-->
                            {!! nl2br(htmlspecialchars($model->IN_ADDR)) !!}
                            <?php // echo wordwrap($model->CA_ADDR, 200, "<br>\n", TRUE); ?>
                        <!--</p>-->
                    </td>
                    @if(Auth::user()->user_cat =='1')
                        <th style="width: 16%;">@lang('public-case.case.CA_ADDR') MyIdentity</th>
                        <th> : </th>
                        <th style="width: 34%;">
                            <!--{{-- $model->docnopengadu ? $model->docnopengadu->myidentity_address : $model->CA_MYIDENTITY_ADDR --}}-->
                            {!! 
                                $model->docnopengadu 
                                ? nl2br(htmlspecialchars($model->docnopengadu->myidentity_address)) 
                                : nl2br(htmlspecialchars($model->CA_MYIDENTITY_ADDR)) 
                            !!}
                        </th>
                    @endif
                </tr>
                <tr>
                    <td>@lang('public-case.case.CA_POSTCD')</td>
                    <td> : </td>
                    <td>{{ $model->IN_POSTCD }}</td>
                    @if(Auth::user()->user_cat =='1')
                        <th>@lang('public-case.case.CA_POSTCD')</th>
                        <th> : </th>
                        <th>{{ $model->docnopengadu ? $model->docnopengadu->myidentity_postcode : $model->CA_MYIDENTITY_POSCD }}</th>
                    @endif
                </tr>
                <tr>
                    <td>@lang('public-case.case.CA_DISTCD')</td>
                    <td> : </td>
                    <td>
                        {{ $model->daerahpengadu ? $model->daerahpengadu->descr : $model->IN_DISTCD }}
                    </td>
                    @if(Auth::user()->user_cat =='1')
                        <th>@lang('public-case.case.CA_DISTCD')</th>
                        <th> : </th>
                        <th>{{ 
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
                        {{ $model->negeripengadu ? $model->negeripengadu->descr : $model->IN_STATECD }}
                    </td>
                    @if(Auth::user()->user_cat =='1')
                        <th>@lang('public-case.case.CA_STATECD')</th>
                        <th> : </th>
                        <th>{{ 
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
                    <td>{{ $model->IN_TELNO }}</td>
                </tr>
                <tr>
                    <td>@lang('public-case.case.CA_FAXNO')</td>
                    <td> : </td>
                    <td>{{ $model->IN_FAXNO }}</td>
                </tr>
                <tr>
                    <td>
                    @lang('public-case.case.CA_MOBILENO')
                    <!-- @lang('public-case.case.CA_TELNO') -->
                    </td>
                    <td> : </td>
                    <td>{{ $model->IN_MOBILENO }}</td>
                </tr>
                <tr>
                    <td>@lang('public-case.case.CA_EMAIL')</td>
                    <td> : </td>
                    <td>{{ $model->IN_EMAIL }}</td>
                </tr>
            </table>
        </div>
        <br />
        @endif
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; "><th colspan="6">@lang('public-case.case.detailcomplaint')</th></tr>
                <tr>
                    <td style="width: 18%; ">
                        @lang('public-case.case.CA_RCVTYP')
                    </td>
                    <td style="width: 1%;"> : </td>
                    <td>
                        {{ 
                            $model->CaraPenerimaan 
                            ? $model->CaraPenerimaan->descr 
                            : $model->IN_RCVTYP }}
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
                    <td>@lang('public-case.case.IN_CHANNEL')</td>
                    <td> : </td>
                    <td>
                        {{ $model->channel ? $model->channel->descr : $model->IN_CHANNEL }}
                    </td>
                </tr>
                <tr>
                    <td>@lang('public-case.case.IN_SECTOR')</td>
                    <td> : </td>
                    <td>
                        {{ $model->sector ? $model->sector->descr : $model->IN_SECTOR }}
                    </td>
                </tr>
                <tr>
                    <td>@lang('public-case.case.IN_CLASSIFICATION')</td>
                    <td> : </td>
                    <td>
                        {{ $model->classification ? $model->classification->descr : $model->IN_CLASSIFICATION }}
                    </td>
                </tr>
                <tr>
                    <td>@lang('public-case.case.CA_CMPLCAT')</td>
                    <td> : </td>
                    <td>
                        {{ 
                            $model->kategori 
                            ? $model->kategori->descr 
                            : $model->IN_CMPLCAT 
                        }}
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="width: 18%;">
                        <!-- @lang('public-case.case.CA_AGAINSTNM') -->
                        @lang('public-case.case.IN_AGAINSTNM')
                    </td>
                    <td style="width: 1%;"> : </td>
                    <td>{{ $model->IN_AGAINSTNM }}</td>
                </tr>
                <tr>
                    <td style="width: 18%;">
                        @lang('public-case.case.IN_REFTYPE')
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
                    <td>@lang('public-case.case.IN_REFTYPE_BGK')</td>
                    <td> : </td>
                    <td>
                        {{ $model->IN_BGK_CASEID }}
                    </td>
                </tr>
                @elseif($model->IN_REFTYPE == 'TTPM')
                <tr>
                    <td>@lang('public-case.case.IN_TTPMNO')</td>
                    <td> : </td>
                    <td>
                        {{ $model->IN_TTPMNO }}
                    </td>
                </tr>
                <tr>
                    <td>@lang('public-case.case.IN_TTPMFORM')</td>
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
                    <td>@lang('public-case.case.IN_REFOTHER')</td>
                    <td> : </td>
                    <td>
                        {{ $model->IN_REFOTHER }}
                    </td>
                </tr>
                @endif
                <tr>
                    <td>@lang('public-case.case.IN_AGAINSTLOCATION')</td>
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
                    <td>@lang('public-case.case.CA_STATECD')</td>
                    <td> : </td>
                    <td>
                        {{ $model->againstbrstatecd ? $model->againstbrstatecd->descr : $model->IN_AGAINST_BRSTATECD }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 18%;">
                        @lang('public-case.case.IN_BRNCD')
                    </td>
                    <td style="width: 1%;"> : </td>
                    <td>
                        {{ $model->BrnCd ? $model->BrnCd->BR_BRNNM : $model->IN_BRNCD }}
                    </td>
                </tr>
                @elseif($model->IN_AGAINSTLOCATION == 'AGN')
                <tr>
                    <td>@lang('public-case.case.IN_AGENCYCD')</td>
                    <td> : </td>
                    <td>
                        {{ $model->agencycd ? $model->agencycd->MI_DESC : $model->IN_AGENCYCD }}
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="width: 18%;">
                        @lang('public-case.case.IN_SUMMARY_TITLE')
                    </td>
                    <td style="width: 1%;"> : </td>
                    <td>{{ $model->IN_SUMMARY_TITLE }}</td>
                </tr>
                <tr>
                    <td>@lang('public-case.case.IN_SUMMARY')</td>
                    <td> : </td>
                    <td>
                        <!--<p class="word-break">-->
                            {!! nl2br(htmlspecialchars($model->IN_SUMMARY)) !!}
                            <!--{{-- $model->CA_SUMMARY --}}-->
                            <?php // echo wordwrap($model->CA_SUMMARY, 200, "<br>\n", TRUE); ?>
                        <!--</p>-->
                    </td>
                </tr>
                <tr>
                    <td>@lang('public-case.attachment.lampiranlabel')</td>
                    <td> : </td>
                    <td>
                        <?php $t = 1; ?>
                        @foreach ($lampiranaduan as $pic)
                        <!-- {{-- '<a href='.Storage::disk('bahanpath')->url(rawurlencode($pic->IC_PATH.$pic->IC_DOCNAME)).' target="_blank">'.$t++.') '.$pic->IC_DOCFULLNAME.'</a><br />' --}} -->
                        {!! '<a href='.Storage::disk('integritibahanpath')->url($pic->IC_PATH.$pic->IC_DOCNAME).' target="_blank">'.$t++.') '.$pic->IC_DOCFULLNAME.'</a><br />' !!}
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td>@lang('public-case.case.invsts')</td>
                    <td> : </td>
                    <td>
                        {{ $model->StatusAduan ? $model->StatusAduan->descr : $model->IN_INVSTS }}
                    </td>
                </tr>
            </table>
        </div>
        <br />
        @if(Auth::user()->user_cat =='1')
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; "><th colspan="6">Penugasan</th></tr>
                <tr>
                    <td style="width: 18%;"> Penugasan Aduan Oleh</td>
                    <td style="width: 1%;">:</td>
                    <td style="width: 30%;">
                        {{ count($model->asgby) == '1'? $model->asgby->name:$model->IN_ASGBY }}
                    </td>
                    <td style="width: 18%;">Tarikh Penugasan</td>
                    <td style="width: 1%;"> : </td>
                    <td style="width: 30%;">
                        {{$model->IN_ASGDT != ''? Carbon::parse($model->IN_ASGDT)->format('d-m-Y h:i A'):''}}
                    </td>
                </tr>
                <tr>
                    <td>Penyiasat</td>
                    <td> : </td>
                    <td>
                        <!--@if($model->CA_INVBY!=''){{-- $model->namapenyiasat->name --}}@endif-->
                        {{ count($model->invby) == '1' ? $model->invby->name : $model->IN_INVBY }}
                    </td>
                    <td>Tarikh Selesai </td>
                    <td> : </td>
                    <td>
                        {{$model->IN_COMPLETEDT != ''? Carbon::parse($model->IN_COMPLETEDT)->format('d-m-Y h:i A'):''}}
                    </td>
                </tr>
            </table>
        </div>
        <br />
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; "><th colspan="6">Siasatan</th></tr>
                <!-- <tr> -->
                    <!-- <td style="width: 18%;">Status Aduan</td> -->
                    <!-- <td style="width: 1%;"> : </td> -->
                    <!-- <td> -->
                        <!-- {{-- @if($model->IN_INVSTS!='') --}} -->
                            <!-- {{-- Ref::GetDescr('292',$model->CA_INVSTS,App::getLocale()) --}} -->
                        <!-- {{-- @endif --}} -->
                        <!-- {{-- $model->StatusAduan ? $model->StatusAduan->descr : $model->IN_INVSTS --}} -->
                    <!-- </td> -->
                <!-- </tr> -->
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
        </div>
        <br/>    
        @endif
        @if(Auth::user()->user_cat == '1')
            @if($model->IN_SSP =='YES')
            <div class="table-responsive">
                <table>
                    <tr style="background: #115272; color: white; "><th colspan="6">Wujud Kes</th></tr>
                    <tr>
                        <th>Bil</th>
                        <th> No Kertas Siasatan / EP </th>
                        <th> Akta </th>
                        <th> Jenis Kesalahan </th>
                    </tr>
                    <tr>
                        <?php $modelcount = 1; ?>
                        <!-- {{-- @foreach($kes as $keslist) --}} -->
                        <!-- <td> {{-- $modelcount++ --}} </td> -->
                        <!-- <td>{{-- $keslist->CT_IPNO --}}</td> -->
                        <!-- <td>{{-- Ref::GetDescr('713',$keslist->CT_AKTA,App::getLocale()) --}}</td> -->
                        <!-- <td>{{-- Ref::GetDescr('714',$keslist->CT_SUBAKTA,App::getLocale()) --}}</td> -->
                        <!-- {{-- @endforeach --}} -->
                    </tr>
                </table>
            </div>
            <br/>
            @endif
        @endif
        @if(Auth::user()->user_cat =='1')
        @if (count($saranan) > 0)
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; "><th colspan="2">Saranan</th></tr>
                <?php $sarananCount = 1; ?>
                @foreach($saranan as $sarananList)
                <tr>
                    <td style="width: 3%"> {{ $sarananCount++ }}. </td>
                    <td>{{ $sarananList->ID_DESC }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <br>
        @endif
        @if($model->IN_RESULT)
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; ">
                    <th colspan="6">@lang('public-case.case.CA_RESULT')</th>
                </tr>
                <tr>
                    <td style="width: 18%;">@lang('public-case.case.CA_RESULT')</td>
                    <td style="width: 1%;"> : </td>
                    <td>
                        <!--<p class="word-break">-->
                            <!--{{-- $model->CA_RESULT --}}-->
                            {!! nl2br(htmlspecialchars($model->IN_RESULT)) !!}
                            <?php // echo wordwrap($model->CA_RESULT, 200, "<br>\n", TRUE); ?>
                        <!--</p>-->
                    </td>
                </tr>
            </table>
        </div>
        <br>
        @endif
        <!-- {{-- @if ($model->CA_ANSWER != NULL) --}} -->
        @if ($model->IN_ANSWER && (in_array($model->IN_IPSTS, ['04','05','09']) || in_array($model->IN_INVSTS, ['04','05','06','07','08'])))
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; ">
                    <th colspan="6">@lang('public-case.case.CA_ANSWER')</th>
                </tr>
                <tr>
                    <td style="width: 18%;">@lang('public-case.case.CA_ANSWER')</td>
                    <td style="width: 1%;"> : </td>
                    <td>
                        <!--<p class="word-break">-->
                            <!--{{-- $model->CA_ANSWER --}}-->
                            {!! nl2br(htmlspecialchars($model->IN_ANSWER)) !!}
                            <?php // echo wordwrap($model->CA_ANSWER, 200, "<br>\n", TRUE); ?>
                        <!--</p>-->
                    </td>
                </tr>
            </table>
        </div>
        <br/>
        @endif
        @if(count($lampiransiasatan) > 0)
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; ">
                    <th colspan="6">@lang('public-case.case.CA_EVIDENCE')</th>
                </tr>
                <?php $s = 1; ?>
                @foreach($lampiransiasatan as $bukti)
                <tr>
                    <td> {{ $s++ }}. </td>
                    <!-- <td>{{-- $bukti->CC_IMG_NAME --}}</td> -->
                    <td>
                        <!-- {{-- '<a href='.Storage::disk('bahanpath')->url(rawurlencode($bukti->IC_PATH.$bukti->IC_DOCNAME)).' target="_blank">'.$bukti->IC_DOCFULLNAME.'</a>' --}} -->
                        {!! '<a href='.Storage::disk('bahanpath')->url($bukti->IC_PATH.$bukti->IC_DOCNAME).' target="_blank">'.$bukti->IC_DOCFULLNAME.'</a>' !!}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif
        @else
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; ">
                    <th colspan="6">@lang('public-case.case.CA_ANSWER')</th>
                </tr>
                <tr>
                    <td style="width: 18%;">@lang('public-case.case.CA_ANSWER')</td>
                    <td style="width: 1%;"> : </td>
                    <td>
                        <!--<p class="word-break">-->
                            <!--{{-- $model->CA_ANSWER --}}-->
                            {!! nl2br(htmlspecialchars($model->IN_ANSWER)) !!}
                            <?php // echo wordwrap($model->CA_ANSWER, 200, "<br>\n", TRUE); ?>
                        <!--</p>-->
                    </td>
                </tr>
            </table>
        </div>
        <br />
        @endif
        @if(count($transaksi) > 0)
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; ">
                    <th colspan="8">@lang('public-case.case.transactionsm')</th>
                </tr>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($transaksi as $sorotan)
                    <tr>
                        <td style="width: 5%"> {{ $i++ }}. </td>
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
                                    @if (in_array($sorotan->ID_INVSTS,['010','01','02','04','05','06','07','08','011','012','013','014']) && $sorotan->ID_IPSTS == NULL)
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
                                @if (in_array($sorotan->ID_INVSTS,['01','02','04','05','06','07','08','011','012','013','014']) && $sorotan->ID_IPSTS == NULL)
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
        </div>
        <br />
        @endif
        <div class="table-responsive">
            <table>
                <tr style="background: #115272; color: white; "><th colspan="6">@lang('public-case.case.CD_DOCATTCHID_PUBLIC')</th></tr>
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
                @if($sorotan->ID_INVSTS!='010')
                    @if($sorotan->ID_DOCATACHID_PUBLIC != '' || $sorotan->ID_DOCATACHID_ADMIN != '')
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
                            @if($sorotan->ID_DOCATACHID_PUBLIC != '')
                            <!--Surat Kepada Pengadu :-->
                            {!! '<a href='.Storage::disk('letter')->url($sorotan->docattachidpublic->file_name_sys).' target="_blank">'.$sorotan->docattachidpublic->file_name.'</a>' !!}
                            @endif
                        </td>
                        @endif
                        @if(Auth::user()->user_cat =='1')
                            <td> 
                                @if($sorotan->ID_DOCATACHID_ADMIN != '')
                                    <!--Surat Kepada Pegawai :--> 
                                    {!! '<a href='.Storage::disk('letter')->url($sorotan->docattachidadmin->file_name_sys).' target="_blank">'.$sorotan->docattachidadmin->file_name.'</a>' !!}
                                    @if($sorotan->ID_INVSTS == '02')
                                        <!--<a href="{{-- route('tugas.generateword', $model->CA_CASEID) --}}" target="_blank" class="btn btn-success btn-sm">-->
                                            <!--<i class="fa fa-file-word-o"></i> Muat Turun Word-->
                                        <!--</a>-->
                                        {{-- Form::open([
                                            'route' => ['tugas.generateword', $model->CA_CASEID, $sorotan->CD_ACTTO], 
                                            'class' => 'form-horizontal', 
                                            'method' => 'GET', 
                                            'style'=>'display:inline'
                                        ]) --}}
                                        {{-- Form::button('<i class="fa fa-file-word-o"></i>'.' Muat Turun Word', [
                                            'type' => 'submit', 
                                            'class' => 'btn btn-success btn-sm', 
                                            'data-toggle'=>'tooltip', 
                                            'data-placement'=>'right', 
                                            'title'=>'Muat Turun Word'
                                        ]) --}}
                                        {{-- Form::close() --}}
                                    @endif
                                @endif
                            </td>
                        @endif
                    </tr>
                    @endif
                @endif
                @endforeach
            </table>
        </div>
        <br/>
        <a href="{{ route('integritibase.printsummary', $model->id) }}" target="_blank" 
            class="btn btn-success btn-sm">
            @lang('public-case.case.PRINT')
        </a>
    </div>
</div>
