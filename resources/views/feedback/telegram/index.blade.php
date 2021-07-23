@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="content">
                <h2 class="m-b">Maklumbalas Melalui Telegram <i class="fa fa-telgram"></i></h2>
                <div class="box box-primary">
                    <div class="box-body">
                        @include('feedback.telegram.steps', ['step' => 1])
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <table style="width: 100%" id="feedback-table"
                                                   class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>Tarikh</th>
                                                    <th>Mesej Terakhir</th>
                                                    <th>Status</th>
                                                    <th>Tugasan</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script_datatable')
    <script type="text/javascript">
      var FeedbackTable

      function Create() {
        $('#modal-create')
          .modal('show')
          .find('#modalCreateContent')
          .load("{{ route('fail-kes.create') }}")
        return false
      }

      function inactive_thread(id) {
        console.log('id', id)
        if (confirm('Nyahaktifkan perbincangan?')) {
          $.get('/feedback/telegram/' + id + '/inactive', function (data) {
            console.log('data', data)
          }).fail(function (err) {
            console.error('err', err)
          })

          FeedbackTable.ajax.reload()
        }
      }

      $(function () {
        setInterval(function () {
          FeedbackTable.ajax.reload()
        }, 5000)

        FeedbackTable = $('#feedback-table').DataTable({
          processing: true,
          serverSide: true,
          bFilter: false,
          aaSorting: [],
          pagingType: 'full_numbers',
          pageLength: 50,
          dom: '<\'row\'<\'col-sm-6\'i><\'col-sm-6 html5buttons\'<\'pull-right\'l>>>' +
            '<\'row\'<\'col-sm-12\'tr>>' +
            '<\'row\'<\'col-sm-12\'p>>',
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
            url: "{{ url('feedback/telegram/dt')}}",
            data: function (d) {
              d.title = $('#feedback-table #title').val()
              d.remark = $('#feedback-table #remark').val()
            }
          },
          columns: [
            { data: 'DT_Row_Index', name: 'id', 'width': '1%', searchable: false, orderable: false },
            { data: 'name', name: 'name' },
            { data: 'last_message_date', name: 'last_message_date' },
            {
              data: 'last_message', name: 'last_message',
              'render': function (data, type, row) {
                return data.split('_n_').join('<br/>')
              }
            },
            { data: 'status', name: 'status' },
            { data: 'response_id', name: 'response_id' },
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
