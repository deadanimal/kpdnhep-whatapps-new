@if($PertanyaanAdmin->AS_ASKSTS == '2' && (Auth::user()->role->role_code == '180' || Auth::user()->role->role_code == '800'))
    <a href="{{ url("pertanyaan-admin/{$PertanyaanAdmin->AS_ASKID}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Jawab">
       <i class="fa fa-pencil"></i>
    </a>
@elseif(($PertanyaanAdmin->AS_ASKSTS == '1')&&($PertanyaanAdmin->AS_CREBY == Auth::user()->id))
    <!--<a href="{{-- url("public-case/check/{$pcase->CA_CASEID}") --}}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>-->
    <a href="{{ url("pertanyaan-admin/editadmin/{$PertanyaanAdmin->id}") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
       <i class="fa fa-pencil"></i>
    </a>
@endif
