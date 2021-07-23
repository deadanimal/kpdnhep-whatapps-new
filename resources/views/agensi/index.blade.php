<?php
use App\Ref;
?>
@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Senarai Agensi</h2>
                <div class="ibox-content">
                    <form method="POST" id="search-form" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-group">
                                    {{ Form::label('MI_MINCD', 'Kod', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('MI_MINCD', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('MI_DESC', 'Nama Kementerian/Agensi', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('MI_DESC', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('status', 'Status', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('status', Ref::GetList('256', true), null, ['class' => 'form-control input-sm', 'id' => 'status']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                            <a class="btn btn-default btn-sm" href="{{ url('agensi')}}">Semula</a>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-9">
                            <a href="{{ route('agensi.create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Agensi</a>
                        </div>
                        <br>
                    </div>
                    <div class="table-responsive">
                        <table style="width: 100%" id="agensi-table" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kod</th>
                                    <th>Nama Kementerian / Agensi</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
        var AgensiTable = $('#agensi-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            pageLength: 100,
//            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
            dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
//            dom: 'T<"clear">lfrtip',
//            tableTools: {
//                "sSwfPath": "{{ url('js/plugins/dataTables/swf/copy_csv_xls_pdf.swf') }}"
//            },
//            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
//                {extend: 'copy'},
//                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},
                {extend: 'print',customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ],
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ items.',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('agensi/getdata')}}",
                data: function (d) {
                    d.MI_MINCD = $('#MI_MINCD').val();
                    d.MI_DESC = $('#MI_DESC').val();
                    d.status = $('#status').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'MI_MINCD', name: 'MI_MINCD'},
                {data: 'MI_DESC', name: 'MI_DESC'},
                {data: 'MI_STS', name: 'MI_STS'},
                {data: 'action', name: 'action', width: '7%', searchable: false, orderable: false}
            ]
        });
        
        $('#search-form').on('submit', function(e) {
            AgensiTable.draw();
            e.preventDefault();
        });
    });
</script>
@stop