@extends('layouts.main')

@section('content')
    <h2>Selenggara Tetapan Sistem</h2>
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a onclick='updatePage(1)' data-toggle="tab" href="#tab-1" aria-expanded="true">Server</a></li>
            <li class=""><a onclick='updatePage(2)' data-toggle="tab" href="#tab-2" aria-expanded="false">Pangkalan Data</a></li>
            <li class=""><a onclick='updatePage(3)' data-toggle="tab" href="#tab-3" aria-expanded="false">Email</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <form id="form-system" action="{{ route('settings.update') }}" method="post" class="form-horizontal ">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="form-group">
                                {{ Form::label('APP_NAME', 'Nama Sistem', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('APP_NAME', env('APP_NAME'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="form-group">
                                {{ Form::label('APP_ENV', 'Persekitaran', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('APP_ENV', env('APP_ENV'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="form-group">
                                {{ Form::label('APP_DEBUG', 'Debug', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('APP_DEBUG', env('APP_DEBUG') == '0'? 'false':'true', ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="form-group">
                                {{ Form::label('APP_URL', 'URL Sistem', ['class' => 'col-sm-3 control-label']) }}
                                <div class="col-sm-9">
                                    {{ Form::text('APP_URL', env('APP_URL'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-group" align="center">
                            <button type="button" onclick='submitForm()' class="btn btn-primary btn-sm"> Simpan </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <form id="form-database" action="{{ route('settings.update') }}" method="post" class="form-horizontal ">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('DB_CONNECTION', 'Jenis Pangkalan Data', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('DB_CONNECTION', env('DB_CONNECTION'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('DB_HOST', 'Host / IP', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('DB_HOST', env('DB_HOST'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('DB_PORT', 'Port', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('DB_PORT', env('DB_PORT'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('DB_DATABASE', 'Nama Pangkalan Data', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('DB_DATABASE', env('DB_DATABASE'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('DB_USERNAME', 'Nama Pengguna (username)', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('DB_USERNAME', env('DB_USERNAME'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('DB_PASSWORD', 'Kata Laluan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('DB_PASSWORD', env('DB_PASSWORD'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-group" align="center">
                            <button type="button" onclick='submitForm()' class="btn btn-primary btn-sm"> Simpan </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-3" class="tab-pane">
                <div class="panel-body">
                    <form id="form-email" action="{{ route('settings.update') }}" method="post" class="form-horizontal ">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('MAIL_DRIVER', 'Pemandu Mel', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('MAIL_DRIVER', env('MAIL_DRIVER'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('MAIL_HOST', 'Host / IP', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('MAIL_HOST', env('MAIL_HOST'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('MAIL_PORT', 'Port', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('MAIL_PORT', env('MAIL_PORT'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('MAIL_USERNAME', 'Nama Pengguna (username)', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('MAIL_USERNAME', env('MAIL_USERNAME'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('MAIL_PASSWORD', 'Kata Laluan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('MAIL_PASSWORD', env('MAIL_PASSWORD'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('MAIL_FROM_NAME', 'Nama Pengirim', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('MAIL_FROM_NAME', env('MAIL_FROM_NAME'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('MAIL_FROM_ADDRESS', 'Emel Pengirim', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                {{ Form::label('MAIL_ENCRYPTION', 'Enkripsi', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::text('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION'), ['class' => 'form-control input-sm']) }}
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-group" align="center">
                            <button type="button" onclick='submitForm()' class="btn btn-primary btn-sm"> Simpan </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script_datatable')
    <script type="text/javascript">
        var page = 1;

        function updatePage(id) {
            page = id;
        }
    
        function submitForm() {
            if(page == 1)
                var form = $('#form-system');
            else if(page == 2)
                var form = $('#form-database');
            else if(page == 3)
                var form = $('#form-email');
            else
                return;
            form.submit();
        }
    
        $("form").submit(function(e){
            e.preventDefault();
            var form = $(this);
            
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
                    console.log(data);
                    if(data.status=='ok'){
                        swal({
                            title: "Berjaya",
                            text: "Kemaskini Berjaya", 
                            type: "success"
                        },
                        function () {
                        });
                    }
                }
            });
            
//            console.log(new FormData(form[0]));
//            alert('Berjaya');
//            swal({
//                title: "Test Title",
//                text: "Test Text", 
//                type: "success"
//            });
        });
    </script>
@stop