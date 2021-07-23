<?php // dd($CallCenterCaseDoc->id);?>

<?php //@if($CallCenterCaseDoc->callCenterCase->CA_INVSTS == '1')?>
    <!--<a onclick="ModalEditAttachment({{-- $CallCenterCaseDoc->id --}})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>-->
    <!--<a href="{{-- route('call-center-case.AttachmentDelete',['CASEID'=>$CallCenterCaseDoc->CC_CASEID,'DOCATTCHID'=>$CallCenterCaseDoc->CC_DOCATTCHID]) --}}" onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></a>-->
<?php //@elseif($CallCenterCaseDoc->callCenterCase->CA_INVSTS == '2')?>
    <!--<button class="btn btn-xs btn-primary disabled" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></button>-->
    <!--<button class="btn btn-xs btn-danger disabled" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></button>-->
<?php //@endif?>

<a onclick="ModalAttachmentEdit({{ $CallCenterCaseDoc->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
<i class="fa fa-pencil"></i></a>
<a href="{{ route('call-center-case.docdestroy',['id' => $CallCenterCaseDoc->id]) }}" onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus">
<i class="fa fa-trash"></i></a>
