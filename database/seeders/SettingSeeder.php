<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    protected $settingList = [
        [
            'key' => 'lottery_wait_time',
            'value' => 2,
            'group' => 'general',
            'type' => 'numeric',
            'available_value' => null,
            'description' => 'Çekiliş sırasında her yeni bir numarayı çekmek için saniye cinsinden ne kadar beklemesi gerektiğini belirler.',
        ],
        [
            'key' => 'lottery_stat_detail',
            'value' => true,
            'group' => 'general',
            'type' => 'boolean',
            'available_value' => null,
            'description' => 'Çekiliş ekranında yer alan satılan bilet ve satın alan kullanıcı bilgilerinin gösterilip gösterilmeyeceğini belirler.',
        ]
    ];

    public function run(): void
    {
        foreach ($this->settingList as $setting)
        {
            Setting::firstOrCreate(['key' => $setting['key'],],
            [
                'uuid' => Str::uuid(),
                'key' => $setting['key'],
                'value' => $setting['value'],
                'group' => $setting['group'],
                'type' => $setting['type'],
                'available_value' => $setting['available_value'],
                'description' => $setting['description'],
            ]);
        }
    }
}
