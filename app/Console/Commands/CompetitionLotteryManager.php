<?php

namespace App\Console\Commands;

use App\Models\Competition;
use App\Service\CompetitionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompetitionLotteryManager extends CompetitionCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:competition-lottery-manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bet suresi dolan cekilisleri kimlerin kazandigini belirler.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Competition Lottery Manager started.');

        while (true) {
            $lotteryFinished = $this->doLotteryForFinishedCompetitions();

            if (!$lotteryFinished)
                sleep(1);

            $this->pong(60);
        }
    }

    private function doLotteryForFinishedCompetitions(): bool
    {
        $doLottery = false;
        $competitions = Competition::with(['plannedCompetition'])->readyForLottery()->get();
        foreach ($competitions as $competition) {
            Log::channel('competition')->info("Ready for start to results! | Competition ID: {$competition->uuid}");
            $doLottery = true;
            CompetitionService::doLottery($competition);
        }

        return $doLottery;
    }
}
