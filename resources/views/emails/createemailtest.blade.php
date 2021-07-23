@extends('layouts.main')
@section('content')
<?php 
    use App\Pertanyaan\PertanyaanAdmin;
?>
    <style> 
        textarea {
            resize: vertical;
        }
        span.select2 {
        width: 100% !important;
        }
        .select2-dropdown{
            z-index:3000 !important;
        }
    </style>
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <!-- <li class="active"><a data-toggle="tab" href="#enq_info">Maklumat Pertanyaan/Cadangan</a></li> -->
            <li class="active"><a data-toggle="tab" href="#email_form">Pernyataan Emel</a></li>
            <!-- <li class=""><a data-toggle="tab" href="#email_info">Emel Yang Dihantar</a></li> -->
            <!-- <li class=""><a data-toggle="tab" href="#transaction_info">Sorotan Transaksi</a></li> -->
        </ul>
        <div class="tab-content">
            <div id="email_form" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['url' => ['test/sendemailtest'], 'class'=>'form-horizontal']) !!}
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12">
                                <!--<hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">-->
                                <div class="alert alert-danger">
                                    <p style="font-weight:bold; font-size:1.5rem">
                                    Cara masukkan email yang banyak pada kotak 'Kepada', 'Cc' & 'Bcc' adalah dengan menggunakan 
                                    simbol ';' untuk memisahkan antara emel-emel yang ingin dihantar. Sebagai contoh: abc@gmail.com;def@yahoo.com
                                    </p>  
                                </div>
                                <h4>Penyataan e-Mel:</h4>
                                <div class="form-group">
                                    {{ Form::label('title', 'Tajuk', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('title', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('email', 'Kepada', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('email', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('cc', 'Cc', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('cc', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('bcc', 'Bcc', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('bcc', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('content', 'Mesej', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('content', '', ['class' => 'form-control input-sm', 'rows' => 4]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group" align="center">
                                
                                <!--{{-- Form::submit('Hantar', ['name' => 'btnHantar','class' => 'btn btn-success btn-sm']) --}}-->
                                {{ Form::submit('Hantar Emel', ['name' => 'btnSimpan','class' => 'btn btn-primary btn-sm']) }}
                                <a class="btn btn-default btn-sm" href="{{ route('pertanyaan-admin.index') }}">Kembali</a>
                            </div>  
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            
            
        </div>
    </div>
    
    <!-- Modal Create Attachment Start -->
    <div class="modal fade" id="modal-create-attachment" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" id='modalCreateContent'></div>
        </div>
    </div>
    
    <!-- Modal Edit Attachment Start -->
    <div class="modal fade" id="modal-edit-attachment" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" id='modalEditContent'></div>
        </div>
    </div>
@stop

@section('script_datatable')
<script type="text/javascript">
    


    $("select").select2();
    
    
    
    
    
    $('#AS_CMPLCAT').on('change', function (e) {
        var AS_CMPLCAT = $(this).val();
        $.ajax({
            type: 'GET',
            url: "{{ url('pertanyaan-admin/getcmpllist') }}" + "/" + AS_CMPLCAT,
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('select[name="AS_CMPLCD"]').empty();
                $.each(data, function (key, value) {
                    if (value == '0') {
                        $('select[name="AS_CMPLCD"]').append('<option value="">' + key + '</option>');
                        $('select[name="AS_CMPLCD"]').trigger('change');
                    }
                    else {
                        $('select[name="AS_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                        $('select[name="AS_CMPLCD"]').trigger('change');
                    }
                });
            }
        });
    });
    
    function ModalAttachmentCreate(id) {
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('pertanyaan-admin-doc-jawapan.create','') }}" + "/" + id);
        return false;
    }
    function ModalAttachmentEdit(id) {
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('pertanyaan-admin-doc-jawapan.edit','') }}" + "/" + id);
        return false;
    }
    
    
</script>
@stop
