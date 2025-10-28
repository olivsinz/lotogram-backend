<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

abstract class CompetitionCommand extends Command
{
    public function line($string, $style = null, $verbosity = null): void
    {
        parent::line("[{$this->getDate()}] " . $string, $style, $verbosity);
    }

    private function getDate(): string
    {
        return Carbon::now()->toDateTimeString();
    }

    public function pong($interval) {
        if (Carbon::now()->second % $interval == 0) {
            $ping = DB::selectOne("SELECT NOW() as pong");
            $this->line($this->signature . ' has pong: ' . $ping->pong);
        }
    }
}
