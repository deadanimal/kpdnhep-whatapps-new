<a onclick="ModalAttachmentEdit({{ $PenyiasatanDoc->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
    <i class="fa fa-pencil"></i>
</a>

<a data-id="{{ $PenyiasatanDoc->id }}" class="btn btn-xs btn-danger deletes" data-toggle="tooltip" data-placement="right" title="Hapus">
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
                url: "{{ url('integritisiasat/destroyattachsiasat') }}" + "/" + id,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#admin-case-attachment-table').DataTable({
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
                            url: "{{ url('integritisiasat/getAttachmentSiasat', str_replace('/','_',$PenyiasatanDoc->IC_CASEID)) }}"
                        },
                        columns: [
                            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                            {data: 'IC_DOCFULLNAME', name: 'IC_DOCFULLNAME', orderable: false},
                            {data: 'IC_REMARKS', name: 'IC_REMARKS', orderable: false},
                            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
                        ]
                    });
                }
            });
        }else{
            return false;
        }
    });
</script>
