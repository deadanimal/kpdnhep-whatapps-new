{{ Form::open(['route' => ['enquirypaper.dataentry.loots.store', $enquiryPaperCaseId]]) }}
    @includeIf('enquirypaper.dataentry.loot.fields')
    <div class="row">
        <!-- Submit Field -->
        <div class="form-group col-sm-12 m-b-none text-center">
            <a href="{{ route('enquirypaper.dataentry.loots.index', [$enquiryPaperCaseId]) }}" class="btn btn-default">
                Kembali
            </a>
            {{ Form::submit('Simpan', ['class' => 'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}
