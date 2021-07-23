@extends('layouts.main_public')
<?php
use App\Ref;
use App\PublicCase;
?>
@section('content')
<div class="row">
    <div class="panel panel-primary">
        <div class="panel-heading">@lang('public-case.case.newcomplaint')</div>
        <h2></h2>
        <!--<div class="ibox-content">-->
        <div class="row">
            <div class="col-sm-12">
                @include('public-case._tab')
            </div>
        </div>
        <br/>
        <form>
        <div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="CA_CMPLCAT" class="col-sm-4 control-label required">@lang('public-case.case.CA_CMPLCAT')</label>
                        <div class="col-sm-8">
                            {{ Form::select('CA_CMPLCAT', (Auth::user()->lang == 'ms' ? Ref::GetList('244', true) : Ref::GetList('244', true, 'en')), $mPcase->CA_CMPLCAT, array('class' => 'form-control input-sm', 'id' => 'CA_CMPLCAT', 'disabled' =>true)) }}
                        </div>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label for="CA_CMPLCD" class="col-sm-4 control-label required">@lang('public-case.case.CA_CMPLCD')</label>
                        <div class="col-sm-8">
                            {{ Form::select('CA_CMPLCD',array
                                  ( '' => '-- SILA PILIH --',
                                   '1' => 'SUB1', 
                                   '2' => 'SUB2',
                                   '3' => 'SUB3'), $mPcase->CA_CMPLCD,array('class' => 'form-control  input-sm','disabled' => true))}}
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label for="CA_AGAINST_PREMISE" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_PREMISE')</label>
                        <div class="col-sm-8">
                            {{ Form::select('CA_AGAINST_PREMISE', Auth::user()->lang == 'ms' ? Ref::GetList('221', true) : Ref::GetList('221', true, 'en'), $mPcase->CA_AGAINST_PREMISE, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_PREMISE','disabled' => true]) }}
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label for="CA_SUMMARY" class="col-sm-4 control-label required">@lang('public-case.case.CA_SUMMARY')</label>
                        <div class="col-sm-8">
                            {{ Form::textarea('CA_SUMMARY', $mPcase->CA_SUMMARY, ['class' => 'form-control input-sm', 'rows'=> '2','disabled' => true]) }}
                        </div>
                    </div><br><br><br><br>
                    <div class="form-group">
                        <label for="CA_AGAINST_TELNO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_TELNO')</label>
                        <div class="col-sm-8">
                            {{ Form::text('CA_AGAINST_TELNO', $mPcase->CA_AGAINST_TELNO, ['class' => 'form-control input-sm','disabled' => true]) }}
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label for="CA_AGAINST_MOBILENO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_MOBILENO')</label>
                        <div class="col-sm-8">
                            {{ Form::text('CA_AGAINST_MOBILENO', $mPcase->CA_AGAINST_MOBILENO, ['class' => 'form-control input-sm','disabled' => true]) }}
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label for="CA_AGAINST_EMAIL" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_EMAIL')</label>
                        <div class="col-sm-8">
                            {{ Form::email('CA_AGAINST_EMAIL', $mPcase->CA_AGAINST_EMAIL, ['class' => 'form-control input-sm','disabled' => true]) }}
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label for="CA_AGAINST_FAXNO" class="col-sm-4 control-label">@lang('public-case.case.CA_AGAINST_FAXNO')</label>
                        <div class="col-sm-8">
                            {{ Form::text('CA_AGAINST_FAXNO', $mPcase->CA_AGAINST_FAXNO, ['class' => 'form-control input-sm','disabled' => true]) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="CA_AGAINSTNM" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINSTNM')</label>
                        <div class="col-sm-8">
                            {{ Form::text('CA_AGAINSTNM', $mPcase->CA_AGAINSTNM, ['class' => 'form-control input-sm','disabled' => true]) }}
                        </div>
                    </div><br><br>    
                    <div class="form-group">
                        <label for="CA_AGAINSTADD" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINSTADD')</label>
                        <div class="col-sm-8">
                            {{ Form::textarea('CA_AGAINSTADD', $mPcase->CA_AGAINSTADD, ['class' => 'form-control input-sm', 'rows'=> '2','disabled' => true]) }}
                        </div>
                    </div><br><br><br><br>
                    <div class="form-group">
                        <label for="CA_AGAINST_POSTCD" class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_POSTCD')</label>
                        <div class="col-sm-8">
                            {{ Form::text('CA_AGAINST_POSTCD', $mPcase->CA_AGAINST_POSTCD, ['class' => 'form-control input-sm','disabled' => true]) }}
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_STATECD')</label>
                        <div class="col-sm-8">
                            {{ Form::select('CA_AGAINST_STATECD', Ref::GetList('17', true), $mPcase->CA_AGAINST_STATECD, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_STATECD','disabled' => true]) }}
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label class="col-sm-4 control-label required">@lang('public-case.case.CA_AGAINST_DISTCD')</label>
                        <div class="col-sm-8">
                            {{ Form::select('CA_AGAINST_DISTCD', ($mPcase->CA_AGAINST_STATECD == '' ? [ '-- SILA PILIH --'] : PublicCase::GetDstrtList($mPcase->CA_AGAINST_STATECD)), $mPcase->CA_AGAINST_DISTCD, ['class' => 'form-control input-sm', 'id' => 'CA_AGAINST_DISTCD','disabled' => true]) }}
                        </div>
                    </div><br><br>

                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="form-group col-sm-12" align="center">
                <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}">Kembali</a>
            </div>
        </div>
    </form>
    </div>
</div>

@stop

@section('javascript')
<script type="text/javascript">
 $(function() {
    $('#CA_AGAINST_STATECD').change(function (e) {
            var CA_AGAINST_STATECD = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('public-case/getdaerahlist') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success:function(data){
                    $('select[name="CA_AGAINST_DISTCD"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="CA_AGAINST_DISTCD"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            }); 
        });
});

</script>
@stop
