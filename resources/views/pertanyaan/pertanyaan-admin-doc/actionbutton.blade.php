<a onclick="ModalAttachmentEdit({{ $PertanyaanAdminDoc->id }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
    <i class="fa fa-pencil"></i>
</a>
<!--<a href="{{-- route('pertanyaan-admin-doc.destroy',['id'=>$PertanyaanAdminDoc->id]) --}}" 
    onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" class="btn btn-xs btn-danger" 
    data-toggle="tooltip" data-placement="right" title="Hapus">
    <i class="fa fa-trash"></i>
</a>-->
{!! Form::open(['route' => ['pertanyaan-admin-doc.destroy', $PertanyaanAdminDoc->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
    {{ Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit', 
        'class' => 'btn btn-danger btn-xs', 
        'data-toggle'=>'tooltip', 
        'data-placement'=>'right', 
        'title'=>'Hapus', 
        'onclick' => 'return confirm(\'Anda pasti untuk hapuskan rekod ini?\')'
        ]) 
    }}
{!! Form::close() !!}