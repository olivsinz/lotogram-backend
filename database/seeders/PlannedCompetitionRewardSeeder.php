<?php

namespace Database\Seeders;

use App\Enum\CompetitionTicketType;
use App\Enum\UserType;
use App\Models\PlannedCompetition;
use App\Models\PlannedCompetitionReward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlannedCompetitionRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competitions = PlannedCompetition::get();

        foreach ($competitions as $competition) {
            $rewards = [2, 2, 2, 2, 2, 5, 5, 5, 5, 10, 10, 10, 10, 10];

            foreach ($rewards as $reward) {
                /*PlannedCompetitionReward::create([
                    'uuid' => Str::uuid(),
                    'title' => 'Ödül %' . $reward,
                    'planned_competition_id' => $competition->id,
                    'percentage' => $reward,
                    'type' => UserType::Bot->value
                ]);*/
            }

            PlannedCompetitionReward::create([
                'uuid' => Str::uuid(),
                'title' => 'Ödül %' . $reward,
                'planned_competition_id' => $competition->id,
                'percentage' => 100,
                'type' => CompetitionTicketType::User->value
            ]);
        }

    }
}
