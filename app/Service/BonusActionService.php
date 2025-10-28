<?php

namespace App\Service;

use App\Models\User;
use App\Models\Bonus;
use App\Models\Method;
use App\Events\Balance;
use App\Enum\TransactionType;
use App\Events\BonusApproved;
use App\Enum\TransactionStatus;
use App\Enum\TransactionPurpose;
use App\Traits\LoggerTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusActionService
{
    use LoggerTrait;

    public function welcome(Bonus $bonus)
    {
        $this->writeLog($bonus->name . ' ( ' . $bonus->action_key . ')  adlı bonus için user seçimi başladı.');
        $bonussableUsers = User::active()->emailVerified()->notAwardedBonus($bonus->id)->filterByCreatedAt([$bonus->started_at, $bonus->ended_at])->get();

        if ($bonussableUsers->count() == 0) {
            $this->writeLog($bonus->name . ' ( ' . $bonus->action_key . ')  adlı bonus için uygun user bulunamadı.');
            return false;
        }

        $this->writeLog($bonus->name . ' ( ' . $bonus->action_key . ')  adlı bonus için toplam ' . $bonussableUsers->count() . ' adet user bulundu.');

        return DB::transaction(function () use ($bonussableUsers, $bonus) {
            foreach ($bonussableUsers as $user) {
                $user->bonuses()->attach($bonus->id);
                $trx = TransactionService::newTransaction($user, $bonus->amount, 0, $bonus->amount, TransactionPurpose::In, TransactionStatus::Completed, TransactionType::Bonus);
                NotificationService::send($user, 'bonus_approved', $bonus->uuid, Bonus::class, [
                    new Balance($user),
                    new BonusApproved($user, $trx)
                ]);
                $this->writeLog($bonus->name . ' ( ' . $bonus->action_key . ')  adlı bonus ' . $user->username . ' kullanıcısına uygulandı.');
            }
            return true;
        });
    }
}
