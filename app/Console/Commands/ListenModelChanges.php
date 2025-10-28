<?php

namespace App\Console\Commands;

use App\Models\HistoryLogger;
use App\Service\LoggerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ListenModelChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:listen-model-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Model değişikliklerini redis üzerinden dinler ve mongo db ye kaydeder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Listening model changes...');

        Redis::subscribe(['model-changes-channel'], function ($message) {
            try {
                $data = json_decode($message, true);
                HistoryLogger::create($data['data']);
                $this->info("[History Log] {$data['data']['action']} {$data['data']['ip_address']} {$data['data']['x-request-id']} {$data['data']['user_id']} {$data['data']['ownerable_type']} {$data['data']['ownerable_id']}");
            }
            catch (\Throwable $t) {
                $this->error("Redis Subscribe Error: " . $t->getMessage());
            }
        });
    }
}
