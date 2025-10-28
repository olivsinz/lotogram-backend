<?php

namespace Database\Seeders;

use App\Enum\MethodType;
use App\Models\Method;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class MethodSeeder extends Seeder
{
    protected $methodList = [
        [
            'name' => 'Maksipara - Papara',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'Papara',
            'type' => MethodType::VirtualWallet,
        ],
        [
            'name' => 'Maksipara - Banka Transferi',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'BankTransfer',
            'type' => MethodType::BankTransfer,
            'is_active' => false,
        ],
        [
            'name' => 'Maksipara - BTC - USDT',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'Crypto',
            'type' => MethodType::Crypto,
            'is_active' => false,
        ],
        [
            'name' => 'Maksipara - Kredi Kartı',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'CreditCard',
            'type' => MethodType::BankTransfer,
            'is_active' => false,
        ],
        [
            'name' => 'Maksipara - Pep',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'Pep',
            'type' => MethodType::VirtualWallet,
        ],
        [
            'name' => 'Maksipara - Hayhay',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'Hayhay',
            'type' => MethodType::VirtualWallet,
        ],
        [
            'name' => 'Maksipara - Paycell',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'Paycell',
            'type' => MethodType::VirtualWallet,
        ],
        [
            'name' => 'Maksipara - Paybol',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'Paybol',
            'type' => MethodType::VirtualWallet,
        ],
        [
            'name' => 'Maksipara - Popypara',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'Popypara',
            'type' => MethodType::BankTransfer,
            'is_active' => false,
        ],
        [
            'name' => 'Maksipara - OzanPay',
            'panel_domain' => 'https://www.maksipara.com',
            'slug' => 'OzanPay',
            'type' => MethodType::VirtualWallet,
        ],

    ];

    public function run(): void
    {
        foreach ($this->methodList as $method)
        {
            Method::firstOrCreate([
                'slug' => Str::slug($method['name']),
            ],
            [
                'name' => $method['name'],
                'panel_domain' => $method['panel_domain'],
                'is_active' => $method['is_active'] ?? true,
                'type' => $method['type'],
                'deposit_status' => true,
                'withdraw_status' => true,
                'worker_status' => true,
                'slug' => $method['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
