            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Kemaskini Bahan Bukti</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(['route' => ['call-center-case.AttachmentUpdate',$mAttachment->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' => 'form-edit-attachment']) }}
                {{ csrf_field() }}{{ method_field('PUT') }}
                <div id ="doc_title_field" class="form-group">
                    {{ Form::label('doc_title', 'Nama Fail', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        {{ Form::text('doc_title', $mAttachment->doc_title, ['class' => 'form-control input-sm']) }}
                        <span id="doc_title_block" style="display: none;" class="help-block"><strong></strong></span>
                    </div>
                </div>

                <div id ="file_field" class="form-group">
                    {{ Form::label('file', 'Fail', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-9">
                        {{ Form::file('file') }}
                        <span id="file_block" style="display: none;" class="help-block"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-12" align="center">
                        <input type="button" value="Kemaskini" id="btnsubmitupdate" class="btn btn-sm btn-primary">
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