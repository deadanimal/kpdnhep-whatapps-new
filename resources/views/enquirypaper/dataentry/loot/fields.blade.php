<div class="row">
    <div class="table-responsive">
        <div class="col-sm-12">
            <div class="row">
                <!-- Nombor Fail Kes EP Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('no_kes', 'Nombor Fail Kes EP:') }}
                    {{ Form::text('no_kes', $input['noKes'], ['class' => 'form-control', 'readonly' => true]) }}
                </div>
            </div>
            <div class="row">
                <!-- Id Barang Rampasan Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('kesbrg_id', 'Id Barang Rampasan:') }}
                    {{ Form::text('kesbrg_id', null, ['class' => 'form-control']) }}
                </div>

                <!-- Kategori Barang Rampasan Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('kategori_brg_rampasan', 'Kategori Barang Rampasan:') }}
                    {{ Form::text('kategori_brg_rampasan', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="row">
                <!-- Jenis Barang Rampasan Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('jenis_brg_rampasan', 'Jenis Barang Rampasan:') }}
                    {{ Form::text('jenis_brg_rampasan', null, ['class' => 'form-control']) }}
                </div>

                <!-- Karya Tempatan Antarabangsa Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('karya_tempatan_antarabangsa', 'Karya Tempatan Antarabangsa:') }}
                    {{ Form::text('karya_tempatan_antarabangsa', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="row">
                <!-- Jenama Barang Rampasan Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('jenama_brg_rampasan', 'Jenama Barang Rampasan:') }}
                    {{ Form::text('jenama_brg_rampasan', null, ['class' => 'form-control']) }}
                </div>

                <!-- Nombor Seal SSM Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('no_seal_ssm', 'Nombor Seal SSM:') }}
                    {{ Form::text('no_seal_ssm', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="row">
                <!-- Nilai Rampasan Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('nilai_rampasan', 'Nilai Rampasan:') }}
                    {{ Form::number('nilai_rampasan', null, ['class' => 'form-control']) }}
                </div>

                <!-- Nilai Kenderaan Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('nilai_kenderaan', 'Nilai Kenderaan:') }}
                    {{ Form::number('nilai_kenderaan', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="row">
                <!-- Unit Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('unit', 'Unit:') }}
                    {{ Form::text('unit', null, ['class' => 'form-control']) }}
                </div>

                <!-- Unit Metrik Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('unit_metric', 'Unit Metrik:') }}
                    {{-- Form::text('unit_metric', null, ['class' => 'form-control']) --}}
                    {{ Form::select('unit_metric', $data['unitMetrics'], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
                </div>
            </div>
            <div class="row" style="display: none;">
                <!-- Jana Field -->
                <div class="form-group col-sm-6">
                    {{ Form::label('generate', 'Jana:') }}
                    {{ Form::hidden('generate', $input['generate'], ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
