<?php
    use App\Ref;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true"><i class="fa fa-close"></i></span><span class="sr-only">Close</span>
    </button>
    <h4 class="modal-title">Tambah Akta</h4>
</div>
<div class="modal-body">
    {!! Form::open(['route' => ['sas-case.storeakta', $CASEID], 'id' => 'form-create-akta', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        {{ csrf_field() }}
        <div id="CT_IPNO_field" class="form-group {{ $errors->has('CT_IPNO') ? ' has-error' : '' }}">
            {{ Form::label('CT_IPNO', 'No. Kertas Siasatan / EP', ['class' => 'col-sm-5 control-label required']) }}
            <div class="col-sm-7">
                {{ Form::text('CT_IPNO', '', ['class' => 'form-control input-sm']) }}
                <span id="CT_IPNO_block" style="display: none;" class="help-block"></span>
            </div>
        </div>
        <div id="CT_AKTA_field" class="form-group {{ $errors->has('CT_AKTA') ? ' has-error' : '' }}">
            {{ Form::label('CT_AKTA', 'Akta', ['class' => 'col-sm-5 control-label required']) }}
            <div class="col-sm-7">
                {{ Form::select('CT_AKTA', Ref::GetList('713', true),'', ['class' => 'form-control input-sm required', 'id' => 'CT_AKTA']) }}
                <span id="CT_AKTA_block" style="display: none;" class="help-block"></span>
            </div>
        </div>
        <div id="CT_SUBAKTA_field" class="form-group {{ $errors->has('CT_SUBAKTA') ? ' has-error' : '' }}">
            {{ Form::label('CT_SUBAKTA', 'Jenis Kesalahan', ['class' => 'col-sm-5 control-label required']) }}
            <div class="col-sm-7">
                {{ Form::select('CT_SUBAKTA', ['' => '-- SILA PILIH --'],'',['class' => 'form-control input-sm', 'id' => 'CT_SUBAKTA'])}}
                <span id="CT_SUBAKTA_block" style="display: none;" class="help-block"></span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12" align="center">
                <button type="submit" id="btnsubmitcreate" class="ladda-button ladda-button-demo btn btn-success btn-sm">@lang('button.add')</button>
            </div>
        </div>
    {!! Form::close() !!}
</div>

<script type="text/javascript">
    $("#form-create-akta").submit(function(e){
        e.preventDefault();
        var l = $('.ladda-button-demo').ladda();
        var _this = this;
        $.ajax({
            type : "POST",
            url: "{{ url('siasat/AjaxValidateKes') }}",
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
                        $("#form-create-akta div[id=" + key + "_field]").addClass('has-error');
                        $("#form-create-akta span[id=" + key + "_block]").show().html('<strong>' + value + '</strong>');
                    });
                    l.ladda('stop');
                } else {
                    $.ajax({
                        type : "POST",
                        url: "{{ url('sas-case/storeakta/'.$CASEID) }}",
                        dataType : "json",
                        data: new FormData(_this),
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            l.ladda('start');
                        },
                        success: function (data) {
                            //alert(data);
                            l.ladda('stop');
                            //close modal
                            $('#modal-create-kes').modal("hide");
                            $('#sas-kes-table').DataTable({
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
                                    url: "{{ url('sas-case/getakta/'.$CASEID) }}"
                                },
                                columns: [
                                    {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                                    {data: 'CT_IPNO', name: 'CT_IPNO'},
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
    $('#CT_AKTA').on('change', function (e) {
        var CT_AKTA = $(this).val();
        $.ajax({
            type: 'GET',
            url: "{{ url('siasat/getkeslist') }}" + "/" + CT_AKTA,
            dataType: "json",
            success: function (data) {
                $('select[name="CT_SUBAKTA"]').empty();
                $.each(data, function (key, value) {
                    $('select[name="CT_SUBAKTA"]').append('<option value="' + value + '">' + key + '</option>');
                });
            }
        });
    });
</script>