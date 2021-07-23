<div class="modal inmodal fade" id="case-act-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Tutup</span>
                </button>
                <p class="modal-title">Carian Nombor Aduan Kepenggunaan</p>
            </div>
            <div class="modal-body">
                <form id="search-form" role="form">
                    <div class="row">
                        <!-- Nombor Aduan Kepenggunaan Field -->
                        <div class="form-group col-sm-6">
                            {{ Form::label('CT_CASEID', 'Nombor Aduan Kepenggunaan:') }}
                            {{ Form::text('CT_CASEID', null, ['class' => 'form-control', 'placeholder' => 'Carian Nombor Aduan Kepenggunaan']) }}
                        </div>

                        <!-- Nombor Fail Kes EP Field -->
                        <div class="form-group col-sm-6">
                            {{ Form::label('CT_EPNO', 'Nombor Fail Kes EP:') }}
                            {{ Form::text('CT_EPNO', null, ['class' => 'form-control', 'placeholder' => 'Carian Nombor Fail Kes EP']) }}
                        </div>
                    </div>
                    <div class="row">
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12 m-b-none text-center">
                            {{ Form::button('Semula '.' <i class="fa fa-refresh"></i>', ['type' => 'reset', 'class' => 'btn btn-default', 'id' => 'buttonresetform']) }}
                            {{ Form::button('Carian '.' <i class="fa fa-search"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) }}
                        </div>
                    </div>
                </form>
                <hr class="hr-line-solid">
                <div class="table-responsive">
                    <table id="ep-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>No. Aduan</th>
                                <th>No. EP</th>
                                <th>Akta</th>
                                <th>Jenis Kesalahan</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>