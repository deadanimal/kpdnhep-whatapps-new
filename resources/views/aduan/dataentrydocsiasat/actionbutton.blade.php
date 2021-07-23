<a class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" 
   title="Kemaskini" onclick="ModalDocSiasatEdit({{ $dataEntryDoc->id }})">
    <i class="fa fa-pencil"></i>
</a>
{{ Form::open([
    'route' => ['dataentrydocsiasat.destroy', $dataEntryDoc->id], 
    'class' => 'form-horizontal', 
    'method' => 'DELETE', 
    'id' => 'form-delete', 
    'style' => 'display:inline'
]) }}
{{ Form::button('<i class="fa fa-trash"></i>', [
    'type' => 'submit', 
    'class' => 'btn btn-danger btn-xs', 
    'data-toggle' => 'tooltip', 
    'data-placement' => 'right', 
    'title' => 'Hapus', 
    'onclick' => 'return confirm("Anda pasti untuk hapuskan rekod ini?")'
]) }}
{{ Form::close() }}