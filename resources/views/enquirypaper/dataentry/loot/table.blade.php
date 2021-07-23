<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="table-responsive">
                    <h5>Senarai @yield('page_title')</h5>
                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" onclick="lootCreateModal('{{ $enquiryPaperCaseId }}')">
                        <i class="fa fa-plus"></i> Tambah Barang Rampasan
                    </button>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataentry-loot-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>Nombor Fail Kes EP</th>
                                <th>ID Barang Rampasan</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ route('caseenquirypaper.dataentries.processes.edit', [$enquiryPaperCaseId, 2]) }}">
                        <i class="fa fa-chevron-left"></i> Sebelum
                    </a>
                    <a class="btn btn-success" href="{{ route('enquirypaper.dataentry.step4.edit', [$enquiryPaperCaseId]) }}">
                        Seterusnya <i class="fa fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
