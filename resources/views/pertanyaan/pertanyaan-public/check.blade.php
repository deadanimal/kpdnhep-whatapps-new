@extends('layouts.main_public')
@section('content')
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#enq_info">@lang('pertanyaan.tab.enquiry')</a></li>
        <li class=""><a data-toggle="tab" href="#transaction_info">@lang('pertanyaan.tab.transaction_info')</a></li>
    </ul>
      <style>
            .form-control[readonly] {
                background-color: #ffffff;
            }
        </style>
    <div class="tab-content">
        <div id="enq_info" class="tab-pane active">

            <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                <div class="col-lg-12">
                    <div class="panel panel-success" style="border: 1px solid #115272;">
                        <div class="panel-heading" style="background: #115272;">
                            <i class="fa fa fa-tag"></i> @lang('button.info1')
                        </div>
                        <div class="panel-body" style="color: black; ">
                            {!! Form::open(['class'=>'form-horizontal', 'method' => 'patch']) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::label(trans('pertanyaan.form_label.summary'), '', ['class' => 'col-sm-2 control-label required']) }}
                                        <div class="col-sm-10">
                                            {{ Form::textarea('AS_SUMMARY', $tanya->AS_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 4, 'readonly' => true]) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('AS_ANSWER') ? ' has-error' : '' }}">
                                        {{ Form::label(trans('pertanyaan.form_label.answer'), '', ['class' => 'col-sm-2 control-label required']) }}
                                        <div class="col-sm-10">
                                            {{ Form::textarea('AS_ANSWER', $tanya->AS_ANSWER, ['class' => 'form-control input-sm', 'rows' => 4, 'readonly' => true]) }}
                                            @if ($errors->has('AS_ANSWER'))
                                            <span class="help-block"><strong>{{ $errors->first('AS_ANSWER') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <h4>@lang('pertanyaan.title.attachment')</h4>
                                    <hr style="background-color: #ccc; height: 1px; border: 0;">
                                    <table>
                                        <tr>
                                            @foreach($mPertanyaanDoc as $PertanyaanPublicCaseDoc)
                                            <?php $ExtFile = substr($PertanyaanPublicCaseDoc->img_name, -3); ?>
                                            @if($ExtFile == 'pdf' || $ExtFile == 'PDF')
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($PertanyaanPublicCaseDoc->path.$PertanyaanPublicCaseDoc->img) }}" target="_blank">
                                                        <img src="{{ url('img/PDF.png') }}" class="img-lg img-thumbnail"/>
                                                        <br />
                                                        {{ $PertanyaanPublicCaseDoc->img_name }}
                                                    </a>
                                                </div>
                                            </td>
                                            @else
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($PertanyaanPublicCaseDoc->path.$PertanyaanPublicCaseDoc->img) }}" target="_blank">
                                                        <img src="{{ Storage::disk('bahanpath')->url($PertanyaanPublicCaseDoc->path.$PertanyaanPublicCaseDoc->img) }}" class="img-lg img-thumbnail"/>
                                                        <br />
                                                        {{ $PertanyaanPublicCaseDoc->img_name }}
                                                    </a>
                                                </div>
                                            </td>
                                            @endif
            <!--                                <td style="max-width: 10%; min-width: 10%; ">
                                                @lang('pertanyaan.title.remarks') : <br />
                                                {{-- $PertanyaanPublicCaseDoc->remarks --}}
                                            </td>-->
                                            @endforeach
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group" align="center">
                                    <a class="btn btn-warning btn-sm" href="{{ route('dashboard',['#enquery']) }}">@lang('button.back')</a>
                                </div>  
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div id="transaction_info" class="tab-pane">
                <div class="row" style="padding-top: 20px; padding-bottom: 10px; background-color:#efefef; margin-left: 0px; ">
                    <div class="col-lg-12">
                        <div class="panel panel-success" style="border: 1px solid #115272;">
                            <div class="panel-heading" style="background: #115272;">
                                <i class="fa fa-calendar-plus-o "></i> @lang('button.transaksi')
                            </div>
                            <div class="panel-body" style="color: black; ">
                                <div class="table-responsive">
                                    <table style="width: 100%" id="transaction-table" class="table table-striped table-bordered table-hover" >
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>@lang('public-case.case.status')</th>
                                                <!--<th>@lang('public-case.case.curstatus')</th>-->
                                                <th>@lang('public-case.case.ask_officer')</th>
                                                <th>@lang('public-case.case.date')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @stop

        @section('javascript')
        <script type="text/javascript">

            $('#transaction-table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
                bLengthChange: false,
                bPaginate: false,
                bInfo: false,
                language: {
                    zeroRecords: 'Tiada rekod ditemui',
                    infoEmpty: 'Tiada rekod ditemui'
                },
                ajax: {
                    url: "{{ url('pertanyaan-public/getdatattable_transaction',$tanya->AS_ASKID)}}"
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                    {data: 'AD_ASKSTS', name: 'AD_ASKSTS'},
//                    {data: 'AD_CURSTS', name: 'AD_CURSTS'},
                    {data: 'AD_CREBY', name: 'AD_CREBY'},
                    {data: 'AD_CREDT', name: 'AD_CREDT'}
                ]
            });
        </script>
        @stop
