@if($adminCase->CA_INVSTS == '10')
    <a href="{{ url("admin-case/{$adminCase->id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
    <a href="{{ url("admin-case/delete/$adminCase->id") }}" class="btn btn-xs btn-danger" data-toggle="tooltip" onclick = "return confirm('@lang('action.delete')')" data-placement="right" title="Hapus">
       <i class="fa fa-trash"></i>
    </a>
@else
    <a href="{{ url("admin-case/check/{$adminCase->CA_CASEID}") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-search"></i></a>
@endif
