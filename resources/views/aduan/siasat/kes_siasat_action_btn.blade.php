<a onclick="ModalKesEdit({{ $SiasatKes->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
    <i class="fa fa-pencil"></i>
</a>
<!--<a href="{{-- route('siasat.destroykessiasat',['id'=>$SiasatKes->id]) --}}" onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus">-->
    <!--<i class="fa fa-trash"></i>-->
<!--</a>-->

<a data-id="{{ $SiasatKes->id }}" class="btn btn-xs btn-danger deletes" data-toggle="tooltip" data-placement="right" title="Hapus">
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
                url: "{{ url('siasat/destroykessiasat') }}" + "/" + id,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#admin-case-kes-table').DataTable({
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
                        },
                        ajax: {
                            url: "{{ url('siasat/getKesSiasat', $SiasatKes->CT_CASEID) }}"
                        },
                        columns: [
                            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                            {data: 'CT_IPNO', name: 'CT_IPNO'},
							{data: 'CT_EPNO', name: 'CT_EPNO'},
                            {data: 'CT_AKTA', name: 'CT_AKTA'},
                            {data: 'CT_SUBAKTA', name: 'CT_SUBAKTA'},
                            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
                        ]
                    });
                    var countakta = parseInt($('input[name="countakta"]').val());
                    countakta = countakta - 1;
                    $('input[name="countakta"]').val(countakta);
                }
            });
        }else{
            return false;
        }
    });
</script>
