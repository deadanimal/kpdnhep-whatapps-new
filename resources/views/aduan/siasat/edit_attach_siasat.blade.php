<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Kemaskini Lampiran Siasatan</h4>
</div>
<div class="modal-body">
    {!! Form::open(['route' => ['siasat.updateattachsiasat',$mPublicCaseDoc->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' => 'form-edit-attachment']) !!}
    {{ csrf_field() }}{{ method_field('PUT') }}

    <div id="file_field" class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
        {{ Form::label('file', trans('public-case.attachment.file'), ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-10">
            {{ Form::file('file') }}
            <span style="color: red;">@lang('public-case.attachment.fileformat')</span>
            <span id="file_block" style="display: none;" class="help-block"></span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('CC_REMARKS', trans('public-case.attachment.remarks'), ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-10">
            {{ Form::textArea('CC_REMARKS', $mPublicCaseDoc->CC_REMARKS, ['class' => 'form-control input-sm', 'rows' => 2]) }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12" align="center">
            <button type="submit" id="btnsubmitcreate" class="ladda-button ladda-button-demo btn btn-success btn-sm">@lang('button.update')</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<script type="text/javascript">
    $("#form-edit-attachment").submit(function(e){
        e.preventDefault();
        var l = $('.ladda-button-demo').ladda();
        var _this = this;
        $.ajax({
            type : 'POST',
            url: "{{ url('call-center-case/AjaxValidateEditAttachment') }}",
            dataType : "json",
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function () {
                l.ladda('start');
            },
            success: function (data) {
                console.log(data);
                if (data['fails']) {
                    $('.form-group').removeClass('has-error');
                    $('.help-block').hide().text();
                    $.each(data['fails'], function (key, value) {
                        $("#form-edit-attachment div[id=" + key + "_field]").addClass('has-error');
                        $("#form-edit-attachment span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                    });
                    l.ladda('stop');
                } else {
//                    $('#form-edit-attachment').unbind('submit').submit();
                    $.ajax({
                        type : "POST",
                        url: "{{ url('siasat/updateattachsiasat/'.$mPublicCaseDoc->id) }}",
                        dataType : "json",
                        data: new FormData(_this),
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            l.ladda('start');
                        },
                        success: function (data) {
                            l.ladda('stop');
                            $('#modal-edit-attachment').modal("hide");
                            $('#admin-case-attachment-table').DataTable({
                                destroy: true,
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
                                    url: "{{ url('siasat/getAttachmentSiasat', $mPublicCaseDoc->CC_CASEID) }}"
                                },
                                columns: [
                                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                                    {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
                                    {data: 'CC_REMARKS', name: 'CC_REMARKS', orderable: false},
                                    {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
                                ]
                            });
                        }
                    });
                }
            }
        });
    });
</script>