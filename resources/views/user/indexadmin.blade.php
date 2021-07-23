@extends('layouts.main')
<?php
    use App\Ref;
    use App\Branch;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Selenggara Pengguna</h2>
            <div class="ibox-content">
                <form method="POST" id="search-form" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('username', 'ID Pengguna (No Kad Pengenalan / Pasport)', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('username', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('name', 'Nama Pengguna', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('status', 'Status', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('status', Ref::GetList('256', true), '-1-', ['class' => 'form-control input-sm', 'id' => 'status']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    @if(substr(Auth::user()->Role->role_code, 0, 1) == 2 || substr(Auth::user()->Role->role_code, 0, 1) == 3)
                                        {{ Form::select('state_cd_disabled', Ref::GetList('17', true), Auth::user()->state_cd, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                                        {{ Form::hidden('Negeri', Auth::user()->state_cd, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                                    @else
                                        {{ Form::select('state_cd', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('brn_cd') ? ' has-error' : '' }}">
                                {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    @if(substr(Auth::user()->Role->role_code, 0, 1) == 2)
                                        {{ Form::select('BahagianCawangan', Branch::GetListByState(Auth::user()->state_cd), null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                                    @elseif(substr(Auth::user()->Role->role_code, 0, 1) == 3)
                                        {{ Form::select('brn_cd_disabled', Branch::GetListByState(Auth::user()->state_cd), Auth::user()->brn_cd, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                                        {{ Form::hidden('BahagianCawangan', Auth::user()->brn_cd, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                                    @else
                                        {{ Form::select('brn_cd', ['' => '-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                                    @endif
                                    @if ($errors->has('brn_cd'))
                                    <span class="help-block"><strong>{{ $errors->first('brn_cd') }}</strong></span>
                                    @endif
                                 </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('role', 'Peranan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::select('role', Ref::GetRole('152', true), null, ['class' => 'form-control input-sm', 'id' => 'role']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                        <a class="btn btn-default btn-sm" id="btnreset" href="{{ url('user') }}">Semula</a>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-9">
                        <a href="{{ route('createadmin') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Pengguna</a>
                    </div>
                    <br>
                </div>
                <div class="table-responsive">
                    <table id="useradmin-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Pengguna (No Kad Pengenalan / Pasport)</th>
                                <th>Nama Pengguna</th>
                                <th>Negeri</th>
                                <th>Cawangan</th>
                                <th>Status</th>
                                <th>Peranan</th>
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
//    $(document).ready(function () {
//        $('.js-example-basic-single').select2();
//    });
    $(function () {
        var oTable = $('#useradmin-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            pageLength: 50,
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"]
            ],
//            bLengthChange: true,
//            bStateSave: true,
            // dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    // "<'row'<'col-sm-12'tr>>" +
                    // "<'row'<'col-sm-12'p>>",
            dom: "<'row'<'col-sm-6'i><'col-sm-6 Bfrtip html5buttons'B<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
//            dom: 'T<"clear">lfrtip',
//            tableTools: {
//                "sSwfPath": "{{ url('js/plugins/dataTables/swf/copy_csv_xls_pdf.swf') }}"
//            },
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
                url: "{{ url('user/getdatatableadmin')}}",
                data: function (d) {
                    d.username = $('#username').val();
                    d.name = $('#name').val();
                    d.state_cd = $('#state_cd').val();
                    d.brn_cd = $('#brn_cd').val();
                    d.status = $('#status').val();
                    d.role = $('#role').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '3%', searchable: false, orderable: false},
                // {data: 'username', name: 'username', 'width': '12%'},
                {
                    data: 'username',
                    name: 'username',
                    'width': '12%',
                    render: function (data, type, row) {
                        return type === 'export' 
                        ? (data ? "' " + data : "") 
                        : data;
                    },
                },
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'status', name: 'status'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ],
            buttons: [
                {
                    extend: 'excel',
                    title: 'Senarai Pengguna eAduan 2.0',
                },
                {
                    extend: 'pdf',
                    title: 'Senarai Pengguna eAduan 2.0',
                },
                {
                    extend: 'print',
                    text: 'Cetak',
                    title: 'Senarai Pengguna eAduan 2.0',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ]
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        
        $('#btnreset').on('click', function (e) {
//            alert("Berjaya");
//            $('#users-table').DataTable({
//                bStateSave: false
//            });
            oTable.order.neutral().draw();
            oTable.page('first');
            oTable.fnSort([]);
            oTable.state.clear();
//            oTable.draw();
            e.preventDefault();
        });
        
        $('#state_cd').on('change', function (e) {
            var state_cd = $(this).val();
            $.ajax({
                type:'GET',
                url:"{{ url('user/getbrnlist') }}" + "/" + state_cd,
                dataType: "json",
                success:function(data){
                    $('select[name="brn_cd"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="brn_cd"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            }); 
        });
    });
</script>
@stop