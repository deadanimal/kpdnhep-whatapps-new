<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <div class="m-xxs bg-primary p-xs b-r-sm">
                    <span class="number">1.</span> Maklumat Am
                </div>
            </div>
            <div class="col-lg-3">
                <div class="m-xxs bg-primary p-xs b-r-sm">
                    <span class="number">2.</span> Maklumat OKT
                </div>
            </div>
            @if(!in_array($caseEp->asas_tindakan, ['ADUAN']))
                <div class="col-lg-3">
                    <div class="m-xxs bg-muted p-xs b-r-sm">
                        <span class="number">3.</span> Maklumat Barang Rampasan
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="m-xxs bg-muted p-xs b-r-sm">
                        <span class="number">4.</span> Maklumat Pendakwaan
                    </div>
                </div>
            @endif
        </div>
        <hr class="hr-line-solid">
    </div>
    <div class="col-lg-12">
        <div class="row">
            <!-- Nama Okt Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('nama_okt', 'Nama Okt:') }}
                {{ Form::text('nama_okt', null, ['class' => 'form-control']) }}
            </div>
            <!-- Nombor Ic Pasport Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('no_ic_pasport', 'Nombor IC / Pasport:') }}
                {{ Form::text('no_ic_pasport', null, ['class' => 'form-control']) }}
            </div>
            <!-- Jantina Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('jantina', 'Jantina:') }}
                {{-- {{ Form::text('JANTINA', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('jantina', $collections['jantina'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>
            <!-- Status Okt Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('status_okt', 'Status Okt:') }}
                {{-- {{ Form::text('status_okt', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('status_okt', $collections['statusOkt'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>
        <div class="row">
        </div>
            <!-- Taraf Kerakyatan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('taraf_kerakyatan', 'Taraf Kerakyatan:') }}
                {{-- {{ Form::text('taraf_kerakyatan', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('taraf_kerakyatan', $collections['tarafKerakyatan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Catatan Kerakyatan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('catatan_kerakyatan', 'Catatan Kerakyatan:') }}
                {{-- {{ Form::text('catatan_kerakyatan', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('catatan_kerakyatan', $collections['catatanKerakyatan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>
            <!-- Nombor Report Polis Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('no_report_polis', 'Nombor Report Polis:') }}
                {{ Form::text('no_report_polis', null, ['class' => 'form-control']) }}
            </div>

            <!-- Nombor Ssm Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('no_ssm', 'Nombor SSM:') }}
                {{ Form::text('no_ssm', null, ['class' => 'form-control']) }}
            </div>

            <!-- Nama Premis Syarikat Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('nama_premis_syarikat', 'Nama Premis Syarikat:') }}
                {{ Form::text('nama_premis_syarikat', null, ['class' => 'form-control']) }}
            </div>

            <!-- Alamat Field -->
            <div class="form-group col-sm-12 col-lg-12">
                {{ Form::label('alamat', 'Alamat:') }}
                {{ Form::text('alamat', null, ['class' => 'form-control']) }}
            </div>
            
            <!-- Jenama Premis Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('jenama_premis', 'Jenama Premis:') }}
                <span>(Kes Yang Melibatkan Stesen Minyak Sahaja)</span>
                {{-- {{ Form::text('jenama_premis', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('jenama_premis', $collections['jenamaPremis'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Kawasan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('kawasan', 'Kawasan:') }}
                {{-- {{ Form::text('kawasan', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('kawasan', $collections['kawasan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>
            
            <!-- Jenis Perniagaan Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('jenis_perniagaan', 'Jenis Perniagaan:') }}
                {{-- {{ Form::text('jenis_perniagaan', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('jenis_perniagaan', $collections['jenisPerniagaan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Kategori Premis Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('kategori_premis', 'Kategori Premis:') }}
                {{-- {{ Form::text('kategori_premis', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('kategori_premis', $collections['kategoriPremis'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Jenis Premis Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('jenis_premis', 'Jenis Premis:') }}
                {{-- {{ Form::text('jenis_premis', null, ['class' => 'form-control']) }} --}}
                {{ Form::select('jenis_premis', $collections['jenisPremis'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Nilai Transaksi Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('nilai_transaksi', 'Nilai Transaksi:') }}
                {{ Form::text('nilai_transaksi', null, ['class' => 'form-control']) }}
            </div>

            <!-- c01 Field -->
            <div class="form-group col-sm-12 col-md-6">
                {{ Form::label('c01', 'Nama Laman Web / URL (C01):') }}
                {{ Form::text('c01', null, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>
</div>
