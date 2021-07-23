@extends('layouts.main')
<?php
use App\Ref;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>Selenggara Pengguna Awam</h2>
            <div class="ibox-content">
                <form method="POST" id="search-form" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('username', 'No.Kad Pengenalan / Passport', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('username', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('email', 'Emel', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('email', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('age', 'Umur', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('age', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('citizen', 'Kerakyatan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('citizen', Ref::GetList('199', true), null, ['class' => 'form-control input-sm', 'id' => 'citizen']) }}
                                </div>
                            </div>
                            <div class="form-group" id="date">
                                {{ Form::label('', 'Tarikh Daftar', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    <div class="input-daterange input-group" id="datepicker">
                                        {{ Form::text('created_at_from', '', ['class' => 'form-control input-sm', 'id' => 'created_at_from']) }}
                                        <span class="input-group-addon">hingga</span>
                                        {{ Form::text('created_at_to', '', ['class' => 'form-control input-sm', 'id' => 'created_at_to']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('name', 'Nama', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('state_cd', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('gender', 'Jantina', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('gender', Ref::GetList('202', true), null, ['class' => 'form-control input-sm', 'id' => 'gender']) }}
                                </div>
                            </div>
                            <div class="form-group">
                            <?php echo Form::label('status', NULL, ['class' => 'col-sm-4 control-label']); ?>
                                <div class="col-sm-8">
                                {{ Form::select('status', Ref::GetList('256', true), '-1-', ['class' => 'form-control input-sm', 'id' => 'status']) }}
                                </div>
                            </div>
                        </div>
<!--                        <div class="col-sm-6">
                            <div class="form-group">
                            <?php // echo Form::label('status', NULL, ['class' => 'col-sm-4 control-label']); ?>
                                <div class="col-sm-8">
                                {{-- Form::select('status', Ref::GetList('256', true), null, ['class' => 'form-control input-sm', 'id' => 'status']) --}}
                                </div>
                            </div>
                        </div>-->
<!--                        <div class="col-sm-6">
                            <label class="col-sm-2 control-label">Penerangan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="descr" id="descr">
                            </div>
                        </div>-->
                    </div>
                    <div class="form-group" align="center">
                        <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                        <a class="btn btn-default btn-sm" id="btnreset" href="{{-- url('publicuser') --}}">Semula</a>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-9">
                        <a href="{{ route('createpublic') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Pengguna Awam</a>
                    </div>
                    <br>
                </div>
                <div class="table-responsive">
                    <table id="userpublic-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>Nama</th>
                                <th>ID Pengguna (No Kad Pengenalan / Pasport)</th>
                                <th>Emel</th>
                                <th>Jantina</th>
                                <th>Umur</th>
                                <th>Negara Asal</th>
                                <th>No. Telefon (Bimbit)</th>
                                <th>No. Telefon (Pejabat)</th>
                                <th>Poskod</th>
                                <th>Negeri</th>
                                <th>Daerah</th>
                                <th>Jumlah Aduan</th>
                                <th>Status</th>
                                <th>Tarikh Daftar</th>
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
    
    $('#date .input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
    
    $(function () {
        var oTable = $('#userpublic-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
//            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
//            rowId: 'id',
//            bStateSave: true,
            dom: "<'row'<'col-sm-6'i><'col-sm-6 Bfrtip html5buttons'B<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
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
                url: "{{ url('publicuser/getdatatablepublic')}}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.username = $('#username').val();
                    d.status = $('#status').val();
                    d.email = $('#email').val();
                    d.state_cd = $('#state_cd').val();
                    d.gender = $('#gender').val();
                    d.age = $('#age').val();
                    d.citizen = $('#citizen').val();
                    d.created_at_from = $('#created_at_from').val();
                    d.created_at_to = $('#created_at_to').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'name', name: 'name'},
                {data: 'username', name: 'username'},
                {data: 'email', name: 'email'},
                {data: 'gender', name: 'gender', visible: false},
                {data: 'age', name: 'age', visible: false},
                {data: 'ctry_cd', name: 'ctry_cd', visible: false},
                {data: 'mobile_no', name: 'mobile_no', visible: false},
                {data: 'office_no', name: 'office_no', visible: false},
                {data: 'postcode', name: 'postcode', visible: false},
                {data: 'state_cd', name: 'state_cd', visible: false},
                {data: 'distrinct_cd', name: 'distrinct_cd', visible: false},
                {data: 'jumlahaduan', name: 'jumlahaduan'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ],
            buttons: [
                {extend: 'colvis', text: 'Paparan Medan'}
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
    });
</script>
@stop