@extends('layouts.main')
<?php
    use App\Ref;
?>
@section('content')
<h2>Buka Semula Aduan</h2>
<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#buka-semula">Buka Semula</a></li>
            <!-- <li class=""><a data-toggle="tab" href="#case-info"> Maklumat Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#adu-diadu"> Maklumat Pengadu Dan Diadu</a></li>
            <li class=""><a data-toggle="tab" href="#attachment">Bukti Aduan dan Gabungan Aduan</a></li>
            <li class=""><a data-toggle="tab" href="#transaction">Sorotan Transaksi</a></li> -->
        </ul>
        <div class="tab-content">
            <div id="buka-semula" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open(['route' => ['integritibukasemula.update', $mBukaSemula->id], 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }} {{ method_field('PATCH') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CASEID', $mBukaSemula->IN_CASEID, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ASGBY', 'Penugasan Oleh', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_ASGBY', $mBukaSemula->IN_ASGBY != '' ? $mBukaSemula->asgby->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_COMPLETEBY', 'Diselesai Oleh', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_COMPLETEBY', $mBukaSemula->IN_COMPLETEBY != '' ? $mBukaSemula->completeby->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEBY', 'Ditutup Oleh', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CLOSEBY', $mBukaSemula->IN_CLOSEBY != '' ? $mBukaSemula->closeby->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CMPLCAT', 'Kategori Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CMPLCAT', Ref::GetDescr('1344', $mBukaSemula->IN_CMPLCAT), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_INVSTS', 'Status Aduan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_INVSTS', Ref::GetDescr('1334', $mBukaSemula->IN_INVSTS), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_IPSTS', 'Status Penyiasatan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_IPSTS', Ref::GetDescr('1370', $mBukaSemula->IN_IPSTS), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_INVBY', 'Pegawai Penyiasat/Serbuan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_INVBY', $mBukaSemula->IN_INVBY != '' ? $mBukaSemula->InvBy->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_COMPLETEDT', 'Tarikh Selesai', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_COMPLETEDT', $mBukaSemula->IN_COMPLETEDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->IN_COMPLETEDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CLOSEDT', 'Tarikh Penutupan', ['class' => 'col-sm-5 control-label']) }}
                                <div class="col-sm-7">
                                    {{ Form::text('IN_CLOSEDT', $mBukaSemula->IN_CLOSEDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->IN_CLOSEDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_SUMMARY', 'Aduan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_SUMMARY', $mBukaSemula->IN_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_ANSWER', 'Jawapan Kepada Pengadu', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_ANSWER', $mBukaSemula->IN_ANSWER, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_RESULT', 'Hasil Siasatan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_RESULT', $mBukaSemula->IN_RESULT, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-sm-12" align="center">
                            {{ Form::submit('Buka Semula', ['class' => 'btn btn-success btn-sm', 'id' => 'SubmitBtn']) }}
                            {{ link_to('integritibukasemula', 'Kembali', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!--<div id="case-info" class="tab-pane">
                <div class="panel-body">
                    <div class="panel-body">
                    {!! Form::open(['class' => 'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_CASEID', 'No. Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CASEID', $mBukaSemula->IN_CASEID, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_BRNCD', 'Kod Cawangan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_BRNCD', $mBukaSemula->IN_BRNCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_FILEREF', 'No. Rujukan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_FILEREF', $mBukaSemula->IN_FILEREF, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_RCVDT', 'Tarikh Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_RCVDT', $mBukaSemula->IN_RCVDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->IN_RCVDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('IN_CREDT', 'Tarikh Cipta', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('IN_CREDT', $mBukaSemula->IN_CREDT != '' ? date('d-m-Y h:i A', strtotime($mBukaSemula->IN_CREDT)) : '', ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <h4>MAKLUMAT ADUAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_RCVTYP', 'Cara Penerimaan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('IN_RCVTYP', Ref::GetList('259', true, 'ms'), $mBukaSemula->IN_RCVTYP, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('IN_RCVBY', 'Penerima', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{-- Form::text('IN_RCVBY', $mBukaSemula->IN_RCVBY != '' ? $mBukaSemula->namapenerima->name : '', ['class' => 'form-control input-sm', 'readonly' => true]) --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {{ Form::label('IN_SUMMARY', 'Aduan', ['class' => 'col-sm-2 control-label']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('IN_SUMMARY', $mBukaSemula->IN_SUMMARY, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                </div>
                            </div>
                        </div>
                    </div><br>
                    {!! Form::close() !!}
                </div>
                </div>
            </div>-->
            <!--<div id="adu-diadu" class="tab-pane">
                <div class="panel-body">
                    {!! Form::open(['class' => 'form-horizontal']) !!}
                        <h4>MAKLUMAT PENGADU</h4>
                        <div class="hr-line-solid"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('IN_NAME', 'Nama Pengadu', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('IN_NAME', $mBukaSemula->IN_NAME, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_RACECD', 'Bangsa', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('IN_RACECD', Ref::GetList('580', true, 'ms'), $mBukaSemula->IN_RACECD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_SEXCD', 'Jantina', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::select('IN_SEXCD', Ref::GetList('202', true, 'ms'), $mBukaSemula->IN_SEXCD, ['class' => 'form-control input-sm', 'disabled' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_ADDR', 'Alamat', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('IN_ADDR', $mBukaSemula->IN_ADDR, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('IN_DOCNO', 'No. Kad Pengenalan/Pasport', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_DOCNO', $mBukaSemula->IN_DOCNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_NATCD', 'Warganegara', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_NATCD', $mBukaSemula->IN_NATCD, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_MOBILENO', $mBukaSemula->IN_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_TELNO', 'No. Telefon', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_TELNO', $mBukaSemula->IN_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_FAXNO', $mBukaSemula->IN_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_EMAIL', $mBukaSemula->IN_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4>MAKLUMAT DIADU</h4>
                        <div class="hr-line-solid"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('IN_AGAINSTNM', 'Nama Diadu', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::text('IN_AGAINSTNM', $mBukaSemula->IN_AGAINSTNM, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_AGAINSTADD', 'Alamat', ['class' => 'col-sm-3 control-label']) }}
                                    <div class="col-sm-9">
                                        {{ Form::textarea('IN_AGAINSTADD', $mBukaSemula->IN_AGAINSTADD, ['class' => 'form-control input-sm', 'rows' => 3, 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('IN_AGAINST_MOBILENO', 'No. Telefon Bimbit', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_AGAINST_MOBILENO', $mBukaSemula->IN_AGAINST_MOBILENO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_AGAINST_TELNO', 'No. Telefon', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_AGAINST_TELNO', $mBukaSemula->IN_AGAINST_TELNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_AGAINST_FAXNO', 'No. Faks', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_AGAINST_FAXNO', $mBukaSemula->IN_AGAINST_FAXNO, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('IN_AGAINST_EMAIL', 'Emel', ['class' => 'col-sm-5 control-label']) }}
                                    <div class="col-sm-7">
                                        {{ Form::text('IN_AGAINST_EMAIL', $mBukaSemula->IN_AGAINST_EMAIL, ['class' => 'form-control input-sm', 'readonly' => true]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>-->
            <!--<div id="attachment" class="tab-pane">
                <div class="panel-body">
                    <h4>BAHAN BUKTI</h4>
                    <div class="hr-line-solid"></div>
                    <div class="table-responsive">
                        <table id="buka-semula-attachment-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Nama Fail</th>
                                    <th>Catatan</th>
                                    <th>Tarikh Muatnaik</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <h4>GABUNGAN ADUAN</h4>
                    <div class="hr-line-solid"></div>
                    <div class="table-responsive">
                        <table id="buka-semula-gabung-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>No. Aduan</th>
                                    <th>Aduan</th>
                                    <th>Tarikh Terima</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>-->
            <!--<div id="transaction" class="tab-pane">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="buka-semula-transaction-table" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Status</th>
                                    <th>Daripada</th>
                                    <th>Kepada</th>
                                    <th>Tarikh Transaksi</th>
                                    <th>Saranan</th>
                                    <th>Surat Kepada Pengadu</th>
                                    <th>Surat Kepada Pegawai/Agensi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
</div>

@stop

@section('script_datatable')
<script type="text/javascript">
    
    $('#SubmitBtn').click(function(e) {
//        e.preventDefault();
//        var Confirm = confirm('Anda pasti untuk buka semula aduan?\nNo.Aduan baru yang akan dijana ialah ' + '<?php // echo $NewNoAduan; ?>');
        var Confirm = confirm('Anda pasti untuk buka semula aduan?\nNo. Aduan yang baru akan dijana.');
        if(Confirm) {
            return true;
        }else{
            return false;
        }
    });
    
    function check(value) {
        if (value == 'YES') {
            $('#ya').show();
        } else {
            $('#ya').hide();
        }
    }
    
    function checkold(value) {
        if (value == 'YES') {
            $('#ya-old').show();
        } else {
            $('#ya-old').hide();
        }
    }
    
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href='+hash+']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
    
    $('#IN_AKTA').on('change', function (e) {
        var IN_AKTA = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('bukasemula/getsubaktalist') }}" + "/" + IN_AKTA,
            dataType: "json",
            success:function(data){
                $('select[name="IN_SUBAKTA"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="IN_SUBAKTA"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        }); 
    });
    
    $('#buka-semula-attachment-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        info: false,
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
                last: 'Terakhir'
            }
        },
        ajax: {
            url: "{{ url('bukasemula/getdatatableattachment', $mBukaSemula->IN_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id_no', 'width': '5%', searchable: false, orderable: false},
//            {data: 'doc_title', name: 'doc_title', orderable: false},
//            {data: 'file_name_sys', name: 'file_name_sys', orderable: false},
            {data: 'CC_IMG_NAME', name: 'CC_IMG_NAME'},
            {data: 'CC_REMARKS', name: 'CC_REMARKS'},
            {data: 'updated_at', name: 'updated_at', orderable: false}
//            {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
        ]
    });
    
    $('#buka-semula-gabung-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
        info: false,
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
                last: 'Terakhir'
            }
        },
        ajax: {
            url: "{{ url('bukasemula/getdatatablemergecase', $mBukaSemula->IN_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'IN_CASEID', name: 'IN_CASEID'},
            {data: 'IN_SUMMARY', name: 'IN_SUMMARY'},
            {data: 'IN_RCVDT', name: 'IN_RCVDT'}
        ]
    });
    
    $('#buka-semula-transaction-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        aaSorting: [],
        bLengthChange: false,
        bPaginate: false,
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
                last: 'Terakhir'
            }
        },
        ajax: {
            url: "{{ url('bukasemula/getdatatabletransaction', $mBukaSemula->IN_CASEID) }}"
        },
        columns: [
            {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
            {data: 'CD_INVSTS', name: 'CD_INVSTS'},
            {data: 'CD_ACTFROM', name: 'CD_ACTFROM'},
            {data: 'CD_ACTTO', name: 'CD_ACTTO'},
            {data: 'CD_CREDT', name: 'CD_CREDT'},
            {data: 'CD_DESC', name: 'CD_DESC'},
            {data: 'CD_DOCATTCHID_PUBLIC', name: 'CD_DOCATTCHID_PUBLIC'},
            {data: 'CD_DOCATTCHID_ADMIN', name: 'CD_DOCATTCHID_ADMIN'}
        ]
    });
    
    $(document).ready(function(){
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
                lengthMenu: 'Paparan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
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
                url: "{{ url('bukasemula/getdatatableuser') }}",
                data: function (d) {
                    d.name = $('#name').val();
                    d.icnew = $('#icnew').val();
                    d.state_cd = $('#state_cd_user').val();
                    d.brn_cd = $('#brn_cd').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'state_cd', name: 'state_cd'},
                {data: 'brn_cd', name: 'brn_cd'},
                {data: 'action', name: 'action', searchable: false, orderable: false, width: '8%'}
            ]
        });

        $('#state_cd_user').on('change', function (e) {
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

        $('#btn-reset').on('click', function(e) {
            document.getElementById("user-search-form").reset();
            oTable.draw();
            e.preventDefault();
        });

        $('#user-search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    
    function carianpenyiasat(id) {
        $.ajax({ 
            url: "{{ url('bukasemula/getuserdetail') }}" + "/" + id,
            dataType: "json",
            success:function(data){
                $.each(data, function(key, value) {
                    document.getElementById("IN_INVBY_name").value = key;
                    document.getElementById("IN_INVBY_id").value = value;
                });
                $('#carian-penyiasat').modal('hide');
            }
        });
    };
</script>
@stop