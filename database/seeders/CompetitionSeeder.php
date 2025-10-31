<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\PlannedCompetition;
use Illuminate\Support\Str;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $plannedCompetitions = PlannedCompetition::all();

        if ($plannedCompetitions->isEmpty()) {
            $this->command->warn("\n⚠️ No planned competition found. Please seed planned competition first.");
            return;
        }

        foreach ($plannedCompetitions as $plannedCompetition) {
            Competition::create([
                'uuid' => Str::uuid(),
                'planned_competition_id' => $plannedCompetition->id,
                'status' => rand(0, 4), // Example statuses: 0=Preparing, 1=Ready, 2=Active, 3=WaitingResults, 4=Finished
                'is_settled_for_bots' => rand(0, 1),
                'planned_finish_at' => $now->copy()->addDays(rand(1, 10)),
                'bet_started_at' => $now->copy()->subDays(rand(1, 5)),
                'bet_finished_at' => $now->copy()->addDays(rand(1, 3)),
                'result_started_at' => null,
                'result_finished_at' => null,
            ]);
        }
    }
}
