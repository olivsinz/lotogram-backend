<?php

namespace App\Service;

use App\Enum\TransactionPurpose;
use App\Enum\TransactionStatus;
use App\Enum\TransactionType;
use App\Models\Balance;
use App\Models\Method;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;

class TransactionService
{
    public static function generateTransactionId(): string
    {
        return 'APL' . time() . rand(1000, 9999);
    }

    public static function newTransaction(User $user, float $amount, float $withdrawable_amount, float $bonus_amount, TransactionPurpose $purpose, TransactionStatus $status,  TransactionType $type, ?Method $method = null, $methodPayload = []): Transaction
    {
        $transaction = new Transaction();
        $transaction->uuid = Str::uuid();
        $transaction->user_id = $user->id;
        $transaction->amount = $amount;
        $transaction->withdrawable_amount = $withdrawable_amount;
        $transaction->bonus_amount = $bonus_amount;
        $transaction->purpose = $purpose->value;
        $transaction->type = $type->value;
        $transaction->status = $status->value;
        $transaction->method_id = $method ? $method->id : null;
        $transaction->save();

        $transaction->transactionDetail()->create([
            'method_payload' => $methodPayload,
        ]);

        return $transaction;
    }

    public static function balance(User $user)
    {
        $balance = Balance::filterUser($user->id)
            ->sum('balance');

        return $balance;
    }

    public static function withdrawableBalance(User $user)
    {
        $balance = Balance::filterUser($user->id)
            ->sum('withdrawable_balance');

        return $balance;
    }

    public static function bonusBalance(User $user)
    {
        $balance = Balance::filterUser($user->id)
            ->sum('bonus_balance');

        return $balance;
    }
}
