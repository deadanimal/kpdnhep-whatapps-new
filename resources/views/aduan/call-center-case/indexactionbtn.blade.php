<!--<a href="{{ route('call-center-case.edit',$callcentercase->id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>-->

<!--{{-- @if($callcentercase->CA_INVSTS == '10' || (($date == date('Y-m-d', strtotime($callcentercase->CA_RCVDT)))) && $callcentercase->CA_INVSTS == '1') --}}-->
@if($callcentercase->CA_INVSTS == '10')
    <a href="{{ url("call-center-case/{$callcentercase->id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
       <i class="fa fa-pencil"></i>
    </a>
    @if(empty($callcentercase->CA_CASEID))
        <!--<a href="{{ url("call-center-case/delete/$callcentercase->id") }}" class="btn btn-xs btn-danger" data-toggle="tooltip" onclick = "return confirm('@lang('action.delete')')" data-placement="right" title="Hapus">-->
           <!--<i class="fa fa-trash"></i>-->
        <!--</a>--> 
    @endif
@elseif(($date == date('Y-m-d', strtotime($callcentercase->CA_RCVDT))) && $callcentercase->CA_INVSTS == '1')
    <a href="{{ url("call-center-case/{$callcentercase->id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
       <i class="fa fa-pencil"></i>
    </a> 
@else
    <a href="{{ url("call-center-case/check/{$callcentercase->CA_CASEID}") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
       <i class="fa fa-search"></i>
    </a>
@endif
