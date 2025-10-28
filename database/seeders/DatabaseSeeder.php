<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\FakeUserSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            MethodSeeder::class,
            PlannedCompetitionSeeder::class,
            PlannedCompetitionRewardSeeder::class,
            BotSeeder::class
        ]);

        if (config('app.env') == 'local' || config('app.env') == 'test')
        {
            $this->call([
                FakeUserSeeder::class,
                BonusSeeder::class
            ]);
        }
    }
}
