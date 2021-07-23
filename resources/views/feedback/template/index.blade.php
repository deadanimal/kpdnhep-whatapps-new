@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Senarai Template Feedback
                    <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('feedback.template.create') !!}">Add New</a>
                </h2>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table style="width: 100%" id="feedback-table"
                               class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kategori</th>
                                <th>Kod</th>
                                <th>Tajuk</th>
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

      function Create() {
        $('#modal-create').modal('show').find('#modalCreateContent').load("{{ route('fail-kes.create') }}")
        return false
      }

      $(function () {
        var FeedbackTable = $('#feedback-table').DataTable({
          processing: true,
          serverSide: true,
          bFilter: false,
          aaSorting: [],
          pagingType: 'full_numbers',
          pageLength: 50,
//            order: [[ 3, 'asc' ]],
//            bLengthChange: false,
          dom: '<\'row\'<\'col-sm-6\'i><\'col-sm-6 html5buttons\'<\'pull-right\'l>>>' +
            '<\'row\'<\'col-sm-12\'tr>>' +
            '<\'row\'<\'col-sm-12\'p>>',
//            dom: 'T<"clear">lfrtip',
//            tableTools: {
//                "sSwfPath": "{{ url('js/plugins/dataTables/swf/copy_csv_xls_pdf.swf') }}"
//            },
//            dom: '<"html5buttons"B>lTfgitp',
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
              last: 'Terakhir'
            }
          },
          ajax: {
            url: "{{ url('feedback/template/dt')}}",
            data: function (d) {
              d.title = $('#feedback-table #title').val()
              d.remark = $('#feedback-table #remark').val()
            }
          },
          columns: [
            { data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false },
            { data: 'category', name: 'category' },
            { data: 'code', name: 'code' },
            { data: 'title', name: 'title' },
            { data: 'action', name: 'action', width: '9%', searchable: false, orderable: false }
          ]
        })

        $('#search-form').on('submit', function (e) {
          FeedbackTable.draw()
          e.preventDefault()
        })
      })

    </script>
@stop
