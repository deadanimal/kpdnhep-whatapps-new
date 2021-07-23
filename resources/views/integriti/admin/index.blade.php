@extends('layouts.main')
<?php
    use App\Ref;
    use App\Aduan\AdminCase;
?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Senarai Aduan Integriti</h2>
                <!-- {{-- @include('nota') --}} -->
                <div class="ibox-content">
                    {{ Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) }}
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('IN_CASEID', '', ['class' => 'form-control input-sm','id' => 'IN_CASEID']) }}
                                        {{ Form::hidden('SEARCH', 1, ['class' => 'form-control input-sm', 'id' => 'SEARCH']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('IN_SUMMARY', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                               <!-- <div class="form-group"> -->
                                    <!-- {{-- Form::label('CA_RCVDT', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) --}} -->
                                    <!-- <div class="col-sm-8"> -->
                                        <!-- {{-- Form::text('CA_RCVDT', '', ['class' => 'form-control input-sm']) --}} -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <div class="form-group" id="date">
                                    {{ Form::label('IN_RCVDT_FROM', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <div class="input-daterange input-group" id="datepicker">
                                            {{ Form::text('IN_RCVDT_FROM', '', ['class' => 'form-control input-sm', 'id' => 'IN_RCVDT_FROM']) }}
                                            <span class="input-group-addon">hingga</span>
                                            {{ Form::text('IN_RCVDT_TO', '', ['class' => 'form-control input-sm', 'id' => 'IN_RCVDT_TO']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_NAME', 'Nama Pengadu', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('IN_NAME', '', ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <!-- <div class="form-group"> -->
                                    <!-- {{-- Form::label('CA_INVSTS', 'Status Aduan', ['class' => 'col-sm-4 control-label']) --}} -->
                                    <!-- <div class="col-sm-8"> -->
                                        <!-- {{-- Form::select('CA_INVSTS', AdminCase::getinvstslist('292', true), null, ['class' => 'form-control input-sm', 'id' => 'CA_INVSTS']) --}} -->
                                    <!-- </div> -->
                                <!-- </div> -->
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                            {{ link_to('integritiadmin', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    {{ Form::close() }}
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ url('integritiadmin/create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Aduan Baru</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="admin-case-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <!-- <th><center>Hari</center></th> -->
                                    <th>No. Aduan</th>
                                    <th>Keterangan Aduan</th>
                                    <th>Nama Pengadu</th>
                                    <th>Status Aduan</th>
                                    <!--<th>Status Perkembangan</th>-->
                                    <th>Tarikh Penerimaan</th>
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
    <!-- Modal Start -->
    <div id="modal-show-summary-integriti" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id='ModalShowSummaryIntegriti'></div>
        </div>
    </div>
    <!-- Modal End -->
@stop

@section('script_datatable')
    <script type="text/javascript">
        
        function showsummaryintegriti(id)
        {
            $('#modal-show-summary-integriti')
                .modal("show")
                .find("#ModalShowSummaryIntegriti")
                .load("{{ route('integritibase.showsummary','') }}" + "/" + id);
        }

        $(function() {
            var oTable = $('#admin-case-table').DataTable({
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
                ajax: {
                    url: "{{ url('integritiadmin/getdatatablecase') }}",
                    data: function (d) {
                        d.IN_CASEID = $('#IN_CASEID').val();
                        d.IN_SUMMARY = $('#IN_SUMMARY').val();
                        d.IN_NAME = $('#IN_NAME').val();
                        d.IN_RCVDT_FROM = $('#IN_RCVDT_FROM').val();
                        d.IN_RCVDT_TO = $('#IN_RCVDT_TO').val();
                        d.IN_INVSTS = $('#IN_INVSTS').val();
                        d.SEARCH = $('#SEARCH').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                    // {data: 'tempoh', name: 'tempoh'},
                    {data: 'IN_CASEID', name: 'IN_CASEID'},
                    {data: 'IN_SUMMARY', name: 'IN_SUMMARY'},
                    {data: 'IN_NAME', name: 'IN_NAME'},
                    {data: 'IN_INVSTS', name: 'IN_INVSTS'},
                    {data: 'IN_RCVDT', name: 'IN_RCVDT'},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ],
                buttons: [
//                    {extend: 'excel'},
                    {
                        extend: 'excel',
                        title: 'Senarai Aduan',
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
                ]
            });

            $('#search-form').on('submit', function(e) {
                $('#SEARCH').val(1);
                oTable.draw();
                e.preventDefault();
            });
        });
        
        $('#CA_RCVDT').datepicker({
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });
        
        $('#date .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });
    </script>
@stop