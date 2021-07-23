@extends('layouts.main')

@section('content')
<h2>Selenggara Keterangan Portal CMS</h2>
<div class="ibox">
    <div class="ibox-content">
        <div class="table-responsive">
            <table id="portal-cms-table" class="table table-striped table-bordered table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>Bil.</th>
                        <th>Seksyen</th>
                        <th>Bil. Kandungan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('script_datatable')
<script type="text/javascript">
    $(function() {
        var oTable = $('#portal-cms-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            paging: false,
            info: false,
            aaSorting: [],
            bLengthChange: false,
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" + 
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
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
                url: "{{ url('portalcms/getdatatable') }}",
                data: function (d) {
                    d.title_my = $('#title_my').val();
                    d.title_en = $('#title_en').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'descr', name: 'descr'},
                {data: 'Bilangan', name: 'Bilangan'},
                {data: 'action', name: 'action', width: '5%', searchable: false, orderable: false}
            ]
        });

        $('#search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });
</script>
@stop