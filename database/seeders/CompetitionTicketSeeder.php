<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\User;
use App\Models\CompetitionTicket;
use App\Enum\CompetitionTicketType;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompetitionTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competitions = Competition::all();

        if ($competitions->isEmpty()) {
            $this->command->warn("\n\n⚠️ No competitions found. Please seed competitions first.");
            return;
        }

        $total = 0;

        foreach ($competitions as $competition) {
            // Example: each competition gets 10–20 tickets
            $count = rand(10, 20);

            for ($i = 1; $i <= $count; $i++) {
                CompetitionTicket::create([
                    'uuid' => Str::uuid(),
                    'competition_id' => $competition->id,
                    'amount' => rand(500, 2000) / 100, // random between 5.00 – 20.00
                    'number' => strtoupper(Str::random(10)),
                    'number_order' => $i,
                    'user_id' => null, // available ticket (not purchased yet)
                    'bet_at' => null,
                    'won' => null,
                    'type' => CompetitionTicketType::User->value,
                ]);
                $total++;
            }
        }

        CompetitionTicket::create([
            'uuid' => Str::uuid(),
            'competition_id' => $competitions->random()->value('id'),
            'amount' => rand(500, 2000) / 100, // random between 5.00 – 20.00
            'number' => strtoupper(Str::random(10)),
            'number_order' => 1,
            'user_id' => User::where('email', 'ozkan@virgosoft.io')->value('id'),
            'bet_at' => null,
            'won' => null,
            'type' => CompetitionTicketType::User->value,
        ]);

        $this->command->info("\n\n✅ Seeded {$total} competition tickets successfully.");
    }
}
