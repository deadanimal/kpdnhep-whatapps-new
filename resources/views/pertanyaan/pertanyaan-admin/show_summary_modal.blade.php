<?php
    use App\Ref;
//    use App\User;
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
    <h4 class="modal-title">Maklumat Pertanyaan/Cadangan</h4>
</div>
<div class="modal-body">
    <div class="row">
        <!--<div class="col-sm-6">-->
        <!--<table style="width: 100%; " class="table-bordered table-stripped">-->
        <table>
            <tr>
                <th>No. Pertanyaan/Cadangan</th>
                <th> : </td>
                <th>{{$model->AS_ASKID}}</th>
                <th>Nama Cawangan</th>
                <th> : </th>
                <th>{{Branch::GetBranchName($model->AS_BRNCD)}}</th>

            </tr>
            <tr>
                <th>Tarikh Cipta</th>
                <th> : </th>
                <th>{{Carbon::parse($model->AS_CREDT)->format('d-m-Y h:i A')}}</th>
                <th>Tarikh Penerimaan</th>
                <th> : </th>
                <th>{{Carbon::parse($model->AS_RCVDT)->format('d-m-Y h:i A')}}</th>

            </tr>
        </table>
        <br />
        <!--<table style="width: 100%; " class="table-bordered table-stripped">-->
        <table>
            <tr style="background: #115272;"><th colspan="3" style="color: white; ">Keterangan Pertanyaan/Cadangan</th></tr>
