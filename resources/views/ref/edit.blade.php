@extends('layouts.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h1>Kemaskini Kategori </h1>
                <hr>
                {!! Form::open(['url' => 'ref/storeKategori']) !!}
                <div class="form-group">
                    <?php echo Form::label('descr', 'Penerangan', ['class' => 'col-sm-2 control-label']); ?>
                    <div class="col-sm-10">
                        <?php echo Form::text('descr', '', ['class' => 'form-control input-sm']); ?>
                    </div>
                </div>

                <div class="col-sm-2"><br></div>
                <div class="form-group" align="center">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?php echo Form::submit('Kemaskini', ['class' => 'btn btn-success btn-sm']); ?>
                        <a href="{{ url('ref')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection