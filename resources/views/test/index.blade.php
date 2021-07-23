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
    <h2>Pengujian Aplikasi Sistem</h2>
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <!-- <li class="active"><a data-toggle="tab" href="#enq_info">Maklumat Pertanyaan/Cadangan</a></li> -->
            <li class="active">
                <a data-toggle="tab" href="#email_form">
                <i class="fa fa-envelope"></i>&nbsp;&nbsp;Emel
                </a>
            </li>
            <!-- <li class=""> -->
                <!-- <a data-toggle="tab" href="#email_info"> -->
                    <!-- Emel Yang Dihantar -->
                <!-- </a> -->
            <!-- </li> -->
            <li class="">
                <a data-toggle="tab" href="#attachment">
                    <!-- <span class="fa-stack"> -->
                        <!-- <span style="font-size: 14px;" class="badge badge-danger">2</span> -->
                    <!-- </span> -->
                    <i class="fa fa-upload"></i>&nbsp;&nbsp;Lampiran
                </a>
            </li>
            <!-- <li class=""><a data-toggle="tab" href="#transaction_info">Sorotan Transaksi</a></li> -->
        </ul>
        <div class="tab-content">
            <div id="email_form" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['url' => ['testapp/sendemail'], 'class'=>'form-horizontal']) !!}
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12">
                                <!--<hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">-->
                                <div class="alert alert-info">
                                    <p style="font-weight:bold; font-size:1.5rem">
                                    Cara masukkan email yang banyak pada kotak 'Kepada', 'Cc' & 'Bcc' adalah dengan menggunakan 
                                    simbol ';' untuk memisahkan antara emel-emel yang ingin dihantar. Sebagai contoh: abc@gmail.com;def@yahoo.com
                                    </p>  
                                </div>
                                <h4>Keterangan e-Mel:</h4>
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
                                <a class="btn btn-default" href="{{ route('dashboard') }}">Kembali</a>
                                {{ Form::submit('Hantar Emel', ['name' => 'btnSimpan','class' => 'btn btn-success']) }}
                            </div>  
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div id="attachment" class="tab-pane">
                <div class="panel-body" style="color: black;">
                    <!-- <h4>( Maksimum 5 Lampiran sahaja )</h4> -->
                    <div class="row">
                        <div class="col-sm-12">
                            <a onclick="ModalAttachmentCreate()" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Lampiran
                            </a>
                        </div>
                    </div>
                    <!-- <br /> -->
                    <div class="table-responsive">
                        <table id="attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Nama Fail</th>
                                    <th>Catatan</th>
                                    <th>Tarikh Muatnaik</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <!-- <tbody> -->
                            <!-- </tbody> -->
                        </table>
                    </div>
                    <!-- <div class="row"> -->
                        <!-- <div class="form-group col-sm-12" align="center"> -->
                        <!-- </div> -->
                    <!-- </div> -->
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
    
    // function ModalAttachmentCreate(id) {
    //     $('#modal-create-attachment')
    //         .modal("show")
    //         .find("#modalCreateContent")
    //         .load("{{ route('pertanyaan-admin-doc-jawapan.create','') }}" + "/" + id);
    //     return false;
    // }
    function ModalAttachmentCreate() {
        $('#modal-create-attachment')
            .modal("show")
            .find("#modalCreateContent")
            .load("{{ route('testappdoc.create','') }}");
        return false;
    }

    // function ModalAttachmentEdit(id) {
    //     $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('pertanyaan-admin-doc-jawapan.edit','') }}" + "/" + id);
    //     return false;
    // }

    $('#attachment-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        bInfo: false,
        language: {
            lengthMenu: 'Paparan _MENU_ rekod',
            zeroRecords: 'Tiada rekod ditemui',
            info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
            infoEmpty: 'Tiada rekod ditemui',
            infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
        },
        ajax: {
            // url: "{{-- url('admin-case-doc/getdatatable', $model->id) --}}"
            url: "{{ url('testappdoc/getdatatable') }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
    
</script>
@stop
