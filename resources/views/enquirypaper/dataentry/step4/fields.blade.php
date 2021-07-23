<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <!-- Nombor Fail Kes EP Field -->
            <div class="form-group col-md-6">
                <label>Nombor Fail Kes EP:</label>
                <p class="form-control-static">
                    <a id="{{ $enquiryPaperCase->id }}" onclick="showSummary('{{ $enquiryPaperCase->id }}')">
                        {{ $enquiryPaperCase->no_kes }}
                    </a>
                </p>
            </div>
        </div>
        <div class="row">
            <!-- Tarikh Kompaun Dikeluarkan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_kompaun_dikeluarkan', 'Tarikh Kompaun Dikeluarkan:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_kompaun_dikeluarkan', $data['tkh_kompaun_dikeluarkan'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Nilai Kompaun Ditawarkan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('nilai_kompaun_ditawarkan', 'Nilai Kompaun Ditawarkan:') }}
                {{ Form::text('nilai_kompaun_ditawarkan', null, ['class' => 'form-control']) }}
            </div>

            <!-- Tarikh Kompaun Diserahkan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_kompaun_diserahkan', 'Tarikh Kompaun Diserahkan:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_kompaun_diserahkan', $data['tkh_kompaun_diserahkan'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Tarikh Kompaun Dibayar Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_kompaun_dibayar', 'Tarikh Kompaun Dibayar:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_kompaun_dibayar', $data['tkh_kompaun_dibayar'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Nilai Kompaun Dibayar Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('nilai_kompaun_dibayar', 'Nilai Kompaun Dibayar:') }}
                {{ Form::text('nilai_kompaun_dibayar', null, ['class' => 'form-control']) }}
            </div>

            <!-- Nombor Resit Pembayaran Kompaun Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('no_resit_pembayaran_kompaun', 'Nombor Resit Pembayaran Kompaun:') }}
                {{ Form::text('no_resit_pembayaran_kompaun', null, ['class' => 'form-control']) }}
            </div>

            <!-- Pegawai Pendakwa Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('pegawai_pendakwa', 'Pegawai Pendakwa:') }}
                {{ Form::text('pegawai_pendakwa', null, ['class' => 'form-control']) }}
            </div>

            <!-- Mahkamah Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('mahkamah', 'Mahkamah:') }}
                {{ Form::text('mahkamah', null, ['class' => 'form-control']) }}
            </div>

            <!-- Nombor Pendaftaran Mahkamah Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('no_pendaftaran_mahkamah', 'Nombor Pendaftaran Mahkamah:') }}
                {{ Form::text('no_pendaftaran_mahkamah', null, ['class' => 'form-control']) }}
            </div>

            <!-- Tarikh Daftar Mahkamah Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_daftar_mahkamah', 'Tarikh Daftar Mahkamah:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_daftar_mahkamah', $data['tkh_daftar_mahkamah'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Tarikh Sebutan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_sebutan', 'Tarikh Sebutan:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_sebutan', $data['tkh_sebutan'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Tarikh Bicara Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_bicara', 'Tarikh Bicara:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_bicara', $data['tkh_bicara'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Tarikh Denda Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_denda', 'Tarikh Denda:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_denda', $data['tkh_denda'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Nilai Denda Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('nilai_denda', 'Nilai Denda:') }}
                {{ Form::text('nilai_denda', null, ['class' => 'form-control']) }}
            </div>

            <!-- Tarikh Penjara Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_penjara', 'Tarikh Penjara:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_penjara', $data['tkh_penjara'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Tempoh Penjara Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tempoh_penjara', 'Tempoh Penjara:') }}
                {{ Form::text('tempoh_penjara', null, ['class' => 'form-control']) }}
            </div>

            <!-- Tarikh DNAA Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_dnaa', 'Tarikh DNAA:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_dnaa', $data['tkh_dnaa'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Tarikh NFA Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_nfa', 'Tarikh NFA:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_nfa', $data['tkh_nfa'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Tarikh AD Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_ad', 'Tarikh AD:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_ad', $data['tkh_ad'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Tarikh Kes Tutup Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tkh_kes_tutup', 'Tarikh Kes Tutup:') }}
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{ Form::text('tkh_kes_tutup',  $data['tkh_kes_tutup'],
                        ['class' => 'form-control', 'placeholder' => 'HH-BB-TTTT', 'readonly' => true])
                    }}
                </div>
            </div>

            <!-- Status Group Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('status_grp', 'Status Group:') }}
                {{-- {{ Form::text('status_grp', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('status_grp', $collections['statusGroups'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Status Kes Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('status_kes', 'Status Kes:') }}
                {{-- {{ Form::text('status_kes', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('status_kes', $collections['statusCases'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Status Kes Det Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('status_kes_det', 'Status Kes Det:') }}
                {{-- {{ Form::text('status_kes_det', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('status_kes_det', $collections['statusKesDets'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Pergerakan Ep Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('pergerakan_ep', 'Pergerakan EP:') }}
                {{ Form::text('pergerakan_ep', null, ['class' => 'form-control']) }}
            </div>

            <!-- Status Eksibit Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('status_eksibit', 'Status Eksibit:') }}
                {{ Form::text('status_eksibit', null, ['class' => 'form-control']) }}
            </div>

            <!-- Minggu Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('week', 'Minggu:') }}
                {{ Form::text('week', null, ['class' => 'form-control']) }}
            </div>

            <!-- TPR Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('tpr', 'TPR:') }}
                {{ Form::text('tpr', null, ['class' => 'form-control']) }}
            </div>

            <!-- BS Dalam Siasatan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('bs_dalam_siasatan', 'BS Dalam Siasatan:') }}
                {{ Form::text('bs_dalam_siasatan', null, ['class' => 'form-control']) }}
            </div>

            <!-- Catatan Field -->
            <div class="form-group col-sm-12 col-md-12{{ $errors->has('detail_note') ? ' has-error' : '' }}">
                {{ Form::label('detail_note', 'Catatan:', ['class' => 'control-label required']) }}
                {!! Form::textarea('detail_note', null, ['class' => 'form-control']) !!}
                @if ($errors->has('detail_note'))
                    <span class="help-block"><strong>{{ $errors->first('detail_note') }}</strong></span>
                @endif
            </div>
            <!-- Hasil Siasatan Field -->
            <div class="form-group col-sm-12 col-md-12{{ $errors->has('detail_result') ? ' has-error' : '' }}">
                {{ Form::label('detail_result', 'Hasil Siasatan:', ['class' => 'control-label required']) }}
                {!! Form::textarea('detail_result', null, ['class' => 'form-control']) !!}
                @if ($errors->has('detail_result'))
                    <span class="help-block"><strong>{{ $errors->first('detail_result') }}</strong></span>
                @endif
            </div>
            <!-- Jawapan Kepada Pengadu Field -->
            <div class="form-group col-sm-12 col-md-12{{ $errors->has('detail_answer') ? ' has-error' : '' }}">
                {{ Form::label('detail_answer', 'Jawapan Kepada Pengadu:', ['class' => 'control-label required']) }}
                {!! Form::textarea('detail_answer', null, ['class' => 'form-control']) !!}
                @if ($errors->has('detail_answer'))
                    <span class="help-block"><strong>{{ $errors->first('detail_answer') }}</strong></span>
                @endif
            </div>
        </div>
    </div>
</div>
