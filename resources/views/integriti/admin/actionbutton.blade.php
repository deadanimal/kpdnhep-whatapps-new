@if($adminCase->IN_INVSTS == '010')
    <a href="{{ url("integritiadmin/{$adminCase->id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
        <i class="fa fa-pencil"></i>
    </a>
@endif