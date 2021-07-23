<div class="row">
    <div class="col-lg-12">
        <div class="row">

            <!-- Kesbrg Id Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('kesbrg_id', 'Kesbrg Id:') !!}
                {!! Form::text('kesbrg_id', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Kategori Barang Rampasan Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('kategori_brg_rampasan', 'Kategori Barang Rampasan:') !!}
                {{-- {!! Form::text('kategori_brg_rampasan', null, ['class' => 'form-control']) !!} --}}
                {{ Form::select('kategori_brg_rampasan', $collections['kategoriBarangRampasan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Jenis Barang Rampasan Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('jenis_brg_rampasan', 'Jenis Barang Rampasan:') !!}
                {{-- {!! Form::text('jenis_brg_rampasan', null, ['class' => 'form-control']) !!} --}}
                {{ Form::select('jenis_brg_rampasan', $collections['jenisBarangRampasan'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Jenis Barang Rampasan2 Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('jenis_brg_rampasan2', 'Jenis Barang Rampasan2:') !!}
                {{-- {!! Form::text('jenis_brg_rampasan2', null, ['class' => 'form-control']) !!} --}}
                {{ Form::select('jenis_brg_rampasan2', $collections['jenisBarangRampasan2'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Karya Tempatan Antarabangsa Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('karya_tempatan_antarabangsa', 'Karya Tempatan Antarabangsa:') !!}
                {{-- {!! Form::text('karya_tempatan_antarabangsa', null, ['class' => 'form-control']) !!} --}}
                {{ Form::select('karya_tempatan_antarabangsa', $collections['karyaTempatanAntarabangsa'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>

            <!-- Jenama Barang Rampasan Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('jenama_brg_rampasan', 'Jenama Barang Rampasan:') !!}
                {!! Form::text('jenama_brg_rampasan', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Bob Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('bob', 'Bob:') !!}
                {!! Form::text('bob', null, ['class' => 'form-control']) !!}
            </div>

            <!-- No Seal Ssm Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('no_seal_ssm', 'No Seal Ssm:') !!}
                {!! Form::text('no_seal_ssm', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Unit Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('unit', 'Unit:') !!}
                {!! Form::text('unit', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Tong Field -->
            {{-- <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('tong', 'Tong:') !!}
                {!! Form::text('tong', null, ['class' => 'form-control']) !!}
            </div> --}}

            <!-- Tangki Field -->
            {{-- <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('tangki', 'Tangki:') !!}
                {!! Form::text('tangki', null, ['class' => 'form-control']) !!}
            </div> --}}

            <!-- Liter Field -->
            {{-- <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('liter', 'Liter:') !!}
                {!! Form::text('liter', null, ['class' => 'form-control']) !!}
            </div> --}}

            <!-- Kg Field -->
            {{-- <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('kg', 'Kg:') !!}
                {!! Form::text('kg', null, ['class' => 'form-control']) !!}
            </div> --}}

            <!-- Nilai Rampasan Field -->
            <div class="form-group col-sm-12 col-lg-6">
                {!! Form::label('nilai_rampasan', 'Nilai Rampasan:') !!}
                {!! Form::text('nilai_rampasan', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group col-sm-12 m-b-none text-center">
                <a href="{{ url('caseenquirypaper/dataentries') }}" class="btn btn-default">Kembali <i class="fa fa-home"></i></a>
                {{ Form::button('Simpan '.' <i class="fa fa-save"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
            </div>
        </div>
    </div>
</div>
