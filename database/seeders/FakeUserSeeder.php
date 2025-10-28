<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enum\UserType;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FakeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '1024M');

        // 5000 bot users
        $usernames = ['ahmet', 'mehmet', 'mahmut', 'kemal', 'hasan', 'huseyin'];

        foreach ($usernames as $username)
        {
            $userData[] = [
                'uuid' => Str::uuid(),
                'first_name' => $username,
                'last_name' => $username,
                'username' => $username,
                'email' => $username . '@local',
                'type' => UserType::User,
                'password' => 'x',
                'email_verified_at' => now(),
                'password_change_required' => false,
                'created_at' => now(),
                'updated_at' => now(),
                'language' => 1,
            ];
        }

        User::insert($userData);

    }
}
