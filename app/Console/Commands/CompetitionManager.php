<?php

namespace App\Console\Commands;

use App\Enum\CompetitionStatus;
use App\Enum\PlannedCompetitionStatus;
use App\Models\Competition;
use App\Models\PlannedCompetition;
use App\Service\CompetitionService;
use Carbon\Carbon;

class CompetitionManager extends CompetitionCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:competition-manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bu competitionlari yaratir ve realtime devamli ayakta kalmalarini saglar.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Competition Manager started.');

        while (true) {
            $created = $this->manageNewCompetitions();
            $confirmed = $this->confirmRequirementsCompetitions();
            $finished = $this->manageFinishedCompetitions();

            if (!$created || !$confirmed)
                sleep(1);

            $this->pong(60);
        }

    }

    private function manageNewCompetitions(): bool
    {
        $created = false;
        $plannedCompetitions = PlannedCompetition::active()->get();
        
        foreach ($plannedCompetitions as $plannedCompetition) {
            if (CompetitionService::isNeedMore($plannedCompetition)) {
                $created = true;
                $this->warn('[1/2] Need more: ' . $plannedCompetition->title);
                CompetitionService::newCompetition($plannedCompetition);
                $this->info('[2/2] Created: ' . $plannedCompetition->title);
            }
        }

        if ($plannedCompetitions->count() == 0)
            $this->error('There is no active planned competition.');

        return $created;
    }

    private function confirmRequirementsCompetitions(): bool
    {
        $confirmed = false;
        $readyCompetitions = Competition::with(['plannedCompetition', 'availableTickets'])->ready()->get();
        foreach ($readyCompetitions as $competition) {
            $confirmed = true;
            $this->warn('[1/3] Checking: ' . $competition->plannedCompetition->title);
            if (CompetitionService::isReady($competition)) {
                $this->warn('[2/3] Confirming: ' . $competition->plannedCompetition->title);
                CompetitionService::activate($competition);
                $this->info('[3/3] Confirmed and activated: ' . $competition->plannedCompetition->title);
            }
        }

        return $confirmed;
    }

    private function manageFinishedCompetitions(): bool
    {
        $finished = false;
        $finishedCompetitions = Competition::with(['plannedCompetition'])->active()->plannedDateExpired()->get();
        foreach ($finishedCompetitions as $competition) {
            $finished = true;
            $this->warn('[1/2] Finishing: ' . $competition->plannedCompetition->title);
            CompetitionService::disableBets($competition);
            $this->warn('[2/2] Bet disabled: ' . $competition->plannedCompetition->title);
        }

        return $finished;
    }
}
