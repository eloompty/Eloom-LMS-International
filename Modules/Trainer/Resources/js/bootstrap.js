import Echo from 'laravel-echo';
window.io = require('socket.io-client');

// Configure Echo instance with Socket.io server URL
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://127.0.0.1:3000', // Update based on your local server
});

window.Echo.private(`chat.${chatId}`)
    .listen('MessageSent', (e) => {
        console.log(e.message);
        // Append the message to chat UI
    });
