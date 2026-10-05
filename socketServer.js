/**
 * This file is part of Nikola Project
 * Author : Saikat Dutta(saikatdutta1991@gmail.com)
 * Company : PrevueLogic
 *
 * installtion guide :
    npm install express
    npm install request
    npm install fs
    npm install socket.io
    npm install mysql
    sudo npm install pm2 -g
    pm2 start socketServer.js
    shock open
    sock.run
    sock
    sock.connect
**/

process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";
process.env.TZ = 'UTC' // setting timezone to utc


/** storing socket server configuration */
var socketConfig = {
    socket_port: 3000,
    is_https: true,
    https_key_path: '/var/www/certs/privkey.pem',
    https_cert_path: '/var/www/certs/fullchain.pem',
    https_ca_path: '/var/www/certs/ssl-dhparams.pem',
    // php_server_host: 'http://104.248.135.98'
    php_server_host: 'http://localhost'
    // php_server_host: 'http://127.0.0.1'
    
}
/** storing socket server configuration end */

/** importing required node modules */
var request = require('request');
// var app = require('express')();

const express = require('express'); // Ensure you require express
const app = express(); // Initialize expres

var fs = require('fs');
var socketio = require('socket.io');
/** importing required node modules end */

// Middleware for parsing JSON request bodies
app.use(express.json());


/** configuring socket server based on http or https(ssl)*/
var server = null;
if (socketConfig.is_https) {

    console.log('Socket https enabled')

    // Debugging: Log paths
    console.log('Using SSL Key Path:', socketConfig.https_key_path);
    console.log('Using SSL Cert Path:', socketConfig.https_cert_path);
    console.log('Using SSL CA Path:', socketConfig.https_ca_path);

    // var socketOptions = {
    //     key: fs.readFileSync(socketConfig.socket_port.https_key_path),
    //     cert: fs.readFileSync(socketConfig.socket_port.https_cert_path),
    //     ca: fs.readFileSync(socketConfig.socket_port.https_ca_path),
    //     rejectUnauthorized: false,
    //     requestCert: false
    // };

    var socketOptions = {
        key: fs.readFileSync(socketConfig.https_key_path),
        cert: fs.readFileSync(socketConfig.https_cert_path),
        ca: fs.readFileSync(socketConfig.https_ca_path),
        rejectUnauthorized: false,
        requestCert: false
    };

    server = require('https').createServer(socketOptions, app);

} else {

    console.log('Socket http enabled')

    server = require('http').Server(app);
}

/** configuring socket server end */





/** starting socket server, listening on port defined in config */
server.listen(socketConfig.socket_port, function () {
    console.log('Server started on port : ' + socketConfig.socket_port);
});

//adding socket io to server
// var io = socketio(server);

var io = socketio(server, {
    path: '/socket.io/', // Ensure this matches the Nginx config
    cors: {
        origin: 'https://lmsnp.eloom.com.au', // Make sure the origin matches your frontend
        methods: ['GET', 'POST', 'OPTIONS'], // Ensure OPTIONS is included
        allowedHeaders: ['Content-Type'],
        credentials: true, // Include if using credentials
    },
});


/** starting socket server end*/



/** default route */
app.get('/', function (req, res) {
    res.send('Socket server is running, You are not authorized to access this server.')
})
/** default route end*/

app.post('/emit', (req, res) => {
    const { chat_id, user_id, user_name, user_type, user_image, message, messageType } = req.body;

    console.log(`Emitting message to chat room: chat_${chat_id}`);
    io.to(`${chat_id}`).emit('new_message_sent_to_chat', {
        message: message,
        messageType: messageType,
        sender_id: user_id,
        sender_name: user_name,
        sender_type: user_type,
        sender_image: user_image,
    });

    res.status(200).send('Message emitted successfully');
});

/** helper functions */

