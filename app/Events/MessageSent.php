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

    public $conservationId;

    public $userFrom;
    public $userTo;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $userTo, $userFrom, $conservationId)
    {
        $this->message = $message;
        $this->userTo = $userTo;
        $this->userFrom = $userFrom;
        $this->conservationId = $conservationId;
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
        $user = $this->userFrom->toArray();
        return [
            'conservationId' => $this->conservationId,
            'userFrom' => [
                'name' => $user['name'],
                'avatar_url' => $user['avatar_url']
            ],
            'message' => $this->message
        ];
    }
}
