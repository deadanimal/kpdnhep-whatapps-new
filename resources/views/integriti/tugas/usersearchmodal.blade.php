<?php
use App\Ref;
use App\User;
use App\Branch;
?>

<div id="carian-penerima" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Carian Penerima</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('icnew', 'No. Kad Pengenalan', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('icnew', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-sm-6">
                        <!-- <div class="form-group"> -->
                            <!-- {{-- Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) --}} -->
                            <!-- <div class="col-sm-9"> -->
                                <!-- {{-- Form::text('state_cd', Ref::GetDescr('17', $mUser->state_cd), ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                            <!-- </div> -->
                        <!-- </div> -->
                        <!-- <div class="form-group{{ $errors->has('brn_cd') ? ' has-error' : '' }}"> -->
                            <!-- {{-- Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label']) --}} -->
                            <!-- <div class="col-sm-9"> -->
                                <!-- {{-- Form::text('brn_cd', Branch::GetBranchName($mUser->brn_cd), ['class' => 'form-control input-sm', 'readonly' => true]) --}} -->
                            <!-- </div> -->
                        <!-- </div> -->
<!--                        <div class="form-group{{-- $errors->has('role_cd') ? ' has-error' : '' --}}">
                            {{-- Form::label('role_cd', 'Peranan', ['class' => 'col-sm-3 control-label']) --}}
                            <div class="col-sm-9">
                                {{-- Form::select('role_cd', Ref::GetList('152', true), null, ['class' => 'form-control input-sm', 'id' => 'role_cd']) --}}
                            </div>
                        </div>-->
                        <div class="form-group">
                            {{ Form::label('name', 'Nama', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('name', '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" align="center">
                        {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                        {{ Form::reset('Semula', ['class' => 'btn btn-default btn-sm', 'id' => 'resetbtn']) }}
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="table-responsive">
                    <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Kad Pengenalan</th>
                                <th>Nama</th>
                                <!-- <th>Negeri</th> -->
                                <!-- <th>Cawangan</th> -->
                                <th>Tugas</th>
                                <th>Peranan</th>
                                <th>Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>