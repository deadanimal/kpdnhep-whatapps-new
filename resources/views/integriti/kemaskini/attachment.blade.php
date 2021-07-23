@extends('layouts.main')
<?php
    use App\Ref;
    use App\Aduan\AdminCase;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
</style>
<h2>Lampiran Aduan Integriti (Maklumat Tidak Lengkap)</h2>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="">
            <!-- <a href='{{-- $model->IN_INVSTS == "07" ? route("integritikemaskini.edit", $model->id) : "" --}}'> -->
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">1</span>
                </span>
                MAKLUMAT ADUAN
            </a>
        </li>
        <li class="active">
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">2</span>
                </span>
                LAMPIRAN
            </a>
        </li>
        <li class="">
            <!-- <a href='{{-- $model->IN_INVSTS == "07" ? route("integritikemaskini.preview", $model->id) : "" --}}'> -->
            <a>
                <span class="fa-stack">
                    <span style="font-size: 14px;" class="badge badge-danger">3</span>
                </span>
                SEMAKAN ADUAN
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="panel-body">
                <h4>Senarai Lampiran Aduan Integriti</h4>
                <h4>(Maksimum 5 Lampiran Aduan sahaja)</h4>
                <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                @if($countDoc < 5)
                    <div class="row">
                        <div class="col-sm-12">
                            <a onclick="ModalAttachmentCreate('{{ $model->id }}')" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Lampiran
                            </a>
                        </div>
                    </div>
                <br />
                @endif
                <div class="table-responsive">
                    <table id="doc-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>Nama Fail</th>
                                <th>Catatan</th>
                                <th>Tarikh Muatnaik</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12" align="center">
                        <a class="btn btn-success btn-sm" href="{{ route('integritikemaskini.edit',$model->id) }}"><i class="fa fa-chevron-left"></i> Sebelum</a>
                        <a class="btn btn-warning btn-sm" href="{{ url('integritikemaskini') }}">Kembali</a>
                        <a class="btn btn-success btn-sm" href="{{ route('integritikemaskini.preview',$model->id) }}">Simpan & Seterusnya <i class="fa fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Create Attachment Start -->
<div class="modal fade" id="modal-create-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalCreateContent'></div>
    </div>
</div>
<!-- Modal Edit Attachment Start -->
<div class="modal fade" id="modal-edit-attachment" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id='modalEditContent'></div>
    </div>
</div>
@stop

@section('script_datatable')
    <script type="text/javascript">
        function ModalAttachmentCreate(id) {
            $('#modal-create-attachment')
                .modal("show")
                .find("#modalCreateContent")
                .load("{{ route('integritikemaskinidoc.create','') }}" + "/" + id)
            ;
            return false;
        }
        function ModalAttachmentEdit(id) {
            $('#modal-edit-attachment')
                .modal("show")
                .find("#modalEditContent")
                .load("{{ route('integritikemaskinidoc.edit','') }}" + "/" + id)
            ;
            return false;
        }
        
        $('#doc-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
            bPaginate: false,
            bInfo: false,
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
            },
            ajax: {
                url: "{{ url('integritikemaskinidoc/getdatatable', $model->id) }}"
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'IC_DOCFULLNAME', name: 'IC_DOCFULLNAME'},
                {data: 'IC_REMARKS', name: 'IC_REMARKS'},
                {data: 'IC_UPDATED_AT', name: 'IC_UPDATED_AT'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
            ]
        });
    </script>
@stop
