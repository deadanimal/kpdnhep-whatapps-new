<a 
    onclick="ModalAttachmentEdit({{ $adminCaseDoc->id }})" 
    class="btn btn-xs btn-primary" 
    data-toggle="tooltip" 
    data-placement="right" 
    title="Kemaskini"
>
    <i class="fa fa-pencil"></i>
</a>
<a 
    href="{{ route('integritikemaskinidoc.destroy',['id'=>$adminCaseDoc->id]) }}" 
    onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" 
    class="btn btn-xs btn-danger" 
    data-toggle="tooltip" 
    data-placement="right" 
    title="Hapus"
>
    <i class="fa fa-trash"></i>
</a>