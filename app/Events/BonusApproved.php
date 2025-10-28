<?php

namespace App\Events;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BonusApproved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected User $user, protected Transaction $transaction)
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
        return 'bonus_approved';
    }

    public function broadcastWith(): array
    {
        return [
            'transaction' => [
                'id' => $this->transaction->uuid,
                'amount' => $this->transaction->amount,
            ]
        ];
    }
}
