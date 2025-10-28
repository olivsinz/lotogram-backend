<?php

namespace App\Events;

use App\Http\Resources\CompetitionAPI\PlannedCompetitionRewardResource;
use App\Models\Competition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class CompetitionNew implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected Competition $competition)
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
        return 'competition_new';
    }

    public function broadcastWith(): array
    {
        Log::channel('competition')->info("New Competition: {$this->competition->status} announced! | Competition ID: {$this->competition->uuid}");

        return [
            'competition' => [
                'id' => $this->competition->uuid,
                'title' => $this->competition->plannedCompetition->title,
                'octet' => $this->competition->plannedCompetition->octet,
                'status' => $this->competition->status,
                'ticket_amount' => $this->competition->plannedCompetition->ticket_amount,
                //'total_reward_all' => $this->plannedCompetition->ticket_amount * $this->purchasedTickets->count(),
                'total_reward_amount' => (($this->competition->plannedCompetition->ticket_amount * $this->competition->purchasedTickets->count()) / 100) * (100 - $this->competition->plannedCompetition->cost_percentage),
                //'rewards' => $this->when($this->relationLoaded('plannedCompetition'), PlannedCompetitionRewardResource::collection($this->plannedCompetition->rewards)),
                'bet_started_at' => $this->competition->bet_started_at,
                'bet_finished_at' => $this->competition->bet_finished_at,
                'planned_finish_at' => $this->competition->planned_finish_at,
                // TODO: buradaki count'lar optimize edilmeli.
                'total_tickets' => $this->competition->purchased_tickets_count ?? null,
                'total_users' => $this->competition->total_users,
                //'available_tickets' => $this->when($this->relationLoaded('availableTickets'), CompetitionTicketResource::collection($this->availableTickets)),
                //'purchased_tickets' => $this->when($this->relationLoaded('purchasedTickets'), CompetitionTicketResource::collection($this->purchasedTickets))
            ],


        ];
    }
}
