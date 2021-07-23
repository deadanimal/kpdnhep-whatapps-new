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
<h2>Kemaskini Aduan (Maklumat Tidak Lengkap)</h2>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="">
            <a href='{{ $model->CA_INVSTS == "7" ? route("kemaskini.edit", $model->CA_CASEID) : "" }}'>
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
            <a href='{{ $model->CA_INVSTS == "7" ? route("kemaskini.preview", $model->CA_CASEID) : "" }}'>
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
                <h4>Senarai Lampiran Aduan</h4>
                <h4>(Maksimum 5 Lampiran Aduan sahaja)</h4>
                <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                @if($countDoc < 5)
                    <div class="row">
                        <div class="col-sm-12">
                            <a onclick="ModalAttachmentCreate('{{ $model->CA_CASEID }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Lampiran</a>
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
                        <a class="btn btn-success btn-sm" href="{{ route('kemaskini.edit',$model->CA_CASEID) }}"><i class="fa fa-chevron-left"></i> Sebelum</a>
                        <a class="btn btn-warning btn-sm" href="{{ url('kemaskini') }}">Kembali</a>
                        <a class="btn btn-success btn-sm" href="{{ route('kemaskini.preview',$model->CA_CASEID) }}">Simpan & Seterusnya <i class="fa fa-chevron-right"></i></a>
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
            $('#modal-create-attachment').modal("show").find("#modalCreateContent").load("{{ route('kemaskini-doc.create','') }}" + "/" + id);
            return false;
        }
        function ModalAttachmentEdit(id) {
            $('#modal-edit-attachment').modal("show").find("#modalEditContent").load("{{ route('kemaskini-doc.edit','') }}" + "/" + id);
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
                url: "{{ url('kemaskini-doc/getdatatable', $model->CA_CASEID) }}"
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
                {data: 'CC_REMARKS', name: 'CC_REMARKS'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
            ]
        });
    </script>
@stop
