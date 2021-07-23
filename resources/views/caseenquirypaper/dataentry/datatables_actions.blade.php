@if(!empty($enquiryPaperCase)
    && !empty($enquiryPaperCase->case_status_code)
    && in_array($enquiryPaperCase->case_status_code, [1]))
    <div class='btn-group'>
        <a href="{{ route('caseenquirypaper.dataentries.edit', $enquiryPaperCase->id) }}" class='btn btn-primary btn-xs' data-toggle='tooltip' data-placement='right' title='Kemaskini'>
            <i class="fa fa-edit"></i>
        </a>
    </div>
@endif
