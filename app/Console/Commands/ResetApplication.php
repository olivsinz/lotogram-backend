<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ResetApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (!in_array(env('APP_ENV'), ['local', 'test'])) {
            $this->error('Bu komut yalnızca local veya testing ortamlarında çalıştırılabilir. ');
            return;
        }

        $commands = [
            'git pull origin' => 'Git kodları güncellendi.',
            'pm2 delete services.yml' => 'Tüm servisler durduruldu.',
            'pm2 delete competition-services.yml' => 'Yarışma servisleri durduruldu.',
            'php artisan migrate:fresh --seed' => 'Veritabanı yenilendi ve seed işlemi tamamlandı.',
            'php artisan optimize:clear' => 'Cache temizlendi.',
            'pm2 start services.yml' => 'Servisler başlatıldı.',
            'pm2 start competition-services.yml' => 'Yarışma servisleri başlatıldı.',
        ];

        $step = 1;
        foreach ($commands as $command => $successMessage) {
            $this->line("\n<fg=yellow>Adım $step:</> <fg=default>{$command}</>");
            $process = Process::fromShellCommandline($command);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->error("Hata: " . $process->getErrorOutput());
                return;
            }

            $this->line("<fg=green>✔ Başarılı:</> $successMessage");
            $step++;
        }

        $this->info("\nSistem başarılı bir şekilde yenilendi.");
    }
}
