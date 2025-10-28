<?php

namespace App\Events;

use App\Models\Competition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Models\PlannedCompetitionReward;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class CompetitionRewardStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected Competition $competition, protected PlannedCompetitionReward $reward)
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
        return 'competition_reward_started';
    }

    public function broadcastWith(): array
    {
        $amount = (($this->competition->plannedCompetition->ticket_amount * $this->competition->purchasedTickets->count()) / 100) * (100 - $this->competition->plannedCompetition->cost_percentage);
        Log::channel('competition')->info("Reward start announced! | Reward: {$this->reward->uuid} ({$this->reward->title}) | Amount: {$amount} TRY | Competition ID: {$this->competition->uuid}");

        return [
            'competition' => [
                'id' => $this->competition->uuid,
                'title' => $this->competition->plannedCompetition->title,
                'total_reward_amount' => $amount,
                'reward' => [
                    'id' => $this->reward->uuid,
                    'title' => $this->reward->title,
                    'percentage' => $this->reward->percentage,
                ]
            ],
        ];
    }
}
