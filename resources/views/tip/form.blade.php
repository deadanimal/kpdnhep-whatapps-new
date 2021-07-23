<style>
    .form-control[readonly][type="text"] {
        background-color: #ffffff;
    }
</style>
<div class="panel panel-success">
    <div class="panel-heading"><i class="fa fa-edit"></i></div>
    <div class="panel-body">
        <div class="row">
		    <div class="col-lg-12">
		    	<div class="row">
		            <div class="col-md-6">
				    	<div class="form-group {{ $errors->has('NO_KES') ? ' has-error' : '' }}">
				            {{ Form::label('NO_KES', 'Nombor Kes', ['class' => 'control-label required']) }}
				            @if(isset($id))
				            	<p class="form-control-static">{{ $id }}</p>
		                    @else
		                    	<div class="input-group">
				            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    		{{ Form::text('NO_KES', null, ['class' => 'form-control', 'placeholder' => 'Nombor Kes, Contoh : ATS-2020-0001-KGR']) }}
				        		</div>
		                    @endif
		                    @if ($errors->has('NO_KES'))
		                        <span class="help-block"><strong>{{ $errors->first('NO_KES') }}</strong></span>
		                    @endif
				        </div>
			        </div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
				    	<div class="form-group {{ $errors->has('KOD_NEGERI') ? ' has-error' : '' }}">
				            {{ Form::label('KOD_NEGERI', 'Negeri', ['class' => 'control-label required']) }}
				    		<div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
				            	{{ Form::text('KOD_NEGERI', null, ['class' => 'form-control', 'placeholder' => 'Negeri']) }}
				            </div>
				            @if ($errors->has('KOD_NEGERI'))
		                        <span class="help-block"><strong>{{ $errors->first('KOD_NEGERI') }}</strong></span>
		                    @endif
				        </div>
			        </div>
			        <div class="col-md-6">
				    	<div class="form-group {{ $errors->has('KOD_CAWANGAN') ? ' has-error' : '' }}">
				            {{ Form::label('KOD_CAWANGAN', 'Cawangan', ['class' => 'control-label required']) }}
				            <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('KOD_CAWANGAN', null, ['class' => 'form-control', 'placeholder' => 'Cawangan']) }}
				        	</div>
		                    @if ($errors->has('KOD_CAWANGAN'))
		                        <span class="help-block"><strong>{{ $errors->first('KOD_CAWANGAN') }}</strong></span>
		                    @endif
				        </div>
			        </div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('AKTA') ? ' has-error' : '' }}">
				            {{ Form::label('AKTA', 'Akta', ['class' => 'control-label required']) }}
				            <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('AKTA', null, ['class' => 'form-control', 'placeholder' => 'Akta']) }}
				        	</div>
		                    @if ($errors->has('AKTA'))
		                        <span class="help-block"><strong>{{ $errors->first('AKTA') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('ASAS_TINDAKAN') ? ' has-error' : '' }}">
				            {{ Form::label('ASAS_TINDAKAN', 'Asas Tindakan', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('ASAS_TINDAKAN', null, ['class' => 'form-control', 'placeholder' => 'Asas Tindakan']) }}
					        </div>
		                    @if ($errors->has('ASAS_TINDAKAN'))
		                        <span class="help-block"><strong>{{ $errors->first('ASAS_TINDAKAN') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('SERAHAN_AGENSI') ? ' has-error' : '' }}">
				            {{ Form::label('SERAHAN_AGENSI', 'Serahan Agensi', ['class' => 'control-label required']) }}
				            <div class="input-group">
				            	<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('SERAHAN_AGENSI', null, ['class' => 'form-control', 'placeholder' => 'Serahan Agensi']) }}
				        	</div>
		                    @if ($errors->has('SERAHAN_AGENSI'))
		                        <span class="help-block"><strong>{{ $errors->first('SERAHAN_AGENSI') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('TKH_KEJADIAN') ? ' has-error' : '' }}">
				            {{ Form::label('TKH_KEJADIAN', 'Tarikh Kejadian', ['class' => 'control-label required']) }}
				            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
		                    	{{ Form::text('TKH_KEJADIAN',
		                    		!empty($tip->TKH_KEJADIAN) ? date('d-m-Y', strtotime($tip->TKH_KEJADIAN)) : null, [
		                    		'class' => 'form-control',
                                    'placeholder' => 'HH-BB-TTTT',
                                    'readonly' => true
	                    			]
                    			) }}
		                    </div>
		                    @if ($errors->has('TKH_KEJADIAN'))
		                        <span class="help-block"><strong>{{ $errors->first('TKH_KEJADIAN') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('TKH_SERAHAN') ? ' has-error' : '' }}">
				            {{ Form::label('TKH_SERAHAN', 'Tarikh Serahan', ['class' => 'control-label required']) }}
				            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
		                    	{{ Form::text('TKH_SERAHAN',
		                    		!empty($tip->TKH_SERAHAN) ? date('d-m-Y', strtotime($tip->TKH_SERAHAN)) : null, [
			                    		'class' => 'form-control',
	                                    'placeholder' => 'HH-BB-TTTT',
	                                    'readonly' => true
	                    			]
                    			) }}
		                    </div>
		                    @if ($errors->has('TKH_SERAHAN'))
		                        <span class="help-block"><strong>{{ $errors->first('TKH_SERAHAN') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('PENGELASAN_KES') ? ' has-error' : '' }}">
				            {{ Form::label('PENGELASAN_KES', 'Pengelasan Kes', ['class' => 'control-label required']) }}
				            <div class="input-group">
				            	<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('PENGELASAN_KES', null, ['class' => 'form-control', 'placeholder' => 'Pengelasan Kes']) }}
				        	</div>
		                    @if ($errors->has('PENGELASAN_KES'))
		                        <span class="help-block"><strong>{{ $errors->first('PENGELASAN_KES') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('KATEGORI_KESALAHAN') ? ' has-error' : '' }}">
				            {{ Form::label('KATEGORI_KESALAHAN', 'Kategori Kesalahan', ['class' => 'control-label required']) }}
				            <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('KATEGORI_KESALAHAN', null, ['class' => 'form-control', 'placeholder' => 'Kategori Kesalahan']) }}
				        	</div>
		                    @if ($errors->has('KATEGORI_KESALAHAN'))
		                        <span class="help-block"><strong>{{ $errors->first('KATEGORI_KESALAHAN') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('KESALAHAN') ? ' has-error' : '' }}">
				            {{ Form::label('KESALAHAN', 'Kesalahan', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('KESALAHAN', null, ['class' => 'form-control', 'placeholder' => 'Kesalahan']) }}
					        </div>
		                    @if ($errors->has('KESALAHAN'))
		                        <span class="help-block"><strong>{{ $errors->first('KESALAHAN') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('PEGAWAI_SERBUAN_RO') ? ' has-error' : '' }}">
				            {{ Form::label('PEGAWAI_SERBUAN_RO', 'Pegawai Serbuan RO', ['class' => 'control-label required']) }}
				            <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('PEGAWAI_SERBUAN_RO', null, ['class' => 'form-control', 'placeholder' => 'Pegawai Serbuan RO']) }}
				        	</div>
		                    @if ($errors->has('PEGAWAI_SERBUAN_RO'))
		                        <span class="help-block"><strong>{{ $errors->first('PEGAWAI_SERBUAN_RO') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('PEGAWAI_PENYIASAT_AIO') ? ' has-error' : '' }}">
				            {{ Form::label('PEGAWAI_PENYIASAT_AIO', 'Pegawai Penyiasat AIO', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('PEGAWAI_PENYIASAT_AIO', null, ['class' => 'form-control', 'placeholder' => 'Pegawai Penyiasat AIO']) }}
					        </div>
		                    @if ($errors->has('PEGAWAI_PENYIASAT_AIO'))
		                        <span class="help-block"><strong>{{ $errors->first('PEGAWAI_PENYIASAT_AIO') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('PEGAWAI_PENYIASAT_IO') ? ' has-error' : '' }}">
				            {{ Form::label('PEGAWAI_PENYIASAT_IO', 'Pegawai Penyiasat IO', ['class' => 'control-label required']) }}
				            <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('PEGAWAI_PENYIASAT_IO', null, ['class' => 'form-control', 'placeholder' => 'Pegawai Penyiasat IO']) }}
				        	</div>
		                    @if ($errors->has('PEGAWAI_PENYIASAT_IO'))
		                        <span class="help-block"><strong>{{ $errors->first('PEGAWAI_PENYIASAT_IO') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('NO_REPORT_POLIS') ? ' has-error' : '' }}">
				            {{ Form::label('NO_REPORT_POLIS', 'Nombor Repot Polis', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('NO_REPORT_POLIS', null, ['class' => 'form-control', 'placeholder' => 'Nombor Repot Polis']) }}
					        </div>
		                    @if ($errors->has('NO_REPORT_POLIS'))
		                        <span class="help-block"><strong>{{ $errors->first('NO_REPORT_POLIS') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('NO_SSM') ? ' has-error' : '' }}">
				            {{ Form::label('NO_SSM', 'Nombor SSM', ['class' => 'control-label required']) }}
				            <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('NO_SSM', null, ['class' => 'form-control', 'placeholder' => 'Nombor SSM']) }}
				        	</div>
		                    @if ($errors->has('NO_SSM'))
		                        <span class="help-block"><strong>{{ $errors->first('NO_SSM') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('NAMA_PREMIS_SYARIKAT') ? ' has-error' : '' }}">
				            {{ Form::label('NAMA_PREMIS_SYARIKAT', 'Nama Premis Syarikat', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('NAMA_PREMIS_SYARIKAT', null, ['class' => 'form-control', 'placeholder' => 'Nama Premis Syarikat']) }}
					        </div>
		                    @if ($errors->has('NAMA_PREMIS_SYARIKAT'))
		                        <span class="help-block"><strong>{{ $errors->first('NAMA_PREMIS_SYARIKAT') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		            <div class="col-md-12">
		            	<div class="form-group {{ $errors->has('ALAMAT') ? ' has-error' : '' }}">
				            {{ Form::label('ALAMAT', 'Alamat', ['class' => 'control-label required']) }}
				            <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('ALAMAT', null, ['class' => 'form-control', 'placeholder' => 'Alamat']) }}
				        	</div>
		                    @if ($errors->has('ALAMAT'))
		                        <span class="help-block"><strong>{{ $errors->first('ALAMAT') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('JENAMA_PREMIS') ? ' has-error' : '' }}">
				            {{ Form::label('JENAMA_PREMIS', 'Jenama Premis', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('JENAMA_PREMIS', null, ['class' => 'form-control', 'placeholder' => 'Jenama Premis']) }}
					        </div>
		                    @if ($errors->has('JENAMA_PREMIS'))
		                        <span class="help-block"><strong>{{ $errors->first('JENAMA_PREMIS') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('KAWASAN') ? ' has-error' : '' }}">
				            {{ Form::label('KAWASAN', 'Kawasan', ['class' => 'control-label required']) }}
				            <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
		                    	{{ Form::text('KAWASAN', null, ['class' => 'form-control', 'placeholder' => 'Kawasan']) }}
				        	</div>
		                    @if ($errors->has('KAWASAN'))
		                        <span class="help-block"><strong>{{ $errors->first('KAWASAN') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('JENIS_PERNIAGAAN') ? ' has-error' : '' }}">
				            {{ Form::label('JENIS_PERNIAGAAN', 'Jenis Perniagaan', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('JENIS_PERNIAGAAN', null, ['class' => 'form-control', 'placeholder' => 'Jenis Perniagaan']) }}
					        </div>
		                    @if ($errors->has('JENIS_PERNIAGAAN'))
		                        <span class="help-block"><strong>{{ $errors->first('JENIS_PERNIAGAAN') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('KATEGORI_PREMIS') ? ' has-error' : '' }}">
				            {{ Form::label('KATEGORI_PREMIS', 'Kategori Premis', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('KATEGORI_PREMIS', null, ['class' => 'form-control', 'placeholder' => 'Kategori Premis']) }}
					        </div>
		                    @if ($errors->has('KATEGORI_PREMIS'))
		                        <span class="help-block"><strong>{{ $errors->first('KATEGORI_PREMIS') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('JENIS_PREMIS') ? ' has-error' : '' }}">
				            {{ Form::label('JENIS_PREMIS', 'Jenis Premis', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('JENIS_PREMIS', null, ['class' => 'form-control', 'placeholder' => 'Jenis Premis']) }}
					        </div>
		                    @if ($errors->has('JENIS_PREMIS'))
		                        <span class="help-block"><strong>{{ $errors->first('JENIS_PREMIS') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        	<div class="col-md-6">
		            	<div class="form-group {{ $errors->has('STATUS_OKT') ? ' has-error' : '' }}">
				            {{ Form::label('STATUS_OKT', 'Status OKT', ['class' => 'control-label required']) }}
		                    <div class="input-group">
			            		<span class="input-group-addon"><i class="fa fa-edit"></i></span>
			            		{{ Form::text('STATUS_OKT', null, ['class' => 'form-control', 'placeholder' => 'Status OKT']) }}
					        </div>
		                    @if ($errors->has('STATUS_OKT'))
		                        <span class="help-block"><strong>{{ $errors->first('STATUS_OKT') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
			</div>
		</div>
    </div>
    <div class="panel-footer text-center">
        <a class="btn btn-default" href="{{ url('tips') }}">Kembali  <i class="fa fa-home"></i></a>
        {{ Form::button('Simpan '.' <i class="fa fa-save"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
    </div>
</div>
