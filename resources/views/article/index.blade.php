@extends('layouts.main')
<?php
    use App\Articles;
    use App\MenuCms;
    use App\Ref;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Artikel</h2>
                <div class="ibox-content">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group" id="data_5">
                                    {{ Form::label('start_dt', 'Tarikh', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        <div class="input-daterange input-group" id="datepicker">
                                            {{ Form::text('start_dt', '', ['class' => 'form-control input-sm', 'id' => 'start_dt']) }}
                                            <span class="input-group-addon">hingga</span>
                                            {{ Form::text('end_dt', '', ['class' => 'form-control input-sm', 'id' => 'end_dt']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('menu_id', 'Menu', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('menu_id', MenuCms::MenuList(true), null, ['class' => 'form-control input-sm', 'id' => 'menu_id']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('cat', 'Kategori', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('cat', Ref::GetList(1258), null, ['class' => 'form-control input-sm', 'id' => 'cat']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('title_my', 'Tajuk (BM)', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('title_my', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('title_en', 'Tajuk (BI)', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('title_en', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                            <a class="btn btn-default btn-sm" href="{{ url('article')}}">Semula</a>
                        </div>
                    {!! Form::close() !!}
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ url('article/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Artikal</a>
                        </div>
                        <br>
                    </div>
                    <div class="table-responsive">
                        <table id="article-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <!--<th>Jenis Menu</th>-->
                                    <th>Tarikh Mula</th>
                                    <th>Tarikh Tamat</th>
                                    <th>Menu</th>
                                    <th>Tajuk (BM)</th>
                                    <th>Tajuk (BI)</th>
                                    <th>Kategori</th>
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
        var oTable = $('#article-table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [],
            // bFilter: false, disable search field
            bFilter: false,
            // bLengthChange: true, enable records selection per page
            bLengthChange: true,
            pagingType: "full_numbers",
//            rowId: 'id',
            // bStateSave: true, simpan pilihan paparan rekod
            bStateSave: true,
//            dom: '<"top"i>rt<"bottom"flp><"clear">',
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" + 
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Harap maaf - Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
//                    previous: "<",
//                    next: ">"
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                }
            },
//            ajax: 'http://localhost:1338/eaduanV2/public/article/get_datatable',
            ajax: {
                url: "{{ url('article/get_datatable')}}",
//                url: 'http://localhost:1338/eaduanV2/public/article/get_datatable',
                data: function (d) {
                    d.menu_id = $('#menu_id').val();
                    d.start_dt = $('#start_dt').val();
                    d.end_dt = $('#end_dt').val();
                    d.title_my = $('#title_my').val();
                    d.title_en = $('#title_en').val();
                    d.cat = $('#cat').val();
                }
            },
            columns: [
//                {data: 'id', name: 'id', 'width': '5%'},
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
//                {data: 'menu_type', name: 'menu_type'},
                {data: 'start_dt', name: 'start_dt'},
                {data: 'end_dt', name: 'end_dt'},
                {data: 'menu_id', name: 'menu_id'},
                {data: 'title_my', name: 'title_my'},
                {data: 'title_en', name: 'title_en'},
                {data: 'cat', name: 'cat'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
        
        $('#search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    
    $('#data_5 .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
//        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
</script>
@stop