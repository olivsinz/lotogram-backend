<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enum\UserType;
use App\Models\Method;
use App\Enum\UserLanguage;
use App\Models\Transaction;
use App\Enum\TransactionType;
use Illuminate\Support\Carbon;
use App\Enum\TransactionStatus;
use Illuminate\Database\Seeder;
use App\Enum\TransactionPurpose;
use App\Service\TransactionService;
use App\Models\UserInterfaceSetting;

class UserSeeder extends Seeder
{
    protected $userList = [
        [
            'first_name' => 'Kenan',
            'last_name' => 'Birkan',
            'email' => 'kenan@virgosoft.io',
            'username' => 'kenan'
        ],
        [
            'first_name' => 'Ozkan',
            'last_name' => 'Singlecore',
            'email' => 'ozkan@virgosoft.io',
            'username' => 'ozkan'
        ],
        [
            'first_name' => 'Fuat',
            'last_name' => 'Naturel',
            'email' => 'fuat@virgosoft.io',
            'username' => 'fuat'
        ]

    ];

    public function run(): void
    {
        foreach ($this->userList as $user)
        {
            $user = User::firstOrCreate([
                'email' => $user['email'],
            ],
            [
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => 'AnladinMi.',
                'email_verified_at' => Carbon::now(),
                'password_change_required' => false,
                'created_at' => now(),
                'updated_at' => now(),
                'language' => UserLanguage::TR->value,
                'type' => UserType::User->value,
            ]);

            UserInterfaceSetting::firstOrCreate([
                'user_id' => $user->id,
            ],
            [
                'setting' => '{}',
            ]);

            TransactionService::newTransaction($user, 1000000, 0, 1000000, TransactionPurpose::In, TransactionStatus::Completed, TransactionType::Method, Method::inRandomOrder()->first());
        }
    }
}
