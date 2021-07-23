@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Maklumat Perinci: {{$feeds->phone}}</h2>
                <hr>
                @if($feeds->supporter_id === null)
                    {!! Form::open(['route' => ['email.mytask.add', $feeds->id], 'class'=>'form-horizontal']) !!}
                @elseif(auth()->user()->role->role_code == 800 || auth()->user()->role->role_code == 120)
                    {!! Form::open(['route' => ['email.mytask.release', $feeds->id], 'class'=>'form-horizontal']) !!}
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="chat-discussion" style="background-color:unset !important; height: 80% !important;">
                            @foreach($feed_details as $k => $fd)
                                <div class="chat-message {{$fd->is_input === 1 ? 'left' : 'right'}}">
                                    <div class="message">
                                        <b>{{$fd->is_input === 1 ? $feeds->email : 'KPDNHEP' }}</b>
                                        <span class="message-date"> {{$fd->created_at}} </span>
                                        <span class="message-content m-t-md">
                                            <p>Subjek: <b>{{$fd->subject}}</b></p>
                                            <div>{!! nl2br($fd->body) !!}</div>
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
