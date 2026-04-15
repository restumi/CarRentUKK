<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat_messages;

    /**
     * Create a new event instance.
     */
    public function __construct($chat_messages)
    {
        $this->chat_messages = $chat_messages;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chat_messages->receiver_id);
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        $formattedTime = $this->chat_messages->created_at
            ->timezone('Asia/Jakarta')
            ->format('H:i');

        return [
            'id' => $this->chat_messages->id,
            'sender_id' => $this->chat_messages->sender_id,
            'receiver_id' => $this->chat_messages->receiver_id,
            'message' => $this->chat_messages->message,
            'created_at' => $formattedTime,
        ];
    }
}
