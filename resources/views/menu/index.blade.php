@extends('layouts.main')
<?php use App\Menu;?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Menu</h2>
                <div class="ibox-content">
                    <form method="POST" id="search-form" class="form-horizontal">
                    <div class="form-group">
                            {{ Form::label('Nama Menu', null, ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-6">
                            {{ Form::text('menu_txt', '', array_merge(['class' => 'form-control input-sm', 'id' => 'menu_txt'])) }}
                        </div>
                    </div>
                    <div class="form-group">
                            {{ Form::label('Menu Utama', null, ['class' => 'col-md-4 control-label']) }}
                        <div class="col-sm-6">
                            {{ Form::select('menu_parent_id', Menu::MenuList(true), null, ['class' => 'form-control input-sm', 'id' => 'menu_parent_id']) }}
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                        <a class="btn btn-default btn-sm" id="btnreset" href="{{-- url('menu') --}}">Semula</a>
                    </div>
                    </form>
                    <div class="row">
                        <br>
                        <a href="{{ url('menu/create') }}" class="btn btn-success btn-sm"> Tambah Menu </a>
                    </div>
                    <table id="menu-table" class="table table-striped table-bordered table-hover dataTables-example" >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Menu</th>
                                <th>Menu Utama</th>
                                <th>Modul</th>
                                <th>Aktif?</th>
                                <th>Susunan</th>
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
<!-- Page-Level Scripts -->
<script type="text/javascript">
  $(function() {
              
        var oTable = $('#menu-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pageLength: 50,
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'pull-right'l>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod.',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan dari _MAX_ total rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir'
                }
            },
//            ajax: 'http://localhost:1338/eaduanV2/public/ref/get_datatable',
            ajax: {
                url: "{{ url('menu/getdatatablekat')}}",
                data: function (d) {
                    d.menu_txt = $('#menu_txt').val();
                    d.menu_parent_id = $('#menu_parent_id').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'menu_txt', name: 'menu_txt'},
                {data: 'menu_parent_id', name: 'menu_parent_id'},
                {data: 'module_ind', name: 'module_ind', width: '1%'},
                {data: 'hide_ind', name: 'hide_ind', width: '1%'},
                {data: 'sort', name: 'sort', width: '1%'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });

        $('#search-form').on('submit', function(e) {
//        alert("Berjaya");
        oTable.draw();
        e.preventDefault();
    });
    });
</script>
@stop