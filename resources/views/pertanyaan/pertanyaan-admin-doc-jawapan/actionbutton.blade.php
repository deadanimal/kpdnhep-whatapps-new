<a onclick="ModalAttachmentEdit({{ $PertanyaanAdminDoc->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
    <i class="fa fa-pencil"></i>
</a>

<a data-id="{{ $PertanyaanAdminDoc->id }}" class="btn btn-xs btn-danger deletes" data-toggle="tooltip" data-placement="right" title="Hapus">
    <i class="fa fa-trash"></i>
</a>

<script type="text/javascript">
    $('.deletes').on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).data('id');
        var Confirm = confirm('Anda pasti untuk hapuskan rekod ini?');
        if(Confirm) {
            $.ajax({
                type : "GET",
                url: "{{ url('pertanyaan-admin-doc-jawapan/delete') }}" + "/" + id,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#pertanyaan-admin-doc-jawapan-table').DataTable({
                        destroy: true,
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
                            paginate: {
                                previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                                next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                                first: 'Pertama',
                                last: 'Terakhir'
                            }
                        },
                        ajax: {
                            url: "{{ url('pertanyaan-admin-doc-jawapan/getdatatable', $PertanyaanAdminDoc->askid) }}"
                        },
                        columns: [
                            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                            {data: 'img_name', name: 'img_name'},
                            {data: 'remarks', name: 'remarks'},
                            {data: 'updated_at', name: 'updated_at'},
                            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
                        ]
                    });
                }
            });
        } else {
            return false;
        }
    });
</script>
