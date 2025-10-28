<?php

namespace App\Events;

use App\Models\User;
use App\Models\CompetitionTicket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class CompetitionTicketCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected User $user, protected CompetitionTicket $ticket)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->uuid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'competition_ticket_cancelled';
    }

    public function broadcastWith(): array
    {
        return [
            'ticket' => [
                'id' => $this->ticket->uuid,
                'number' => $this->ticket->number,
                'amount' => $this->ticket->amount,
                'competition' => [
                    'id' => $this->ticket->competition->uuid,
                    'title' => $this->ticket->competition->plannedCompetition->title
                ],
            ],
        ];
    }
}