<!--            <tr>
                <td style="width: 18%; ">Cara Penerimaan</td>
                <td style="width: 1%;"> : </td>
                <td>@if($model->CA_RCVTYP!=''){{--Ref::GetDescr('259',$model->CA_RCVTYP,'ms')--}}@endif</td>
            </tr>-->
            <tr>
                <td style="width: 18%;">Dijawab Oleh</td>
                <td style="width: 1%;"> : </td>
                <td>{{ $model->AS_COMPLETEBY != ''? $model->CompleteBy->name:''}}</td>
            </tr>
            <tr>
                <td>Keterangan Pertanyaan/Cadangan</td>
                <td> : </td>
                <td>{!! $model->AS_SUMMARY != '' ? nl2br(htmlspecialchars($model->AS_SUMMARY)) : '' !!}</td>
            </tr>
            <tr>
                <td>Jawapan</td>
                <td> : </td>
                <td>{!! $model->AS_ANSWER != '' ? nl2br(htmlspecialchars($model->AS_ANSWER)) : '' !!}</td>
            </tr>
            <tr>
                <td>Lampiran Jawapan</td>
                <td> : </td>
                <td>
                    <?php $t = 1; ?>
                    @foreach ($imganswer as $pic)
                    {!! '<a href='.Storage::disk("bahanpath")->url($pic->path.$pic->img).' target="_blank">'.$t++.') '.$pic->img_name.'</a><br />' !!}
                    @endforeach
                </td>
            </tr>
        </table>
        <br />
        <!--<table style="width: 100%; " class="table-bordered table-stripped">-->
        <table>
            <tr style="background: #115272;">
                <th colspan="6" style="color: white;">Butiran Pihak Yang Bertanya</th>
            </tr>
            <tr>
                <td style="width: 18%;">Nama </td>
                <td style="width: 2%;">:</td>
                <!--<td>{{-- $model->PublicUser->name --}}</td>-->
                <td style="width: 30%;">{{ $model->AS_NAME != '' ? $model->AS_NAME : '' }}</td>
            </tr>
            <tr>
                <td >No Kad Pengenalan / Pasport</td>
                <td> : </td>
                <!--<td>{{-- $model->PublicUser->icnew --}}</td>-->
                <td>{{ $model->AS_DOCNO != '' ? $model->AS_DOCNO : '' }}</td>
            </tr>
            <!--{{-- @if(Auth::user()->user_cat =='1') --}}-->
            <!--<tr>-->
                <!--<td>Bangsa</td>-->
                <!--<td> : </td>-->
                <!--<td>{{-- @if($model->CA_RACECD!='') --}} {{-- Ref::GetDescr('580',$model->CA_RACECD)-- }}{{-- @endif --}}</td>-->
            <!--</tr>-->
            <!--{{-- @endif --}}-->
            <tr>
                <td>Warganegara</td>
                <td> : </td>
                <td>@if($model->CA_NATCD!=''){{Ref::GetDescr('947',$model->CA_NATCD)}}@endif</td>
            </tr>
            @if(Auth::user()->user_cat =='1')
            <tr>
                <td>Jantina</td>
                <td> : </td>
                <!--<td>{{-- $model->PublicUser->gender != ''? Ref::GetDescr('202',$model->PublicUser->gender,'ms'):'' --}}</td>-->
                <td>{{ $model->AS_SEXCD != '' ? Ref::GetDescr('202', $model->AS_SEXCD, 'ms') : '' }}</td>
            </tr>
            @endif
            <tr>
                <td>Alamat</td>
                <td> : </td>
                <!--<td>{{-- $model->PublicUser->address != ''? $model->PublicUser->address:'' --}}</td>-->
                <td>{!! $model->AS_ADDR != '' ? nl2br(htmlspecialchars($model->AS_ADDR)) : '' !!}</td>
                @if(Auth::user()->user_cat =='1')
                    <td style="width: 18%;">
                        @lang('public-case.case.CA_ADDR') MyIdentity
                    </td>
                    <td style="width: 2%;"> : </td>
                    <td style="width: 30%;">
                        {!!
                            $model->docnouser
                            ? nl2br(htmlspecialchars($model->docnouser->myidentity_address))
                            : ''
                        !!}
                    </td>
                @endif
            </tr>
            <tr>
                <td>Poskod</td>
                <td> : </td>
                <!--<td>{{-- $model->PublicUser->postcode != ''? $model->PublicUser->postcode:'' --}}</td>-->
                <td>{{ $model->AS_POSCD != '' ? $model->AS_POSCD : '' }}</td>
                @if(Auth::user()->user_cat =='1')
                    <td>@lang('public-case.case.CA_POSTCD')</td>
                    <td> : </td>
                    <td>{{ $model->docnouser ? $model->docnouser->myidentity_postcode : '' }}</td>
                @endif
            </tr>
            <tr>
                <td>Daerah</td>
                <td> : </td>
                <!--<td>{{-- $model->PublicUser->distrinct_cd != ''? Ref::GetDescr('18',$model->PublicUser->distrinct_cd,'ms'):'' --}}</td>-->
                <td>{{ $model->AS_DISTCD != '' ? Ref::GetDescr('18', $model->AS_DISTCD, 'ms') : '' }}</td>
                @if(Auth::user()->user_cat =='1')
                    <td>@lang('public-case.case.CA_DISTCD')</td>
                    <td> : </td>
                    <td>
                        {{
                            $model->docnouser ?
                            ($model->docnouser->DaerahMyidentity ? $model->docnouser->DaerahMyidentity->descr : '')
                            : ''
                        }}
                    </td>
                @endif
            </tr>
            <tr>
                <td>Negeri</td>
                <td> : </td>
                <!--<td>{{-- $model->PublicUser->state_cd != ''? Ref::GetDescr('17',$model->PublicUser->state_cd,'ms'):'' --}}</td>-->
                <td>{{ $model->AS_STATECD != '' ? Ref::GetDescr('17', $model->AS_STATECD, 'ms') : '' }}</td>
                @if(Auth::user()->user_cat =='1')
                    <td>@lang('public-case.case.CA_STATECD')</td>
                    <td> : </td>
                    <td>
                        {{
                            $model->docnouser ?
                            ($model->docnouser->NegeriMyidentity ? $model->docnouser->NegeriMyidentity->descr : '')
                            : ''
                        }}
                    </td>
                @endif
            </tr>
<!--            <tr>
                <td>No.Tel</td>
                <td> : </td>
                <td>{{-- $model->PublicUser->office_no != ''? $model->PublicUser->office_no:'' --}}</td>
            </tr>-->
