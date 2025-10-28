<?php

namespace App\Events;

use App\Models\Competition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class CompetitionStatusChanged implements ShouldBroadcast
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
        return 'competition_status_changed';
    }

    public function broadcastWith(): array
    {
        Log::channel('competition')->error("New Status: {$this->competition->status} announced! | Competition ID: {$this->competition->uuid}");

        return [
            'competition' => [
                'id' => $this->competition->uuid,
                'title' => $this->competition->plannedCompetition->title,
                'status' => $this->competition->status,
            ],
        ];
    }
}
