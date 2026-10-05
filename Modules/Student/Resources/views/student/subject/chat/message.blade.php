@extends('student::student.layouts.master')
@section('title', 'Student | Chat Messages')
@section('header-script')
<!-- Load Socket.io and Laravel Echo from CDNs -->
<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
@endsection
@section('content')
<style>
    .chat {
        max-width: 75%;
    }

    .rounded-full {
        border-radius: 100%;
    }

    #search-btn:hover {
        background-color: rgb(87, 129, 255);
        color: white;
    }

    .bar-btn {
        height: 56px;
        width: 56px;
        margin: auto;
    }

    .side_profile_items {
        margin: auto;
    }

    @media screen and (min-width: 900px) {
        .bar-btn {
            display: none;
        }
    }

    @media screen and (max-width: 900px) {
        .active-convo {
            display: none;
        }
    }

    @media screen and (max-width: 1080px) {
        .min-hide-status {
            display: none;
        }
    }

    @media screen and (max-width: 840px) {
        .min-hide-msg {
            display: none;
        }
    }

    @media screen and (max-width: 760px) {
        .min-hide {
            display: none;
        }

        .side_profile_items {
            margin: auto;
        }

        #search-btn {
            width: 60px;
            height: 60px;
            padding: 2px;
            justify-content: center;
            align-items: center;
            font-size: 20px;
        }
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chat Messages</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntake->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntake->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.chat.index', $studentIntake->id) }}">Chats</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <section class="py-4 bg-secondary">
        <div class="card border-lg m-0 pb-3">

            <div class="d-flex">
                <div class="w-100">

                    <div class="border-bottom">
                        <nav class="navbar navbar-light pr-2">
                            <a class="navbar-brand" href="#">
                                <img src="{{ asset($chat->image) }}" width="38" height="38" class="d-inline-block align-top" alt="admin-name">
                                {{ $chat->title }}
                            </a>
                        </nav>
                    </div>

                    <div class="pt-3 pe-3 overflow-auto position-relative mx-100 py-3 pr-3 mx-100" id="chat-container" style="height: 67vh; overflow-y: scroll;">
                        <!-- Existing messages are loaded here -->
                        @foreach($messages as $message)
                        @if ($message['self'] == true)
                        <div class="d-flex flex-row chat justify-content-end ml-auto" data-id="{{ $message['id'] }}">
                            <div>
                                <p class="medium m-1 mr-1 text-muted text-right"><?php echo $message['user_name']; ?></p>
                                <p class="p-2 me-3 mb-1 text-white rounded-3 bg-primary rounded-lg"><?php echo $message['message']; ?></p>
                                <p class="me-3 mb-3 rounded-3 text-muted small text-right">{{ $message['date_time'] }}</p>
                            </div>
                            <img src="{{ $message['user_image'] }}" class="rounded-full" alt="avatar 1" style="width: 35px; height: 100%;">
                        </div>
                        @else
                        <div class="d-flex flex-row justify-content-start chat" data-id="{{ $message['id'] }}">
                            <img class="rounded-full" src="{{ $message['user_image'] }}" alt="avatar 1" style="width: 35px; height: 100%;">
                            <div>
                                <p class="medium m-0 ml-2 text-muted text-left"><?php echo $message['user_name']; ?></p>
                                <p class="p-2 ml-2 mb-1 rounded-lg bg-light text-secondary"> <?php echo $message['message']; ?></p>
                                <p class="small mb-3 pl-2 rounded-3 text-muted float-end">{{ $message['date_time'] }}</p>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <!-- <form action="{{ route('student.subject.chat.message.send', $chat->id) }}" method="POST" enctype="multipart/form-data"> -->
                    <!-- @csrf -->
                    <div class="text-muted d-flex justify-content-start align-items-center pt-3 mt-2">
                        <img src="{{ asset(Auth::guard('student')->user()->image) }}" alt="avatar 3" style="width: 40px; height: 100%;">
                        <input type="text" name="message" id="message" class="form-control form-control-lg" placeholder="Type message">
                        <input type="file" id="pick_file" name="pick_file" accept="image/png, image/jpeg" style="display:none;" />
                        <a class="p-2 text-muted" id="pick_file_btn">
                            <i class="fas fa-paperclip"></i>
                        </a>
                        <button type="button" class="btn active" id="send-message-btn"><i class="fas fa-paper-plane text-blue"></i></button>
                    </div>
                    <!-- </form> -->
                </div>
            </div>

        </div>
    </section>

