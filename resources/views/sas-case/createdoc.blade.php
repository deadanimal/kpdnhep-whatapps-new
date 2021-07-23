<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true"><i class="fa fa-close"></i></span><span class="sr-only">Close</span>
    </button>
    <h4 class="modal-title">Tambah Bahan Bukti</h4>
</div>
{!! Form::open(['url' => 'sas-case/create-attachment', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'form-create-attachment']) !!}
{{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group" id="file_field">
            {{ Form::label('file', 'Fail', ['class' => 'col-sm-2 control-label required']) }}
            <div class="col-sm-10">
                {{ Form::file('file') }}
                <div>
                    <span style="color: red;">
                        Format : pdf, jpeg, png, gif. Maksimum 2Mb bagi setiap fail.
                    </span>
                </div>
                <span id="file_block" style="display: none;" class="help-block"></span>
            </div>
        </div>
        <div class="form-group" id="remarks_field">
            {{ Form::label('CC_REMARKS', 'Catatan', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::textarea('CC_REMARKS', '', ['class' => 'form-control input-sm', 'rows' => 2]) }}
                {{ Form::hidden('CC_CASEID', $id, ['class' => 'form-control input-sm']) }}
                <span id="remarks_block" style="display: none;" class="help-block"></span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12" align="center">
                <button type="submit" class="ladda-button ladda-button-demo btn btn-success btn-sm">Tambah</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

<script type="text/javascript">
    $("#form-create-attachment").submit(function(e){
        e.preventDefault();
        var l = $('.ladda-button-demo').ladda();
        $.ajax({
            type : "POST",
            url: "{{ url('call-center-case/AjaxValidateCreateAttachment') }}",
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
                        $("#form-create-attachment div[id=" + key + "_field]").addClass('has-error');
                        $("#form-create-attachment span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                    });
                    l.ladda('stop');
                } else {
                    $('#form-create-attachment').unbind('submit').submit();
                }
            }
        });
    });
</script>
