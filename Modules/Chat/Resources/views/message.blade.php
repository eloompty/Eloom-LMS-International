@extends('user::layouts.master')
@section('title', 'Admin | Chat Messages')

@section('header-script')
<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
@endsection

@section('content')
<style>
    /* ── Chat Shell ── */
    .chat-shell {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 200px);
        min-height: 480px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 10px rgba(15,23,42,.06);
    }

    /* ── Chat Header ── */
    .chat-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        flex-shrink: 0;
    }
    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        flex-shrink: 0;
    }
    .chat-header-name {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }
    .chat-header-meta {
        font-size: 11px;
        color: #6b7280;
        font-weight: 600;
    }
    .chat-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        background: #fff;
        text-decoration: none;
        margin-left: auto;
        flex-shrink: 0;
    }
    .chat-back-btn:hover {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
        text-decoration: none;
    }

    /* ── Messages Area ── */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 18px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .chat-messages::-webkit-scrollbar { width: 4px; }
    .chat-messages::-webkit-scrollbar-track { background: transparent; }
    .chat-messages::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

    /* ── Message Bubbles ── */
    .msg-row {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        max-width: 72%;
    }
    .msg-row.msg-self {
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    .msg-row.msg-other {
        align-self: flex-start;
    }
    .msg-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #fff;
        box-shadow: 0 1px 4px rgba(15,23,42,.12);
    }
    .msg-content { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .msg-name {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        padding: 0 4px;
    }
    .msg-self .msg-name { text-align: right; }
    .msg-bubble {
        padding: 9px 13px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.45;
        word-break: break-word;
    }
    .msg-self .msg-bubble {
        background: #2563eb;
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    .msg-other .msg-bubble {
        background: #fff;
        color: #111827;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 3px rgba(15,23,42,.06);
    }
    .msg-time {
        font-size: 10px;
        color: #9ca3af;
        padding: 0 4px;
        font-weight: 600;
    }
    .msg-self .msg-time { text-align: right; }

    /* ── Input Bar ── */
    .chat-input-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        border-top: 1px solid #e5e7eb;
        background: #fff;
        flex-shrink: 0;
    }
    .chat-input-bar .sender-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e5e7eb;
    }
    .chat-input-bar .form-control {
        flex: 1;
        min-width: 0;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        padding: 8px 16px;
        font-size: 13px;
        background: #f8fafc;
        color: #111827;
        box-shadow: none;
    }
    .chat-input-bar .form-control:focus {
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        outline: none;
    }
    .chat-attach-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        text-decoration: none;
    }
    .chat-attach-btn:hover { border-color: #bfdbfe; color: #2563eb; background: #eff6ff; text-decoration: none; }
    .chat-send-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: background .15s, transform .15s;
        min-height: 0 !important;
    }
    .chat-send-btn:hover { background: #1d4ed8; transform: scale(1.05); }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center py-1">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:20px;font-weight:800;color:#111827;line-height:1.2;">Chat Messages</h1>
                <p class="m-0 mt-1" style="font-size:13px;color:#6b7280;">{{ $chat->title }}</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0" style="background:none;padding:0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color:#2563eb;">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.chat.index') }}" style="color:#2563eb;">Chats</a></li>
                    <li class="breadcrumb-item active" style="color:#6b7280;">Messages</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="chat-shell">

            <!-- Chat Header -->
            <div class="chat-header">
                <img src="{{ asset($chat->image) }}" class="chat-header-avatar" alt="{{ $chat->title }}">
                <div>
                    <div class="chat-header-name">{{ $chat->title }}</div>
                    <div class="chat-header-meta">
                        <span class="badge badge-{{ $chat->status == 1 ? 'success' : 'secondary' }}" style="font-size:10px;">
                            {{ getChatStatus($chat->status) }}
                        </span>
                        &nbsp;{{ ucfirst($chat->type) }} Chat
                    </div>
                </div>
                <a href="{{ route('admin.chat.index') }}" class="chat-back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Chats
                </a>
            </div>

            <!-- Messages -->
            <div class="chat-messages" id="chat-container">
                @foreach($messages as $message)
                @if ($message['self'] == true)
                <div class="msg-row msg-self" data-id="{{ $message['id'] }}">
                    <img src="{{ $message['user_image'] }}" class="msg-avatar" alt="{{ $message['user_name'] }}">
                    <div class="msg-content">
                        <span class="msg-name">{{ $message['user_name'] }}</span>
                        <div class="msg-bubble">{{ $message['message'] }}</div>
                        <span class="msg-time">{{ $message['date_time'] }}</span>
                    </div>
                </div>
                @else
                <div class="msg-row msg-other" data-id="{{ $message['id'] }}">
                    <img src="{{ $message['user_image'] }}" class="msg-avatar" alt="{{ $message['user_name'] }}">
                    <div class="msg-content">
                        <span class="msg-name">{{ $message['user_name'] }}</span>
                        <div class="msg-bubble">{{ $message['message'] }}</div>
                        <span class="msg-time">{{ $message['date_time'] }}</span>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <!-- Input Bar -->
            <div class="chat-input-bar">
                <img src="{{ asset(Auth::guard('user')->user()->image) }}"
                     class="sender-avatar"
                     alt="You">
                <input type="text"
                       id="message"
                       class="form-control"
                       placeholder="Type a message…"
                       autocomplete="off">
                <input type="file" id="pick_file" name="pick_file"
                       accept="image/png, image/jpeg" style="display:none;">
                <a class="chat-attach-btn" id="pick_file_btn" title="Attach file">
                    <i class="fas fa-paperclip"></i>
                </a>
                <button type="button" class="chat-send-btn" id="send-message-btn" title="Send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const chatId   = "{{ $chat->id }}";
    const userId   = "{{ Auth::guard('user')->user()->id }}";
    const userType = 'Admin';

    const socket = io('https://lmsnp.eloom.com.au:3000', {
        path: '/socket.io/',
        cors: { origin: "*", methods: ["GET", "POST"], allowedHeaders: ['Content-Type'], withCredentials: true },
        query: { room: chatId, user_id: userId, type: userType }
    });

    // Scroll to bottom on load
    const chatContainer = document.getElementById('chat-container');
    chatContainer.scrollTop = chatContainer.scrollHeight;

    // File picker
    document.getElementById('pick_file_btn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('pick_file').click();
    });

    socket.on('connected',    data => console.log('Connected:', data));
    socket.on('disconnect',   data => console.log('Disconnected:', data));

    socket.on('new_message_sent_to_chat', data => appendMessageToChat(data));

    socket.on('new_file_uploaded',    data => displayUploadedFile(data.file_url, data.sender_id));
    socket.on('user_typing',          data => showTypingIndicator(data.sender_id));
    socket.on('user_stopped_typing',  data => hideTypingIndicator(data.sender_id));

    document.getElementById('send-message-btn').addEventListener('click', function() {
        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();
        if (!message) return;

        const formData = new FormData();
        formData.append('chat_id', chatId);
        formData.append('message', message);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('/admin/chat/createMessage', { method: 'POST', body: formData })
            .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
            .then(() => { messageInput.value = ''; })
            .catch(err => console.error('Error sending message:', err));
    });

    // Allow Enter key to send
    document.getElementById('message').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('send-message-btn').click();
        }
    });

    function buildBubble(data, isSelf) {
        const avatarSrc = isSelf
            ? "{{ asset(Auth::guard('user')->user()->image) }}"
            : data.sender_image;
        const name = data.sender_name;
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const rowClass = isSelf ? 'msg-self' : 'msg-other';
        return `
            <div class="msg-row ${rowClass}">
                <img src="${avatarSrc}" class="msg-avatar" alt="${name}">
                <div class="msg-content">
                    <span class="msg-name">${name}</span>
                    <div class="msg-bubble">${data.message}</div>
                    <span class="msg-time">${time}</span>
                </div>
            </div>`;
    }

    function appendMessageToChat(data) {
        const isSelf = data.sender_type === 'Admin' && String(data.sender_id) === String(userId);
        chatContainer.insertAdjacentHTML('beforeend', buildBubble(data, isSelf));
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Infinite scroll — load older messages when scrolled to top
    $(document).ready(function() {
        var $chat = $('#chat-container');
        var page = 1;
        var loading = false;
        var moreMessages = true;

        $chat.on('scroll', function() {
            if ($chat.scrollTop() === 0 && !loading && moreMessages) {
                loading = true;
                page++;
                var firstId = $('#chat-container .msg-row').first().data('id');

                $.ajax({
                    url: '/admin/chat/loadMessage',
                    method: 'GET',
                    data: { page: page, chatId: chatId },
                    success: function(response) {
                        if (response.length > 0) {
                            response.forEach(function(msg) {
                                var isSelf = msg.self;
                                var rowClass = isSelf ? 'msg-self' : 'msg-other';
                                var time = msg.date_time;
                                var html = `
                                    <div class="msg-row ${rowClass}" data-id="${msg.id}">
                                        <img src="${msg.user_image}" class="msg-avatar" alt="${msg.user_name}">
                                        <div class="msg-content">
                                            <span class="msg-name">${msg.user_name}</span>
                                            <div class="msg-bubble">${msg.message}</div>
                                            <span class="msg-time">${time}</span>
                                        </div>
                                    </div>`;
                                $chat.prepend(html);
                            });
                            $chat.scrollTop($chat[0].scrollHeight / 2);
                        } else {
                            moreMessages = false;
                        }
                        loading = false;
                    }
                });
            }
        });
    });
</script>
@endsection
