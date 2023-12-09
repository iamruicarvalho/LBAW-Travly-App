<!-- resources/views/mensagens/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
        {{-- Left Sidebar --}}
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}">🏠 Home</a></li>
                <li><a href="#">🔍 Explore</a></li>
                <li><a href="#">🔔 Notifications</a></li>
                <li><a href="{{ route('messages.showAllConversations') }}">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="{{ route('groups') }}">👥 Groups</a></li>
            </ul>
            <div class="profile-section">
                <!-- Profile information here -->
                <a href="{{ route('profile.show', auth()->id())  }}">👤 {{ auth()->user()->username }}</a>
            </div>
        </div>

        {{-- Main Content --}}

        <link rel="stylesheet" href="{{ asset('css/messages_menu.css') }}">
            <div class="messages-show">
                <div class="header">
                    <h1>Messages</h1>
                    <div class="new-message-link">
                        <a href="#">✉️</a>
                    </div>
                </div>
                <div class="conversations-list">
                    @foreach($conversations as $conversation)
                        @if($conversation->receiver)
                            <a href="{{ route('messages.show', ['id' => $conversation->receiver]) }}">
                                <div class="conversation-item">
                                    <div class="user-avatar"></div>
                                    <div class="conversation-details">
                                        <p class="user-name">{{ $conversation->receiverName }}</p>
                                        <p class="username">{{ $conversation->receiverUsername }}</p>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>

            </div>


            <div class="messages-section" id="messagesSection">
                @if(isset($selectedConversation))
                    @foreach($selectedConversation->messages as $message)
                        <div class="message-item">
                            <div class="user-avatar"></div>
                            <div class="message-details">
                                <p class="user-name">{{ $message->sender }}</p>
                                <p class="message-content">{{ $message->content }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="default-message">
                        <p>Select a message</p>
                        <p>Choose from your existing conversations, start a new one, or just keep swimming.</p>
                    </div>
                @endif
            </div>
    </div>



@endsection
