<div class="row">
    <!-- No Kes Field -->
    <div class="form-group col-sm-12 col-md-6">
        <label>Nombor Fail Kes EP:</label>
        <p class="form-control-static">
            <a id="{{ $enquiryPaperCase->id }}" onclick="showSummary('{{ $enquiryPaperCase->id }}')">
                {{ $enquiryPaperCase->no_kes }}
            </a>
        </p>
    </div>

    <!-- Pegawai Penyiasat Io Field -->
    <div class="form-group col-sm-12 col-md-6{{ $errors->has('close_by_user_id') || $errors->has('close_by_user_name') ? ' has-error' : '' }}">
        {{ Form::label('close_by_user_name', 'Penutupan Oleh:', ['class' => 'control-label required']) }}
        <div class="input-group">
            {{ Form::text('close_by_user_name', null, ['class' => 'form-control', 'readonly' => true]) }}
            {{ Form::hidden('close_by_user_id', null, ['class' => 'form-control', 'readonly' => true]) }}
            <span class="input-group-btn">
                {{ Form::button('Carian '.' <i class="fa fa-search"></i>', [
                    'class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#modalOfficer'
                ]) }}
            </span>
        </div>
        @if ($errors->has('close_by_user_id'))
            <span class="help-block"><strong>{{ $errors->first('close_by_user_id') }}</strong></span>
        @elseif ($errors->has('close_by_user_name'))
            <span class="help-block"><strong>{{ $errors->first('close_by_user_name') }}</strong></span>
        @endif
    </div>

    <!-- Catatan Siasatan Field -->
    {{-- <div class="form-group col-sm-12 col-md-12{{ $errors->has('note_investigation') ? ' has-error' : '' }}"> --}}
        {{-- {{ Form::label('note_investigation', 'Catatan Siasatan:', ['class' => 'control-label']) }} --}}
        {{-- {!! Form::textarea('note_investigation', null, ['class' => 'form-control', 'readonly' => true]) !!} --}}
        {{-- @if ($errors->has('note_investigation')) --}}
            {{-- <span class="help-block"><strong>{{ $errors->first('note_investigation') }}</strong></span> --}}
        {{-- @endif --}}
    {{-- </div> --}}
    <!-- Catatan Siasatan Field -->
    <div class="form-group col-sm-12 col-md-12">
        <label>Catatan Siasatan:</label>
        <p class="form-control-static"></p>
    </div>

    <!-- Catatan Field -->
    <div class="form-group col-sm-12 col-md-12{{ $errors->has('note_detail') ? ' has-error' : '' }}">
        {{ Form::label('note_detail', 'Catatan:', ['class' => 'control-label required']) }}
        {!! Form::textarea('note_detail', null, ['class' => 'form-control']) !!}
        @if ($errors->has('note_detail'))
            <span class="help-block"><strong>{{ $errors->first('note_detail') }}</strong></span>
        @endif
    </div>
</div>
