<?php

namespace App\Http\Controllers\CompetitionAPI;

use App\Events\Balance;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enum\TransactionStatus;
use App\Events\DepositApproved;
use App\Enum\TransactionPurpose;
use App\Events\WithdrawApproved;
use App\Http\Controllers\Controller;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CompetitionAPI\Deposit\MaksiparaCallbackRequest;

class MaksiparaController extends Controller
{
    public function gateway(Request $request) {

        if ($request->service == 'info') {
            $trx = Transaction::uuid($request->trx)->where('purpose', TransactionPurpose::In->value)->first();
            if (!$trx)
                return response()->json([
                    'code' => 999,
                    'message' => 'IN TX not found.'
                ]);

            return response()->json([
                'code' => 200,
                'message' => 'Müşteri yatırım gerçekleştirebilir!'
            ]);
        } else if ($request->service == 'deposit') {
            $maksiparaRequest = MaksiparaCallbackRequest::createFrom($request);
            $maksiparaRequest->setContainer(app())
                ->setRedirector(app()->make('redirect'))
                ->validateResolved();

            return $this->depositCallback($maksiparaRequest);
        }
        else if ($request->service == 'withdraw') {
            $maksiparaRequest = MaksiparaCallbackRequest::createFrom($request);
            $maksiparaRequest->setContainer(app())
                ->setRedirector(app()->make('redirect'))
                ->validateResolved();
            return $this->withdrawCallback($maksiparaRequest);
        }
    }

    public function depositCallback(MaksiparaCallbackRequest $request) {
        $trx = Transaction::where('purpose', TransactionPurpose::In->value)->uuid($request->trx);
        $trx->method_tx_id = $request->transaction_id;
        $trx->amount = $request->amount;
        $trx->status = $request->status === 'S' ? TransactionStatus::Completed->value : TransactionStatus::Canceled->value;
        $trx->save();

        NotificationService::send($trx->user, 'deposit_approved', $trx->uuid, Transaction::class, [
            new Balance($trx->user),
            new DepositApproved($trx->user, $trx)
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Müşteri hesabına bakiye eklendi!'
        ]);
    }

    public function withdrawCallback(MaksiparaCallbackRequest $request) {
        $trx = Transaction::where('purpose', TransactionPurpose::Out->value)->uuid($request->trx);
        $trx->method_tx_id = $request->transaction_id;
        $trx->amount = $request->amount;
        $trx->status = $request->status === 'C' ? TransactionStatus::Completed->value : TransactionStatus::Canceled->value;
        $trx->save();

        NotificationService::send($trx->user, 'withdraw_approved', $trx->uuid, Transaction::class, [
            new Balance($trx->user),
            new WithdrawApproved($trx->user, $trx)
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Müşteri hesabına bakiye eklendi!'
        ]);
    }
}
