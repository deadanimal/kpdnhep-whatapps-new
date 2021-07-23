@if($PertanyaanPublic->AS_ASKSTS == '1')
    <a href="{{ url("pertanyaan-public/{$PertanyaanPublic->id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
@else
    <a href="{{ url("pertanyaan-public/check/{$PertanyaanPublic->AS_ASKID}") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Papar"><i class="fa fa-search"></i></a>
@endif
