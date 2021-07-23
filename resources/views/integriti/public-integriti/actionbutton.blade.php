<!-- {{-- @if($integritiPublic->IN_INVSTS == '010') --}} -->
@if(in_array($integritiPublic->IN_INVSTS,['010', '07']))
    <a href="{{ url("public-integriti/{$integritiPublic->id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title=@lang('button.edit')>
        <i class="fa fa-pencil"></i>
    </a>
    <!-- <a href="{{-- url("public-case/delete/$integritiPublic->id") --}}" class="btn btn-xs btn-danger" data-toggle="tooltip" onclick = "return confirm('@lang('action.delete')')" data-placement="right" title="Hapus"> -->
       <!-- <i class="fa fa-trash"></i> -->
    <!-- </a> -->
<!-- {{-- @elseif(($integritiPublic->IN_INVSTS == '7') && ($integritiPublic->GetDuration($integritiPublic->IN_RCVDT, $integritiPublic->IN_CASEID) < $integritiPublic->GetTempohMaklumatTidakLengkap())) --}} -->
<!-- {{-- @elseif($integritiPublic->IN_INVSTS == '07') --}} -->
    <!-- <a href="{{-- url("public-integriti/{$integritiPublic->id}/edit") --}}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title=@lang('button.edit')> -->
        <!-- <i class="fa fa-pencil"></i> -->
    <!-- </a> -->
<!-- {{-- @else --}} -->
    <!-- <a href="{{-- url("public-case/check/{$integritiPublic->IN_CASEID}") --}}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Papar"><i class="fa fa-search"></i></a> -->
@endif
