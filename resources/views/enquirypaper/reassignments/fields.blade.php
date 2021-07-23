<div class="row">
    <div class="table-responsive">
        {{-- <div class="col-lg-12"> --}}
            {{-- <b></b><br> --}}
            {{-- <i class="fa fa-info-circle text-warning"></i> --}}
            {{-- <small></small> --}}
        {{-- </div> --}}
        <div class="col-lg-12">
            <div class="row">
                <!-- No Kes Field -->
                <div class="form-group col-sm-6">
                    <label>Nombor Fail Kes EP:</label>
                    <p class="form-control-static">
                        <a id="{{ $enquiryPaperCase->id }}" onclick="showSummary('{{ $enquiryPaperCase->id }}')">
                            {{ $enquiryPaperCase->no_kes }}
                        </a>
                    </p>
                </div>
            </div>
            <div class="row">
                <!-- Penugasan Oleh Field -->
                <div class="form-group col-sm-6">
                    <label>Penugasan Oleh:</label>
                    <p class="form-control-static">{{ $user->name }}</p>
                </div>

                <!-- Pegawai Serbuan Ro Field -->
                <div class="form-group col-sm-6">
                    <label>Pegawai Serbuan RO:</label>
                    <p class="form-control-static">{{ $enquiryPaperCase->pegawai_serbuan_ro }}</p>
                </div>
            </div>
            <div class="row">
                <!-- Pegawai Penyiasat Io Field -->
                <div class="form-group col-sm-6{{ $errors->has('pegawai_penyiasat_io') ? ' has-error' : '' }}">
                    {{ Form::label('pegawai_penyiasat_io', 'Pegawai Penyiasat IO:', ['class' => 'control-label required']) }}
                    <div class="input-group">
                        {{ Form::text('pegawai_penyiasat_io', null, ['class' => 'form-control', 'readonly' => true]) }}
                        {{ Form::hidden('io_user_id', null, ['class' => 'form-control', 'readonly' => true]) }}
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

                <!-- Pegawai Penyiasat Aio Field -->
                <div class="form-group col-sm-6{{ $errors->has('pegawai_penyiasat_aio') ? ' has-error' : '' }}">
                    {{ Form::label('pegawai_penyiasat_aio', 'Pegawai Penyiasat AIO:', ['class' => 'control-label required']) }}
                    {{-- <div class="input-group"> --}}
                        {{ Form::text('pegawai_penyiasat_aio', null, ['class' => 'form-control']) }}
                        {{-- {{ Form::hidden('aio_user_id', null, ['class' => 'form-control', 'readonly' => true]) }} --}}
                        {{-- <span class="input-group-btn"> --}}
                            {{-- {{ Form::button('Carian '.' <i class="fa fa-search"></i>', [
                                'class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#modalAio'
                            ]) }} --}}
                        {{-- </span> --}}
                    {{-- </div> --}}
                    @if ($errors->has('pegawai_penyiasat_aio'))
                        <span class="help-block"><strong>{{ $errors->first('pegawai_penyiasat_aio') }}</strong></span>
                    @endif
                </div>
            </div>
            {{-- <div class="row"> --}}
                <!-- Catatan Field -->
                {{-- <div class="form-group col-lg-12{{ $errors->has('note') ? ' has-error' : '' }}"> --}}
                    {{-- {{ Form::label('note', 'Catatan:', ['class' => 'control-label required']) }} --}}
                    {{-- {{ Form::textarea('note', null, ['class' => 'form-control']) }} --}}
                    {{-- @if ($errors->has('note')) --}}
                        {{-- <span class="help-block"><strong>{{ $errors->first('note') }}</strong></span> --}}
                    {{-- @endif --}}
                {{-- </div> --}}
            {{-- </div> --}}
        </div>
    </div>
</div>