<!--            <tr>
                <td>No.Faks</td>
                <td> : </td>
                <td></td>
            </tr>-->
            <tr>
                <td>No.Telefon Bimbit</td>
                <td> : </td>
                <!--<td>{{-- $model->PublicUser->mobile_no != ''? $model->PublicUser->mobile_no:'' --}}</td>-->
                <td>{{ $model->AS_MOBILENO != '' ? $model->AS_MOBILENO : '' }}</td>
            </tr>
            <tr>
                <td>Emel</td>
                <td> : </td>
                <!--<td>{{-- $model->PublicUser->email != ''? $model->PublicUser->email:'' --}}</td>-->
                <td>{{ $model->AS_EMAIL != '' ? $model->AS_EMAIL : '' }}</td>
            </tr>
            <tr>
                <td>Lampiran Pertanyaan</td>
                <td> : </td>
                <td>
                    <?php $t = 1; ?>
                    @foreach ($img as $pic)
                    {!! '<a href='.Storage::disk("bahanpath")->url($pic->path.$pic->img).' target="_blank">'.$t++.') '.$pic->img_name.'</a><br />' !!}
                    @endforeach
                </td>
            </tr>
        </table>
        <br />
<!--        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="6">Maklumat Yang Diadu</th></tr>
            <tr>
                <td style="width: 18%;">Nama (Syarikat/Premis)</td>
                <td style="width: 1%;"> : </td>
                <td>{{--$model->CA_AGAINSTNM--}}</td>
            </tr>
            <tr>
                <td >Alamat</td>
                <td> : </td>
                <td>{{--$model->CA_AGAINSTADD--}}</td>
            </tr>
            <tr>
                <td>Poskod</td>
                <td> : </td>
                <td>{{--$model->CA_AGAINST_POSTCD--}}</td>
            </tr>
            <tr>
                <td>Daerah</td>
                <td> : </td>
                <td>@if($model->CA_AGAINST_DISTCD!=''){{--Ref::GetDescr('18',$model->CA_AGAINST_DISTCD,'ms')--}}@endif</td>
            </tr>
            <tr>
                <td>Negeri</td>
                <td> : </td>
                <td>@if($model->CA_AGAINST_STATECD!=''){{--Ref::GetDescr('17',$model->CA_AGAINST_STATECD,'ms')--}}@endif</td>
            </tr>
            <tr>
                <td>No.Tel</td>
                <td> : </td>
                <td>{{--$model->CA_AGAINST_TELNO--}}</td>
            </tr>
            <tr>
                <td>No.Faks</td>
                <td> : </td>
                <td>{{--$model->CA_AGAINST_FAXNO--}}</td>
            </tr>
            <tr>
                <td>No.Telefon Bimbit</td>
                <td> : </td>
                <td>{{--$model->CA_AGAINST_MOBILENO--}}</td>
            </tr>
            <tr>
                <td>Emel</td>
                <td> : </td>
                <td>{{--$model->CA_AGAINST_EMAIL--}}</td>
            </tr>
        </table>-->
        <!--<br />-->
<!--        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="6">Penugasan</th></tr>
            <tr>
                <td style="width: 18%;"> Penugasan Aduan Oleh</td>
                <td style="width: 1%;">:</td>
                <td>@if($model->CA_ASGBY!=''){{--$model->namaPemberiTugas->name--}}@endif</td>
            </tr>
            <tr>
                <td>Tarikh Penugasan</td>
                <td> : </td>
                <td>{{--Carbon::parse($model->CA_ASGDT)->format('d-m-Y h:i A')--}}</td>
            </tr>
            <tr>
                <td>Tarikh Selesai </td>
                <td> : </td>
                <td>{{--Carbon::parse($model->CA_COMPLETEDT)->format('d-m-Y h:i A')--}}</td>
            </tr>
            <tr>
                <td>Penyiasat</td>
                <td> : </td>
                <td>@if($model->CA_INVBY!=''){{--$model->namapenyiasat->name--}}@endif</td>
            </tr>

        </table>-->
