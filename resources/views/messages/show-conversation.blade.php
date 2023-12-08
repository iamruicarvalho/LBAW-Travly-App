@extends('layouts.app')

@section('content')
    <div class="conversation-show">
        <h1>Conversa</h1>
        <div class="message-list">
            @foreach($messages as $message)
                <div class="message">
                    <p class="user-name">{{ $message->senderUser->sender }}</p>
                    <p class="message-content">{{ $message->description_ }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
