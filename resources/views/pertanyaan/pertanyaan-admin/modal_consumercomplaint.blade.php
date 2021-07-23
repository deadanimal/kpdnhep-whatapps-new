<div class="modal inmodal fade" id="modalConsumerComplaint" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Tutup</span>
                </button>
                <p class="modal-title">Senarai Aduan Kepenggunaan</p>
            </div>
            <div class="modal-body">
                <form id="consumercomplaint-search-form" role="form">
                    <div class="row">
                        <!-- Nombor Aduan Kepenggunaan Field -->
                        <div class="form-group col-sm-6">
                            {{ Form::label('CA_CASEID', 'Nombor Aduan Kepenggunaan:') }}
                            {{ Form::text('CA_CASEID', null, ['class' => 'form-control', 'placeholder' => 'Carian Nombor Aduan Kepenggunaan']) }}
                        </div>

                        <!-- Nama Pegawai Penyiasat Field -->
                        <div class="form-group col-sm-6">
                            {{ Form::label('investigator', 'Nama Pegawai Penyiasat:') }}
                            {{ Form::text('investigator', null, ['class' => 'form-control', 'placeholder' => 'Carian Nama Pegawai Penyiasat']) }}
                        </div>
                    </div>
                    <div class="row" style="display: none;">
                        <!-- Status Carian Field -->
                        <div class="form-group col-sm-6">
                            {{ Form::label('search_status', 'Status Carian:') }}
                            {{ Form::hidden('search_status', 0, ['class' => 'form-control input-sm']) }}
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
                    <table id="modal-consumercomplaint-table" class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>Nombor Aduan Kepenggunaan</th>
                                <th>Jawapan Kepada Pengadu</th>
                                <th>Nama Pegawai Penyiasat</th>
                                <th>Tarikh Terima</th>
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