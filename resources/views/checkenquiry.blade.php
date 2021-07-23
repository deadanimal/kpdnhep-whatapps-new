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
                @if($askinfo)
                    <div class="table-responsive">
                        <table style="width: 80%" align="center">
                            <thead>
                            <tr>
                                <th colspan="6" style="text-align: center; background: #115272; color: white;">
                                    @lang('pertanyaan.table_header.enquiry.info')
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 26%;">@lang('pertanyaan.table_header.enquiry.askid')</td>
                                <td style="width: 1%;"> :</td>
                                <td>{{ $askinfo->AS_ASKID }}</td>
                                <td>@lang('pertanyaan.form_label.brncd')</td>
                                <td> : </td>
                                <td>
                                    {{-- Branch::GetBranchName($askinfo->AS_BRNCD) --}}
                                    {{ $askinfo->branch ? $askinfo->branch->BR_BRNNM : '' }}
                                </td>
                            </tr>
                            <tr>
                                <!--<td>Tarikh Cipta</td>-->
                                <td>@lang('pertanyaan.form_label.credt')</td>
                                <td> :</td>
                                <td>
                                    {{--  Carbon::parse($askinfo->AS_CREDT)->format('d-m-Y h:i A') --}}
                                    {{ $askinfo->AS_CREDT ? date('d-m-Y h:i A', strtotime($askinfo->AS_CREDT)) : '' }}
                                </td>
                                <!--<td>Tarikh Penerimaan</td>-->
                                <td>@lang('pertanyaan.table_header.enquiry.date')</td>
                                <td> :</td>
                                <td>
                                    {{-- Carbon::parse($askinfo->AS_RCVDT)->format('d-m-Y h:i A') --}}
                                    {{ $askinfo->AS_RCVDT ? date('d-m-Y h:i A', strtotime($askinfo->AS_RCVDT)) : '' }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br/>
                    <div class="table-responsive">
                        <table style="width: 80%;" align="center">
                            <tr>
                                <th colspan="3" style="text-align: center; background: #115272; color: white;">
                                    @lang('pertanyaan.table_header.enquiry.summary')
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 26%;">@lang('pertanyaan.form_label.completeby')</td>
                                <td style="width: 1%;"> :</td>
                            <!--<td>{{-- $askinfo->AS_COMPLETEBY != ''? $askinfo->AS_COMPLETEBY:'' --}}</td>-->
                                <td>{{ $askinfo->CompleteBy ? $askinfo->CompleteBy->name : '' }}</td>
                            </tr>
                            <tr>
                                <td>@lang('pertanyaan.form_label.summary')</td>
                                <td> :</td>
                                <td>
                                    {{-- $askinfo->AS_SUMMARY != ''? $askinfo->AS_SUMMARY:'' --}}
                                    {!! nl2br(htmlspecialchars($askinfo->AS_SUMMARY)) !!}
                                </td>
                            </tr>
                            <tr>
                                <td>@lang('pertanyaan.table_header.enquiry.answer')</td>
                                <td> :</td>
                                <td>
                                    {{-- $askinfo->AS_ANSWER =! ''? $askinfo->AS_ANSWER:'' --}}
                                    {!! nl2br(htmlspecialchars($askinfo->AS_ANSWER)) !!}
                                </td>
                            </tr>
                            <tr>
                                <td>@lang('pertanyaan.title.attachment')</td>
                                <td> :</td>
                                <td>
                                    <?php $t = 1; ?>
                                    @foreach ($img as $pic)
                                        {!! '<a href='.Storage::disk("bahanpath")->url($pic->path.$pic->img).' target="_blank">'.$t++.') '.$pic->img_name.'</a><br />' !!}
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br/>
                    <div class="table-responsive">
                        <table style="width: 80%;" align="center">
                            <tr>
                                <th colspan="3" style="text-align: center; background: #115272; color: white;">
                                    @lang('pertanyaan.table_header.enquiry.askbydetail')
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 26%;">@lang('pertanyaan.form_label.name')</td>
                                <td style="width: 1%;">:</td>
                                <td>{{ $askinfo->AS_NAME }}</td>
                            </tr>
                            <tr>
                                <td>@lang('pertanyaan.form_label.docno')</td>
                                <td> :</td>
                                <td>{{ $askinfo->AS_DOCNO }}</td>
                            </tr>
                        <!--                    <tr>
                        <td>Bangsa</td>
                        <td> : </td>
                        <td>
                            @if($askinfo->CA_RACECD!='')
                            {{-- Ref::GetDescr('580',$askinfo->CA_RACECD) --}}
                        @endif
                                </td>
                            </tr>-->
                            <tr>
                                <td>@lang('pertanyaan.form_label.natcd')</td>
                                <td> :</td>
                                <td>
                                    {{ $askinfo->nationality ? $askinfo->nationality->descr : '' }}
                                    {{-- @if($askinfo->AS_NATCD !='') --}}
                                        {{-- Ref::GetDescr('947', $askinfo->AS_NATCD) --}}
                                    {{-- @endif --}}
                                </td>
                            </tr>
                            <!--<tr>-->
                            <!--<td>Jantina</td>-->
                            <!--<td> : </td>-->
                        <!--<td>{{-- $askinfo->AS_SEXCD != '' ? Ref::GetDescr('202', $askinfo->AS_SEXCD) : '' --}}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                            <!--<td>No.Tel</td>-->
                            <!--<td> : </td>-->
                        <!--<td>{{-- $askinfo->PublicUser->office_no != ''? $askinfo->PublicUser->office_no:'' --}}</td>-->
                            <!--</tr>-->
                            <tr>
                                <td>@lang('pertanyaan.form_label.mobileno')</td>
                                <td> :</td>
                                <td>{{ $askinfo->AS_MOBILENO != '' ? $askinfo->AS_MOBILENO : '' }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td> :</td>
                                <td>{{ $askinfo->AS_EMAIL != '' ? $askinfo->AS_EMAIL : '' }}</td>
                            </tr>
                        </table>
                    </div>
                    <br/>
                    <div class="table-responsive">
                        <table style="width: 80%;" align="center">
                            <tr>
                                <th colspan="3" style="text-align: center; background: #115272; color: white;">
                                    @lang('pertanyaan.table_header.transaction.transaction_info')
                                </th>
                            </tr>
                            <tr>
                                <th>No.</th>
                                <th>Status</th>
                                <th>@lang('pertanyaan.table_header.transaction.transaction_date')</th>
                            </tr>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach ($transaksi as $sorotan)
                                <tr>
                                    <td> {{ $i++ }} </td>
                                    <td>
                                        @if($sorotan->AD_ASKSTS!='')
                                            {{-- Ref::GetDescr('1061', $sorotan->AD_ASKSTS, app()->getLocale()) --}}
                                            {{ $sorotan->ShowStatus ? $sorotan->ShowStatus->descr : '' }}
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Carbon::parse($sorotan->AD_CREDT)->format('d-m-Y h:i A') --}}
                                        {{ $sorotan->AD_CREDT ? date('d-m-Y h:i A', strtotime($sorotan->AD_CREDT)) : '' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br/>
                    <!--<div align="center">-->
                <!--<a class="btn btn-default btn-sm" href="{{ url('kepenggunaan') }}">@lang('button.back')</a>-->
                    <!--</div>-->
                    <!--<br />-->
                @elseif(!$askinfo)
                    <div align="center">
                        <p>@lang('portal.validation.norecordenq')</p>
                    </div>
                @endif
                <div align="center" style="padding-top: 20px; padding-bottom: 50px">
                    <a class="btn btn-primary btn-sm" href="{{ url('kepenggunaan') }}">@lang('button.back')</a>
                </div>
            </div>
        </div>
    </div>
@stop
