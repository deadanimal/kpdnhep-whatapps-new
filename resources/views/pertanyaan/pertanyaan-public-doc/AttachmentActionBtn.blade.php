<?php if(Auth()->user()->lang == 'ms'){?>
    <a onclick="ModalAttachmentEdit({{ $PertanyaanPublicDoc->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
    <a href="{{ route('pertanyaan-public-doc.destroy',['id'=>$PertanyaanPublicDoc->id]) }}" onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></a>
<?php } else { ?>
    <a onclick="ModalAttachmentEdit({{ $PertanyaanPublicDoc->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Update"><i class="fa fa-pencil"></i></a>
    <a href="{{ route('pertanyaan-public-doc.destroy',['id'=>$PertanyaanPublicDoc->id]) }}" onclick="return confirm('Are you sure to delete this record?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash"></i></a>
<?php } ?>