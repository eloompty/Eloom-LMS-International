<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Chat\Entities\ChatMessage;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        // For 1:1 Chat, use Private Channel
        return new PrivateChannel('chat.' . $this->message->chat_id);

         // For 1:* Group Chat, use Presence Channel
        return new PresenceChannel('chat.' . $this->message->chat_id);
    }

    public function broadcastWith()
    {
        return ['message' => $this->message->load('user')];
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
