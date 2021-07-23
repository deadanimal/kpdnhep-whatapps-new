@extends('layouts.main_public')
@section('content')
<?php 
use App\Ref;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <br>
                <form method="POST" id="search-form" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="CA_CASEID" class="col-sm-4 control-label required">@lang('public-case.case.CA_CASEID')</label>
                                <div class="col-sm-8">
                                    {{ Form::text('CA_CASEID', '', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="CA_CASESTS" class="col-sm-4 control-label required">@lang('public-case.case.CA_CASESTS')</label>
                                <div class="col-sm-8">
                                    {{ Form::select('CA_CASESTS', Auth::user()->lang == 'ms' ? Ref::GetList('292', true) : Ref::GetList('292', true, 'en'), null, ['class' => 'form-control input-sm', 'id' => 'CA_CASESTS']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="CA_SUMMARY" class="col-sm-4 control-label required">@lang('public-case.case.CA_SUMMARY')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="CA_SUMMARY" id="descr">
                            </div>
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                        <a class="btn btn-default btn-sm" href="{{ url('public-case/checkcase', Auth::user()->id)}}">Semula</a>
                        <a class="btn btn-default btn-sm" href="{{ url('dashboard')}}">Kembali</a>
                    </div>
                </form>
                <br>
                <table class="table table-striped table-bordered table-hover dataTables-example" id="case-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>@lang('public-case.case.CA_CASEID')</th>
                            <th>@lang('public-case.case.trxcomplaint')</th>
                            <th>@lang('public-case.case.status')</th>
                            <th>@lang('public-case.case.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
  $(function() {
   var oTable = $('#case-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ items.',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(filtered from _MAX_ total records)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('public-case/get_datatable',$mUser->username)}}",
                data: function (d) {
                    d.CA_CASEID = $('#CA_CASEID').val();
                    d.CA_SUMMARY = $('#CA_SUMMARY').val();
                    d.CA_CASESTS = $('#CA_CASESTS').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CA_CASEID', name: 'CA_CASEID'},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                {data: 'CA_CASESTS', name: 'CA_CASESTS'},
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

