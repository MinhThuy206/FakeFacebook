<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userFrom;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $userFrom)
    {
        $this->message = $message;
        $this->userFrom = $userFrom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('chat.'.$this->userFrom);
    }

    public function broadcastWith(): array
    {
        return [
            'userFrom' => auth()->id(),
            'message' => $this->message
        ];
    }
}
