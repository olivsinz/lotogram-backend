<?php

namespace App\Console\Commands;

use App\Enum\CompetitionStatus;
use App\Enum\CompetitionTicketType;
use App\Jobs\JoinBotCompetition;
use App\Service\CompetitionService;
use App\Models\Competition;
use App\Models\CompetitionTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CompetitionRewardManipulator extends CompetitionCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:competition-reward-manipulator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Her bir odul icin en az bir tane botun ticket aldigina emin olur.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Competition Reward Manipulator started.');

        while (true) {
            $this->manipulateCompetitions();

            if (Carbon::now()->second % 30 == 0)
                $this->line('Competition Reward Manipulator is working.');

            sleep(1);
        }

    }

    private function manipulateCompetitions(): void
    {
        $ongoingCompetitions = Competition::with('plannedCompetition.rewards')->statuses([CompetitionStatus::Active->value])->waitingForBots()->get();

        foreach ($ongoingCompetitions as $competition) {
            Log::channel('competition')->info("Competition checking for bot rewards... | Competition ID: {$competition->uuid}");

            if (!$competition->planned_finish_at->isFuture())
                continue;

            $leftSecs = $competition->planned_finish_at->diffInSeconds(Carbon::now());
            Log::channel('competition')->info("Competition manipulating for bot rewards... | Finish: {$competition->planned_finish_at} | Left: {$leftSecs} |  Competition ID: {$competition->uuid}");

            $rewards = $competition->plannedCompetition->rewards()->bot()->get();

            Log::channel('competition')->info("{$rewards->count()} reward found for bots! | Finish: {$competition->planned_finish_at} | Left: {$leftSecs} |  Competition ID: {$competition->uuid}");

            foreach ($rewards as $reward) { // join
                $delay = rand(1,$leftSecs - 10);
                $delayedDate = Carbon::now()->addSeconds($delay);
                JoinBotCompetition::dispatch($competition)->delay($delayedDate);
                Log::channel('competition')->info("Bot join job threw! | Expected Join Time: {$delayedDate->toDateTimeString()} | Competition ID: {$competition->uuid}");
            }

            $competition->is_settled_for_bots = 1;
            $competition->save();
            Log::channel('competition')->info("Bot reward manipulation completed! | Competition ID: {$competition->uuid}");
        }
    }
}
