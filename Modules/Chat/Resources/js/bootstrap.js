import Echo from 'laravel-echo';
window.io = require('socket.io-client');

// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.location.hostname + ':6001' // Adjust the port if needed
// });

// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     forceTLS: false,
//     disableStats: true,
// });

// // Initialize Laravel Echo with Socket.io
// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.location.hostname + ':6001',  // Assuming WebSocket runs on port 6001
//     transports: ['websocket'], // Ensure you're using websockets
//     forceTLS: false,  // Disable TLS for local development if necessary
// });

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     wssPort: 6001,
//     forceTLS: false,
//     disableStats: true,
//     encrypted: false,
//     enabledTransports: ['ws', 'wss'],
// });

// Configure Echo instance with Socket.io server URL
window.Echo = new Echo({
    broadcaster: 'socket.io',
    // host: 'http://127.0.0.1:3000', //
    host: 'https://lmsnp.eloom.com.au:3000',
    withCredentials: true
});

window.Echo.private(`chat.${chatId}`)
    .listen('MessageSent', (e) => {
        console.log(e.message);
        // Append the message to chat UI
    });
