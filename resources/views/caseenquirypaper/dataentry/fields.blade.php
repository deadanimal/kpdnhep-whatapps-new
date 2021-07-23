<div class="row">
    <div class="table-responsive">
        {{-- <div class="col-sm-12 col-lg-12"> --}}
            {{-- <div class="row"> --}}
                {{-- <div class="col-lg-3"> --}}
                    {{-- <div class="m-xxs bg-primary p-xs b-r-sm"> --}}
                        {{-- <span class="number">1.</span> Maklumat Am --}}
                    {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="col-lg-3"> --}}
                    {{-- <div class="m-xxs bg-muted p-xs b-r-sm"> --}}
                        {{-- <span class="number">2.</span> Maklumat OKT --}}
                    {{-- </div> --}}
                {{-- </div> --}}
            {{-- </div> --}}
            {{-- <hr class="hr-line-solid"> --}}
        {{-- </div> --}}
        <div class="col-sm-12 col-lg-12">
            <div class="row">
                <!-- Asas Tindakan Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('asas_tindakan') ? ' has-error' : '' }}">
                    {{ Form::label('asas_tindakan', 'Asas Tindakan:', ['class' => 'control-label required']) }}
                    {{-- {{ Form::text('asas_tindakan', null, ['class' => 'form-control']) }} --}}
                    {{ Form::select('asas_tindakan', $collections['asasTindakan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
                    @if ($errors->has('asas_tindakan'))
                        <span class="help-block"><strong>{{ $errors->first('asas_tindakan') }}</strong></span>
                    @endif
                </div>

                <!-- Nombor Fail Kes EP Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('no_kes') ? ' has-error' : '' }}">
                    {{ Form::label('no_kes', 'Nombor Fail Kes EP:', ['class' => 'control-label required']) }}
                    {{ Form::text('no_kes', null, ['class' => 'form-control']) }}
                    @if ($errors->has('no_kes'))
                        <span class="help-block"><strong>{{ $errors->first('no_kes') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row">
                <!-- Nombor Aduan Kepenggunaan Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('complaint_case_number') ? ' has-error' : '' }}"
                    style="display : {{ $errors->has('complaint_case_number') || $data['asas_tindakan'] == 'ADUAN' ? 'block' : 'none' }};"
                    id="complaint_case_number_field">
                    {{ Form::label('complaint_case_number', 'Nombor Aduan Kepenggunaan:') }}
                    <div class="input-group">
                        {{ Form::text('complaint_case_number', null, ['class' => 'form-control', 'placeholder' => 'Nombor Aduan Kepenggunaan, Contoh : 02000001', 'readonly' => true]) }}
                        <span class="input-group-btn">
                            {{ Form::button('Carian '.' <i class="fa fa-search"></i>', ['id' => 'case-act-modal-button', 'class' => 'btn btn-success']) }}
                        </span>
                    </div>
                    @if ($errors->has('complaint_case_number'))
                        <span class="help-block"><strong>{{ $errors->first('complaint_case_number') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row">
                <!-- Negeri Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('kod_negeri') ? ' has-error' : '' }}">
                    {{ Form::label('kod_negeri', 'Negeri:', ['class' => 'control-label required']) }}
                    {{-- {{ Form::text('kod_negeri', null, ['class' => 'form-control']) }} --}}
                    {{ Form::select('kod_negeri', $collections['refStates'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
                    @if ($errors->has('kod_negeri'))
                        <span class="help-block"><strong>{{ $errors->first('kod_negeri') }}</strong></span>
                    @endif
                </div>

                <!-- Cawangan Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('kod_cawangan') ? ' has-error' : '' }}">
                    {{ Form::label('kod_cawangan', 'Cawangan:', ['class' => 'control-label required']) }}
                    {{-- {{ Form::text('kod_cawangan', null, ['class' => 'form-control']) }} --}}
                    {{ Form::select('kod_cawangan', $collections['branches'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
                    @if ($errors->has('kod_cawangan'))
                        <span class="help-block"><strong>{{ $errors->first('kod_cawangan') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row">
                <!-- Akta Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('akta') ? ' has-error' : '' }}">
                    {{ Form::label('akta', 'Akta:', ['class' => 'control-label required']) }}
                    {{-- {{ Form::text('akta', null, ['class' => 'form-control']) }} --}}
                    {{ Form::select('akta', $collections['refActs'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
                    @if ($errors->has('akta'))
                        <span class="help-block"><strong>{{ $errors->first('akta') }}</strong></span>
                    @endif
                </div>
                <!-- Serahan Agensi Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('serahan_agensi') ? ' has-error' : '' }}">
                    {{ Form::label('serahan_agensi', 'Serahan Agensi:') }}
                    {{-- {{ Form::text('serahan_agensi', null, ['class' => 'form-control']) }} --}}
                    {{ Form::select('serahan_agensi', $collections['serahanAgensi'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
                    @if ($errors->has('serahan_agensi'))
                        <span class="help-block"><strong>{{ $errors->first('serahan_agensi') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row">
                <!-- Tarikh Kejadian Field -->
                <div class="form-group col-sm-12 col-md-6">
                    {{ Form::label('tkh_kejadian', 'Tarikh Kejadian:') }}
                    <span>(Tarikh Daftar Fail Kes EP)</span>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {{-- {{ Form::text('tkh_kejadian', null, ['class' => 'form-control',
                            'placeholder' => 'HH-BB-TTTT',
                            'readonly' => true]) }} --}}
                        {{ Form::text('tkh_kejadian', $data['tkh_kejadian'],
                            ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                        }}
                        {{-- {{ Form::text('tkh_kejadian',
                            !empty($caseEnquiryPaper->tkh_kejadian) ? date('d-m-Y', strtotime($caseEnquiryPaper->tkh_kejadian)) : null, [
                            'class' => 'form-control',
                            'placeholder' => 'HH-BB-TTTT',
                            'readonly' => true
                            ]
                        ) }} --}}
                    </div>
                </div>

                <!-- Tarikh Serahan Field -->
                <div class="form-group col-sm-12 col-md-6">
                    {{ Form::label('tkh_serahan', 'Tarikh Serahan:') }}
                    <span>(Serahan Dari Agensi)</span>
                    {{-- {{ Form::text('tkh_serahan', null, ['class' => 'form-control']) }} --}}
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {{ Form::text('tkh_serahan', $data['tkh_serahan'],
                            ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                        }}
                        {{-- {{ Form::text('tkh_serahan',
                            !empty($tip->tkh_serahan) ? date('d-m-Y', strtotime($tip->tkh_serahan)) : null, [
                                'class' => 'form-control',
                                'placeholder' => 'HH-BB-TTTT',
                                'readonly' => true
                            ]
                        ) }} --}}
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Pengelasan Kes Field -->
                <div class="form-group col-sm-12 col-md-6">
                    {{ Form::label('pengelasan_kes', 'Pengelasan Kes:') }}
                    {{-- {{ Form::text('pengelasan_kes', null, ['class' => 'form-control']) }} --}}
                    {{ Form::select('pengelasan_kes', $collections['pengelasanKes'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
                </div>

                <!-- Kategori Kesalahan Field -->
                <div class="form-group col-sm-12 col-md-6">
                    {{ Form::label('kategori_kesalahan', 'Kategori Kesalahan:') }}
                    {{-- {{ Form::text('kategori_kesalahan', null, ['class' => 'form-control']) }} --}}
                    {{ Form::select('kategori_kesalahan', $collections['kategoriKesalahan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
                </div>
            </div>
            <div class="row">
                <!-- Kesalahan Field -->
                <div class="form-group col-sm-12 col-md-6{{ $errors->has('kesalahan') ? ' has-error' : '' }}">
                    {{ Form::label('kesalahan', 'Kesalahan:') }}
                    {{ Form::text('kesalahan', null, ['class' => 'form-control']) }}
                    {{-- Form::select('kesalahan', $collections['kesalahan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) --}}
                    @if ($errors->has('kesalahan'))
                        <span class="help-block"><strong>{{ $errors->first('kesalahan') }}</strong></span>
                    @endif
                </div>
            </div>
            {{-- <div class="row"> --}}
                {{-- <div class="form-group col-sm-12 col-lg-12 m-b-none text-center"> --}}
                    {{-- <a href="{{ url('caseenquirypaper/dataentries') }}" class="btn btn-default"> --}}
                        {{-- Kembali <i class="fa fa-home"></i> --}}
                    {{-- </a> --}}
                    {{-- Form::button('Simpan '.' <i class="fa fa-save"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) --}}
                    {{-- {{ Form::button('Simpan & Seterusnya'.' <i class="fa fa-chevron-right"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }} --}}
                {{-- </div> --}}
            {{-- </div> --}}
        </div>
    </div>
</div>
