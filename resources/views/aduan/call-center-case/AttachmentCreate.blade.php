<div class="modal fade" id="modal-create-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Bahan Bukti</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(['route' => 'call-center-case.storeAttachment', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'form-create-attachment']) }}
                {{ csrf_field() }}
                <div id ="doc_title_field" class="form-group">
                    {{ Form::label('doc_title', 'Nama Fail', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        {{ Form::text('doc_title', '', ['class' => 'form-control input-sm']) }}
                        {{ Form::hidden('CA_CASEID', $mCallCase->CA_CASEID, ['class' => 'form-control input-sm']) }}
                        <span id="doc_title_block" style="display: none;" class="help-block"><strong></strong></span>
                    </div>
                </div>

                <div id ="file_field" class="form-group">
                    {{ Form::label('file', 'Fail', ['class' => 'col-sm-3 control-label required']) }}
                    <div class="col-sm-9">
                        {{ Form::file('file') }}
                        <span id="file_block" style="display: none;" class="help-block"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-12" align="center">
                        <input type="button" value="Tambah" id="btnsubmitcreate" class="btn btn-sm btn-success">
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>