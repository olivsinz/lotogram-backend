<?php

namespace App\Http\Controllers\CompetitionAPI;

use App\Models\User;
use App\Models\Method;
use App\Enum\TransactionType;
use App\Enum\TransactionStatus;
use App\Enum\TransactionPurpose;
use App\Service\TransactionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompetitionAPI\DepositURLResource;
use App\Http\Resources\CompetitionAPI\MethodListResource;
use App\Http\Requests\CompetitionAPI\Deposit\StoreRequest;

class DepositController extends Controller
{
    public function methodList()
    {
        $methods = Method::depositActive()->get();
        return MethodListResource::collection($methods);
    }

    public function store(StoreRequest $request)
    {
        $user = Auth::user();
        $method = Method::slug($request->slug)->first();
        $fullname = urlencode($user->first_name . ' ' . $user->last_name);

        if (config('app.env') == 'local' || config('app.env') == 'test') {
            $user->first_name = 'BEHİYE';
            $user->last_name = 'GÖKMEN';
        }

        $tx = TransactionService::newTransaction($user, 0, 0, 0,TransactionPurpose::In, TransactionStatus::Pending, TransactionType::Method, $method);

        if ($tx) {
            $url = config('app.maksipara_deposit_url') . '/Methods/' . $method->slug . '/?sid=' . config('app.maksipara_sid') . '&username=' . $user->username . '&userID=' . $user->id . '&fullname=' . $fullname . '&trx=' . $tx->uuid . '&return_url=' . config('app.app_ui_url');
            return DepositURLResource::make((object) ['url' => $url]);
        }
    }
}
