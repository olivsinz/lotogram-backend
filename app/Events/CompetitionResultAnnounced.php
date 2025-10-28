<?php

namespace App\Events;

use App\Models\Competition;
use App\Models\CompetitionTicket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Models\PlannedCompetitionReward;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;


class CompetitionResultAnnounced  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(protected Competition $competition, protected PlannedCompetitionReward $reward, protected string $number, protected string $key)
    {

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
        return 'competition_result_announced';
    }

    public function broadcastWith(): array
    {
        Log::channel('competition')->info("Sub Result announced! | Reward: {$this->reward->uuid} ({$this->reward->title}) | Competition ID: {$this->competition->uuid}");

        return [
            'competition' => [
                'id' => $this->competition->uuid,
                'title' => $this->competition->plannedCompetition->title,
                'total_reward_amount' => (($this->competition->plannedCompetition->ticket_amount * $this->competition->purchasedTickets->count()) / 100) * (100 - $this->competition->plannedCompetition->cost_percentage),
            ],
            'reward' => [
                'id' => $this->reward->uuid,
                'title' => $this->reward->title,
                'percentage' => $this->reward->percentage
            ],
            'lottery' => [
                'number' => $this->number,
                'order' => $this->key,
            ],
        ];
    }
}
