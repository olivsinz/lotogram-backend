<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EmailVerified implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected User $user)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->uuid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'email-verified';
    }

    public function broadcastWith(): array
    {
        return [
            'email' => $this->user->email,
            'email_verified_at' => $this->user->email_verified_at,
        ];
    }
}
