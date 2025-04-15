@extends('layouts.app')

@section('title', 'Chat - Pax Republica')

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <!-- Conversations sidebar -->
        <div class="col-md-4 col-lg-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Conversations</h4>
                <a href="{{ route('chat.new') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> New Chat
                </a>
            </div>

            <div class="card">
                <div class="list-group list-group-flush conversation-list">
                    @if($conversations->isEmpty())
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-chat-square-dots" style="font-size: 2rem;"></i>
                            <p class="mt-2">No conversations yet.<br>Start a new chat!</p>
                        </div>
                    @else
                        @foreach($conversations as $user)
                            <a href="{{ route('chat.index', ['user' => $user->id]) }}" 
                                class="list-group-item list-group-item-action d-flex align-items-center conversation-item {{ isset($selectedUser) && $selectedUser->id == $user->id ? 'active' : '' }}">
                                <div class="position-relative">
                                    <div class="avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center">
                                        {{ strtoupper(substr($user->account_name, 0, 1)) }}
                                    </div>
                                    @if($user->unread_count > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $user->unread_count }}
                                        </span>
                                    @endif
                                    @if($user->isOnline())
                                        <span class="position-absolute bottom-0 end-0 translate-middle-x">
                                            <span class="online-indicator"></span>
                                        </span>
                                    @endif
                                </div>
                                <div class="ms-3 flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-truncate">{{ $user->account_name }}</h6>
                                        @if($user->last_message)
                                            <small class="text-muted">{{ $user->last_message->created_at->diffForHumans(null, true) }}</small>
                                        @endif
                                    </div>
                                    @if($user->last_message)
                                        <p class="mb-0 small text-truncate {{ $user->unread_count > 0 && $user->last_message->sender_id != auth()->id() ? 'fw-bold' : 'text-muted' }}">
                                            @if($user->last_message->sender_id == auth()->id())
                                                <i class="bi bi-check2 me-1"></i> 
                                            @endif
                                            {{ Str::limit($user->last_message->content, 30) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat area -->
        <div class="col-md-8 col-lg-9">
            <div class="card chat-container">
                @if(!isset($selectedUser))
                    <div class="chat-placeholder d-flex flex-column align-items-center justify-content-center">
                        <div class="chat-icon mb-3">
                            <i class="bi bi-chat-square-text" style="font-size: 4rem;"></i>
                        </div>
                        <h4>Welcome to Chat</h4>
                        <p class="text-muted mb-4">Select a conversation to start chatting</p>
                        <a href="{{ route('chat.new') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Start New Conversation
                        </a>
                    </div>
                @else
                    <!-- Chat header -->
                    <div class="chat-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="position-relative">
                                <div class="avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2">
                                    {{ strtoupper(substr($selectedUser->account_name, 0, 1)) }}
                                </div>
                                @if($selectedUser->isOnline())
                                    <span class="position-absolute bottom-0 end-0 translate-middle-x">
                                        <span class="online-indicator"></span>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $selectedUser->account_name }}</h5>
                                <small class="text-muted" id="user-status">
                                    {{ $selectedUser->isOnline() ? 'Online' : ($selectedUser->last_activity_at ? 'Last seen ' . $selectedUser->last_activity_at->diffForHumans() : 'Offline') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Chat messages -->
                    <div class="chat-messages p-3" id="chat-messages">
                        @foreach($messages as $message)
                            <div class="message-item {{ $message->sender_id == auth()->id() ? 'message-out' : 'message-in' }}" data-message-id="{{ $message->id }}">
                                <div class="message-bubble">
                                    <div class="message-content">{{ $message->content }}</div>
                                    <div class="message-time small text-muted">
                                        {{ $message->created_at->format('g:i A') }}
                                        @if($message->sender_id == auth()->id() && $message->is_read)
                                            <i class="bi bi-check2-all ms-1"></i>
                                        @elseif($message->sender_id == auth()->id())
                                            <i class="bi bi-check2 ms-1"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Chat input -->
                    <div class="chat-input border-top p-3">
                        <form id="message-form" class="d-flex">
                            <input type="hidden" name="recipient_id" value="{{ $selectedUser->id }}">
                            <input type="text" name="content" id="message-input" class="form-control me-2" placeholder="Type your message..." required>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($selectedUser))
            // Scroll to bottom of messages
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Send message with Ajax
            const messageForm = document.getElementById('message-form');
            messageForm.addEventListener('submit', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                const messageInput = document.getElementById('message-input');
                const content = messageInput.value.trim();
                
                if (content) {
                    const submitButton = this.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    
                    fetch('{{ route('chat.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            recipient_id: {{ $selectedUser->id }},
                            content: content
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add message to chat
                            addMessageToChat(data.message, true);
                            
                            // Clear input
                            messageInput.value = '';
                            
                            // Update conversations list
                            updateConversations();
                        }
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                    });
                }
                
                return false;
            });
            
            // Poll for new messages
            let lastMessageId = getLastMessageId();
            
            setInterval(function() {
                fetchNewMessages(lastMessageId);
            }, 5000);
            
            function fetchNewMessages(lastId) {
                fetch('{{ route('chat.fetch') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: {{ $selectedUser->id }},
                        last_message_id: lastId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        data.messages.forEach(message => {
                            addMessageToChat(message, false);
                        });
                        
                        lastMessageId = getLastMessageId();
                        
                        // Also update conversations to reflect read status
                        updateConversations();
                    }
                });
            }
            
            function addMessageToChat(message, isOutgoing) {
                const chatMessages = document.getElementById('chat-messages');
                
                const messageItem = document.createElement('div');
                messageItem.className = `message-item ${isOutgoing || message.sender_id == {{ auth()->id() }} ? 'message-out' : 'message-in'}`;
                messageItem.dataset.messageId = message.id;
                
                messageItem.innerHTML = `
                    <div class="message-bubble">
                        <div class="message-content">${message.content}</div>
                        <div class="message-time small text-muted">
                            ${formatTime(message.created_at)}
                            ${isOutgoing ? '<i class="bi bi-check2 ms-1"></i>' : ''}
                        </div>
                    </div>
                `;
                
                chatMessages.appendChild(messageItem);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            
            function getLastMessageId() {
                const messages = document.querySelectorAll('.message-item');
                return messages.length > 0 ? messages[messages.length - 1].dataset.messageId : 0;
            }
            
            function formatTime(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
            }
            
            function updateConversations() {
                fetch('{{ route('chat.conversations') }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update unread counts in sidebar, could be implemented later
                        }
                    });
            }

            // Poll for user status
            setInterval(function() {
                checkUserStatus();
            }, 30000);
            
            function checkUserStatus() {
                fetch('{{ route('chat.user-status') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: {{ $selectedUser->id }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const statusElement = document.getElementById('user-status');
                        if (statusElement) {
                            if (data.is_online) {
                                statusElement.textContent = 'Online';
                            } else if (data.last_activity) {
                                statusElement.textContent = 'Last seen ' + data.last_activity;
                            } else {
                                statusElement.textContent = 'Offline';
                            }
                        }
                    }
                });
            }
        @endif
    });
</script>
@endpush

<style>
.avatar {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
}

.online-indicator {
    width: 10px;
    height: 10px;
    background-color: #31a24c;
    border-radius: 50%;
    border: 2px solid white;
    display: inline-block;
}

.conversation-item {
    padding: 10px 15px;
}

.conversation-item.active {
    background-color: #f8f9fa;
    border-left: 3px solid #0d6efd;
}

.chat-container {
    height: calc(100vh - 150px);
    display: flex;
    flex-direction: column;
}

.chat-placeholder {
    height: 100%;
    color: #6c757d;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.message-item {
    max-width: 75%;
    margin-bottom: 15px;
    display: flex;
}

.message-in {
    align-self: flex-start;
}

.message-out {
    align-self: flex-end;
}

.message-bubble {
    padding: 10px 15px;
    border-radius: 15px;
    position: relative;
}

.message-in .message-bubble {
    background-color: #f0f2f5;
    border-bottom-left-radius: 5px;
}

.message-out .message-bubble {
    background-color: #e7f1ff;
    border-bottom-right-radius: 5px;
    text-align: right;
}

.message-content {
    word-wrap: break-word;
}

.message-time {
    margin-top: 5px;
    font-size: 0.7rem;
}
</style> 