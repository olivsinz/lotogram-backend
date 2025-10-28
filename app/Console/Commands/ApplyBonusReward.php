<?php

namespace App\Console\Commands;

use App\Models\Bonus;
use App\Service\BonusService;
use Illuminate\Console\Command;

class ApplyBonusReward extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:apply-bonus-reward';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bonus tablosundaki mümkün olan bonusları uygular.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bonuses = Bonus::active()->get();

        if ($bonuses->count() === 0)
        {
            $this->info('app:apply-bonus-reward Uygulanacak bonus bulunamadı.');
            exit;
        }

        $this->info('app:apply-bonus-reward ' . $bonuses->count() . ' adet uygulanacak bonus bulundu.');

        $bonusService = new BonusService();

        foreach ($bonuses as $bonus)
        {
            $bonusService->applyBonus($bonus);
            sleep(1);
        }

        exit;
    }
}
