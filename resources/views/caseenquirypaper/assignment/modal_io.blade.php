<div class="modal inmodal fade" id="modalIo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Tutup</span>
                </button>
                <p class="modal-title">Senarai Pegawai Penyiasat IO</p>
            </div>
            <div class="modal-body">
                <form id="io-search-form" role="form">
                    <div class="row">
                        <!-- Nama Pegawai Penyiasat IO Field -->
                        <div class="form-group col-sm-6">
                            {{ Form::label('io_name', 'Nama Pegawai Penyiasat IO:') }}
                            {{ Form::text('io_name', null, ['class' => 'form-control', 'placeholder' => 'Carian Nama Pegawai Penyiasat IO']) }}
                        </div>

                        <!-- Nombor Kad Pengenalan Field -->
                        <div class="form-group col-sm-6">
                            {{ Form::label('io_icnumber', 'Nombor Kad Pengenalan:') }}
                            {{ Form::text('io_icnumber', null, ['class' => 'form-control', 'placeholder' => 'Carian Nombor Kad Pengenalan']) }}
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
                    <table id="modal-io-table" class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>No. Kad Pengenalan</th>
                                <th>Nama</th>
                                <th>Negeri</th>
                                <th>Cawangan</th>
                                <th>Tindakan</th>
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