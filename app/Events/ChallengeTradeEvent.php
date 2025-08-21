<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChallengeTradeEvent implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
	public $message;
	public $user_id;
	public $challenge_id;
	
    public function __construct($user_id, $challenge_id, $message)
    {
        $this->message = $message;
        $this->user_id = $user_id;
        $this->challenge_id = $challenge_id;
		//\Log::info('Message Sent Event Triggered:', ['message' => $message]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('trade-notification.'.$this->user_id.'-challenge-'.$this->challenge_id),
        ];
    }
	public function broadcastWith()
    {
        return ['message' => $this->message];
    }
}
