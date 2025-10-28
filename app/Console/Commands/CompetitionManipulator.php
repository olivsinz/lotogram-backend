<?php

namespace App\Console\Commands;

use App\Enum\CompetitionStatus;
use App\Enum\CompetitionTicketType;
use App\Enum\UserType;
use App\Jobs\JoinBotCompetition;
use App\Service\CompetitionService;
use App\Models\Competition;
use App\Models\CompetitionTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CompetitionManipulator extends CompetitionCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:competition-manipulator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yeterli katılımcı olmadığında, botlara ticket aldirarak hareket yaratan bot.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Competition Manipulator started.');

        while (true) {
            $this->manipulateCompetitions();

            if (Carbon::now()->second % 30 == 0)
                $this->line('Competition Manipulator is working.');

            sleep(1);
        }

    }

    private function manipulateCompetitions(): void
    {
        $ongoingCompetitions = Competition::with(['plannedCompetition.rewards', 'purchasedTickets'])->statuses([CompetitionStatus::Active->value])->get();

        foreach ($ongoingCompetitions as $competition) {
            $redisKey = 'hold_bot_join_for_competition_' . $competition->id;
            Log::channel('competition')->info("Competition checking for bots... | Competition ID: {$competition->uuid}");

            if (!$competition->planned_finish_at->isFuture())
                continue;

            $lastTicket = $competition->purchasedTickets()->with('user')->LastPurchased()->first();

            $secs = 0;

            if ($lastTicket)
                $secs = UserType::Bot->value == $lastTicket->user->type ? $competition->plannedCompetition->manipulate_wait_secs_after_bot : $competition->plannedCompetition->manipulate_wait_secs_after_user;

            if ((empty($lastTicket) || $lastTicket->bet_at->diffInSeconds(Carbon::now()) >= $secs) && !Cache::has($redisKey)) {
                Cache::put($redisKey, true, 2);
                JoinBotCompetition::dispatch($competition)->delay(Carbon::now()->addSeconds(rand(1,10)));
                Log::channel('competition')->info("Bot join job threw! | Expected Join Time: Now | Competition ID: {$competition->uuid}");
            }
        }
    }
}
