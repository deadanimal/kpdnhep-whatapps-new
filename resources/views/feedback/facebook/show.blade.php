@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Maklumat Perinci:</h2>
                <hr>
                {!! Form::open(['route' => ['facebook.addtomytask', $feeds->id], 'class'=>'form-horizontal']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="chat-discussion" style="background-color:unset !important; height: 80% !important;">
                            <div class="chat-message center">
                                <div class="message">
                                    <b>{{$feeds->supporter_id != null ? $feeds->supporter->name : '' }}{{$feeds->profile_id != null ? $feeds->profile->name : 'System'}}</b>
                                    <span class="message-date"> {{$feeds->created_at}} </span>
                                    <span class="message-content">
                                        {{$feeds->message}}
                                    </span>
                                </div>
                            </div>
                            @foreach($feed_details as $k => $fd)
                                <div class="chat-message {{$fd->profile_id === null ? 'right' : 'left'}}">
                                    <div class="message">
                                        <b>{{$fd->supporter_id != null ? $fd->supporter->name : '' }}{{$fd->profile_id != null ? $fd->profile->name : 'System'}}</b>
                                        <span class="message-date"> {{$fd->created_at}} </span>
                                        <span class="message-content">
                                            {{$fd->message}}
                                            @if($fd->message_url !== null)
                                                <img height="200px" src="{{$fd->message_url}}" alt="{{$fd->caption}}">
                                                <br><i>{{$fd->message_caption}}</i>
                                            @endif                                         
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <a class="btn btn-plain btn-block" href="{{ url()->previous() }}">Kembali</a>
                    </div>
                    <div class="col-md-6 col-md-push-3">
                        <button class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Add To My Task</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('script_datatable')
@stop