function currentUTCTimestampString() {
    var date = new Date();
    var year = date.getUTCFullYear().toString().padStart(2, 0)
    var month = (date.getUTCMonth() + 1).toString().padStart(2, 0)
    var day = date.getUTCDate().toString().padStart(2, 0)
    var hours = (date.getUTCHours() + 1).toString().padStart(2, 0)
    var minutes = (date.getUTCMinutes() + 1).toString().padStart(2, 0)
    var seconds = (date.getUTCSeconds() + 1).toString().padStart(2, 0)
    var now_utc = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`
    return now_utc
}


/** helper functions end*/

io.on('connection', (socket) => {
    try {
        console.log('New client connected to socket:', socket.id);

        // Initialize client details from handshake query
        socket.entity = {
            id: socket.id,
            user_id: socket.handshake.query.user_id,
            type: socket.handshake.query.type,
            room_id: socket.handshake.query.room, // Example: 'chat_1'
        };

        console.log('Socket client details:', socket.entity);
        // Join the room associated with the chat or entity
        socket.join(socket.entity.room_id);
        // Emit 'connected' event back to the client with entity info
        socket.emit('connected', {
            message: 'Successfully connected!',
            entity: socket.entity,
        });

    } catch (e) {
        console.error('Socket client connection error:', e.message);
        socket.disconnect(); // Disconnect on error
    }

    // Handle disconnection
    socket.on('disconnect', () => {
        console.log('Client disconnected:', socket.id);
    });



    /** sending message from user to provider */
    socket.on('send_message_to_chat', (data) => {
        console.log('send_message_to_chat event', data)

        /** insert messsag to db */
        var timestamp = currentUTCTimestampString()
        var type = 'up';

        console.log('room id:', `${data.chat_id}`)
        io.sockets.in(`${data.chat_id}`).emit('new_message_sent_to_chat', data);


        // var query = `INSERT INTO chat_messages (request_id, user_id, provider_id, message, type, delivered, created_at, updated_at) VALUES (${data.request_id}, ${socket.entity.id}, ${data.provider_id}, '${data.message}', '${type}', 1, '${timestamp}', '${timestamp}')`
        // console.log('query', query)

        // conn.query(query, (err, result) => {

        //     if (err) {
        //         console.log('message not inserted', err.message)
        //         return
        //     }

        //     console.log('message inserted to db', result.insertId)

        //     //send message to provider after modifiing data
        //     data.id = result.insertId
        //     data.created_at = timestamp
        //     data.updated_at = timestamp
        //     data.type = type
        //     console.log('room id:', `${data.chat_id}`)
        //     io.sockets.in(`${data.chat_id}`).emit('new_message_sent_to_chat', data);


        //     request(socketConfig.php_server_host + '/send_push?isuser=2&user_id=' + data.provider_id + '&request_id=' + data.request_id + '&title=New Message&message=' + data.message, { json: true }, (err, res, body) => {
        //         /* if (err) { return console.log(err); }
        //         console.log(body.url);
        //         console.log(body.explanation); */
        //     });

        // })



    })
    /** sending message from user to provider end*/





    /** sending messag from provider to user */
    socket.on('send_message_to_user', (data) => {
        console.log('send_message_to_user event', data)

        /** insert messsag to db */
        var timestamp = currentUTCTimestampString()
        var type = 'pu';
        // var query = `INSERT INTO chat_messages (request_id, user_id, provider_id, message, type, delivered, created_at, updated_at) VALUES (${data.request_id}, ${data.user_id}, ${socket.entity.id}, '${data.message}', '${type}', 1, '${timestamp}', '${timestamp}')`
        // console.log('query', query)

        io.sockets.in(`${data.chat_id}`).emit('new_message_from_user', data);

        // conn.query(query, (err, result) => {

        //     if (err) {
        //         console.log('message not inserted', err.message)
        //         return
        //     }

        //     console.log('message inserted to db', result.insertId)

        //     //send message to provider after modifiing data
        //     data.id = result.insertId
        //     data.created_at = timestamp
        //     data.updated_at = timestamp
        //     data.type = type
        //     io.sockets.in(`user_${data.user_id}`).emit('new_message_from_provider', data);


        //     request(socketConfig.php_server_host + '/send_push?isuser=1&user_id=' + data.user_id + '&request_id=' + data.request_id + '&title=New Message&message=' + data.message, { json: true }, (err, res, body) => {
        //         /* if (err) { return console.log(err); }
        //         console.log(body.url);
        //         console.log(body.explanation); */
        //     });

        // })

    })
    /** sending messag from provider to user end */



    socket.join(socket.handshake.query.sender);

    socket.emit('connected', 'Connection to server established!');

    socket.on('update sender', function (data) {
        //console.log('update sender', data);
        socket.join(data.sender);
        socket.handshake.query.sender = data.sender;
        socket.emit('sender updated', 'Sender Updated ID:' + data.sender);
    });

    socket.on('send location', function (data) {
        //console.log("send location", data);
        //data.sender = socket.handshake.query.sender;
        data.time = new Date();
        socket.broadcast.to(data.receiver).emit('message', data);
    });
})
/** socket io program starts here end */
