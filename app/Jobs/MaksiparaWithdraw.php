<?php

namespace App\Jobs;

use App\Enum\MethodType;
use App\Enum\TransactionStatus;
use App\Models\Transaction;
use App\Service\LoggerService;
use App\Service\TransactionService;
use App\Traits\LoggerTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MaksiparaWithdraw implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LoggerTrait;

    protected Transaction $tx;

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $tx)
    {
        $this->tx = $tx;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $balance = TransactionService::balance($this->tx->user);

        if ($balance < $this->tx->amount) {
            $this->tx->status = TransactionStatus::Canceled->value;
            $this->tx->transactionDetail->description = 'Bakiye yetersiz. İşlem iptal edildi. Bakiye: ' . $balance . ' İstenen: ' . $this->tx->amount;
            $this->tx->transactionDetail->save();
            return;
        }
        
        $this->tx->status = TransactionStatus::Processing->value; // @TODO: Burada Processing olan islemlerin de bakiyeden dusmesi gerekiyor. Balances hesaplayan fonksiyon (dbdeki) guncellenmeli.
        $this->tx->save();

        if ($this->tx->method->type == MethodType::VirtualWallet->value) {
            $this->withdrawToVirtualWallet();
        } elseif ($this->tx->method == MethodType::BankTransfer->value) {
            //$this->withdrawToBank();
        } elseif ($this->tx->method == MethodType::Crypto->value) {
            //$this->withdrawToCrypto();
        }
    }

    private function withdrawToVirtualWallet(): void
    {
        $payload = [
            ... $this->staticPayload(),
            'account' => $this->tx->transactionDetail->method_payload['account'],
        ];

        $requestStartTime = microtime(true);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post(config('app.maksipara_api_url') . '/Withdrawal/' . $this->tx->method->slug, $payload);

        LoggerService::methodTraffic(
            $this->tx->id,
            $this->tx->method_id,
            config('app.maksipara_api_url') . '/Withdrawal/' . $this->tx->method->slug,
            'POST',
            ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            $payload,
            $response,
            $requestStartTime
        );
    }

    private function staticPayload(): array {
        return [
            'sid' => config('app.maksipara_sid'),
            'key' => config('app.maksipara_key'),
            'user_id' => $this->tx->user->id,
            'username' => $this->tx->user->username,
            'trx' => $this->tx->uuid,
            'fullname' => $this->tx->user->first_name . ' ' . $this->tx->user->last_name,
            'amount' => $this->tx->amount,
            'data' =>null,
        ];
    }
}
