<a 
	href="{{ url("holiday/edit", $holiday) }}" 
	class="btn btn-xs btn-primary" 
	data-toggle="tooltip" 
	data-placement="right" 
	title="Kemaskini">
   <i class="fa fa-pencil"></i>
</a>
{!! Form::open([
	"url" => ["holiday/delete", $holiday], 
	"method" => "GET", 
	"style"=>"display:inline"
]) !!}
{!! Form::button("<i class='fa fa-trash'></i>", [
	"type" => "submit", 
	"class" => "btn btn-danger btn-xs", 
	"data-toggle" => "tooltip", 
	"data-placement" => "right", 
	"title" => "Hapus", 
	"onclick" => "return confirm('Anda pasti untuk hapuskan rekod ini?')"
]) !!}
{!! Form::close() !!}