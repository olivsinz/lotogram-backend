<?php

namespace App\Jobs;

use App\Enum\CompetitionTicketType;
use App\Events\CompetitionTicketPurchased;
use App\Models\Competition;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JoinBotCompetition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Competition $competition;

    /**
     * Create a new job instance.
     */
    public function __construct(Competition $competition)
    {
        $this->competition = $competition;
    }

    /**
     * Execute the job.
     */
    public function handle(): bool
    {
        $bot = User::bot()->inRandomOrder()->limit(1)->first();
        if (!$bot) {
            echo '[' . date('Y-m-d H:i:s') . '] For competition [#'. $this->competition->id .'], there is no available bot!';
            return false;
        }
        $ticket = $this->competition->availableTickets()->inRandomOrder()->limit(1)->first();
        if (!$ticket) {
            echo '[' . date('Y-m-d H:i:s') . '] For competition [#'. $this->competition->id .'], there is no available ticket for bot!';
            return false;
        }

        $ticket->user_id = $bot->id;
        $ticket->bet_at = now();
        $ticket->type = CompetitionTicketType::Bot->value;
        $ticket->save();

        echo '[' . date('Y-m-d H:i:s') . '] Bot ['. $bot->username .'] joined the competition: #' . $this->competition->id. ' with ticket: ' . $ticket->number . PHP_EOL;

        event(new CompetitionTicketPurchased($ticket));
        return true;
    }
}
