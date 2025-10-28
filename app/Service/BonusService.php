<?php

namespace App\Service;

use App\Models\Bonus;
use Illuminate\Support\Facades\Log;

class BonusService
{
    public function applyBonus(Bonus $bonus)
    {
        $bonusActionService = new BonusActionService();
        return $bonusActionService->{$bonus->action_key}($bonus);
    }
}
