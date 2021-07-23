@extends('layouts.main')
<?php 
    use App\Ref;
    use App\Integriti\IntegritiAdmin;
?>
@section('content')
    <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <h2>Senarai Pengesahan Penutupan (Integriti)</h2>
                    <div class="ibox-content">
                        <form method="POST" id="search-form" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('IN_CASEID', '', ['class' => 'form-control input-sm', 'id' => 'IN_CASEID']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('IN_AGAINSTNM', 'Nama yang Diadu', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('IN_AGAINSTNM', '', ['class' => 'form-control input-sm', 'id' => 'IN_AGAINSTNM']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::text('IN_SUMMARY', '', ['class' => 'form-control input-sm', 'id' => 'IN_SUMMARY']) }}
                                        </div>
                                    </div>
                                     <div class="form-group">
                                           {{ Form::label('IN_IPSTS', 'Status Penyiasatan', ['class' => 'col-sm-4 control-label']) }}
                                        <div class="col-sm-8">
                                            {{ Form::select('IN_IPSTS', IntegritiAdmin::GetStatusList('1370',['03'],true), '', ['class' => 'form-control input-sm', 'id' => 'IN_IPSTS']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                                <a class="btn btn-default btn-sm" href="{{ url('integrititutup')}}">Semula</a>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table style="width: 100%" id="penugasan-table" class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>No. Aduan</th>
                                        <th>Keterangan Aduan</th>
                                        <th>Nama Yang Diadu</th>
                                        <th>Nama Penyiasat</th>
                                        <th>Tarikh Penerimaan</th>
                                        <th>Status Penyiasatan</th>
                                        <!-- <th><center>Hari</center></th> -->
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
<div id="modal-show-invby" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowInvBy'></div>
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
    
function ShowInvBy(id)
{
    $('#modal-show-invby').modal("show").find("#ModalShowInvBy").load("{{ route('carian.showinvby','') }}" + "/" + id);
}

$(function() {
    var PenutupanTable = $('#penugasan-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        pagingType: "full_numbers",
        pageLength: 50,
        dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
        buttons: [
            {
                extend: 'excel',
                title: 'Senarai Pengesahan Penutupan Aduan',
                exportOptions: { 
                    orthogonal: 'export'
                }
            },
            {extend: 'pdf', title: 'ExampleFile'},
            {extend: 'print',text: 'Cetak',customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                }
            }
        ],
        language: {
            lengthMenu: 'Paparan _MENU_ rekod',
            zeroRecords: 'Tiada rekod ditemui',
            info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod.',
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
            url: "{{ url('integrititutup/get_datatable')}}",
            data: function (d) {
                d.IN_CASEID = $('#IN_CASEID').val();
                d.IN_SUMMARY = $('#IN_SUMMARY').val();
                d.IN_AGAINSTNM = $('#IN_AGAINSTNM').val();
                d.IN_RCVDT = $('#IN_RCVDT').val();
                d.IN_IPSTS = $('#IN_IPSTS').val();
            }
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'IN_CASEID', render: function (data, type) {
                return type === 'export' ?
                    "' " + data :
                    data;
            }, name: 'IN_CASEID'},
            {data: 'IN_SUMMARY', name: 'IN_SUMMARY'},
            {data: 'IN_AGAINSTNM', name: 'IN_AGAINSTNM'},
            {data: 'IN_INVBY', name: 'IN_INVBY'},
            {data: 'IN_RCVDT', name: 'IN_RCVDT'},
            {data: 'IN_IPSTS', name: 'IN_IPSTS'},
            // {data: 'tempoh', name: 'tempoh'},
            {data: 'action', name: 'action', searchable: false, orderable: false}
        ]
    });
    
    $('#search-form').on('submit', function(e) {
        PenutupanTable.draw();
        e.preventDefault();
    });
});

</script>
@stop
