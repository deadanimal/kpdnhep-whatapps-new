@extends('layouts.main')
<?php

use App\Ref;
use App\Menu;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Peranan</h2>
                <div class="ibox-content">
                    <form method="POST" id="search-form" class="form-horizontal">
                        <div class="form-group">
                            {{ Form::label('Peranan', null, ['class' => 'col-md-4 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::select('role_code', Ref::GetList('152', true), null, ['class' => 'form-control input-sm', 'id' => 'role_code']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('Menu', null, ['class' => 'col-md-4 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::select('menu_id', Menu::MenuList(true), null, ['class' => 'form-control input-sm', 'id' => 'menu_id']) }}
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                            <a class="btn btn-default btn-sm" id="btnreset" href="{{-- url('role') --}}">Semula</a>
                        </div>
                    </form>
                    <div class="row">
                        <br>
                        <a href="{{ url('role/create') }}" class="btn btn-success btn-sm"> Tambah Peranan </a>
                    </div>
                    <table id="role-table" class="table table-striped table-bordered table-hover dataTables-example" >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Peranan</th>
                                <th>Menu</th>
                                <th>Susunan Menu</th>
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
    $(function () {
        var oTable = $('#role-table').DataTable({
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
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('role/getdatatable')}}",
                data: function (d) {
                    d.role_code = $('#role_code').val();
                    d.menu_id = $('#menu_id').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'role_code', name: 'role_code'},
                {data: 'menu_id', name: 'menu_id'},
                {data: 'sort', name: 'sort'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
</script>
@stop