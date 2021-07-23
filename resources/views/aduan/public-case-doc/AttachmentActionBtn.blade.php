@if($PublicCase->CA_INVSTS == '10' || $PublicCase->CA_INVSTS == '7')
    <?php if(Auth()->user()->lang == 'ms'){?>
    <a onclick="ModalAttachmentEdit({{ $PublicCaseDoc->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
    <a href="{{ route('public-case-doc.destroy',['id'=>$PublicCaseDoc->id]) }}" onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></a>
    <?php } else { ?>
    <a onclick="ModalAttachmentEdit({{ $PublicCaseDoc->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Update"><i class="fa fa-pencil"></i></a>
    <a href="{{ route('public-case-doc.destroy',['id'=>$PublicCaseDoc->id]) }}" onclick="return confirm('Are you sure to delete this record?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash"></i></a>
    <?php } ?>
@else
    <?php if(Auth()->user()->lang == 'ms') { ?>
    <!--<button class="btn btn-xs btn-primary disabled" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></button>-->
    <button class="btn btn-xs btn-danger disabled" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></button>
    <?php } else { ?>
    <!--<button class="btn btn-xs btn-primary disabled" data-toggle="tooltip" data-placement="right" title="Update"><i class="fa fa-pencil"></i></button>-->
    <button class="btn btn-xs btn-danger disabled" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash"></i></button>
    <?php }?>
@endif
