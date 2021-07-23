@extends('layouts.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h3>Kemaskini Kategori Parameter</h3>
                <form method="POST" action="{{ url('ref/putkat', $mRef->id) }}" class="form-horizontal">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">

<!--                        <div class="form-group">
                            <label for="category" class="col-sm-2 control-label">Kategori</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" id="category" name="category">
                            </div>
                        </div>-->

                        <div class="form-group">
                            <label for="code" class="col-sm-2 control-label">Kategori</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" id="code" name="code" readonly="true" value="{{ $mRef->code }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descr" class="col-sm-2 control-label">Penerangan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" id="descr" name="descr" value="{{ $mRef->descr }}">
                            </div>
                        </div>

<!--                        <div class="form-group">
                            <label for="sort" class="col-sm-2 control-label">Susunan</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control input-sm" id="sort" name="sort">
                            </div>
                        </div>-->

                    </div>
                    <div class="col-sm-2"></div>

                    <div class="form-group" align="center">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-default btn-sm" href="{{ url('ref') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
