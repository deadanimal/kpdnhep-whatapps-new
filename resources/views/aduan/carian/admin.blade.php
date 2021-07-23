@extends('layouts.main')
<?php
use App\Ref;
use App\Branch;
?>
@section('content')
    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
    <div class="row">
        <!--<div class="ibox">-->
        <h2>Carian Ikut Kriteria</h2>
        <div class="ibox-content" style="padding-bottom: 0px">
            <div class="form-group" style="margin-bottom: 0px">
                <div class="col-sm-6">
                    <div class="form-group">
                        {{ Form::label('carian', 'Carian', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            {{ Form::select('carian', Ref::GetList('1068', true), null, ['class' => 'form-control input-sm', 'id' => 'carian']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('tarikh', 'Tarikh', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            {{ Form::select('tarikh', Ref::GetList('1083', true, 'ms'), null, ['class' => 'form-control input-sm', 'id' => 'tarikh']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            {{ Form::select('state_cd', Ref::GetList('17', true), null, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('searchfield', 'Carian Medan', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-9">
                            {{ Form::select('searchfield', Ref::GetList('1134', true), null, ['class' => 'form-control input-sm', 'id' => 'searchfield']) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <!--<div class="col-sm-12">-->
                    {{ Form::text('carian_text', '', ['class' => 'form-control input-sm', 'id' => 'carian_text']) }}
                    {{ Form::hidden('search', 1, ['class' => 'form-control input-sm', 'id' => 'search']) }}
                    <!--</div>-->
                    </div>
                    <div class="form-group">
                        <div class="input-daterange input-group col-sm-12" id="datepicker">
                            {{ Form::text('date_start', '', ['class' => 'form-control input-sm', 'id' => 'date_start']) }}
                            <span class="input-group-addon">hingga</span>
                            {{ Form::text('date_end', '', ['class' => 'form-control input-sm', 'id' => 'date_end']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::select('brn_cd', [''=>'-- SILA PILIH --'], null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--</div>-->
    </div>

    <div class="row">
        <select id="selectBox" name="BR_OTHDIST[]" class="form-control dual_select" multiple>
            <!--<option value="">-- Sila Pilih Carian Medan --</option>-->
            <!--<option value="12" selected="selected">Test</option>-->
        </select>
    </div>
    <br>
    <div class="row">
        <div class="ibox">
            <div class="ibox-content">
                <div class="form-group" align="center" style="margin-bottom: 0px">
                    {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                    {{ link_to('carian/admin', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    @include('nota')

    <div class="row">
        <div class="ibox">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="admin-table" class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Hari</th>
                            <th>No. Aduan</th>
                            <th>Kategori Aduan</th>
                            <th>Subkategori Aduan</th>
                            <th>Jenis Barangan</th>
                            <th>Jenis Premis</th>
                            <th>Cara Penerimaan</th>
                            <th>Aduan</th>
                            <th>Aduan Penuh</th>
                            <th>Nama Pengadu</th>
                            <th>No Pengenalan Pengadu</th>
                            <th>Umur Pengadu</th>
                            <th>Nama Diadu</th>
                            <th>Pembekal Perkhidmatan</th>
                            <th>Cara Pembayaran</th>
                            <th>Nama Bank</th>
                            <th>No. Akaun Bank</th>
                            <th>Nama Penyiasat</th>
                            <th>Jantina</th>
                            <th>Warganegara</th>
                            <th>Negara</th>
                            <th>Status Pengadu</th>
                            <th>Status Aduan</th>
                            <th>Tarikh Penerimaan</th>
                            <th>Tarikh Selesai</th>
                            <th>Tarikh Penutupan</th>
                            <th>Negeri</th>
                            <th>Cawangan</th>
                            <th>No. Rujukan Fail</th>
                            <th>Hasil Siasatan</th>
                            <th>Jawapan Kepada Pengadu</th>
                            <th>Lanjutan Siasatan (IP / EP)</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Start -->
    <div id="modal-show-summary" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id='ModalShowSummary'></div>
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
      $('.dual_select').bootstrapDualListbox({
        selectorMinimalHeight: 100,
//            nonSelectedListLabel: 'Sedia untuk digunakan',
//            selectedListLabel: 'Dipilih',
//            preserveSelectionOnMove: 'moved',
//            moveOnSelect: false,
        showFilterInputs: true,
        filterPlaceHolder: 'Carian',
        infoText: false
      })

      $(document).ready(function () {

        $('#todo, #inprogress, #completed').sortable({
          connectWith: '.connectList',
          update: function (event, ui) {
            console.log(event)
//                    var todo = $( "#todo" ).sortable();
//                    var inprogress = $( "#inprogress" ).sortable( "toArray" );
//                    var completed = $( "#completed" ).sortable( "toArray" );
//                    $('.output').html("ToDo: " + window.JSON.stringify(todo) + "<br/>" + "In Progress: " + window.JSON.stringify(inprogress));
          }
        }).disableSelection()

      })

      function ShowSummary(CASEID) {
        $('#modal-show-summary').modal('show').find('#ModalShowSummary').load("{{ route('tugas.showsummary','') }}" + '/' + CASEID)
        document.getElementById(CASEID).style.color = 'purple'
      }

      function ShowInvBy(id) {
        $('#modal-show-invby').modal('show').find('#ModalShowInvBy').load("{{ route('carian.showinvby','') }}" + '/' + id)
      }

      $(function () {
        var AdminTable = $('#admin-table').DataTable({
          scrollY: 500,
          scrollX: true,
          // responsive: true,
          processing: true,
          serverSide: true,
          bFilter: false,
          aaSorting: [],
//                order: [ 6, 'desc' ],
//                pageLength: 50,
          aLengthMenu: [
            [ 25, 50, 100, 200, 500 ],
            [ 25, 50, 100, 200, 500 ]
          ],
          pagingType: 'full_numbers',
          dom: '<\'row\'<\'col-sm-6\'i><\'col-sm-6 Bfrtip html5buttons\'B<\'pull-right\'l>>>' +
            '<\'row\'<\'col-sm-12\'tr>>' +
            '<\'row\'<\'col-sm-12\'p>>',
          language: {
            lengthMenu: 'Memaparkan _MENU_ rekod',
            zeroRecords: 'Tiada rekod ditemui',
            info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
            infoEmpty: 'Tiada rekod ditemui',
            infoFiltered: '',
//                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
            paginate: {
              previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
              next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
              first: 'Pertama',
              last: 'Terakhir'
            }
          },
          ajax: {
            url: "{{ url('carian/getdatatableadmin') }}",
            data: function (d) {
              d.search = $('#search').val()
              d.carian = $('#carian').val()
              d.carian_text = $('#carian_text').val()
              d.tarikh = $('#tarikh').val()
              d.date_start = $('#date_start').val()
              d.date_end = $('#date_end').val()
              d.state_cd = $('#state_cd').val()
              d.brn_cd = $('#brn_cd').val()
              d.test = $('#selectBox').val()
//                        d.test = $( "#inprogress" ).sortable( "toArray" );
//                        d.sexcd = GetElementInsideContainer("inprogress", "CA_SEXXCD").val();
            }
          },
//                aoColumnDefs: [
//                    { sType: "html", aTargets: [ 1 ] }
//                  ],
          columns: [
            { data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false },
            { data: 'tempoh', name: 'tempoh', orderable: false },
            {
              data: 'CA_CASEID', render: function (data, type, row) {
                return type === 'export' ?
                  '\' ' + data :
                  data
              }, orderable: false
            },
            { data: 'CA_CMPLCAT', name: 'CA_CMPLCAT', visible: false, orderable: false },
            { data: 'CA_CMPLCD', name: 'CA_CMPLCD', visible: false, orderable: false },
            { data: 'CA_CMPLKEYWORD', name: 'CA_CMPLKEYWORD', visible: false, orderable: false },
            { data: 'CA_AGAINST_PREMISE', name: 'CA_AGAINST_PREMISE', visible: false, orderable: false },
            { data: 'CA_RCVTYP', name: 'CA_RCVTYP', visible: false, orderable: false },
            { data: 'CA_SUMMARY', name: 'CA_SUMMARY_FULL', orderable: false },
            { data: 'CA_SUMMARY_FULL', name: 'CA_SUMMARY_FULL', visible: false, orderable: false },
            { data: 'CA_NAME', name: 'CA_NAME', orderable: false },
            { data: 'CA_DOCNO', name: 'CA_DOCNO', visible: false, orderable: false },
            { data: 'CA_AGE', name: 'CA_AGE', visible: false, orderable: false },
            { data: 'CA_AGAINSTNM', name: 'CA_AGAINSTNM', orderable: false },
            { data: 'CA_ONLINECMPL_PROVIDER', name: 'CA_ONLINECMPL_PROVIDER', visible: false, orderable: false },
            { data: 'CA_ONLINECMPL_PYMNTTYP', name: 'CA_ONLINECMPL_PYMNTTYP', visible: false, orderable: false },
            { data: 'CA_ONLINECMPL_BANKCD', name: 'CA_ONLINECMPL_BANKCD', visible: false, orderable: false },
            {
              data: 'CA_ONLINECMPL_ACCNO', name: 'CA_ONLINECMPL_ACCNO',
              render: function (data, type, row) {
                return type === 'export' ?
                  (data ? '\' ' + data : '') :
                  data
              },
              visible: false, orderable: false
            },
            { data: 'CA_INVBY', name: 'CA_INVBY', orderable: false },
            { data: 'CA_SEXCD', name: 'CA_SEXCD', visible: false, orderable: false },
            { data: 'CA_NATCD', name: 'CA_NATCD', visible: false, orderable: false },
            { data: 'CA_COUNTRYCD', name: 'CA_COUNTRYCD', visible: false, orderable: false },
            { data: 'CA_STATUSPENGADU', name: 'CA_STATUSPENGADU', visible: false, orderable: false },
            { data: 'CA_INVSTS', name: 'CA_INVSTS', orderable: false },
            { data: 'CA_RCVDT', name: 'CA_RCVDT', orderable: false },
            { data: 'CA_COMPLETEDT', name: 'CA_COMPLETEDT', orderable: false },
            { data: 'CA_CLOSEDT', name: 'CA_CLOSEDT', visible: false, orderable: false },
            { data: 'descr', name: 'descr', visible: false, orderable: false },
            { data: 'BR_BRNNM', name: 'BR_BRNNM', orderable: false },
            { data: 'CA_FILEREF', name: 'CA_FILEREF', visible: false, orderable: false },
            { data: 'CA_RESULT', name: 'CA_RESULT', visible: false, orderable: false },
            { data: 'CA_ANSWER', name: 'CA_ANSWER', visible: false, orderable: false },
            { data: 'CA_SSP', name: 'CA_SSP', visible: false, orderable: false }
          ],
          buttons: [
//                    {extend: 'copy'},
//                    {extend: 'csv'},
            {
              extend: 'excel',
              exportOptions: {
                orthogonal: 'export',
                columns: ':visible'
              }
            },
//                    {
//                        extend: 'pdf',
//                        orientation: 'landscape',
//                        exportOptions: {
//                            orthogonal: 'export' ,
//                            columns: ':visible'
//                        }
//                    },
            {
              extend: 'print', text: 'Cetak', customize: function (win) {
                $(win.document.body).addClass('white-bg')
                $(win.document.body).css('font-size', '10px')
                $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit')
              }
            },
            { extend: 'colvis', text: 'Paparan Medan' }
          ]
        })

        function GetElementInsideContainer(containerID, childID) {
          var elm = {}
          var elms = document.getElementById(containerID).getElementsByTagName('*')
          for (var i = 0; i < elms.length; i++) {
            if (elms[ i ].id === childID) {
              elm = elms[ i ]
              break
            }
          }
          return elm
        }


        $('#search-form').on('submit', function (e) {
          $('#search').val(1)
          AdminTable.draw()
          e.preventDefault()
        })
      })

      $('.input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        //        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
      })

      //        $('#searchfield').on('change', function (e) {
      //        var searchfieldcd = $(this).val();
      //        var expfieldcd = searchfieldcd.split('~');
      //        console.log(expfieldcd[0]);
      //            $.ajax({
      //                type: 'GET',
      //                url: "{{ url('carian/getsearchfieldcd') }}" + "/" + searchfieldcd,
      //                dataType: "json",
      //                success: function (data) {
      //                    console.log(data);
      //                    $('#todo').empty();
      ////                    $('ul').append('<li>asdasdad</li>');
      //                    $.each(data, function (key, value) {
      ////                        document.getElementById('todo').append('<li>asdasdad</li>');
      //                        $('#todo').append('<li id="' + expfieldcd[0] + '~' + key +  '">' + value + '</li>');
      //                    });
      //                }
      //            });
      //
      //        });

      $('#searchfield').on('change', function (e) {

        var searchfieldcd = $(this).val()
        var expfieldcd = searchfieldcd.split('~')
        $.ajax({
          type: 'GET',
          url: "{{ url('carian/getsearchfieldcd') }}" + '/' + searchfieldcd,
          dataType: 'json',
          success: function (data) {
            $('#selectBox option').not(':selected').remove()
            $.each(data, function (key, value) {
              $('select[name="BR_OTHDIST[]"]').append('<option value="' + expfieldcd[ 0 ] + '~' + key + '">' + value + '</option>')
              $('.dual_select').bootstrapDualListbox('refresh')
            })
          }
        })

//        var searchfieldcd = $(this).val();
//        var expfieldcd = searchfieldcd.split('~');
//        console.log(expfieldcd[0]);
//            $.ajax({
//                type: 'GET',
//                url: "{{ url('carian/getsearchfieldcd') }}" + "/" + searchfieldcd,
//                dataType: "json",
//                success: function (data) {
//                    console.log(data);
//                    $('#todo').empty();
////                    $('ul').append('<li>asdasdad</li>');
//                    $.each(data, function (key, value) {
////                        document.getElementById('todo').append('<li>asdasdad</li>');
//                        $('#todo').append('<li id="' + expfieldcd[0] + '~' + key +  '">' + value + '</li>');
//                    });
//                }
//            });

      })

      $('#state_cd').on('change', function (e) {
        var state_cd = $(this).val()
        $.ajax({
          type: 'GET',
          url: "{{ url('user/getbrnlist') }}" + '/' + state_cd,
          dataType: 'json',
          success: function (data) {
            $('select[name="brn_cd"]').empty()
            $.each(data, function (key, value) {
              $('select[name="brn_cd"]').append('<option value="' + key + '">' + value + '</option>')
            })
          }
        })
      })

      //        $('#CA_RCVDT').datepicker({
      //            format: 'dd-mm-yyyy',
      //            todayBtn: "linked",
      //            todayHighlight: true,
      //            keyboardNavigation: false,
      //            forceParse: false,
      //            autoclose: true
      //        });
    </script>
@stop