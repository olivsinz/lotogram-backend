<?php

namespace App\Events;

use App\Models\User;
use App\Service\TransactionService;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Balance implements ShouldBroadcast
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
        return 'balance';
    }

    public function broadcastWith(): array
    {
        return [
            'balance' => TransactionService::balance($this->user),
            'withdrawable_balance' => TransactionService::withdrawableBalance($this->user),
            'bonus_balance' => TransactionService::bonusBalance($this->user),
        ];
    }
}
