<?php
function human_filesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}
?>

@extends('layouts.main')
@section('content')
<h2>Pengurusan Simpanan</h2>

                <form id="form_backup" action="{{ route('backup.store') }}" method="post" class="form-horizontal ">
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            <label for="notification" class="control-label col-md-4"> Jenis Simpanan 
                                <span class="required"> * </span>
                            </label>
                            <div id='types' class="col-md-6 md-checkbox-inline">
                                <div class="md-checkbox">
                                    <input id="type_db" onchange="generateName()" name="types[]" type="checkbox" value="1">
                                    <label for="type_db">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Pangkalan Data
                                    </label>
                                </div>
                                <div class="md-checkbox">
                                    <input id="type_files" onchange="generateName()" name="types[]" type="checkbox" value="2">
                                    <label for="type_files">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Fail Sistem
                                    </label>
                                </div>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="control-label col-md-4">Nama Fail
                                <span class="required"> &nbsp;&nbsp; </span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="filename" name='filename'  unupper="unUpper">
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="control-label col-md-4"></label>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            </div>
                        </div>
                        
                    </div>
                </form>

                <table id="backup-table" class="table table-striped table-bordered table-hover table-responsive">
                    <thead>
                        <th>No.</th>
                        <th>Nama Fail</th>
                        <th>Saiz Fail</th>
                        <th>Tarikh Cipta</th>
                        <th>Tindakan</th>
                    </thead>
                    <tbody>
                        <?php $e = 1; ?>
                        @foreach($files as $i => $file)
                        <tr>
                            <td>{{ $e++ }}</td>
                            <td>{{ basename($file) }}</td>
                            <td>{{ human_filesize(filesize($file)) }}</td>
                            <td>{{ date('d/m/Y h:i A', filemtime($file)) }}</td>
                            <td>
                                <a class='btn btn-primary btn-xs' href='{{ route("backup.download", ["filename" => basename($file)]) }}'><i class='fa fa-download'></i></a>
                                <a class='btn btn-danger btn-xs' url='{{ route("backup.delete", ["filename" => basename($file)]) }}' onclick='deleteFile(this)' rel="tooltip" data-original-title="{{ __('button.delete') }}" ><i class='fa fa-trash'></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
@stop

@section('script_datatable')

<script>

    $('#backup-table').DataTable({
        processing: true,
        serverSide: false,
        bFilter: false,
        aaSorting: [],
        pagingType: "full_numbers",
        pageLength: 50,
        dom: "<'row'<'col-sm-6'i><'col-sm-6'<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
        language: {
            lengthMenu: 'Memaparkan _MENU_ rekod',
            zeroRecords: 'Tiada rekod ditemui',
            info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod.',
            infoEmpty: 'Tiada rekod ditemui',
            infoFiltered: '(Paparan dari _MAX_ total rekod)',
            paginate: {
                previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                first: 'Pertama',
                last: 'Terakhir',
            },
        },
    });

    function generateName() {

        var filename = "";

        if( $("#type_db").prop("checked") )
            filename += "database-";

        if( $("#type_files").prop("checked") )
            filename += "filesystem-";

        filename += moment().format('YYYYMMDDHHmm');

        $("#filename").val(filename);
    }

    function deleteFile (button) {

        var url = $(button).attr('url');

        swal({
            title: "Perhatian",
            text: "Anda pasti untuk menghapus rekod ini?",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "fade-out",
            showLoaderOnConfirm: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Hapus"
        },
        function(){
            
            $.ajax({
                url: url,
                type: "DELETE",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: "JSON",
                success: function(data){
                    if(data.status=="ok"){
                        swal({
                            title: "Berjaya!",
                            text: "Rekod telah berjaya dihapuskan!",
                            type: "success",
                            showCancelButton: false,
                            closeOnConfirm: true
                        },
                        function(){
                            swal({
                                text: "{{ __('new.reloading') }}",
                                showConfirmButton: false
                            });
                            location.reload();
                        });
                    } else {
                        swal("{{ __('new.error') }}!", "{{ __('new.something_wrong') }}", "error");
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    swal("{{ __('new.unexpected_error') }}!", thrownError, "error");
                }
            });
        });
    }

    $("form").submit(function(e){
        e.preventDefault();
        var form = $(this);

        swal({
            title: "Anda pasti?",
            text: "Proses ini akan mengambil sedikit masa",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            
            $.ajax({
                url: form.attr('action'),
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: form.attr('method'),
                data: new FormData(form[0]),
                dataType: 'json',
                contentType: false,
                processData: false,
                async: true,
                success: function(data) {
                    if(data.status=='ok'){
                        swal({
                            title: "Berjaya",
                            text: "Proses backup selesai", 
                            type: "success"
                        },
                        function () {
                            location.reload();
                        });
                    }
                    else if(data.status=='fail'){
                        swal("{{ __('new.error') }}!", "{{ __('new.backup_failed') }}!", "error");
                    }
                    else {
                        var inputError = [];

                        console.log(Object.keys(data.message)[0]);
                        if($("input[name='"+Object.keys(data.message)[0]+"']").is(':radio') || $("input[name='"+Object.keys(data.message)[0]+"']").is(':checkbox')){
                            var input = $("input[name='"+Object.keys(data.message)[0]+"']");
                        } else {
                            var input = $('#'+Object.keys(data.message)[0]);
                        }

                        $('html,body').animate(
                            {scrollTop: input.offset().top - 100},
                            'slow', function() {
                                //swal("{{ __('new.error') }}!","{{ __('new.fill_required') }}", "error");
                                input.focus();
                            }
                        );

                        $.each(data.message,function(key, data){
                            if($("input[name='"+key+"']").is(':radio') || $("input[name='"+key+"']").is(':checkbox')){
                                var input = $("input[name='"+key+"']");
                            } else {
                                var input = $('#'+key);
                            }
                            var parent = input.parents('.form-group');
                            parent.removeClass('has-success');
                            parent.addClass('has-error');
                            parent.find('.help-block').html(data[0]);
                            inputError.push(key);
                        });

                        $.each(form.serializeArray(), function(i, field) {
                            if ($.inArray(field.name, inputError) === -1)
                            {
                                if($("input[name='"+field.name+"']").is(':radio') || $("input[name='"+field.name+"']").is(':checkbox')){
                                    var input = $("input[name='"+field.name+"']");
                                } else {
                                    var input = $('#'+field.name);
                                }
                                var parent = input.parents('.form-group');
                                parent.removeClass('has-error');
                                parent.addClass('has-success');
                                parent.find('.help-block').html('');
                            }
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.status);
                }
            });


        });

        return false;
    });
</script>

@stop