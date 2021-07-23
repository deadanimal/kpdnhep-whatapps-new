<div class="panel panel-success">
     <div class="panel-heading">
         <i class="fa fa-edit"></i>
     </div>
     <div class="panel-body">
         <div class="row">
               <div class="col-lg-12">
                    <div class="row">
                       <div class="col-md-12">
                              <div class="form-group {{ $errors->has('code') ? ' has-error' : '' }}">
                                 {{ Form::label('code', 'Kod', ['class' => 'control-label required']) }}
                               {{ Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Kod, Contoh : P1']) }}
                               @if ($errors->has('code'))
                                   <span class="help-block"><strong>{{ $errors->first('code') }}</strong></span>
                               @endif
                             </div>
                        </div>
                   </div>
                   <div class="row">
                    <div class="col-md-12">
                         <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                            {{ Form::label('title', 'Tajuk', ['class' => 'control-label required']) }}
                          {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Tajuk']) }}
                          @if ($errors->has('title'))
                              <span class="help-block"><strong>{{ $errors->first('title') }}</strong></span>
                          @endif
                        </div>
                   </div>
                   </div>
                   <div class="row">
                    <div class="col-md-12">
                         <div class="form-group {{ $errors->has('body') ? ' has-error' : '' }}">
                            {{ Form::label('body', 'Penerangan', ['class' => 'control-label required']) }}
                          {{ Form::textarea('body', null, ['class' => 'form-control', 'placeholder' => 'Penerangan']) }}
                          @if ($errors->has('body'))
                              <span class="help-block"><strong>{{ $errors->first('body') }}</strong></span>
                          @endif
                        </div>
                   </div>
                  </div>
                </div>
           </div>
     </div>
     <div class="panel-footer text-center">
         <a class="btn btn-default" href="{{ url('askanswertemplate') }}">
              Kembali  <i class="fa fa-home"></i>
         </a>
         {{ Form::button('Simpan '.' <i class="fa fa-save"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
     </div>
 </div>
 