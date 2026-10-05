<?php

namespace App\WebSocketHandlers;

use BeyondCode\LaravelWebSockets\Server\WebSocketHandler as ServerWebSocketHandler;
use Ratchet\ConnectionInterface;

class ChatWebSocketHandler extends ServerWebSocketHandler
{
    public function onOpen(ConnectionInterface $connection)
    {
        // Called when a new WebSocket connection is opened
    }

    public function onMessage(ConnectionInterface $connection, $message)
    {
        // Broadcast the message to all connected clients
        foreach ($this->connections as $client) {
            if ($client !== $connection) {
                $client->send($message);
            }
        }
    }

    public function onClose(ConnectionInterface $connection)
    {
        // Called when the WebSocket connection is closed
    }
}
