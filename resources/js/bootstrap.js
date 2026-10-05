window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.io = require('socket.io-client');

// Initialize Laravel Echo with Socket.io
// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.location.hostname + ':6001',  // Assuming WebSocket runs on port 6001
//     transports: ['websocket'], // Ensure you're using websockets
//     forceTLS: false,  // Disable TLS for local development if necessary
// });


// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: 'http://127.0.0.1:3000', // Update based on your local server
// });

// Listen for socket.io connection errors
window.io().on('connect_error', (error) => {
    console.error("WebSocket connection error:", error);
});

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
