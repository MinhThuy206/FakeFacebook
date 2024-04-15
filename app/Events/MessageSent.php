<?php

namespace App\Events;

use App\Models\MessageInConservation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userTo;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MessageInConservation $message, $userTo)
    {
        $this->message = $message;
        $this->userTo = $userTo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('chat.' . $this->userTo);
    }

    public function broadcastWith(): array
    {
        return $this->message->toArray();
    }
}