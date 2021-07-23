@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Maklumat Perinci: {{$feeds->name}} ( {{$feeds->phone}} )</h2>
                <hr>
                {!! Form::open(['route' => ['whatsapp.createaduan', $feeds->id], 'class'=>'form-horizontal']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="chat-discussion"
                             style="background-color:unset !important; height: 80% !important;">
                            @foreach($feed_details as $k => $fd)
                                <div class="chat-message {{$fd->is_input === 1 ? 'left' : 'right'}}">
                                    <div class="message">
                                        <b>{{$fd->is_input === 1 ? $feeds->phone : 'KPDNHEP' }}</b>
                                        <span class="message-date"> {{$fd->created_at}} </span>
                                        <span class="message-content m-t-md">
                                            @if($fd->is_input === 1)
                                                <span style="margin-right: 10px; float: left; min-height: 30px">
                                                    <input type="checkbox" name="ws_detail[]" value="{{$fd->id}}">
                                                </span>
                                            @endif
                                            @if($fd->message_url !== null)
                                                @if(strpos($fd->message_mimetype, 'image') !== false)
                                                    <img height="200px" src="{{$fd->message_url}}"
                                                         alt="{{$fd->message_caption}}">
                                                    <br><span>{{$fd->message_caption}}</span>
                                                    <hr>
                                                @endif
                                                @if(strpos($fd->message_mimetype, 'application') !== false)
                                                    <a href="{{$fd->message_url}}">{{$fd->message_caption}}</a>
                                                    <hr>
                                                @endif
                                            @endif
                                            <div>{!! nl2br($fd->message) !!}</div>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group" id="status_template_div">
                                    {{ Form::label('CA_SUMMARY', 'Status', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {!! Form::select('status_template_id', $status, null, ['class' => 'form-control input-sm', 'id' => 'status_template_id', 'onchange'=>'selectTemplateByStatus()', 'placeholder' => 'Sila pilih jika perlu']); !!}
                                        {!! Form::hidden('template_id', '', ['class' => 'form-control input-sm', 'id' => 'template_id']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group" id="template_div" style="display:none">
                                    {{ Form::label('CA_SUMMARY', 'Template', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {!! Form::select('substatus_template_id', [], null, ['class' => 'form-control input-sm', 'id' => 'substatus_template_id', 'onchange'=>'selectTemplate()']); !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-push-1">
                        {!! Form::textarea('username', null, ['class' => 'form-control', 'id' => 'feedback_reply', 'rows'=>20]); !!}
                        <button type="button" class="btn btn-primary btn-block" onclick="doReply()">Balas</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <a class="btn btn-plain btn-block" href="{{ route('whatsapp.mytask.index') }}">Kembali</a>
                    </div>
                    <div class="col-md-3">
                        @if($is_all === 0)
                            <a href="?is_all=1" class="btn btn-info btn-block">Lihat semua chat</a>
                        @else
                            <a href="?is_all=0" class="btn btn-info btn-block">Lihat chat baharu</a>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Cipta aduan baharu
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('script_datatable')
    <script>
      $(document).ready(function () {
        window.scrollTo(0,document.body.scrollHeight);
        console.log('ready')
      })

      function selectTemplateByStatus() {
        var template = $('#status_template_id').val()
        if ($.isNumeric(parseInt(template))) {
          selectTemplate(template)
          $('#template_id').val(template)
          $('#template_div').hide()
        } else {
          $('#template_id').val('')
          let dropdown = $('#substatus_template_id')
          dropdown.empty()
          dropdown.append('<option selected="true" disabled>Sila pilih Subtemplate</option>')
          dropdown.prop('selectedIndex', 0)
          $.getJSON('/feedback/template/' + template + '/bystatus', function (data) {
            $.each(data, function (key, entry) {
              dropdown.append($('<option></option>').attr('value', key).text(entry))
            })
          })
          $('#template_div').show()
        }
      }

      function selectTemplate(template = null) {
        if (template == null) {
          template = $('#substatus_template_id').val()
        }
        $('#template_id').val(template)
        $.get('/feedback/template/' + template + '/all')
          .then(function (data) {
            $('#feedback_reply').val(data.trim())
            console.log(data)
          }, function (err) {
            console.error(err)
          })
      }

      function doReply() {
        var feed = $('#feedback_reply').val()
        console.log(feed)

        const sleep = (milliseconds) => {
          return new Promise(resolve => setTimeout(resolve, milliseconds))
        }

        $.get('/feedback/whatsapp/{{$feeds->id}}/reply', {
          'reply': feed,
          'template': null,
          'template_id': $('#template_id').val()
        })
          .then(function (data) {
            console.log(data)
            sleep(2000).then(() => {
              window.location.replace('/feedback/whatsapp/mytask')
            })
          }, function (err) {
            console.error(err)
          })
      }
    </script>
@stop