<!--        <br />-->
<!--        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="6">Siasatan</th></tr>
            <tr>
                <td style="width: 18%;">Status Aduan</td>
                <td style="width: 1%;"> : </td>
                <td>@if($model->CA_INVSTS!=''){{--Ref::GetDescr('292',$model->CA_INVSTS,'ms')--}}@endif</td>
            </tr>
            <tr>
                <td >Kategori</td>
                <td> : </td>
                <td>@if($model->CA_CMPLCAT!=''){{--Ref::GetDescr('244',$model->CA_CMPLCAT,'ms')--}}@endif</td>
            </tr>
            <tr>
                <td>Subkategori</td>
                <td> : </td>
                <td>@if($model->CA_CMPLCD!=''){{--Ref::GetDescr('634',$model->CA_CMPLCD,'ms')--}}@endif</td>
            </tr>
            @if($model->CA_CMPLCAT=='BPGK 19')
            <tr>
                <td>Pembekal Perkhidmatan</td>
                <td> : </td>
                <td>@if($model->CA_ONLINECMPL_PROVIDER!=''){{--Ref::GetDescr('1091',$model->CA_ONLINECMPL_PROVIDER,'ms')--}}@endif</td>
            </tr>
            <tr>
                <td>Laman Atas Talian/URL</td>
                <td> : </td>
                <td>{{$model->CA_ONLINECMPL_URL}}</td>
            </tr>
            <tr>
                <td>Nama Bank</td>
                <td> : </td>
                <td>@if($model->CA_ONLINECMPL_BANKCD!=''){{--Ref::GetDescr('1106',$model->CA_ONLINECMPL_BANKCD,'ms')--}}@endif</td>
            </tr>
            <tr>
                <td>No Akaun</td>
                <td> : </td>
                <td>{{--$model->CA_ONLINECMPL_ACCNO--}}</td>
            </tr>
            <tr>
                <td>No Aduan Rujukan</td>
                <td> : </td>
                <td>@if($model->CA_ONLINECMPL_ACCNO!=''){{--$model->CA_ONLINECMPL_ACCNO--}}@endif</td>
            </tr>
            @elseif(($model->CA_CMPLCAT == 'BPGK 01') || ($model->CA_CMPLCAT == 'BPGK 03')) 
            <tr>
                <td>Jenis Barangan</td>
                <td> : </td>
                <td>@if($model->CA_CMPLKEYWORD!=''){{--Ref::GetDescr('1051',$model->CA_CMPLKEYWORD,'ms')--}}@endif</td>
            </tr>
            @elseif($model->CA_CMPLCAT=='BPGK 08')
            <tr>
                <td>Penuntut/Penentang</td>
                <td> : </td>
                <td>{{--$model->CA_TTPMTYP--}}</td>
            </tr>
            <tr>
                <td>No TTPM</td>
                <td> : </td>
                <td>{{--$model->CA_TTPMNO--}}</td>
            </tr>
            @endif
            <tr>
                <td>Jumlah Kerugian</td>
                <td> : </td>
                <td>{{--$model->CA_ONLINECMPL_AMOUNT--}}</td>
            </tr>
            <tr>
                <td>Jenis Premis</td>
                <td> : </td>
                <td>@if($model->CA_AGAINST_PREMISE!=''){{-- Ref::GetDescr('221',$model->CA_AGAINST_PREMISE,'ms')--}}@endif</td>
            </tr>
            <tr>
                <td>Nama Premis</td>
                <td> : </td>
                <td>{{-- $model->CA_AGAINSTNM--}}</td>
            </tr>
        </table>-->
        <!--<br/>-->
<!--        <table style="width: 100%; " class="table-bordered table-stripped">
            <tr style="background: #115272; color: white; "><th colspan="6">Hasil Siasatan</th></tr>
            <tr>
                <td style="width: 18%;">Hasil Siasatan</td>
                <td style="width: 1%;"> : </td>
                <td>{{-- $model->CA_RESULT --}}</td>
            </tr>
        </table>-->
        <!--<br />-->
        <!--<table style="width: 100%; " class="table-bordered table-stripped">-->
        <table>
            <tr style="background: #115272;"><th colspan="8" style="color: white;">Sorotan Transaksi</th></tr>
            <tr>
                <th>Bil.</th>
                <th>Status</th>
<!--                <th>Daripada</th>
                <th>Kepada</th>
                <th>Saranan</th>-->
                <th>Tarikh Transaksi</th>
