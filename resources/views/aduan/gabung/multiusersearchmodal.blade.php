<?php
use App\Ref;
use App\User;
use App\Branch;
?>

<div id="carian-lain2-penerima" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Carian Penerima</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['id' => 'search-form-multi', 'class' => 'form-horizontal']) !!}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('icnew', 'No. Kad Pengenalan', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('icnew', '', ['class' => 'form-control input-sm', 'id' => 'icnew_multi']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('name', 'Nama Pengguna', ['class' => 'col-sm-5 control-label']) }}
                            <div class="col-sm-7">
                                {{ Form::text('name', '', ['class' => 'form-control input-sm', 'id' => 'name_multi']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('state_cd', 'Negeri', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::text('state_cd', Ref::GetDescr('17', $mUser->state_cd), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('brn_cd') ? ' has-error' : '' }}">
                            {{ Form::label('brn_cd', 'Cawangan', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::text('brn_cd', Branch::GetBranchName($mUser->brn_cd), ['class' => 'form-control input-sm', 'readonly' => true]) }}
                            </div>
                        </div>
<!--                        <div class="form-group{{-- $errors->has('role_cd') ? ' has-error' : '' --}}">
                            {{-- Form::label('role_cd', 'Peranan', ['class' => 'col-sm-3 control-label']) --}}
                            <div class="col-sm-9">
                                {{-- Form::select('role_cd', Ref::GetList('152', true), null, ['class' => 'form-control input-sm', 'id' => 'role_cd']) --}}
                            </div>
                        </div>-->
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
                    <table id="users-multi-table" class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Kad Pengenalan</th>
                                <th>Nama</th>
                                <th>Negeri</th>
                                <th>Cawangan</th>
                                <th>Tugas</th>
                                <th>Peranan</th>
                                <th></th>
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

<script type="text/javascript">
    var oMembers = {};
    oMembers.arr_persons = [];
    
    function is_exist(user_id) {
        arr = oMembers.arr_persons;
        
        for(var i=0; i<arr.length; i++) {
            var user_id2 = arr[i].user_id;
            if (user_id == user_id2)
                return true;
        }
        return false;
    }
    
    function anyCheck(id,name){
        var oPerson = {};
        oPerson.name = name;
        oPerson.user_id = id;
        
        if (!is_exist(oPerson.user_id)) { // jika belum wujud, add ke dlm array
            oMembers.arr_persons.push(oPerson);
        }else{
            var oRep = oMembers;
            var arr_persons = oRep.arr_persons;
            for(var i=0; i < arr_persons.length; i++) {
                if (oPerson.user_id == arr_persons[i].user_id) {
                    console.log(i);
                    arr_persons.splice(i, 1);
                }
            }
        }

        var arr_persons = oMembers.arr_persons;
        
        var obj1 = $('#recipient1').html('');  
        var obj2 = $('#recipient2').html(''); 
        
        for(var i=0; i < arr_persons.length; i++) {
            str = "<input type='hidden' name='recipient[]' value='"+arr_persons[i].name+"' />";
            obj1.append(str);
            str = arr_persons[i].name + " <a href='#' onclick='do_remove(\""+arr_persons[i].user_id+"\")'> </a> <br />";
            obj2.append(str);
        }
    }
</script>