@extends('layouts.main')
<?php

use App\Ref;
use App\Penugasan;
use Carbon\Carbon;
use App\Aduan\Penyiasatan;
?>
@section('content')
<style> 
    textarea {
        resize: vertical;
    }
    .help-block-red {
        color: red;
    }
</style>
<h2>Pengurusan Aduan</h2>
<div class="row">
    <div class="tabs-container">
        <!-- <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#penugasan"> Pengurusan</a></li>
        </ul> -->
        <div class="tab-content">
            <!-- <div id="penugasan" class="tab-pane active"> -->
                <div class="panel-body">
                    {!! Form::open(['url' => '/pengurusanintegriti/tukar-status/'.$mIntegriti->IN_CASEID,  'class'=>'form-horizontal', 'method' => 'POST']) !!}
                    {{ csrf_field() }}
                    <h4>Maklumat Pengadu</h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-12" style="text-align:center; padding-bottom:5vh">
                            <div class="checkbox checkbox-primary">
                                <input id="IN_SECRETFLAG" type="checkbox" name="IN_SECRETFLAG" disabled {{ $mIntegriti->IN_SECRETFLAG == "1"? 'checked':'' }}>
                                <label for="IN_SECRETFLAG">
                                    <b>Saya ingin merahsiakan maklumat sulit (maklumat, identiti, alamat, pekerjaan dan yang berkaitan dengan pemberi maklumat)</b>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_NAME', 'Nama', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_NAME', $mIntegriti->IN_NAME, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_DOCNO', 'No Kad Pengenalan', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_DOCNO', $mIntegriti->IN_DOCNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_SEXCD', 'Jantina', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_SEXCD', Ref::GetDescr('202', $mIntegriti->IN_SEXCD, 'ms'), ['class' => 'form-control input-sm', 'id' => 'IN_SEXCD', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_RACECD', 'Bangsa', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_RACECD', Ref::GetDescr('580', $mIntegriti->IN_RACECD, 'ms'), ['class' => 'form-control input-sm', 'id' => 'IN_RACECD', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_NATCD', 'Warganegara', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_NATCD', Ref::GetDescr('947', $mIntegriti->IN_RACECD, 'ms'), ['class' => 'form-control input-sm', 'id' => 'IN_NATCD', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_EMAIL', 'Emel', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_EMAIL', $mIntegriti->IN_EMAIL, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ADDR', 'Alamat', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::textarea('IN_ADDR', $mIntegriti->IN_ADDR, ['class' => 'form-control input-sm','readonly' => true,'rows' => '5']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_TELNO', 'No Telefon', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_TELNO', $mIntegriti->IN_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Maklumat Aduan</h4>
                    <hr style="background-color: #ccc; height: 1px; width: 100%; border: 0;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CASEID', 'No Aduan', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CASEID', $mIntegriti->IN_CASEID, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CMPLTYP', 'Jenis Aduan', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CMPLTYP', $mIntegriti->IN_CMPLTYP, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_AGAINSTNM', 'Nama Pegawai Yang Diadu', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_AGAINSTNM', $mIntegriti->IN_AGAINSTNM, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CMPLCAT', 'Kategori', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_CMPLCAT', Ref::GetList('1344', true, 'ms', 'descr'), old('IN_CMPLCAT', $mIntegriti->IN_CMPLCAT), ['class' => 'form-control input-sm required', 'id' => 'IN_CMPLCAT']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_INVSTS', 'Status', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_INVSTS', Ref::GetList('1334', true, 'ms', 'descr'), old('IN_INVSTS', $mIntegriti->IN_INVSTS), ['class' => 'form-control input-sm required', 'id' => 'IN_INVSTS']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_TELNO', 'Bahagian', ['class' => 'col-md-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_TELNO', $mIntegriti->IN_TELNO, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_SUMMARY_TITLE', 'Tajuk Aduan', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::text('IN_SUMMARY_TITLE', $mIntegriti->IN_SUMMARY_TITLE, ['class' => 'form-control input-sm','readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_SUMMARY', 'Keterangan Aduan', ['class' => 'col-md-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_SUMMARY', $mIntegriti->IN_SUMMARY, ['class' => 'form-control input-sm','readonly' => true,'rows' => '5']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Lampiran</h4>
                            <hr style="background-color: #ccc; height: 1px; border: 0;">
                            <table>
                                <tr>
                                    @foreach($mIntegritiPublicDoc as $IntegritiPublicDoc)
                                    <?php $ExtFile = substr($IntegritiPublicDoc->ID_DOCFULLNAME, -3); ?>
                                    @if($ExtFile == 'pdf' || $ExtFile == 'PDF')
                                    <td style="max-width: 10%; min-width: 10%; ">
                                        <div class="p-sm text-center">
                                            <a href="{{ Storage::disk('path')->url($IntegritiPublicDoc->ID_PATH.$IntegritiPublicDoc->ID_DOCNAME) }}" target="_blank">
                                                <img src="{{ url('img/PDF.png') }}" class="img-lg img-thumbnail"/>
                                                <br />
                                                {{ $IntegritiPublicDoc->ID_DOCFULLNAME }}
                                            </a>
                                        </div>
                                    </td>
                                    @else
                                    <td style="max-width: 10%; min-width: 10%; ">
                                        <div class="p-sm text-center">
                                            <a href="{{ Storage::disk('bahanpath')->url($IntegritiPublicDoc->ID_PATH.$IntegritiPublicDoc->ID_DOCNAME) }}" target="_blank">
                                                <img src="{{ Storage::disk('bahanpath')->url($IntegritiPublicDoc->ID_PATH.$IntegritiPublicDoc->ID_DOCNAME) }}" class="img-lg img-thumbnail"/>
                                                <br />
                                                {{ $IntegritiPublicDoc->ID_DOCFULLNAME }}
                                            </a>
                                        </div>
                                    </td>
                                    @endif
                                    <!--<td style="max-width: 10%; min-width: 10%; ">-->
                                        <!--<br />-->
                                        <!--{{-- $IntegritiPublicDoc->CC_IMG_NAME --}}-->
                                    <!--</td>-->
                                    @endforeach
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group col-sm-12" align="center">
                                {{ Form::submit('Hantar', ['class' => 'btn btn-primary btn-sm']) }}
                                {{-- Form::submit('Cetak Surat', ['class' => 'btn btn-success btn-sm']) --}}
                                <a href="{{ url('pengurusanintegriti')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            <!-- </div> -->
        </div>
    </div>
</div>
<!-- Modal Start -->
@include('aduan.tugas.usersearchmodal')
@include('aduan.tugas.multiusersearchmodal')
<!-- Modal End -->
<!-- Modal Start -->
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
<!-- Modal End -->
@stop

@section('script_datatable')
<script type="text/javascript">
    function do_remove(user_idx) {
        var oRep = oMembers;
        
        var arr_persons = oRep.arr_persons;
        for(var i=0; i < arr_persons.length; i++) {
            if (user_idx == arr_persons[i].user_id) {
                console.log(i);
                arr_persons.splice(i, 1);
            }
        }
        oRep.arr_persons = arr_persons;
        
        
        var arr_persons = oMembers.arr_persons;
        //alert('koko'+arr_persons.length);
        var obj1 = $('#recipient1').html(''); // 
        var obj2 = $('#recipient2').html(''); //

        for(var i=0; i < arr_persons.length; i++) {
            str = "<input type='hidden' name='recipient[]' value='"+arr_persons[i].user_id+"' />";
            obj1.append(str);
            str = arr_persons[i].name + " <a href='#' onclick='do_remove(\""+arr_persons[i].user_id+"\")'> </a> <br />";
            obj2.append(str);
        }
        
    }
    
    function ShowSummary(CASEID)
    {
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
    }

    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href=' + hash + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    $('#CA_INVSTS').on('change', function (e) {
        var CA_INVSTS = $(this).val();
         if(CA_INVSTS === '')
            $('#div_BLOCK').hide();
        else
            $('#div_BLOCK').show();
        if(CA_INVSTS === '4')
            $('#magncd').show();
        else
            $('#magncd').hide();
        if(CA_INVSTS == 2)
        {
            $('#div_CA_CMPLCAT').show();
            $('#div_CA_CMPLCD').show();
            $('#div_CA_INVBY').show();
            $('#div_CA_INVBYOTH').show();
            $('#div_CD_DESC').show();
            $('#div_CA_ANSWER').hide();
        }
        else
        {
            $('#div_CA_CMPLCAT').hide();
            $('#div_CA_CMPLCD').hide();
            $('#div_CA_INVBY').hide();
            $('#div_CA_INVBYOTH').hide();
            $('#div_CD_DESC').hide();
            $('#div_CA_ANSWER').show();
        }
    });

    $('#CA_CMPLCAT').on('change', function (e) {
        var CA_CMPLCAT = $(this).val();
        $.ajax({
            type: 'GET',
            url: "{{ url('tugas/getcmpllist') }}" + "/" + CA_CMPLCAT,
            dataType: "json",
            success: function (data) {
                $('select[name="CA_CMPLCD"]').empty();
                $.each(data, function (key, value) {
                    $('select[name="CA_CMPLCD"]').append('<option value="' + value + '">' + key + '</option>');
                });
            }
        });
    });

    function myFunction(id) {
        $.ajax({
            url: "{{ url('tugas/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    document.getElementById("InvByName").value = key;
                    document.getElementById("InvById").value = value;
                });
                $('#carian-penerima').modal('hide');
            }
        });
    }
    ;

    $(document).ready(function () {

        $('#UserSearchModal').on('click', function (e) {
            $("#carian-penerima").modal();
        });
        
        $('#MultiUserSearchModal').on('click', function (e) {
            $("#carian-lain2-penerima").modal();
        });

        $('#carian-penerima').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        });

        var oTable = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
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
                url: "{{ url('tugas/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd_user').val();
                    d.brn_cd = $('#brn_cd').val();
                    d.role_cd = $('#role_cd').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });
        
        var oTableMulti = $('#users-multi-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('tugas/getdatatablemultiuser') }}",
                data: function (d) {
                    d.name = $('#name_multi').val();
                    d.icnew = $('#icnew_multi').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', width: '1%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'count_case', name: 'count_case'},
                {data: 'role.role_code', name: 'role.role_code'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '1%'}
            ],
        });

        $('#state_cd_user').on('change', function (e) {
            var state_cd = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('user/getbrnlist') }}" + "/" + state_cd,
                dataType: "json",
                success: function (data) {
                    $('select[name="brn_cd"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="brn_cd"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        });

        $('#resetbtn').on('click', function (e) {
            document.getElementById("search-form").reset();
            oTable.draw();
            oTable.columns.adjust();
            e.preventDefault();
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        
        $('#search-form-multi').on('submit', function (e) {
            oTableMulti.draw();
            e.preventDefault();
        });
        
        $('#resetbtnmulti').on('click', function(e) {
            document.getElementById("search-form-multi").reset();
            oTableMulti.draw();
            e.preventDefault();
        });

        var SorotanTable = $('#sorotan-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            aaSorting: [],
            bLengthChange: false,
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
                url: "{{ url('/tugas/gettransaction', $mIntegriti->CA_CASEID)}}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CD_INVSTS', name: 'CD_INVSTS'},
                {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
                {data: 'CD_ACTTO', name: 'CD_ACTTO'},
                {data: 'CD_DESC', name: 'CD_DESC'},
                {data: 'CD_CREDT', name: 'CD_CREDT'},
                {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'},
                {data: 'CD_DOCATTCHID_ADMIN', name: 'CD_DOCATTCHID_ADMIN'},
            ]
        });

        var GabungTable = $('#penugasan-gabung-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            info: false,
            paging: false,
            aaSorting: [],
            bLengthChange: false,
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
                url: "{{ url('/tugas/getgabungkes', $mIntegriti->CA_CASEID) }}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CA_CASEID', name: 'CA_CASEID'},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                {data: 'CA_RCVDT', name: 'CA_RCVDT'},
            ]
        });

        var AttachmentTable = $('#penugasan-attachment-table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            info: false,
            paging: false,
            aaSorting: [],
            bLengthChange: false,
            rowId: 'id',
            language: {
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('/tugas/getattachment', $mIntegriti->CA_CASEID) }}",
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME', orderable: false},
                {data: 'CC_REMARKS', name: 'CC_REMARKS'}
//                {data: 'doc_title', name: 'doc_title', orderable: false},
//                {data: 'file_name_sys', name: 'file_name_sys', orderable: false},
//                {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
            ]
        });
    });
</script>
@stop