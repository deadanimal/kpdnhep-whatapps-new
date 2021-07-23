            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Muatnaik Fail Kes</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => ['fail-kes.store'], 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'form-create']) !!}
                {{ csrf_field() }}
                
                <div id="file_field" class="form-group">
                    {{ Form::label('file', 'Fail', ['class' => 'col-sm-2 control-label required']) }}
                    <div class="col-sm-10">
                        {{ Form::file('file') }}
                        <span id="file_block" class="help-block" style="display: none;" ></span>
                        <span id="extension_block" class="help-block" style="display: none;" ></span>
                    </div>
                </div>
                
                <div class="form-group">
                    {{ Form::label('remarks', 'Catatan', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::textArea('remarks', '', ['class' => 'form-control input-sm', 'rows' => 2]) }}
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-sm-12" align="center">
                        {{ Form::submit('Tambah', ['class' => 'btn btn-success btn-sm', 'id' => 'btnsubmitcreate']) }}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

<script type="text/javascript">
//    $(document).ready(function(){
        
        $('#btnsubmitcreate').click(function(e) {
            e.preventDefault();
            
            var $form = $('#form-create');
            var formData = {};
            
            $form.find(':input').each(function() {
                formData[ $(this).attr('name') ] = $(this).val();
            });
            
            $.ajax({
                type: 'POST',
                url: "{{ route('fail-kes.storevalidate') }}",
                dataType: "json",
                data: formData,
                success:function(data){
                    console.log(data);
                    if(data['fails']) {
                        $('.form-group').removeClass('has-error');
                        $('.help-block').hide().text();
                        $.each(data['fails'], function(key, value) {
                            $("#form-create div[id=" + key + "_field]").addClass('has-error');
                            $("#form-create span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                        });
                    }else{
                        $('#form-create').submit();
                    }
                }
            });
        });
        
//    });
</script>