</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    const pick_file = document.getElementById('pick_file_btn');
    const fileInput = document.getElementById('pick_file');
    pick_file.addEventListener('click', function(event) {
        event.preventDefault();
        fileInput.click();
    });
    // Initialize socket connection using Laravel Echo and Socket.io
    const chatId = "{{ $chat->id }}"; // Get the chat ID (passed from Controller)
    const userId = "{{ Auth::guard('student')->user()->id }}"; // Get current user ID
    const userType = 'Student';
    const room = `chat_${chatId}`;

    // const socket = io('http://127.0.0.1:3000', {
    const socket = io('https://lmsnp.eloom.com.au:3000', {
        path: '/socket.io/',
        cors: {
            origin: "*", // Allow all origins
            methods: ["GET", "POST"],
            allowedHeaders: ['Content-Type'],
            withCredentials: true
        },
        query: {
            room: chatId,
            user_id: userId,
            type: userType
        }
    });

    // Listen for specific events
    socket.on('connected', (data) => {
        console.log('Connected to socket:', data);
    });

    // Listen for specific events
    socket.on('disconnect', (data) => {
        console.log('Disconnected to socket:', data);
    });

    // Listen for different types of messages
    socket.on('new_message_sent_to_chat', (data) => {
        console.log('New message received:', data);
        // addMessageToUI(data.message, data.sender_id);
        // Optionally: Append the message to the chat UI
        appendMessageToChat(data);
    });

    socket.on('new_file_uploaded', (data) => {
        console.log('New file uploaded:', data);
        displayUploadedFile(data.file_url, data.sender_id);
    });

    socket.on('user_typing', (data) => {
        console.log(`${data.sender_id} is typing...`);
        showTypingIndicator(data.sender_id);
    });

    socket.on('user_stopped_typing', (data) => {
        console.log(`${data.sender_id} stopped typing.`);
        hideTypingIndicator(data.sender_id);
    });



    document.getElementById('send-message-btn').addEventListener('click', function() {
        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();

        if (message === '') {
            alert('Please enter a message.');
            return;
        }

        // Prepare data to send
        const formData = new FormData();
        formData.append('chat_id', chatId);
        formData.append('message', message);
        formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token

        // Send the message via AJAX
        fetch(`/student/course/semester/subject/chat/createMessage`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Handle successful message send
                console.log('Message sent:', data);
                // Optionally: Append the message to the chat UI
                // appendMessageToChat(data.message);
                messageInput.value = ''; // Clear the input
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
    });

    function appendMessageToChat(data) {
        // Implement this function to update your chat UI with the new message
        console.log("New message received from socket : ", data);
        const chatContainer = document.querySelector('.overflow-auto');
        const newMessage = document.createElement('div');
        newMessage.className = 'd-flex flex-row chat justify-content-end ml-auto';
        newMessage.innerHTML = `
            <div>
                <p class="medium m-1 mr-1 text-muted text-right">${data.sender_name}</p>
                <p class="p-2 me-3 mb-1 text-white rounded-3 bg-primary rounded-lg">${data.message}</p>
                <p class="me-3 mb-3 rounded-3 text-muted small text-right">${new Date().toLocaleTimeString()}</p>
            </div>
            <img class="rounded-full" src="{{ asset(Auth::guard('student')->user()->image) }}" alt="avatar 1" style="width: 35px; height: 100%;">
        `;

        const newMessageOthers = document.createElement('div');
        newMessageOthers.className = 'd-flex flex-row justify-content-start chat';
        newMessageOthers.innerHTML = `
        <img class="rounded-full" src="${data.sender_image}" alt="avatar 1" style="width: 35px; height: 100%;">
        <div>
        <p class="medium m-0 ml-2 text-muted text-left">${data.sender_name}</p>
        <p class="p-2 ml-2 mb-1 rounded-lg bg-light text-secondary"> ${data.message}</p>
        <p class="small mb-3 pl-2 rounded-3 text-muted float-end">${new Date().toLocaleTimeString()}</p>
        </div>
        `;

        if (data.sender_type == 'Student' && data.sender_id == userId) {
            chatContainer.appendChild(newMessage);
            chatContainer.scrollTop = chatContainer.scrollHeight; // Scroll to the bottom
        } else {
            chatContainer.appendChild(newMessageOthers);
            chatContainer.scrollTop = chatContainer.scrollHeight; // Scroll to the bottom
        }

    }

    $(document).ready(function() {
        var chatContainer = $('#chat-container');
        var chatId = "{{ $chat->id }}";
        var page = 1; // Current page number for pagination
        var loading = false; // Prevent multiple AJAX requests at once
        var moreMessages = true; // To track if there are more messages to load
        console.log('scrolling... ')
        // Listen for scroll event on the chat container
        chatContainer.scroll(function() {
            console.log('scrolling to the top ... ')

            // If scrolled to the top and not already loading
            if (chatContainer.scrollTop() === 0 && !loading && moreMessages) {
                loading = true; // Set loading to true to prevent multiple requests
                page++; // Increment the page for pagination
                console.log('scrolled to top and fetcing message ... ')

                // Get the ID of the first message in the current view
                var firstMessageId = $('#chat-container .chat').first().data('id');

                // Make AJAX request to load more messages
                $.ajax({
                    url: '/student/course/semester/subject/chat/load/message', // Replace with your route URL
                    method: 'GET',
                    data: {
                        page: page, // Send the current page number
                        chatId: chatId //
                    },
                    success: function(response) {
                        // Check if there are more messages in the response
                        console.log('fetched message... ')
                        console.log(response);
                        console.log(response.length);

                        if (response.length > 0) {
                            response.forEach(function(message) {
                                // Prepend the message to the chat container
                                if (message.self) {
                                    chatContainer.prepend(`
                                    <div class="d-flex flex-row chat justify-content-end ml-auto" data-id="${message.id}">
                                        <div>
                                            <p class="medium m-1 mr-1 text-muted text-right">${message.user_name}</p>
                                            <p class="p-2 me-3 mb-1 text-white rounded-3 bg-primary rounded-lg">${message.message}</p>
                                            <p class="me-3 mb-3 rounded-3 text-muted small text-right">${message.date_time}</p>
                                        </div>
                                        <img src="${message.user_image}" class="rounded-full" alt="avatar 1" style="width: 35px; height: 100%;">
                                    </div>
                                `);
                                } else {
                                    chatContainer.prepend(`
                                    <div class="d-flex flex-row justify-content-start chat" data-id="${message.id}">
                                        <img class="rounded-full" src="${message.user_image}" alt="avatar 1" style="width: 35px; height: 100%;">
                                        <div>
                                            <p class="medium m-0 ml-2 text-muted text-left">${message.user_name}</p>
                                            <p class="p-2 ml-2 mb-1 rounded-lg bg-light text-secondary">${message.message}</p>
                                            <p class="small mb-3 pl-2 rounded-3 text-muted float-end">${message.date_time}</p>
                                        </div>
                                    </div>
                                `);
                                }
                            });

                            // Maintain scroll position after loading more messages
                            chatContainer.scrollTop(chatContainer[0].scrollHeight / 2);
                        } else {
                            // No more messages to load
                            moreMessages = false;
                        }
                        loading = false; // Set loading to false after the request completes
                    }
                });
            }
        });
    });
</script>
@endsection