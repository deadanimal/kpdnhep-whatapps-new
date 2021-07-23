@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Maklumat Perinci: {{$feeds->name}} ( {{$feeds->phone}} )</h2>
                <hr>
                @if($feeds->supporter_id === null)
                    {!! Form::open(['route' => ['telegram.mytask.add', $feeds->id], 'class'=>'form-horizontal']) !!}
                @elseif(auth()->user()->role->role_code == 800 || auth()->user()->role->role_code == 120)
                    {!! Form::open(['route' => ['telegram.mytask.release', $feeds->id], 'class'=>'form-horizontal']) !!}
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="chat-discussion" style="background-color:unset !important; height: 80% !important;">
                            @foreach($feed_details as $k => $fd)
                                <div class="chat-message {{$fd->is_input === 1 ? 'left' : 'right'}}">
                                    <div class="message">
                                        <b>{{$fd->is_input === 1 ? $feeds->phone : 'KPDNHEP' }}</b>
                                        <span class="message-date"> {{$fd->created_at}} </span>
                                        <span class="message-content m-t-md">
                                            @if($fd->attachment_url !== null)
                                                @if(strpos($fd->attachment_mime, 'image') !== false)
                                                    <img height="200px"
                                                         src="{{\App\Repositories\Feedback\Telegram\TelegramAPIRepository::getAttachmentUrl($fd->attachment_url, $fd->attachment_mime)}}"
                                                         alt="{{$fd->message_text}}">
                                                    <br><span>{{$fd->message_caption}}</span>
                                                    <hr>
                                                @endif
                                                @if(strpos($fd->attachment_mime, 'application') !== false)
                                                    <a href="{{\App\Repositories\Feedback\Telegram\TelegramAPIRepository::getAttachmentUrl($fd->attachment_url, $fd->attachment_mime)}}">
                                                        {{$fd->message_text}}
                                                    </a>
                                                    <hr>
                                                @endif
                                            @endif
                                            <div>{!! nl2br($fd->message_text) !!}</div>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-info btn-block" href="{{ route('telegram.index') }}"><i
                                    class="fa fa-arrow-left fa-2x"></i><br>Kembali</a>
                    </div>
                    @if($feeds->supporter_id === null)
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-block"><i class="fa fa-plus fa-2x"></i><br>Add To My Task
                            </button>
                        </div>
                    @elseif(auth()->user()->role->role_code == 800 || auth()->user()->role->role_code == 120)
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-block"><i class="fa fa-bomb fa-2x"></i><br>Release Task
                                From This Person
                            </button>
                        </div>
                    @endif
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
    </script>
@stop

