<?php

namespace App\Service;

use App\Enum\TransactionPurpose;
use App\Enum\TransactionStatus;
use App\Enum\TransactionType;
use App\Jobs\MaksiparaWithdraw;
use Illuminate\Support\Facades\Log;

class MaksiparaService
{
    public static function saveWithdrawRequest($user, $method, $amount, array $methodPayload)
    {
        $tx = TransactionService::newTransaction($user, $amount, $amount, 0, TransactionPurpose::Out, TransactionStatus::Pending, TransactionType::Method, $method, $methodPayload);
        
        if (!empty($tx))
            MaksiparaWithdraw::dispatch($tx)->onQueue('maksipara-withdraw-queue');

        return $tx;
    }

}
