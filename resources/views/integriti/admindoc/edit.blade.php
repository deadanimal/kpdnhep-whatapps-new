<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <i class="fa fa-close"></i>
    </button>
    <h4 class="modal-title">Kemaskini Lampiran</h4>
</div>
<div class="modal-body">
    {{ Form::open(['route' => ['integritiadmindoc.update',$mAdminCaseDoc->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' => 'form-edit-attachment']) }}
    {{ csrf_field() }}{{ method_field('PUT') }}
    <div id="file_field" class="form-group">
        <label for="file" class="col-sm-2 control-label">Fail</label>
        <div class="col-sm-10">
            {{ Form::file('file') }}
            <span style="color: red;">@lang('public-case.attachment.fileformat')</span>
            <span id="file_block" style="display: none;" class="help-block"></span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('IC_REMARKS', 'Catatan', ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-10">
            {{ Form::textarea('IC_REMARKS', $mAdminCaseDoc->IC_REMARKS, ['class' => 'form-control input-sm', 'rows' => 2]) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12" align="center">
            {{ Form::button('Kemaskini', ['type' => 'submit', 'class' => 'ladda-button ladda-button-demo btn btn-primary btn-sm']) }}
            <!--{{-- Form::submit('Kemaskini', ['class' => 'ladda-button ladda-button-demo btn btn-primary btn-sm']) --}}-->
        </div>
    </div>
    {{ Form::close() }}
</div>
<script type="text/javascript">
    $("#form-edit-attachment").submit(function(e){
        e.preventDefault();
        var l = $('.ladda-button-demo').ladda();
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
                    $('#form-edit-attachment').unbind('submit').submit();
                }
            }
        });
    });
</script>