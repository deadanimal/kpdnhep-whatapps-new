
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <table class="table table-striped table-bordered table-hover dataTables-example" id="case-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aduan</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('javascript')
<script type="text/javascript">
  $(function() {
   var oTable = $('#case-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            rowId: 'id',
//            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
//            dom: 'T<"clear">lfrtip',
//            tableTools: {
//                "sSwfPath": "{{ url('js/plugins/dataTables/swf/copy_csv_xls_pdf.swf') }}"
//            },
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ items.',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(filtered from _MAX_ total records)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
//            ajax: 'http://localhost:1338/eaduanV2/public/ref/get_datatable',
            ajax: {
                url: "{{ url('public-case/get_datatable',$mUser->username)}}",
                data: function (d) {
//                    d.descr = $('#descr').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                {data: 'CA_CASESTS', name: 'CA_CASESTS'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
        
        $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    });
</script>

