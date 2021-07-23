{{ Form::open(['route' => ['enquirypaper.dataentry.loots.destroy', $enquiryPaperCase->id, $enquiryPaperCaseLoot->id], 'method' => 'delete']) }}
	<div class='btn-group'>
	    <a onclick="lootEditModal({{ $enquiryPaperCase->id}}, {{$enquiryPaperCaseLoot->id}})" class='btn btn-primary btn-sm'>
	        <i class="fa fa-edit"></i>
	    </a>
		{{ Form::button('<i class="fa fa-trash"></i>', [
	        'type' => 'submit',
	        'class' => 'btn btn-danger btn-sm',
	        'onclick' => 'return confirm("Anda Pasti untuk padam rekod ini?")',
	        'data-toggle' => 'tooltip', 
    		'data-placement' => 'right', 
    		'title' => 'Padam', 
	    ]) }}
	</div>
{{ Form::close() }}
