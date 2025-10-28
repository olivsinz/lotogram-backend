<?php

namespace App\Events;

use App\Models\CompetitionTicket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class CompetitionTicketWon implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected CompetitionTicket $ticket)
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
            new Channel('competition'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'competition_ticket_won';
    }

    public function broadcastWith(): array
    {
        Log::channel('competition')->info("Ticket won announced! | Reward: {$this->ticket->uuid} ({$this->ticket->number}) | By: {$this->ticket->user->username} | Competition ID: {$this->ticket->competition->uuid}");

        return [
            'ticket' => [
                'id' => $this->ticket->uuid,
                'number' => $this->ticket->number,
                'amount' => $this->ticket->amount,
                'user' => [
                    'id' => $this->ticket->user->uuid,
                    'username' => $this->ticket->user->username,
                ],
                'competition' => [
                    'id' => $this->ticket->competition->uuid,
                    'title' => $this->ticket->competition->plannedCompetition->title,
                ],
                'reward' => [
                    'id' => $this->ticket->reward->uuid,
                    'title' => $this->ticket->reward->title,
                ],
            ],
        ];
    }
}
