<?php
use App\Ref;
use Carbon\Carbon;
use App\Aduan\Penyiasatan;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Kemaskini Wujud Kes</h4>
</div>
<div class="modal-body">
    <!--{!! Form::open(['route' => ['siasat.updatekessiasat',$SiasatKes->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' =>'form-edit-kes']) !!}-->
    {!! Form::open(['route' => ['siasat.updatekessiasat',$SiasatKes->id], 'enctype' => 'multipart/form-data', 'id' =>'form-edit-kes']) !!}
    {{ csrf_field() }}{{ method_field('PUT') }}

    <div id="CT_IPNO_field" class="form-group {{ $errors->has('CT_IPNO') ? ' has-error' : '' }}">
        <!--{{-- Form::label('CT_IPNO', 'No. Kertas Siasatan / EP', ['class' => 'col-sm-5 control-label required']) --}}-->
        {{ Form::label('CT_IPNO', 'No. Kertas Siasatan / EP', ['class' => 'control-label required']) }}
        <!--<div class="col-sm-7">-->
            {{ Form::text('CT_IPNO', $SiasatKes->CT_IPNO, ['class' => 'form-control input-sm']) }}
            <span id="CT_IPNO_block" style="display: none;" class="help-block"></span>
        <!--</div>-->
    </div>
    <div id="CT_EPNO_field" class="form-group {{ $errors->has('CT_EPNO') ? ' has-error' : '' }}">
        {{ Form::label('CT_EPNO', 'No. EP', ['class' => 'control-label required']) }}
        
            {{ Form::text('CT_EPNO', $SiasatKes->CT_EPNO, ['class' => 'form-control input-sm']) }}
            <span id="CT_EPNO_block" style="display: none;" class="help-block"></span>
        
    </div>
    <div id="CT_AKTA_field" class="form-group {{ $errors->has('CT_AKTA') ? ' has-error' : '' }}">
        <!--{{-- Form::label('CT_AKTA', 'Akta', ['class' => 'col-sm-5 control-label required']) --}}-->
        {{ Form::label('CT_AKTA', 'Akta', ['class' => 'control-label required']) }}
        <!--<div class="col-sm-7">-->
            {{ Form::select('CT_AKTA', Ref::GetList('713', true), $SiasatKes->CT_AKTA, ['class' => 'form-control input-sm required', 'id' => 'CT_AKTA_EDIT']) }}
            <span id="CT_AKTA_block" style="display: none;" class="help-block"></span>
        <!--</div>-->
    </div>
    <div id="CT_SUBAKTA_field" class="form-group {{ $errors->has('CT_SUBAKTA') ? ' has-error' : '' }}">
        <!--{{-- Form::label('CT_SUBAKTA', 'Jenis Kesalahan', ['class' => 'col-sm-5 control-label required']) --}}-->
        {{ Form::label('CT_SUBAKTA', 'Jenis Kesalahan', ['class' => 'control-label required']) }}
        <!--<div class="col-sm-7">-->
            {{ Form::select('CT_SUBAKTA', $SiasatKes->CT_SUBAKTA == ''? ['' => '-- SILA PILIH --']:Penyiasatan::GetSubAktaList($SiasatKes->CT_AKTA), $SiasatKes->CT_SUBAKTA, ['class' => 'form-control input-sm', 'id' => 'CT_SUBAKTA']) }}
            <span id="CT_SUBAKTA_block" style="display: none;" class="help-block"></span>
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
            url: "{{ url('siasat/ajaxvalidateeditkes') }}",
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
                        url: "{{ url('siasat/updatekessiasat/'.$SiasatKes->id) }}",
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
                                    url: "{{ url('siasat/getKesSiasat', $SiasatKes->CT_CASEID) }}"
                                },
                                columns: [
                                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                                    {data: 'CT_IPNO', name: 'CT_IPNO'},
                                    {data: 'CT_EPNO', name: 'CT_EPNO'},
                                    {data: 'CT_AKTA', name: 'CT_AKTA'},
                                    {data: 'CT_SUBAKTA', name: 'CT_SUBAKTA'},
                                    {data: 'action', name: 'action', searchable: false, orderable: false, width: '5%'}
                                ]
                            });
                        }
                    });
                }
            }
        });
    });
    $('#CT_AKTA_EDIT').on('change', function (e) {
        var akta = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('siasat/getsubakta') }}" + "/" + akta,
            dataType: "json",
            success:function(data){
                console.log(data);
                $('select[name="CT_SUBAKTA"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="CT_SUBAKTA"]').append('<option value="'+ value +'">'+ key +'</option>');
                });
            }
        });
    });
</script>