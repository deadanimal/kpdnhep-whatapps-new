@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Maklumat Perinci: {{$feeds->phone}}</h2>
                <hr>
                {!! Form::open(['route' => ['whatsapp.createaduan', $feeds->id], 'class'=>'form-horizontal']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="chat-discussion"
                             style="background-color:unset !important; height: 80% !important;">
                            @foreach($feed_details as $k => $fd)
                                <div class="chat-message {{$fd->is_input === 1 ? 'left' : 'right'}}">
                                    <div class="message">
                                        <b>{{$fd->supporter_id != null ? $fd->supporter->name : $feeds->phone }}</b>
                                        <span class="message-date"> {{$fd->created_at}} </span>
                                        <span class="message-content">
                                            @if($fd->is_input === 1)
                                                <span style="margin-right: 10px; float: left; min-height: 30px">
                                                    <input type="checkbox" name="ws_detail[]" value="{{$fd->id}}">
                                                </span>
                                            @endif
                                            <div>
                                                {!! nl2br($fd->message) !!}
                                            </div>
                                            @if($fd->message_url !== null)
                                                <img max-height="200px" max-width="85%" src="{{$fd->message_url}}" alt="{{$fd->caption}}">
                                                <br><i>{{$fd->message_caption}}</i>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="textarea" class="form-control" id="feedback_reply">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary" onclick="doReply()">Balas</button>
                            </span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <a class="btn btn-plain btn-block" href="{{ url()->previous() }}">Kembali</a>
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
        console.log('ready')
      })

      function doReply() {
        var feed = $('#feedback_reply').val()
        console.log(feed)

        $.get('/feedback/whatsapp/{{$feeds->id}}/reply', { 'reply': feed, 'template': null })
          .then(function (data) {
            console.log(data)
          }, function (err) {
            console.error(err)
          })
      }
    </script>
@stop
