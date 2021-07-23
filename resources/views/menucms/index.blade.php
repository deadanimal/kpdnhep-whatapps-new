@extends('layouts.main')
<?php
    use App\MenuCms;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Menu CMS</h2>
                <div class="ibox-content">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
                        <div class="form-group">
                            {{ Form::label('menu_txt', 'Nama Menu (BM)', ['class' => 'col-md-4 control-label']) }}
                            <div class="col-md-6">
                                {{ Form::text('menu_txt', '', array_merge(['class' => 'form-control input-sm', 'id' => 'menu_txt'])) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('menu_txt_en', 'Nama Menu (BI)', ['class' => 'col-md-4 control-label']) }}
                            <div class="col-md-6">
                                {{ Form::text('menu_txt_en', '', ['class' => 'form-control input-sm', 'id' => 'menu_txt_en']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('menu_parent_id', 'Menu Utama', ['class' => 'col-md-4 control-label']) }}
                            <div class="col-md-6">
                                {{ Form::select('menu_parent_id', MenuCms::MenuList(true), null, ['class' => 'form-control input-sm', 'id' => 'menu_parent_id']) }}
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                            {{ link_to('menucms', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    {!! Form::close() !!}
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('menucms.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Menu CMS</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="menu-cms-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Nama Menu (BM)</th>
                                    <th>Nama Menu (BI)</th>
                                    <th>Menu Utama</th>
                                    <th>Modul</th>
                                    <th>Aktif?</th>
                                    <th>Susunan</th>
                                    <th>Kategori</th>
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
</div>
@stop

@section('script_datatable')
<!-- Page-Level Scripts -->
<script type="text/javascript">
    $(function() {
        var oTable = $('#menu-cms-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
//            bLengthChange: true,
//            dom: '<"top"i>rt<"bottom"flp><"clear">',
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
                url: "{{ url('menucms/getdatatable')}}",
                data: function (d) {
                    d.menu_txt = $('#menu_txt').val();
                    d.menu_txt_en = $('#menu_txt_en').val();
                    d.menu_parent_id = $('#menu_parent_id').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'menu_txt', name: 'menu_txt'},
                {data: 'menu_txt_en', name: 'menu_txt_en'},
                {data: 'menu_parent_id', name: 'menu_parent_id'},
                {data: 'module_ind', name: 'module_ind'},
                {data: 'hide_ind', name: 'hide_ind'},
                {data: 'sort', name: 'sort'},
                {data: 'menu_cat', name: 'menu_cat'},
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