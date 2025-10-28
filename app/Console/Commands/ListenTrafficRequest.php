<?php

namespace App\Console\Commands;

use App\Models\TrafficLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ListenTrafficRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:listen-traffic-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Her bir request i detayları ile birlikte redis üzerinden dinler ve mongo db ye kaydeder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Listening traffic logs...');

        Redis::subscribe(['traffic-log-channel'], function ($message) {
            try {
                $data = json_decode($message, true);
                TrafficLogger::create($data['data']);
                $this->info("[Traffic Log] {$data['data']['method']} {$data['data']['ip_address']} {$data['data']['x-request-id']} {$data['data']['path']} ......... {$data['data']['request_time']}");
            }
            catch (\Throwable $t)
            {
                $this->error("Redis Subscribe Error: " . $t->getMessage());
            }
        });

    }
}
