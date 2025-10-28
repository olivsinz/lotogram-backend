<?php

namespace App\Events;

use App\Models\Setting;
use App\Models\CompetitionTicket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class CompetitionTicketPurchased implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected CompetitionTicket $ticket)
    {
        Log::channel('competition')->info("Ticket purchase announce triggered! | Ticket: {$this->ticket->uuid} ({$this->ticket->number}) | Purchased By: {$this->ticket->user->username} | Competition ID: {$this->ticket->competition->uuid}");
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
        return 'competition_ticket_purchased';
    }

    public function broadcastWith(): array
    {
        $stats  = [
            'total_tickets' => null,
            'total_users' => null,
        ];

        $ticketCount = $this->ticket->competition->purchasedTickets->count();
        $plannedCometition = $this->ticket->competition->plannedCompetition;

        if(Setting::getByKey('lottery_stat_detail'))
        {
            $stats = [
                'total_tickets' => $ticketCount,
                'total_users' => $this->ticket->competition->purchasedTickets()->pluck('user_id')->unique()->count(),
            ];
        }

        Log::channel('competition')->info("Ticket purchase announced! | Ticket: {$this->ticket->uuid} ({$this->ticket->number}) | Purchased By: {$this->ticket->user->username} | Competition ID: {$this->ticket->competition->uuid}");

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
                    'title' => $plannedCometition->title,
                    'total_reward_amount' => (($plannedCometition->ticket_amount * $ticketCount) / 100) * (100 - $plannedCometition->cost_percentage),
                    ...$stats
                ],
            ],
        ];
    }
}
