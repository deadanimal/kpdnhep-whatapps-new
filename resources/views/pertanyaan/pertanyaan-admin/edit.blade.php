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
            <li class="active"><a data-toggle="tab" href="#enq_info">Maklumat Pertanyaan/Cadangan</a></li>
            <li class=""><a data-toggle="tab" href="#email_form">Pernyataan Emel</a></li>
            <li class=""><a data-toggle="tab" href="#email_info">Emel Yang Dihantar</a></li>
            <li class=""><a data-toggle="tab" href="#transaction_info">Sorotan Transaksi</a></li>
        </ul>
        <div class="tab-content">
            <div id="enq_info" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => ['pertanyaan-admin.update',$PertanyaanAdmin->AS_ASKID],'class'=>'form-horizontal', 'method' => 'patch']) !!}
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('AS_SUMMARY', 'Keterangan Pertanyaan/Cadangan', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('AS_SUMMARY', $PertanyaanAdmin->AS_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 4, 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('AS_CMPLCAT') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_CMPLCAT', 'Kategori', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('AS_CMPLCAT', PertanyaanAdmin::getcmplcatlist('244', '1277'), old('AS_CMPLCAT', $PertanyaanAdmin->AS_CMPLCAT), ['class' => 'form-control input-sm', 'id' => 'AS_CMPLCAT']) }}
                                        @if ($errors->has('AS_CMPLCAT'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_CMPLCAT') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('AS_CMPLCD') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_CMPLCD', 'Subkategori', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('AS_CMPLCD', PertanyaanAdmin::getcmplcdlist((old('AS_CMPLCAT') ? old('AS_CMPLCAT') : $PertanyaanAdmin->AS_CMPLCAT), 'ms', 'ms'),(old('AS_CMPLCD') ? old('AS_CMPLCD') : ($PertanyaanAdmin->AS_CMPLCD ? $PertanyaanAdmin->AS_CMPLCD : '')), ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('AS_CMPLCD'))
                                        <span class="help-block"><strong>{{ $errors->first('AS_CMPLCD') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('modalConsumerComplaint', 'Carian Aduan Kepenggunaan', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        {{ Form::button('Carian '.' <i class="fa fa-search"></i>', [
                                            'class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#modalConsumerComplaint'
                                        ]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('answertemplate', 'Templat Jawapan', ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        {{ Form::select('answertemplate', $data['answerTemplates'], null, ['class' => 'form-control input-sm', 'placeholder' => '-- SILA PILIH --']) }}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('AS_ANSWER') ? ' has-error' : '' }}">
                                    {{ Form::label('AS_ANSWER', 'Jawapan', ['class' => 'col-sm-3 control-label required']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('AS_ANSWER', $PertanyaanAdmin->AS_ANSWER, ['class' => 'form-control input-sm', 'rows' => 4]) }}
                                        {{ Form::hidden('AS_ASKID', $PertanyaanAdmin->AS_ASKID, ['class' => 'form-control input-sm']) }}
                                        @if ($errors->has('AS_ANSWER'))
                                            <span class="help-block"><strong>{{ $errors->first('AS_ANSWER') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <!--<hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">-->
<!--                                <div class="alert alert-danger">
                                    <p style="font-weight:bold; font-size:1.5rem">Cara masukkan email yang banyak pada kotak 'Kepada', 'Cc' & 'Bcc' adalah dengan menggunakan 
                                    simbol ';' untuk memisahkan antara emel-emel yang ingin dihantar. Sebagai contoh: abc@gmail.com;def@yahoo.com</p>  
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
                                </div>-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <a onclick="ModalAttachmentCreate('{{ $PertanyaanAdmin->AS_ASKID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Lampiran</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="pertanyaan-admin-doc-jawapan-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>Nama Fail</th>
                                        <th>Catatan</th>
                                        <th>Tarikh Muatnaik</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group" align="center">
                                @if($PertanyaanAdmin->AS_ASKSTS == '2')
                                {{ Form::submit('Hantar', ['name' => 'btnHantar','class' => 'btn btn-success btn-sm']) }}
                                {{ Form::submit('Simpan', ['name' => 'btnSimpan','class' => 'btn btn-primary btn-sm']) }}
                                @endif
                                <a class="btn btn-default btn-sm" href="{{ route('pertanyaan-admin.index') }}">Kembali</a>
                            </div>  
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div id="email_form" class="tab-pane">
                <div class="panel-body">
                    {!! Form::open(['route' => ['pertanyaan-admin.update',$PertanyaanAdmin->AS_ASKID],'class'=>'form-horizontal', 'method' => 'patch']) !!}
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12">
                                <!--<hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">-->
                                <div class="alert alert-danger">
                                    <p style="font-weight:bold; font-size:1.5rem">Cara masukkan email yang banyak pada kotak 'Kepada', 'Cc' & 'Bcc' adalah dengan menggunakan 
                                    simbol ';' untuk memisahkan antara emel-emel yang ingin dihantar. Sebagai contoh: abc@gmail.com;def@yahoo.com</p>  
                                </div>
                                <h4>Penyataan e-Mel:</h4>
                                <div class="form-group">
                                    {{ Form::label('title', 'Tajuk', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('title', '', ['class' => 'form-control input-sm']) }}
                                        {{ Form::hidden('AS_ASKID', $PertanyaanAdmin->AS_ASKID, ['class' => 'form-control input-sm']) }}
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
                                @if($PertanyaanAdmin->AS_ASKSTS == '2')
                                <!--{{-- Form::submit('Hantar', ['name' => 'btnHantar','class' => 'btn btn-success btn-sm']) --}}-->
                                {{ Form::submit('Hantar Emel', ['name' => 'btnSimpan','class' => 'btn btn-primary btn-sm']) }}
                                @endif
                                <a class="btn btn-default btn-sm" href="{{ route('pertanyaan-admin.index') }}">Kembali</a>
                            </div>  
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div id="transaction_info" class="tab-pane">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table style="width: 100%" id="transaction-table" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Status</th>
                                    <!--<th>Status Semasa</th>-->
                                    <th>Tarikh</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="email_info" class="tab-pane">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table style="width: 100%" id="email-table" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tajuk</th>
                                    <th>Kepada</th>
                                    <th>CC</th>
                                    <th>BCC</th>
                                    <th>Mesej</th>
                                    <th>Dibuat oleh</th>
                                    <th>Tarikh Dibuat</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
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
    @include('pertanyaan.pertanyaan-admin.modal_consumercomplaint')
@stop

@section('script_datatable')
<script type="text/javascript">
    
//    $(document).ready(function() {
//        var status = <?php // echo $PertanyaanAdmin->AS_ASKSTS; ?>;
//        if(status !== 2) {
//            $('.form-control').attr("readonly", true);
//        }
//    });

    $("select").select2();
    
    $('#transaction-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        bInfo: false,
        language: {
            zeroRecords: 'Tiada rekod ditemui',
            infoEmpty: 'Tiada rekod ditemui'
        },
        ajax: {
            url: "{{ url('pertanyaan-admin/getdatattable_transaction',$PertanyaanAdmin->AS_ASKID)}}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'AD_ASKSTS', name: 'AD_ASKSTS'},
            // {data: 'AD_CURSTS', name: 'AD_CURSTS'},
            {data: 'AD_CREDT', name: 'AD_CREDT'}
        ]
    });
    
    $('#email-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        bInfo: false,
        language: {
            zeroRecords: 'Tiada rekod ditemui',
            infoEmpty: 'Tiada rekod ditemui'
        },
        ajax: {
            url: "{{ url('pertanyaan-admin/getdatattable_askemail',$PertanyaanAdmin->AS_ASKID)}}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'AE_TITLE', name: 'AE_TITLE'},
            // {data: 'AE_TO', name: 'AE_TO'},
            {data: 'AE_TO', render: function (data, type) {
                if (data)
                    return data.indexOf(';') !== -1 ? data.split(";").join("<br/>") : data;
                else 
                    return '';
            }, name: 'AE_TO'},
            {data: 'AE_CC', render: function (data, type) {
                if (data)
                    return data.indexOf(';') !== -1 ? data.split(";").join("<br/>") : data;
                else 
                    return '';
            }, name: 'AE_CC'},
            {data: 'AE_BCC', render: function (data, type) {
                if (data)
                    return data.indexOf(';') !== -1 ? data.split(";").join("<br/>") : data;
                else 
                    return '';
            }, name: 'AE_BCC'},
            {data: 'AE_MESSAGE', name: 'AE_MESSAGE'},
            {data: 'AE_CREBY', name: 'AE_CREBY'},
            {data: 'AE_CREDT', name: 'AE_CREDT'}
        ]
    });
    
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
    
    $('#pertanyaan-admin-doc-jawapan-table').DataTable({
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
            url: "{{ url('pertanyaan-admin-doc-jawapan/getdatatable', $PertanyaanAdmin->AS_ASKID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'img_name', name: 'img_name'},
            {data: 'remarks', name: 'remarks'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });

    var consumerComplaintTable = $('#modal-consumercomplaint-table').DataTable({
        dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
        processing: true,
        serverSide: true,
        order: [ 4, 'desc' ],
        ajax: {
            url: '{{ url("inquiry/consumercomplaints") }}',
            data: function (d) {
                d.search_status = $('input[name=search_status]').val();
                d.CA_CASEID = $('input[name=CA_CASEID]').val();
                d.investigator = $('input[name=investigator]').val();
            }
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', searchable: false, orderable: false, width: '1%'},
            {data: 'CA_CASEID', name: 'CA_CASEID'},
            {data: 'CA_ANSWER', name: 'CA_ANSWER'},
            {data: 'name', name: 'name'},
            {data: 'CA_RCVDT', name: 'CA_RCVDT'},
            {data: 'investigator_action', name: 'investigator_action', searchable: false, orderable: false, width: '5%'}
        ],
        language: {
            lengthMenu: "@lang('datatable.lengthMenu')",
            zeroRecords: "@lang('datatable.infoEmpty')",
            info: "@lang('datatable.info')",
            infoEmpty: "@lang('datatable.infoEmpty')",
            infoFiltered: "(@lang('datatable.infoFiltered'))",
            paginate: {
                previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                first: "@lang('datatable.first')",
                last: "@lang('datatable.last')",
            },
        }
    });

    $('#buttonresetform').on('click', function (e) {
        $('input[name=search_status]').val(0);
        document.getElementById("consumercomplaint-search-form").reset();
        consumerComplaintTable.draw();
        consumerComplaintTable.page('first');
        consumerComplaintTable.state.clear();
        e.preventDefault();
    });

    $('#consumercomplaint-search-form').on('submit', function(e) {
        $('input[name=search_status]').val(1);
        consumerComplaintTable.draw();
        e.preventDefault();
    });

    function selectComplaint(id)
    {
        if(id){
            $.ajax({
                url: "{{ url('inquiry/consumercomplaints') }}" + '/' + id,
                dataType: 'json',
                success: function (data) {
                    var answerComplaint = data.CA_ANSWER != null ? data.CA_ANSWER : '';
                    var investigatorName = data.name != null ? data.name : '';
                    $('#AS_ANSWER').val(function(n, c){
                        return c + '\nJawapan Kepada Pengadu : ' + answerComplaint
                            + '\nPegawai Penyiasat : ' + investigatorName;
                    });
                    $('#modalConsumerComplaint').modal('hide');
                },
                error: function (data) {
                    console.log(data);
                },
                complete: function (data) {
                }
            });
        };
    };

    $('#answertemplate').on('change', function (e) {
        var answertemplateid = $(this).val();
        if(answertemplateid){
            $.ajax({
                type: 'GET',
                url: "{{ url('inquiry/answertemplates') }}" + '/' + answertemplateid,
                dataType: 'json',
                success: function (data) {
                    var templatebody = data.body != null ? data.body : '';
                    $('#AS_ANSWER').val(function(n, c){
                        return c + ' \n' + templatebody;
                    });
                },
                error: function (data) {
                    console.log(data);
                },
                complete: function (data) {
                }
            });
        };
    });
</script>
@stop
