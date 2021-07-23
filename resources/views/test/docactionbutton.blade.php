<!-- <a 
    onclick="ModalAttachmentEdit({{-- $PublicCaseDoc->id --}})" 
    class="btn btn-xs btn-primary" 
    data-toggle="tooltip" 
    data-placement="right" 
    title="Kemaskini">
    <i class="fa fa-pencil"></i>
</a> -->
<!-- <a 
    href="{{-- route('testappdoc.destroy',['id'=>$PublicCaseDoc->id]) --}}" 
    onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" 
    class="btn btn-xs btn-danger" 
    data-toggle="tooltip" 
    data-placement="right" 
    title="Hapus">
    <i class="fa fa-trash"></i>
</a> -->

{{ 
    Form::open([
    'route' => ['testappdoc.destroy', $PublicCaseDoc->id], 
    'class' => 'form-horizontal', 
    'method' => 'DELETE', 
    'id' => 'form-delete', 
    'style' => 'display:inline'
    ]) 
}}
{{ 
    Form::button(
        '<i class="fa fa-trash"></i>', 
        [
        'type' => 'submit', 
        'class' => 'btn btn-danger', 
        'data-toggle' => 'tooltip', 
        'data-placement' => 'right', 
        'title' => 'Hapus', 
        'onclick' => 'return confirm("Anda pasti untuk hapuskan rekod ini?")'
        ]
    ) 
}}
{{ Form::close() }}