<!--                <th>Surat Pengadu</th>
                <th>Surat Pegawai</th>-->
            </tr>

            <tbody>
                <?php $i = 1; ?>
                @foreach ($trnsksi as $sorotan)
                <tr>
                    <td> {{ $i++ }} </td>
                    <td>@if($sorotan->AD_ASKSTS!=''){{ Ref::GetDescr('1061',$sorotan->AD_ASKSTS,'ms')}}@endif</td>
<!--                    <td> 
                        @if($sorotan->CD_ACTFROM != '')
                        {{--$sorotan->UserDaripada->name--}}
                        @endif</td>
                    <td>   @if($sorotan->CD_ACTTO != '')
                        {{--$sorotan->UserKepada->name--}}
                        @endif</td>
                    <td> {{--$sorotan->CD_DESC--}}</td>-->
                    <td> {{Carbon::parse($sorotan->AD_CREDT)->format('d-m-Y h:i A')}}</td>
<!--                    <td>
                        @if($sorotan->CD_DOCATTCHID_PUBLIC != '')
                        {{-- '<a href='.Storage::disk('letter')->url($sorotan->SuratPublic->file_name_sys).' target="_blank">'.$sorotan->SuratPublic->file_name.'</a>' --}}
                        @endif
                    </td>
                    <td> 
                        @if($sorotan->CD_DOCATTCHID_ADMIN != '')
                        {{-- '<a href='.Storage::disk('letter')->url($sorotan->SuratAdmin->file_name_sys).' target="_blank">'.$sorotan->SuratAdmin->file_name.'</a>' --}}
                        @endif
                    </td>-->

                </tr>
                @endforeach
            </tbody>


        </table>
        @if(Auth::user()->user_cat =='1')
        <br/>
        <!--<table style="width: 100%; " class="table-bordered table-stripped emailtable" cell>-->
        <table class="emailtable" cell>
            <tr style="background: #115272;"><th colspan="8" style="color: white;">Pertanyaan Emel</th></tr>
            <tr>
                <th>Bil.</th>
                <th>Tajuk</th>
                <th>Maklumat Emel</th>
                <th>Mesej</th>
                <th>Dibuat Oleh</th>
                <th>Tarikh Dihantar</th>
            </tr>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($emails as $emel)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $emel->AE_TITLE }}</td>
                    <td>
                    @if ($emel->AE_TO != "")
                    <b>Kepada</b>
                        @if (strpos($emel->AE_TO, ';') !== false)
                            @foreach(explode(';', $emel->AE_TO) as $to) 
                            <br/>{{$to}}
                            @endforeach
                        @else
                            {{$emel->AE_TO}}
                        @endif
                    @endif

                    @if ($emel->AE_CC != "")
                    <br/><b>Cc: </b>
                        @if (strpos($emel->AE_CC, ';') !== false)
                            @foreach(explode(';', $emel->AE_CC) as $cc) 
                            <br/>{{$cc}}
                            @endforeach
                        @else
                            {{$emel->AE_CC}}
                        @endif
                    @endif

                    @if ($emel->AE_BCC != "")
                    <br/><b>Bcc: </b>
                        @if (strpos($emel->AE_BCC, ';') !== false)
                            @foreach(explode(';', $emel->AE_BCC) as $bcc) 
                            <br/>{{$bcc}}
                            @endforeach
                        @else
                            {{$emel->AE_BCC}}
                        @endif
                    @endif
                    </td>
                    <td>{{ $emel->AE_MESSAGE }}</td>
                    <td>{{ $emel->AE_CREBY != ''? $emel->CreBy->name:'' }}</td>
                    <td>{{ Carbon::parse($emel->AE_CREDT)->format('d-m-Y h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        <br />
        <a href="{{route('pertanyaan-admin.printsummary', $model->AS_ASKID)}}" target="_blank" class="btn btn-success btn-sm">
            <i class="fa fa-print"></i> Cetak
        </a>
        @if ($model->AS_ASKSTS == '2')
            <a href="{{ url("pertanyaan-admin/{$model->AS_ASKID}/edit") }}" class="btn btn-primary btn-sm">
               <i class="fa fa-pencil"></i> Tindakan
            </a>
        @endif
        <!--</div>-->
        <!--<div class="col-sm-6">-->

        <!--</div>-->
    </div>
</div>
