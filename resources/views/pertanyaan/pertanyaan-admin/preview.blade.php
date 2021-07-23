@extends('layouts.main')
<?php
    use App\Ref;
?>
@section('content')
<style>
    textarea {
        resize: vertical;
    }
    .form-control[readonly] {
        background-color: #ffffff;
    }
</style>
<h2>Semak Pertanyaan / Cadangan</h2>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="">
            <a href='{{ $model->AS_ASKSTS == "1"? route("pertanyaan-admin.editadmin",$model->id):"" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                MAKLUMAT PERTANYAAN / CADANGAN
            </a>
        </li>
        <li class="">
            <a href='{{ $model->AS_ASKSTS == "1"? route("pertanyaan-admin.attachment",$model->id):"" }}'>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                LAMPIRAN
            </a>
        </li>
        <li class="active">
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                SEMAKAN
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="caseinfo" class="tab-pane active">
            <div class="panel-body">
                {!! Form::open(['route' => ['pertanyaan-admin.submit', $model->id], 'class'=>'form-horizontal', 'id' => 'submit-form']) !!}
                    {{ csrf_field() }}
                    <!--{{-- method_field('PUT') --}}-->
                    <h4><strong>Maklumat Pihak Yang Bertanya</strong></h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('AS_DOCNO') ? ' has-error' : '' }}">
                                {{ Form::label('AS_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('AS_DOCNO', old('AS_DOCNO', $model->AS_DOCNO), ['class' => 'form-control input-sm', 'id' => 'AS_DOCNO', 'maxlength' => 15, 'readonly' => 'true']) }}
                                    @if ($errors->has('AS_DOCNO'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_DOCNO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_AGE') ? ' has-error' : '' }}">
                                {{ Form::label('AS_AGE', 'Umur', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('AS_AGE', old('AS_AGE', $model->AS_AGE), ['class' => 'form-control input-sm', 'id' => 'AS_AGE', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 20, 'readonly' => 'true']) }}
                                    @if ($errors->has('AS_AGE'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_AGE') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_EMAIL') ? ' has-error' : '' }}">
                                {{ Form::label('AS_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::email('AS_EMAIL', old('AS_EMAIL', $model->AS_EMAIL), ['class' => 'form-control input-sm', 'maxlength' => 191, 'readonly' => 'true']) }}
                                    <style scoped>input:invalid, textarea:invalid { color: red; }</style>
                                    @if ($errors->has('AS_EMAIL'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_EMAIL') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_MOBILENO') ? ' has-error' : '' }}">
                                {{ Form::label('AS_MOBILENO', 'No. Telefon (Bimbit)', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('AS_MOBILENO', old('AS_MOBILENO', $model->AS_MOBILENO), ['class' => 'form-control input-sm', 'onkeypress' => "return isNumberKey(event)", 'maxlength' => 20, 'readonly' => 'true']) }}
                                    @if ($errors->has('AS_MOBILENO'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_MOBILENO') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_ADDR') ? ' has-error' : '' }}">
                                {{ Form::label('AS_ADDR', 'Alamat', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::textarea('AS_ADDR', old('AS_ADDR', $model->AS_ADDR), ['class' => 'form-control input-sm', 'rows' => '4', 'readonly' => 'true']) }}
                                    @if ($errors->has('AS_ADDR'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_ADDR') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('AS_NAME') ? ' has-error' : '' }}">
                                {{ Form::label('AS_NAME', 'Nama', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('AS_NAME', old('AS_NAME', $model->AS_NAME), ['class' => 'form-control input-sm', 'id' => 'AS_NAME', 'maxlength' => 60, 'readonly' => 'true']) }}
                                    @if ($errors->has('AS_NAME'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_NAME') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_SEXCD') ? ' has-error' : '' }}"> 
                                {{ Form::label('AS_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{-- Form::select('AS_SEXCD', Ref::GetList('202', true, 'ms'), old('AS_SEXCD', $model->AS_SEXCD), ['class' => 'form-control input-sm', 'id' => 'AS_SEXCD']) --}}
                                    {{ Form::text('AS_SEXCD', $model->AS_SEXCD != ''? Ref::GetDescr('202', $model->AS_SEXCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('AS_SEXCD'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_SEXCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('AS_NATCD') ? ' has-error' : '' }}">
                                {{ Form::label('AS_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('AS_NATCD', $model->AS_NATCD != ''? Ref::GetDescr('947', $model->AS_NATCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    @if ($errors->has('AS_NATCD'))
                                    <span class="help-block"><strong>{{ $errors->first('AS_NATCD') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div id="warganegara" style="display:block">
                                <div class="form-group {{ $errors->has('AS_POSCD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_POSCD', 'Poskod', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('AS_POSCD', old('AS_POSCD', $model->AS_POSCD), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        @if ($errors->has('AS_POSCD'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_POSCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('AS_STATECD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_STATECD', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        <!--{{-- Form::select('AS_STATECD', Ref::GetList('17', true, 'ms'), old('AS_STATECD', $model->AS_STATECD), ['class' => 'form-control input-sm required', 'id' => 'AS_STATECD', 'disabled' => true]) --}}-->
                                        {{ Form::text('AS_STATECD', $model->AS_STATECD != ''? Ref::GetDescr('17', $model->AS_STATECD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        @if ($errors->has('AS_STATECD'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_STATECD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('AS_DISTCD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_DISTCD', 'Daerah', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        @if (old('AS_DISTCD'))
                                            {{ Form::select('AS_DISTCD', Ref::GetListDist(old('AS_STATECD')), null, ['class' => 'form-control input-sm', 'id' => 'AS_DISTCD', 'disabled' => true]) }}
                                        @else
                                            <!--{{-- Form::select('AS_DISTCD', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'AS_DISTCD', 'disabled' => true]) --}}-->
                                            {{ Form::text('AS_DISTCD', ($model->AS_DISTCD != '' ? Ref::GetDescr('18', $model->AS_DISTCD, 'ms') : ''), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        @endif
                                        {{ Form::hidden('AS_STATUSPENGADU', '', ['class' => 'form-control input-sm', 'id' => 'AS_STATUSPENGADU']) }}
                                        @if ($errors->has('AS_DISTCD'))
                                            <span class="help-block"><strong>{{ $errors->first('AS_DISTCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--<div id="bknwarganegara" style="display: {{-- (old('AS_NATCD') != ''? (old('AS_NATCD') == '0'? 'block':'none'):'none') --}}">-->
                            <div id="bknwarganegara" style="display: {{ $model->AS_NATCD == '0' ? 'block' : 'none' }}">
                                <div class="form-group {{ $errors->has('AS_COUNTRYCD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_COUNTRYCD', 'Negara Asal', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        <!--{{-- Form::select('AS_COUNTRYCD', Ref::GetList('334', true, 'ms'), old('AS_COUNTRYCD', $model->AS_COUNTRYCD), ['class' => 'form-control input-sm', 'disabled' => true]) --}}-->
                                        {{ Form::text('AS_COUNTRYCD', $model->AS_COUNTRYCD != ''? Ref::GetDescr('334', $model->AS_COUNTRYCD, 'ms') : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                        @if ($errors->has('AS_COUNTRYCD'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_COUNTRYCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4><strong>Maklumat Pertanyaan / Cadangan</strong></h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('AS_SUMMARY') ? ' has-error' : '' }}">
                                {{ Form::label('AS_SUMMARY', 'Keterangan Pertanyaan/Cadangan', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::textarea('AS_SUMMARY', old('AS_SUMMARY', $model->AS_SUMMARY), ['class' => 'form-control input-sm', 'rows' => 4, 'readonly' => true]) }}
                                    @if ($errors->has('AS_SUMMARY'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_SUMMARY') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4><strong>Lampiran Pertanyaan / Cadangan</strong></h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-12">
                            <table>
                                <tr>
                                    @foreach($mPertanyaanAdminDoc as $pertanyaanAdminDoc)
                                        <?php $ExtFile = substr($pertanyaanAdminDoc->img_name, -3); ?>
                                        @if($ExtFile == 'pdf' || $ExtFile == 'PDF')
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($pertanyaanAdminDoc->path.$pertanyaanAdminDoc->img) }}" target="_blank">
                                                        <img src="{{ url('img/PDF.png') }}" class="img-lg img-thumbnail"/>
                                                        <br />
                                                        {{ $pertanyaanAdminDoc->img_name }}
                                                    </a>
                                                </div>
                                            </td>
                                        @else
                                            <td style="max-width: 10%; min-width: 10%; ">
                                                <div class="p-sm text-center">
                                                    <a href="{{ Storage::disk('bahanpath')->url($pertanyaanAdminDoc->path.$pertanyaanAdminDoc->img) }}" target="_blank">
                                                        <img src="{{ Storage::disk('bahanpath')->url($pertanyaanAdminDoc->path.$pertanyaanAdminDoc->img) }}" class="img-lg img-thumbnail"/>
                                                        <br />
                                                        {{ $pertanyaanAdminDoc->img_name }}
                                                    </a>
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" align="center">
                            <a class="btn btn-success btn-sm" href="{{ route('pertanyaan-admin.attachment',$model->id) }}"><i class="fa fa-chevron-left"></i> Sebelum</a>
                            <a class="btn btn-default btn-sm" href="{{ route('pertanyaan-admin.index') }}">Kembali</a>
                            {{ Form::button('Hantar'.' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-sm', 'id' => 'SubmitBtn']) }}
                        </div>  
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
//    $('#SubmitBtn').click(function(e) {
//        var Confirm = confirm('Anda pasti untuk hantar Pertanyaan/Cadangan ini?');
//        if(Confirm) {
//            return true;
//        }else{
//            return false;
//        }
//    });
    
    $(document).ready(function() {
        $('.form-control').attr("disabled", true);
    });
    
    $('#submit-form').submit(
        function () {
            var Confirm = confirm('Anda pasti untuk hantar Pertanyaan/Cadangan ini?');
            if(Confirm) {
                return true;
            }else{
                return false;
            }
        }
    );
</script>
@stop
