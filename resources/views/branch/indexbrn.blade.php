@extends('layouts.main')
<?php
    use App\Ref;
?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Selenggara Cawangan</h2>
                <div class="ibox-content">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('BR_BRNCD', 'Kod Cawangan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('BR_BRNCD', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('BR_BRNNM', 'Nama Cawangan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('BR_BRNNM', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('BR_STATECD', 'Negeri', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('BR_STATECD', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'BR_STATECD']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('BR_STATUS', 'Status', ['class' => 'col-sm-2 control-label']) }}
                                    <div class="col-sm-10">
                                        {{ Form::select('BR_STATUS', Ref::GetList('256', true), null, ['class' => 'form-control input-sm', 'id' => 'BR_STATUS']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                            <a class="btn btn-default btn-sm" href="{{ url('branch')}}">Semula</a>
                        </div>
                    {!! Form::close() !!}
                    <div class="row">
                        <div class="col-md-9">
                            <a href="{{ url('branch/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Cawangan</a>
                        </div>
                        <br>
                    </div>
                    <div class="table-responsive">
                        <table id="branch-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kod Cawangan</th>
                                    <th>Nama</th>
                                    <th>Negeri</th>
                                    <!--<th>Daerah</th>-->
                                    <!--<th>No Telefon</th>-->
                                    <!--<th>No Faks</th>-->
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
    </div>
@stop

@section('script_datatable')
    <script type="text/javascript">
        $(function() {
            var oTable = $('#branch-table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [],
                // bFilter: false, disable search field
                bFilter: false,
//                order: [[ 3, 'asc' ]],
                pagingType: "full_numbers",
                pageLength: 50,
//                bLengthChange: true, enable records selection per page
//                bLengthChange: true,
//                rowId: 'id',
//                bStateSave: true, simpan pilihan paparan rekod, bStateSave: false, reset pilihan paparan rekod
//                bStateSave: false,
                dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
//                dom: '<"top"i>rt<"bottom"flp><"clear">',
//                dom: 'T<"clear">lfrtip',
//                tableTools: {
//                    "sSwfPath": "{{ url('js/plugins/dataTables/swf/copy_csv_xls_pdf.swf') }}"
//                },
                language: {
                    lengthMenu: 'Memaparkan _MENU_ rekod',
                    zeroRecords: 'Tiada rekod ditemui',
                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                    infoEmpty: 'Tiada rekod ditemui',
                    infoFiltered: '(Paparan dari _MAX_ total rekod)',
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        first: 'Pertama',
                        last: 'Terakhir',
                    }
                },
                ajax: {
                    url: "{{ url('branch/get_datatable')}}",
                    data: function (d) {
                        d.BR_BRNCD = $('#BR_BRNCD').val();
                        d.BR_BRNNM = $('#BR_BRNNM').val();
                        d.BR_STATECD = $('#BR_STATECD').val();
                        d.BR_STATUS = $('#BR_STATUS').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                    {data: 'BR_BRNCD', name: 'BR_BRNCD'},
                    {data: 'BR_BRNNM', name: 'BR_BRNNM'},
                    {data: 'BR_STATECD', name: 'BR_STATECD'},
                    {data: 'BR_STATUS', name: 'BR_STATUS'},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ]
            });
        
            $('#search-form').on('submit', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            
            $('#BR_STATECD').on('change', function (e) {
                var BR_STATECD = $(this).val();
                $.ajax({
                    type:'GET',
                    url:"{{ url('branch/getdistlist') }}" + "/" + BR_STATECD,
                    dataType: "json",
                    success:function(data){
                        $('select[name="BR_DISTCD"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="BR_DISTCD"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            });
            
        });
    </script>
@stop