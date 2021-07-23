@extends('layouts.main')
<?php
    use App\Ref;
    use App\Aduan\CallCenterCase;
?>
@section('content')
<h2>Lampiran Aduan Call Center</h2>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class=""><a>MAKLUMAT ADUAN</a></li>
        <li class="active"><a>LAMPIRAN</a></li>
        <li class=""><a>SEMAKAN ADUAN</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="panel-body">
                <h4>( Maksimum 5 Lampiran sahaja )</h4>
                @if($countDoc < 5)
                    <div class="row">
                        <div class="col-md-9">
                            <a onclick="ModalAttachmentCreate('{{ $model->id }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Lampiran</a>
                        </div>
                    </div>
                <br />
                @endif
                <div class="table-responsive">
                    <table id="admin-case-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
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
                    <div class="form-group col-sm-12" align="center">
                        <a class="btn btn-primary btn-sm" href="{{ route('call-center-case.edit',$model->id) }}"><i class="fa fa-chevron-left"></i> Sebelum</a>
                        <a class="btn btn-warning btn-sm" href="{{ url('call-center-case') }}">Kembali</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('call-center-case.preview',$model->id) }}">Simpan & Seterusnya <i class="fa fa-chevron-right"></i></a>
                    </div>
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
@stop

@section('script_datatable')
<script type="text/javascript">
    
    function ModalAttachmentCreate(id) {
        $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('call-center-case.doccreate','') }}" + "/" + id);
        return false;
    }
    function ModalAttachmentEdit(id) {
        $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('call-center-case.docedit','') }}" + "/" + id);
        return false;
    }
    
    $('#admin-case-attachment-table').DataTable({
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
            url: "{{ url('call-center-case/GetDataTableAttachment', $model->id) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
    
//    $(document).ready(function () {
        $('#btnsubmitcreate').click(function (e) {
            e.preventDefault();
            var $form = $('#form-create-attachment');
            var formData = {};
            $form.find(':input').each(function () {
                formData[ $(this).attr('name') ] = $(this).val();
            });
            $.ajax({
                type: 'POST',
                url: "{{ url('admin-case-doc/ajaxvalidatestoreattachment') }}",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if (data['fails']) {
                        $('.form-group').removeClass('has-error');
                        $('.help-block').hide().text();
                        $.each(data['fails'], function (key, value) {
                            $("#form-create-attachment div[id=" + key + "_field]").addClass('has-error');
                            $("#form-create-attachment span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                            console.log(key);
                        });
                        console.log("fails");
                    } else {
                        $('#form-create-attachment').submit();
                        console.log("success");
                    }
                }
            });
        });
//    });
</script>
@stop
