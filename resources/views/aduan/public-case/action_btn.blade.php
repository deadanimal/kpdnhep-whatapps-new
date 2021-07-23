@if($pcase->CA_INVSTS == '10')
    <a href="{{ url("public-case/{$pcase->id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
    <a href="{{ url("public-case/delete/$pcase->id") }}" class="btn btn-xs btn-danger" data-toggle="tooltip" onclick = "return confirm('@lang('action.delete')')" data-placement="right" title="Hapus">
       <i class="fa fa-trash"></i>
    </a>
@elseif(($pcase->CA_INVSTS == '7') && ($pcase->GetDuration($pcase->CA_RCVDT, $pcase->CA_CASEID) < $pcase->GetTempohMaklumatTidakLengkap()))
    <a href="{{ url("public-case/{$pcase->id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
@else
    <a href="{{ url("public-case/check/{$pcase->CA_CASEID}") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Papar"><i class="fa fa-search"></i></a>
@endif
