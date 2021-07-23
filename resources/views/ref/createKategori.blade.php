@extends('layouts.main')

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h3>Tambah Kategori</h3>
                <!--        <form method="POST" action="{{ url('ref/storeKategori') }}" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                             
                                <div class="form-group">
                                    <label for="descr" class="col-sm-2 control-label">Penerangan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-sm" id="descr" name="descr">
                                    </div>
                                </div>
                           
                            </div>
                            <div class="col-sm-2"></div>
                            
                            <div class="form-group" align="center">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success btn-sm">Tambah</button>
                                    <a class="btn btn-default btn-sm" href="{{ url('ref') }}">Kembali</a>
                                </div>
                            </div>
                        </form>-->
                {!! Form::open(['url' => 'ref/storeKategori']) !!}
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <?php echo Form::label('descr', 'Penerangan', ['class' => 'col-sm-2 control-label']); ?>
                        <div class="col-sm-10">
                            <?php echo Form::text('descr', '', ['class' => 'form-control input-sm']); ?>
                        </div>
                    </div>
                    <div class="col-sm-2"><br></div>
                    <div class="form-group" align="center">
                        <div class="col-sm-offset-2 col-sm-10">
                            <?php echo Form::submit('Tambah', ['class' => 'btn btn-success btn-sm']); ?>
                            <a href="{{ url('ref')}}" type="button" class="btn btn-default btn-sm">Kembali</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
               
            </div>
        </div>
    </div>
</div>
@endsection
