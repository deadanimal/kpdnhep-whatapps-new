@extends('layouts.main')
<?php

use App\Ref;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Tambah Pengguna</h2>
            <!--<div class="ibox-content">-->
            {!! Form::open(['url' => 'publicuser/store', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
            {{ csrf_field() }}
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            {{ Form::label('username', 'No.Kad Pengenalan / Passport', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::text('username', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('username'))
                                <span class="help-block"><strong>{{ $errors->first('username') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {{ Form::label('name', 'Nama', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('name'))
                                <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {{ Form::label('password', 'Kata Laluan', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::password('password', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('citizen') ? ' has-error' : '' }}">
                            {{ Form::label('citizen', 'Kerakyatan', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                <?php $Ctzens = Ref::where(['cat' => '199', 'status' => '1'])->get(); ?>
                                @foreach ($Ctzens as $Ctzen)
                                <div>
                                    <label>{{ Form::radio('citizen', $Ctzen->code, false, array('id'=> 'citizen'.$Ctzen->code)) }} <i></i> {{ $Ctzen->descr }} </label>
                                </div>
                                @endforeach
                                @if ($errors->has('citizen'))
                                <span class="help-block"><strong>{{ $errors->first('citizen') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div id="bknwarganegara" style="display:none">
                        <div class="form-group">
                            <?php echo Form::label('Negara', NULL, ['class' => 'col-sm-5 control-label required']); ?>
                            <div class="col-sm-7">
                                {{ Form::select('ctry_cd',array
                                  ( '' => 'Pilih Negara',
                                   '1' => 'Indonesia', 
                                   '2' => 'Singapura',
                                   '3' => 'Thailand'), '',array('class' => 'form-control'))}}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('age') ? ' has-error' : '' }}">
                            {{ Form::label('age', 'Umur', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::text('age', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('age'))
                                <span class="help-block"><strong>{{ $errors->first('age') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            {{ Form::label('address', 'Alamat', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::textarea('address', '', ['class' => 'form-control input-sm', 'rows'=> '2']) }}
                                @if ($errors->has('address'))
                                <span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('postcode') ? ' has-error' : '' }}">
                            {{ Form::label('postcode', 'Poskod', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::text('postcode', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('postcode'))
                                <span class="help-block"><strong>{{ $errors->first('postcode') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('state_cd') ? ' has-error' : '' }}">
                            {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::select('state_cd', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                                @if ($errors->has('state_cd'))
                                <span class="help-block"><strong>{{ $errors->first('state_cd') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('distrinct_cd') ? ' has-error' : '' }}">
                            {{ Form::label('distrinct_cd', 'Daerah', ['class' => 'col-sm-5 control-label required']) }}
                            <div class="col-sm-7">
                                {{ Form::select('distrinct_cd', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'distrinct_cd']) }}
                                @if ($errors->has('distrinct_cd'))
                                <span class="help-block"><strong>{{ $errors->first('distrinct_cd') }}</strong></span>
                                @endif
                            </div>
                        </div>
                            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                {{ Form::label('gender', 'Jantina', ['class' => 'col-sm-5 control-label required']) }}
                                <div class="col-sm-7">
                                    <?php $Gnders = Ref::where(['cat' => '202', 'status' => '1'])->get(); ?>
                                    @foreach ($Gnders as $Gnder)
                                    <div>
                                        <label>{{ Form::radio('gender', $Gnder->code) }} <i></i> {{ $Gnder->descr }} </label>
                                    </div>
                                    @endforeach
                                    @if ($errors->has('gender'))
                                    <span class="help-block"><strong>{{ $errors->first('gender') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <!--<label for="email" class="col-md-4 control-label">E-Mail Address</label>-->
                            {{ Form::label('email', 'Emel', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                <input id="email" type="email" class="form-control input-sm" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                            {{ Form::label('mobile_no', 'No.Tel Bimbit', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                {{ Form::text('mobile_no', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('mobile_no'))
                                <span class="help-block"><strong>{{ $errors->first('mobile_no') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('office_no') ? ' has-error' : '' }}">
                            {{ Form::label('office_no', 'No.Tel Pejabat', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::text('office_no', '', ['class' => 'form-control input-sm']) }}
                                @if ($errors->has('office_no'))
                                <span class="help-block"><strong>{{ $errors->first('office_no') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('lang') ? ' has-error' : '' }}">
                            {{ Form::label('lang', 'Bahasa', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                                {{ Form::select('lang', Ref::GetList('295 ', true), null, ['class' => 'form-control input-sm', 'id' => 'lang']) }}
                                @if ($errors->has('lang'))
                                <span class="help-block"><strong>{{ $errors->first('lang') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            {{ Form::label('status', 'Status', ['class' => 'col-sm-4 control-label required']) }}
                            <div class="col-sm-8">
                                <div><label> <input type="radio" value="1" name="status" checked=""> <i></i> AKTIF </label></div>
                                <div><label> <input type="radio" value="2" name="status"> <i></i> TIDAK AKTIF </label></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12" align="center">
                        {{ Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']) }}
                        <a class="btn btn-default btn-sm" href="{{ route('publicuser') }}">Kembali</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!--</div>-->
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
 $(function () {
        $('#citizen2').on('click', function (e) {
        $('#bknwarganegara').show();
    });
    $('#citizen1').on('click', function (e) {
        $('#bknwarganegara').hide();
    });
    
    $('#state_cd').on('change', function (e) {
            var state_cd = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('publicuser/getdstrtlist') }}" + "/" + state_cd,
                dataType: "json",
                success:function(data){
                    $('select[name="distrinct_cd"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="distrinct_cd"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            }); 
        });
});
</script>
@stop