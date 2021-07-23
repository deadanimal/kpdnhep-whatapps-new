<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">@lang('public-case.attachment.updateattachment')</h4>
</div>
<div class="modal-body">
    {{ Form::open(['route' => ['public-case-doc.update',$mPublicCaseDoc->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' => 'form-edit-attachment']) }}
    {{ csrf_field() }}{{ method_field('PUT') }}
    
    <div id ="file_field" class="form-group">
        <label for="file" class="col-sm-2 control-label">@lang('public-case.attachment.file')</label>
        <div class="col-sm-10">
            {{ Form::file('file') }}
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
            {{ Form::submit(trans('button.update'), ['class' => 'btn btn-primary btn-sm']) }}
            <!--<button id="btnsubmitupdate" class="btn btn-sm btn-primary">@lang('button.update')</button>-->
        </div>
    </div>
    {{ Form::close() }}
</div>
<script type="text/javascript">
    $('#btnsubmitupdate').click(function(e) {
        e.preventDefault();

        var $form = $('#form-edit-attachment');
        var formData = {};

        $form.find(':input').each(function() {
            formData[ $(this).attr('name') ] = $(this).val();
        });
        
        $.ajax({
            type: 'POST',
            url: "{{ url('call-center-case/AjaxValidateEditAttachment') }}",
            dataType: "json",
            data: formData,
            success:function(data){
                if(data['fails']) {
                    $('.form-group').removeClass('has-error');
                    $('.help-block').hide().text();
                    $.each(data['fails'], function(key, value) {
                        $("#form-edit-attachment div[id=" + key + "_field]").addClass('has-error');
                        $("#form-edit-attachment span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                        console.log(key);
                    });
                    console.log("fails");
                }else{
                    $('#form-edit-attachment').submit();
                    console.log("success");
                }
            }
        });
    });
</script>