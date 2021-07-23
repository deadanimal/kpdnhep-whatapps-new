<div class="row">
    <div class="table-responsive">
        <div class="col-lg-12">
            <div class="row">
                <!-- No Kes Field -->
                <div class="form-group col-sm-12 col-md-6">
                    {!! Form::label('no_kes', 'Nombor Fail Kes:') !!}
                    {{-- {!! Form::text('no_kes', null, ['class' => 'form-control']) !!} --}}
                    <p class="form-control-static">
                        <a id="{{ $caseEnquiryPaper->id }}" onclick="showSummary('{{ $caseEnquiryPaper->id }}')">
                            {{ $caseEnquiryPaper->no_kes }}
                        </a>
                    </p>
                </div>

                <!-- Penugasan Oleh Field -->
                <div class="form-group col-sm-12 col-md-6">
                    {!! Form::label('assign_by', 'Penugasan Oleh:') !!}
                    {!! Form::hidden('assign_by', null, ['class' => 'form-control']) !!}
                    <p class="form-control-static">{{ $user->name }}</p>
                </div>

                <!-- Pegawai Penyiasat IO Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('pegawai_penyiasat_io') ? ' has-error' : '' }}">
                    {!! Form::label('pegawai_penyiasat_io', 'Pegawai Penyiasat IO:', ['class' => 'control-label required']) !!}
                    <div class="input-group">
                        {!! Form::hidden('io_user_id', null, ['class' => 'form-control', 'readonly' => true]) !!}
                        {!! Form::text('pegawai_penyiasat_io', null, ['class' => 'form-control', 'readonly' => true]) !!}
                        <span class="input-group-btn">
                            {{ Form::button('Carian '.' <i class="fa fa-search"></i>', [
                                'class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#modalIo'
                            ]) }}
                        </span>
                    </div>
                    @if ($errors->has('pegawai_penyiasat_io'))
                        <span class="help-block"><strong>{{ $errors->first('pegawai_penyiasat_io') }}</strong></span>
                    @endif
                </div>

                <!-- Pegawai Penyiasat AIO Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('pegawai_penyiasat_aio') ? ' has-error' : '' }}">
                    {!! Form::label('pegawai_penyiasat_aio', 'Pegawai Penyiasat AIO:', ['class' => 'control-label required']) !!}
                    {{-- <div class="input-group"> --}}
                        {{-- {!! Form::hidden('aio_user_id', null, ['class' => 'form-control', 'readonly' => true]) !!} --}}
                        {!! Form::text('pegawai_penyiasat_aio', null, ['class' => 'form-control']) !!}
                        {{-- <span class="input-group-btn">
                            {{ Form::button('Carian '.' <i class="fa fa-search"></i>', [
                                'class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#modalAio'
                            ]) }}
                        </span> --}}
                    {{-- </div> --}}
                    @if ($errors->has('pegawai_penyiasat_aio'))
                        <span class="help-block"><strong>{{ $errors->first('pegawai_penyiasat_aio') }}</strong></span>
                    @endif
                </div>

                <!-- Catatan Field -->
                <div class="form-group col-sm-12 col-md-12{{ $errors->has('note') ? ' has-error' : '' }}">
                    {!! Form::label('note', 'Catatan:', ['class' => 'control-label required']) !!}
                    {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('note'))
                        <span class="help-block"><strong>{{ $errors->first('note') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
