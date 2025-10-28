<?php

namespace Database\Seeders;

use App\Models\Bonus;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BonusSeeder extends Seeder
{
    protected $bonusList = [
        /*[
            'name' => 'Hoşgeldin bonusu',
            'description' => 'Üyeliğinize özel 10.000 TL hoşgeldin bonusu bizden.',
            'amount' => 10000,
            'action_key' => 'welcome'
        ]*/
    ];

    public function run(): void
    {
        foreach ($this->bonusList as$bonus)
        {
            Bonus::firstOrCreate(['name' => $bonus['name'],],
            [
                'uuid' => Str::uuid(),
                'name' => $bonus['name'],
                'amount' => $bonus['amount'],
                'description' => $bonus['description'],
                'is_active' => true,
                'started_at' => now(),
                'ended_at' => now()->addDay(30),
                'action_key' => $bonus['action_key']
            ]);
        }
    }
}
