<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Define authorization for private chat channels (1:1 chat)
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // You can add your own logic here to check if the user has access to the chat
    // return Auth::check(); // Only allow if the user is authenticated
    return true;
});

Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    return true;  // Authorize the user for WebSocket connection
});

// Define authorization for group chat channels
Broadcast::channel('group-chat.{groupId}', function ($user, $groupId) {
    // Example logic: only allow access if the user is part of the group
    return $user->isPartOfGroup($groupId); // Replace with your own logic
});
