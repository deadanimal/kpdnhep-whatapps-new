@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Hari Minggu</h2>
                <div class="ibox-content">
                  
                     <form method="POST" id="search-form" class="form-horizontal">
                       
                            <div class="form-group">
                            {{ Form::label('state_code','Negeri', ['class' => 'col-md-2 control-label']) }}
                      
                                   <div class="col-sm-8">     
                     {{ Form::select('state_code', Ref::GetList('17',true), null, ['class' => 'form-control input-sm', 'id' => 'state_code']) }}
                     </div>
                            </div>
                            <div class="form-group">
                               {{ Form::label('work_day','Hari Berkerja', ['class' => 'col-md-2 control-label']) }}
                      
                                      <div class="col-sm-8">  
                 {{ Form::select('work_day', Ref::GetList('156',true), null, ['class' => 'form-control input-sm', 'id' => 'work_day']) }}
                 </div>
                            </div>
<!--                        <div class="form-group">
                                {{-- Form::label('work_code','Sepenuh Hari/Separa Hari', ['class' => 'col-md-2 control-label']) --}}
                      <div class="col-sm-8">  
                                       
                 {{-- Form::select('work_code', Ref::GetList('146',true), null, ['class' => 'form-control input-sm', 'id' => 'work_code']) --}}
                      </div>
                            </div>-->
                       
                    <div class="form-group" align="center">
                        {{ Form::submit('Carian', array('class' => 'btn btn-primary btn-sm')) }}
                        <a href="{{url('workingday')}}" class="btn btn-default btn-sm">Semula</a>
                    </div>
                     </form>
                    <div class="row">
                        <div class="col-md-9">
                        <a href="{{ url('workingday/create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</a>
                        </div>
                        <br>
                    </div>
                    <table id="org_working_day" class="table table-striped table-bordered table-hover dataTables-example" >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Negeri</th>
                                <th>Hari</th>
                                <!--<th>Full Day/Separa Hari</th>-->
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
        var oTable = $('#org_working_day').DataTable({
//            processing: true,
//            serverSide: true,
//            bFilter: false,
//            aaSorting: [],
////            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
//            dom: '<"top"i>rt<"bottom"flp><"clear">',
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
//            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
            rowId: 'id',
            bStateSave: true,
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod.',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
//            ajax: 'http://localhost:1338/eaduanV2/public/workingday/get_datatable',
            ajax: {
                 url: "{{ url('workingday/getdatatable')}}",
                data: function (d) {
                    d.state_code = $('#state_code').val();
                    d.work_day = $('#work_day').val();
//                    d.work_code = $('#work_code').val();
                    

                }
            },
//            columns: [
//                {data: 'id', name: 'id', 'width': '5%'},
//                {data: 'state_code', name: 'state_code'},
//                {data: 'work_day', name: 'work_day'},
//                {data: 'work_code', name: 'work_code'},
//                {data: 'action', name: 'action', searchable: false, orderable: false}
                 columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                 {data: 'state_code', name: 'state_code'},
                 {data: 'work_day', name: 'work_day'},
//                 {data: 'work_code', name: 'work_code'},
                 {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
            
        });
        
        $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    });
</script>

@stop