@extends('layouts.main')

@section('content')

<div class="row">
    <div class="ibox">
        <div class="ibox-content">
            <div class="table-responsive">
                <table id="index-table" class="table table-striped table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <!--<th>No. Aduan</th>-->
                            <!--<th>Kategori Aduan</th>-->
                            <!--<th>Cara Penerimaan</th>-->
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

@stop

@section('script_datatable')
    <script type="text/javascript">
        $(function() {
            var IndexTable = $('#index-table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
//                order: [ 6, 'desc' ],
//                pageLength: 50,
                aLengthMenu: [
                    [25, 50, 100, 200, 500, -1],
                    [25, 50, 100, 200, 500, "Semua"]
                ],
                pagingType: "full_numbers",
                dom: "<'row'<'col-sm-6'i><'col-sm-6 Bfrtip html5buttons'B<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
                language: {
                    lengthMenu: 'Memaparkan _MENU_ rekod',
                    zeroRecords: 'Tiada rekod ditemui',
                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                    infoEmpty: 'Tiada rekod ditemui',
                    infoFiltered: '',
//                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir'
                    }
                },
                ajax: {
                    url: "{{ url('portal-main/getdatatable') }}",
                    data: function (d) {
                        d.search = $('#search').val();
                        d.carian = $('#carian').val();
                        d.carian_text = $('#carian_text').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
//                    {data: 'CA_CMPLCAT', name: 'CA_CMPLCAT', visible: false, orderable: false},
//                    {data: 'CA_RCVTYP', name: 'CA_RCVTYP', visible: false, orderable: false},
//                    {data: 'CA_SUMMARY', name: 'CA_SUMMARY_FULL', orderable: false},
//                    {data: 'CA_SUMMARY_FULL', name: 'CA_SUMMARY_FULL', visible: false, orderable: false},
//                    {data: 'CA_NAME', name: 'CA_NAME', orderable: false},
//                    {data: 'CA_INVBY', name: 'CA_INVBY', orderable: false},
//                    {data: 'CA_SEXCD', name: 'CA_SEXCD', visible: false, orderable: false},
//                    {data: 'CA_NATCD', name: 'CA_NATCD', visible: false, orderable: false},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ],
                buttons: [
                    {extend: 'excel'},
                    {extend: 'pdf'},
                    {extend: 'print',text: 'Cetak',customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    },
                ]
            });

            $('#search-form').on('submit', function(e) {
                $('#search').val(1);
                IndexTable.draw();
                e.preventDefault();
            });
        });
    </script>
@stop