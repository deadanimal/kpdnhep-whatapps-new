<?php
use App\Ref;
use Carbon\Carbon;
use App\Integriti\IntegritiAdmin;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Kemaskini Wujud Kes</h4>
</div>
<div class="modal-body">
    <!--{!! Form::open(['route' => ['siasat.updatekessiasat',$SiasatKes->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' =>'form-edit-kes']) !!}-->
    {!! Form::open(['route' => ['siasat.updatekessiasat',$SiasatKes->id], 'enctype' => 'multipart/form-data', 'id' =>'form-edit-kes']) !!}
    {{ csrf_field() }}{{ method_field('PUT') }}

    <div id="IT_IPNO_field" class="form-group {{ $errors->has('IT_IPNO') ? ' has-error' : '' }}">
        <!--{{-- Form::label('IT_IPNO', 'No. IP', ['class' => 'col-sm-5 control-label required']) --}}-->
        {{ Form::label('IT_IPNO', 'No. IP', ['class' => 'control-label required1']) }}
        <!--<div class="col-sm-7">-->
            {{ Form::text('IT_IPNO', $SiasatKes->IT_IPNO, ['class' => 'form-control input-sm']) }}
            <span id="IT_IPNO_block" style="display: none;" class="help-block"></span>
        <!--</div>-->
    </div>
    <div id="IT_EPNO_field" class="form-group {{ $errors->has('IT_EPNO') ? ' has-error' : '' }}">
        {{ Form::label('IT_EPNO', 'No. EP', ['class' => 'control-label required1']) }}
        
            {{ Form::text('IT_EPNO', $SiasatKes->IT_EPNO, ['class' => 'form-control input-sm']) }}
            <span id="IT_EPNO_block" style="display: none;" class="help-block"></span>
        
    </div>
    <div id="IT_AKTA_field" class="form-group {{ $errors->has('IT_AKTA') ? ' has-error' : '' }}">
        <!--{{-- Form::label('IT_AKTA', 'Akta', ['class' => 'col-sm-5 control-label required']) --}}-->
        {{ Form::label('IT_AKTA', 'Akta', ['class' => 'control-label required']) }}
        <!--<div class="col-sm-7">-->
            {{ Form::select('IT_AKTA', Ref::GetList('713', true), $SiasatKes->IT_AKTA, ['class' => 'form-control input-sm required', 'id' => 'IT_AKTA_EDIT']) }}
            <span id="IT_AKTA_block" style="display: none;" class="help-block"></span>
        <!--</div>-->
    </div>
    <div id="IT_SUBAKTA_field" class="form-group {{ $errors->has('IT_SUBAKTA') ? ' has-error' : '' }}">
        <!--{{-- Form::label('IT_SUBAKTA', 'Jenis Kesalahan', ['class' => 'col-sm-5 control-label required']) --}}-->
        {{ Form::label('IT_SUBAKTA', 'Jenis Kesalahan', ['class' => 'control-label required']) }}
        <!--<div class="col-sm-7">-->
            {{ Form::select('IT_SUBAKTA', $SiasatKes->IT_SUBAKTA == ''? ['' => '-- SILA PILIH --']:IntegritiAdmin::GetSubAktaList($SiasatKes->IT_AKTA), $SiasatKes->IT_SUBAKTA, ['class' => 'form-control input-sm', 'id' => 'IT_SUBAKTA']) }}
            <span id="IT_SUBAKTA_block" style="display: none;" class="help-block"></span>
        <!--</div>-->
    </div>

    <div class="row">
        <div class="form-group col-sm-12" align="center">
            <button type="submit" class="ladda-button ladda-button-demo btn btn-success btn-sm">Kemaskini</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<script type="text/javascript">
    $("#form-edit-kes").submit(function(e){
        e.preventDefault();
        var l = $('.ladda-button-demo').ladda();
        var _this = this;
        $.ajax({
            type : "POST",
            url: "{{ url('integritisiasat/ajaxvalidateeditkes') }}",
            dataType : "json",
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function () {
                l.ladda('start');
            },
            success: function (data) {
                console.log(data);
                if (data['fails']) {
                    $('.form-group').removeClass('has-error');
                    $('.help-block').hide().text();
                    $.each(data['fails'], function (key, value) {
                        $("#form-edit-kes div[id=" + key + "_field]").addClass('has-error');
                        $("#form-edit-kes span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                    });
                    l.ladda('stop');
                } else {
//                    $('#form-edit-kes').unbind('submit').submit();
                    $.ajax({
                        type : "POST",
                        url: "{{ url('integritisiasat/updatekessiasat/'.$SiasatKes->id) }}",
                        dataType : "json",
                        data: new FormData(_this),
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            l.ladda('start');
                        },
                        success: function (data) {
                            l.ladda('stop');
                            $('#modal-edit-kes').modal("hide");
                            $('#admin-case-kes-table').DataTable({
                                destroy: true,
                                processing: true,
                                serverSide: true,
                                bFilter: false,
                                aaSorting: [],
                                bLengthChange: false,
                                bPaginate: false,
                                bInfo: false,
                                language: {
                                    lengthMenu: 'Paparan _MENU_ rekod',
                                    zeroRecords: 'Tiada rekod ditemui',
                                    info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                                    infoEmpty: 'Tiada rekod ditemui',
                                    infoFiltered: '(Paparan daripada _MAX_ jumlah rekod)',
                                },
                                ajax: {
                                    url: "{{ url('integritisiasat/getKesSiasat', str_replace('/','_',$SiasatKes->IT_CASEID)) }}"
                                },
                                columns: [
                                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                                    {data: 'IT_IPNO', name: 'IT_IPNO'},
                                    {data: 'IT_EPNO', name: 'IT_EPNO'},
                                    {data: 'IT_AKTA', name: 'IT_AKTA'},
                                    {data: 'IT_SUBAKTA', name: 'IT_SUBAKTA'},
                                    {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
                                ]
                            });
                        }
                    });
                }
            }
        });
    });
    $('#IT_AKTA_EDIT').on('change', function (e) {
        var akta = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('integritisiasat/getsubakta') }}" + "/" + akta,
            dataType: "json",
            success:function(data){
                console.log(data);
                $('select[name="IT_SUBAKTA"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="IT_SUBAKTA"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        });
    });
</script>