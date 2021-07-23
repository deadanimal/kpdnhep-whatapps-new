<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
    <h4 class="modal-title">Kemaskini Bahan Bukti</h4>
</div>
<div class="modal-body">
    {{ Form::open(['route' => ['admin-case-doc.update', $mAdminCaseDoc->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' => 'form-edit-attachment']) }}
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    
    <div id="file_field" class="form-group">
        <label for="file" class="col-sm-2 control-label">Fail</label>
        <div class="col-sm-10">
            {{ Form::file('file') }}
            <span id="file_block" style="display: none;" class="help-block"></span>
        </div>
    </div>
    <div class="form-group" id="remarks_field">
        {{ Form::label('CC_REMARKS', 'Catatan', ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-10">
            {{ Form::textarea('CC_REMARKS', $mAdminCaseDoc->CC_REMARKS, ['class' => 'form-control input-sm', 'rows' => 2]) }}
            <span id="remarks_block" style="display: none;" class="help-block"></span>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12" align="center">
            {{ Form::button('Kemaskini', ['class' => 'btn btn-primary btn-sm', 'id' => 'btnsubmitupdate']) }}
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
            url: "{{ url('admin-case-doc/ajaxvalidateupdateattachment') }}",
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