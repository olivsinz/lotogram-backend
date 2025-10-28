<?php

namespace App\Http\Controllers\CompetitionAPI;

use App\Enum\MethodType;
use App\Exceptions\WithdrawException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompetitionAPI\Withdraw\StoreRequest;
use App\Http\Resources\CompetitionAPI\MethodListResource;
use App\Http\Resources\CompetitionAPI\TransactionResource;
use App\Models\Method;
use App\Service\MaksiparaService;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WithdrawController extends Controller
{
    public function methodList()
    {
        $methods = Method::withdrawActive()->get();
        return MethodListResource::collection($methods);
    }

    public function storeVirtualWallet(StoreRequest $request)
    {
        $user = Auth::user();

        if ($user->phone == null || $user->national_id == null || $user->birth_date == null)
        {
            throw WithdrawException::missedProfileInfo();
        }

        $method = Method::slug($request->slug)->virtualWallet()->firstOrFail();
        
        $tx = MaksiparaService::saveWithdrawRequest($user,$method, $request->amount, [
            'account' => $request->account,
        ]);

        return (new TransactionResource($tx))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
