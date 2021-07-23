<div class="panel panel-success">
    <div class="panel-heading">
        <i class="fa fa-edit"></i>
    </div>
    <div class="panel-body">
        <div class="row">
		    <div class="col-lg-12">
		    	<div class="row">
		            <div class="col-md-6">
				    	<div class="form-group {{ $errors->has('code') ? ' has-error' : '' }}">
				            {{ Form::label('code', 'Kod', ['class' => 'control-label required']) }}
		                    {{ Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Kod, Contoh : P1']) }}
		                    @if ($errors->has('code'))
		                        <span class="help-block"><strong>{{ $errors->first('code') }}</strong></span>
		                    @endif
				        </div>
			        </div>
			        <div class="col-md-6">
				    	<div class="form-group {{ $errors->has('descr') ? ' has-error' : '' }}">
				            {{ Form::label('descr', 'Penerangan', ['class' => 'control-label required']) }}
		                    {{ Form::text('descr', null, ['class' => 'form-control', 'placeholder' => 'Penerangan, Contoh : Penerangan 1']) }}
		                    @if ($errors->has('descr'))
		                        <span class="help-block"><strong>{{ $errors->first('descr') }}</strong></span>
		                    @endif
				        </div>
			        </div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
				    	<div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
				            {{ Form::label('category', 'Kategori', ['class' => 'control-label required']) }}
				            {{ Form::select('category', $categories, null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
				            @if ($errors->has('category'))
		                        <span class="help-block"><strong>{{ $errors->first('category') }}</strong></span>
		                    @endif
				        </div>
			        </div>
			        <div class="col-md-6">
				    	<div class="form-group {{ $errors->has('sort') ? ' has-error' : '' }}">
				            {{ Form::label('sort', 'Susunan', ['class' => 'control-label required']) }}
		                    {{ Form::text('sort', null, ['class' => 'form-control', 'placeholder' => 'Susunan, Contoh : 1']) }}
		                    @if ($errors->has('sort'))
		                        <span class="help-block"><strong>{{ $errors->first('sort') }}</strong></span>
		                    @endif
				        </div>
			        </div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
				            {{ Form::label('status', 'Status', ['class' => 'control-label required']) }}
		                    {{ Form::select('status', $activeStatus, null, ['class' => 'form-control', 'placeholder' => '-- SILA PILIH --']) }}
		                    @if ($errors->has('status'))
		                        <span class="help-block"><strong>{{ $errors->first('status') }}</strong></span>
		                    @endif
				        </div>
		        	</div>
		        </div>
			</div>
		</div>
    </div>
    <div class="panel-footer text-center">
        <a class="btn btn-default" href="{{ url('casereasontemplates') }}">
        	Kembali  <i class="fa fa-home"></i>
        </a>
        {{ Form::button('Simpan '.' <i class="fa fa-save"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
    </div>
</div>
