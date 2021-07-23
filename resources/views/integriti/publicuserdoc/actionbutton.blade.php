<!-- <a onclick="" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="trans('public-case.case.CA_CMPLCAT')"> -->
<!-- <a onclick="ModalAttachmentEdit({{-- $PublicCaseDoc->id --}})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"> -->
    <!-- <i class="fa fa-pencil"></i> -->
<!-- </a> -->
<!-- {{-- $PublicCaseDoc->id --}} -->
<a onclick="ModalAttachmentEdit({{ $PublicCaseDoc->id }})" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
<!--<a href="{{-- route('admin-case-doc.destroy',['id'=>$adminCaseDoc->id]) --}}" onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus">-->
<!-- <a onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus">
    <i class="fa fa-trash"></i>
</a> -->
<a href="{{ route('integritipublicuserdoc.destroy',['id'=>$PublicCaseDoc->id]) }}" onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></a>
