<?php

namespace App\Console\Commands;

use App\Models\MethodTrafficLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ListenMethodTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:listen-method-traffic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Method trafiğini redis üzerinden dinler ve MongoDB\'ye kaydeder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Listening method traffic...');

        Redis::subscribe(['method-traffic-channel'], function ($message) {
            try {
                $data = json_decode($message, true);
                MethodTrafficLogger::create($data['data']);
                
                $this->info(sprintf(
                    "[Method Traffic] TX: %s, Method: %s, URL: %s, Status: %s, Time: %s ms",
                    $data['data']['transaction_id'],
                    $data['data']['method_id'],
                    $data['data']['url'],
                    $data['data']['status_code'],
                    $data['data']['total_time']
                ));
            }
            catch (\Throwable $t) {
                $this->error("Redis Subscribe Error: " . $t->getMessage());
            }
        });
    }
} 