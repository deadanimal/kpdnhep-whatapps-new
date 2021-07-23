<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('kod_negeri') ? ' has-error' : '' }}">
            {{ Form::label('kod_negeri', 'Negeri', ['class' => 'control-label required']) }}
    		<div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{-- Form::text('kod_negeri', null, ['class' => 'form-control', 'placeholder' => 'Negeri']) --}}
            	{{ Form::select('kod_negeri', $refStates, null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
            </div>
            @if ($errors->has('kod_negeri'))
                <span class="help-block"><strong>{{ $errors->first('kod_negeri') }}</strong></span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('kod_cawangan') ? ' has-error' : '' }}">
            {{ Form::label('kod_cawangan', 'Cawangan', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{-- Form::text('kod_cawangan', null, ['class' => 'form-control', 'placeholder' => 'Cawangan']) --}}
            	{{ Form::select('kod_cawangan', [], null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
        	</div>
            @if ($errors->has('kod_cawangan'))
                <span class="help-block"><strong>{{ $errors->first('kod_cawangan') }}</strong></span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('akta') ? ' has-error' : '' }}">
            {{ Form::label('akta', 'Akta', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{-- Form::text('akta', null, ['class' => 'form-control', 'placeholder' => 'Akta']) --}}
            	{{ Form::select('akta', $refActs, null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
        	</div>
            @if ($errors->has('akta'))
                <span class="help-block"><strong>{{ $errors->first('akta') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('asas_tindakan') ? ' has-error' : '' }}">
            {{ Form::label('asas_tindakan', 'Asas Tindakan', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('asas_tindakan', null, ['class' => 'form-control', 'placeholder' => 'Asas Tindakan']) }}
	        </div>
            @if ($errors->has('asas_tindakan'))
                <span class="help-block"><strong>{{ $errors->first('asas_tindakan') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('serahan_agensi') ? ' has-error' : '' }}">
            {{ Form::label('serahan_agensi', 'Serahan Agensi', ['class' => 'control-label required']) }}
            <div class="input-group">
            	<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{ Form::text('serahan_agensi', null, ['class' => 'form-control', 'placeholder' => 'Serahan Agensi']) }}
        	</div>
            @if ($errors->has('serahan_agensi'))
                <span class="help-block"><strong>{{ $errors->first('serahan_agensi') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('tkh_kejadian') ? ' has-error' : '' }}">
            {{ Form::label('tkh_kejadian', 'Tarikh Kejadian', ['class' => 'control-label required']) }}
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            	{{ Form::text('tkh_kejadian',
            		!empty($tip->tkh_kejadian) ? date('d-m-Y', strtotime($tip->tkh_kejadian)) : null, [
            		'class' => 'form-control',
                    'placeholder' => 'HH-BB-TTTT',
                    'readonly' => true
        			]
    			) }}
            </div>
            @if ($errors->has('tkh_kejadian'))
                <span class="help-block"><strong>{{ $errors->first('tkh_kejadian') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('tkh_serahan') ? ' has-error' : '' }}">
            {{ Form::label('tkh_serahan', 'Tarikh Serahan', ['class' => 'control-label required']) }}
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            	{{ Form::text('tkh_serahan',
            		!empty($tip->tkh_serahan) ? date('d-m-Y', strtotime($tip->tkh_serahan)) : null, [
                		'class' => 'form-control',
                        'placeholder' => 'HH-BB-TTTT',
                        'readonly' => true
        			]
    			) }}
            </div>
            @if ($errors->has('tkh_serahan'))
                <span class="help-block"><strong>{{ $errors->first('tkh_serahan') }}</strong></span>
            @endif
        </div>
	</div>
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('pengelasan_kes') ? ' has-error' : '' }}">
            {{ Form::label('pengelasan_kes', 'Pengelasan Kes', ['class' => 'control-label required']) }}
            <div class="input-group">
            	<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{ Form::text('pengelasan_kes', null, ['class' => 'form-control', 'placeholder' => 'Pengelasan Kes']) }}
        	</div>
            @if ($errors->has('pengelasan_kes'))
                <span class="help-block"><strong>{{ $errors->first('pengelasan_kes') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('kategori_kesalahan') ? ' has-error' : '' }}">
            {{ Form::label('kategori_kesalahan', 'Kategori Kesalahan', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{ Form::text('kategori_kesalahan', null, ['class' => 'form-control', 'placeholder' => 'Kategori Kesalahan']) }}
        	</div>
            @if ($errors->has('kategori_kesalahan'))
                <span class="help-block"><strong>{{ $errors->first('kategori_kesalahan') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('kesalahan') ? ' has-error' : '' }}">
            {{ Form::label('kesalahan', 'Kesalahan', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('kesalahan', null, ['class' => 'form-control', 'placeholder' => 'Kesalahan']) }}
	        </div>
            @if ($errors->has('kesalahan'))
                <span class="help-block"><strong>{{ $errors->first('kesalahan') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('pegawai_serbuan_ro') ? ' has-error' : '' }}">
            {{ Form::label('pegawai_serbuan_ro', 'Pegawai Serbuan RO', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{ Form::text('pegawai_serbuan_ro', null, ['class' => 'form-control', 'placeholder' => 'Pegawai Serbuan RO']) }}
        	</div>
            @if ($errors->has('pegawai_serbuan_ro'))
                <span class="help-block"><strong>{{ $errors->first('pegawai_serbuan_ro') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('pegawai_penyiasat_aio') ? ' has-error' : '' }}">
            {{ Form::label('pegawai_penyiasat_aio', 'Pegawai Penyiasat AIO', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('pegawai_penyiasat_aio', null, ['class' => 'form-control', 'placeholder' => 'Pegawai Penyiasat AIO']) }}
	        </div>
            @if ($errors->has('pegawai_penyiasat_aio'))
                <span class="help-block"><strong>{{ $errors->first('pegawai_penyiasat_aio') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('pegawai_penyiasat_io') ? ' has-error' : '' }}">
            {{ Form::label('pegawai_penyiasat_io', 'Pegawai Penyiasat IO', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{ Form::text('pegawai_penyiasat_io', null, ['class' => 'form-control', 'placeholder' => 'Pegawai Penyiasat IO']) }}
        	</div>
            @if ($errors->has('pegawai_penyiasat_io'))
                <span class="help-block"><strong>{{ $errors->first('pegawai_penyiasat_io') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('no_report_polis') ? ' has-error' : '' }}">
            {{ Form::label('no_report_polis', 'Nombor Repot Polis', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('no_report_polis', null, ['class' => 'form-control', 'placeholder' => 'Nombor Repot Polis']) }}
	        </div>
            @if ($errors->has('no_report_polis'))
                <span class="help-block"><strong>{{ $errors->first('no_report_polis') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('no_ssm') ? ' has-error' : '' }}">
            {{ Form::label('no_ssm', 'Nombor SSM', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{ Form::text('no_ssm', null, ['class' => 'form-control', 'placeholder' => 'Nombor SSM']) }}
        	</div>
            @if ($errors->has('no_ssm'))
                <span class="help-block"><strong>{{ $errors->first('no_ssm') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('nama_premis_syarikat') ? ' has-error' : '' }}">
            {{ Form::label('nama_premis_syarikat', 'Nama Premis Syarikat', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('nama_premis_syarikat', null, ['class' => 'form-control', 'placeholder' => 'Nama Premis Syarikat']) }}
	        </div>
            @if ($errors->has('nama_premis_syarikat'))
                <span class="help-block"><strong>{{ $errors->first('nama_premis_syarikat') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
    	<div class="form-group {{ $errors->has('alamat') ? ' has-error' : '' }}">
            {{ Form::label('alamat', 'Alamat', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{ Form::text('alamat', null, ['class' => 'form-control', 'placeholder' => 'Alamat']) }}
        	</div>
            @if ($errors->has('alamat'))
                <span class="help-block"><strong>{{ $errors->first('alamat') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('jenama_premis') ? ' has-error' : '' }}">
            {{ Form::label('jenama_premis', 'Jenama Premis', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('jenama_premis', null, ['class' => 'form-control', 'placeholder' => 'Jenama Premis']) }}
	        </div>
            @if ($errors->has('jenama_premis'))
                <span class="help-block"><strong>{{ $errors->first('jenama_premis') }}</strong></span>
            @endif
        </div>
	</div>
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('kawasan') ? ' has-error' : '' }}">
            {{ Form::label('kawasan', 'Kawasan', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
            	{{ Form::text('kawasan', null, ['class' => 'form-control', 'placeholder' => 'Kawasan']) }}
        	</div>
            @if ($errors->has('kawasan'))
                <span class="help-block"><strong>{{ $errors->first('kawasan') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('jenis_perniagaan') ? ' has-error' : '' }}">
            {{ Form::label('jenis_perniagaan', 'Jenis Perniagaan', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('jenis_perniagaan', null, ['class' => 'form-control', 'placeholder' => 'Jenis Perniagaan']) }}
	        </div>
            @if ($errors->has('jenis_perniagaan'))
                <span class="help-block"><strong>{{ $errors->first('jenis_perniagaan') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('kategori_premis') ? ' has-error' : '' }}">
            {{ Form::label('kategori_premis', 'Kategori Premis', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('kategori_premis', null, ['class' => 'form-control', 'placeholder' => 'Kategori Premis']) }}
	        </div>
            @if ($errors->has('kategori_premis'))
                <span class="help-block"><strong>{{ $errors->first('kategori_premis') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('jenis_premis') ? ' has-error' : '' }}">
            {{ Form::label('jenis_premis', 'Jenis Premis', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('jenis_premis', null, ['class' => 'form-control', 'placeholder' => 'Jenis Premis']) }}
	        </div>
            @if ($errors->has('jenis_premis'))
                <span class="help-block"><strong>{{ $errors->first('jenis_premis') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('status_okt') ? ' has-error' : '' }}">
            {{ Form::label('status_okt', 'Status OKT', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('status_okt', null, ['class' => 'form-control', 'placeholder' => 'Status OKT']) }}
	        </div>
            @if ($errors->has('status_okt'))
                <span class="help-block"><strong>{{ $errors->first('status_okt') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('jantina') ? ' has-error' : '' }}">
            {{ Form::label('jantina', 'Jantina', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('jantina', null, ['class' => 'form-control', 'placeholder' => 'Jantina']) }}
	        </div>
            @if ($errors->has('jantina'))
                <span class="help-block"><strong>{{ $errors->first('jantina') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('nama_okt') ? ' has-error' : '' }}">
            {{ Form::label('nama_okt', 'Nama OKT', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('nama_okt', null, ['class' => 'form-control', 'placeholder' => 'Nama OKT']) }}
	        </div>
            @if ($errors->has('nama_okt'))
                <span class="help-block"><strong>{{ $errors->first('nama_okt') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('taraf_kerakyatan') ? ' has-error' : '' }}">
            {{ Form::label('taraf_kerakyatan', 'Taraf Kerakyatan', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('taraf_kerakyatan', null, ['class' => 'form-control', 'placeholder' => 'Taraf Kerakyatan']) }}
	        </div>
            @if ($errors->has('taraf_kerakyatan'))
                <span class="help-block"><strong>{{ $errors->first('taraf_kerakyatan') }}</strong></span>
            @endif
        </div>
	</div>
	<div class="col-md-6">
    	<div class="form-group {{ $errors->has('catatan_kerakyatan') ? ' has-error' : '' }}">
            {{ Form::label('catatan_kerakyatan', 'Catatan Kerakyatan', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('catatan_kerakyatan', null, ['class' => 'form-control', 'placeholder' => 'Catatan Kerakyatan']) }}
	        </div>
            @if ($errors->has('catatan_kerakyatan'))
                <span class="help-block"><strong>{{ $errors->first('catatan_kerakyatan') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group {{ $errors->has('no_ic_pasport') ? ' has-error' : '' }}">
            {{ Form::label('no_ic_pasport', 'Nombor IC / Pasport', ['class' => 'control-label required']) }}
            <div class="input-group">
        		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
        		{{ Form::text('no_ic_pasport', null, ['class' => 'form-control', 'placeholder' => 'Nombor IC / Pasport']) }}
	        </div>
            @if ($errors->has('no_ic_pasport'))
                <span class="help-block"><strong>{{ $errors->first('no_ic_pasport') }}</strong></span>
            @endif
        </div>
	</div>
</div>
<div class="row text-center">
    <a class="btn btn-default" href="{{ url('caseenquirypaper/dataentries') }}">Kembali <i class="fa fa-home"></i></a>
    {{ Form::button('Simpan '.' <i class="fa fa-save"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
</div>
