@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Parameter</h2>
                <div class="ibox-content">
                    <form method="POST" id="search-form" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="col-sm-2 control-label">Kategori</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="cat" id="cat">
                                    
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="col-sm-2 control-label">Penerangan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="descr" id="descr">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                            <a class="btn btn-default btn-sm" href="{{ url('ref')}}">Semula</a>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-9">
                            <a href="{{ url('ref/createkat')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Kategori</a>
                            <!--<a href="{{-- url('ref/pdf') --}}" class="btn btn-info btn-sm" target="_blank">Contoh PDF</a>-->
                        </div>
                        <br>
                    </div>
                    <div class="table-responsive">
                        <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <!--<th>Kategori</th>-->
                                    <th>Kategori</th>
                                    <th>Penerangan</th>
                                    <!--<th>Susunan</th>-->
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
    </div>
@stop

@section('script_datatable')
<script type="text/javascript">
  $(function() {
        var oTable = $('#users-table').DataTable({
             processing: true,
                serverSide: true,
                bFilter: false,
                aaSorting: [],
//                order: [ 6, 'desc' ],
                pageLength: 50,
                pagingType: "full_numbers",
                dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
                language: {
                    lengthMenu: 'Memaparkan _MENU_ rekod',
                    zeroRecords: 'Tiada rekod ditemui',
                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                    infoEmpty: 'Tiada rekod ditemui',
//                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                    infoFiltered: '',
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir',
                    },
                },
//            ajax: 'http://localhost:1338/eaduanV2/public/ref/get_datatable',
            ajax: {
                url: "{{ url('ref/getdatatablekat')}}",
                data: function (d) {
                    d.cat = $('#cat').val();
                    d.descr = $('#descr').val();
                }
                
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
//                {data: 'cat', name: 'cat'},
                {data: 'code', name: 'code'},
                {data: 'descr', name: 'descr'},
//                {data: 'sort', name: 'sort'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ],
               buttons: [
//                    {extend: 'excel'},
                    {
                        extend: 'excel',
                        title: 'Senarai Parameter',
                        exportOptions: { 
                            orthogonal: 'export'
                        }
                    },
                    {extend: 'pdf'},
                    {extend: 'print',text: 'Cetak',customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    }
                ],
               
        });
        
        $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    });
</script>
@stop