<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Transaction;
use App\Service\TFAService;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Models\CompetitionTicket;
use App\Service\TransactionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Dashboard\MeResource;
use App\Http\Resources\Dashboard\BonusResource;
use App\Http\Requests\Dashboard\Me\UpdateRequest;
use App\Http\Requests\Dashboard\Me\EnableTFARequest;
use App\Http\Requests\Dashboard\Me\DisableTFARequest;
use App\Http\Resources\Dashboard\NotificationResource;
use App\Http\Requests\CompetitionAPI\CancelTicketReques;
use App\Http\Requests\Dashboard\Me\GetGoogleQRCodeRequest;
use App\Http\Resources\CompetitionAPI\TransactionResource;
use App\Http\Requests\Dashboard\Me\CreateTFASessionRequest;
use App\Http\Requests\Dashboard\Me\SetEmailVerifiedRequest;
use App\Http\Requests\Dashboard\Me\SendMailForTFACodeRequest;
use App\Http\Resources\CompetitionAPI\CompetitionTicketResource;
use App\Http\Requests\Dashboard\Me\SendMailForVerificationCodeRequest;

class MeController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $user->load('title:id,uuid,name', 'userGroup:id,uuid,name');

        return MeResource::make($user);
    }

    public function update(UpdateRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());

        if($request->filled('settings'))
            UserService::createOrUpdateUserInterfaceSetting($user, $request->settings);

        return MeResource::make($user);
    }

    public function enableTFA(EnableTFARequest $request)
    {
        if(TFAService::isEnablePossible(Auth::user(), $request->method, $request->secret))
        {
            (Auth::user())->update([
                'has_tfa' => true,
                'tfa_method' => $request->method
            ]);
        }

        return response()->noContent();
    }

    public function disableTFA(DisableTFARequest $request)
    {
        if(TFAService::isDisablePossible(Auth::user(), $request->secret))
        {
            (Auth::user())->update([
                'has_tfa' => false,
                'tfa_method' => null,
                'tfa_secret' => null
            ]);
        }

        return response()->noContent();
    }

    public function sendMailForTFACode(SendMailForTFACodeRequest $request)
    {
        TFAService::sendCode(Auth::user(), $request->purpose);

        return response()->noContent();
    }

    public function sendMailForVerificationCode(SendMailForVerificationCodeRequest $request)
    {
        UserService::sendEmailVerificationCode(Auth::user(), request()->bearerToken());
        return response()->noContent();
    }

    public function setEmailVerified(SetEmailVerifiedRequest $request)
    {
        UserService::setEmailVerified(Auth::user(), $request->secret);
        return response()->noContent();
    }

    public function getGoogleQRCode(GetGoogleQRCodeRequest $request)
    {
        return response()->json([
            'data' => [
                'url' => TFAService::getQRCodeUrl(Auth::user(), $request->has('fresh'))
            ]
        ]);
    }

    public function createTFASession(CreateTFASessionRequest $request)
    {
        TFAService::createTFASession(Auth::user(), $request->secret);
        return response()->noContent();
    }

    public function sendMailForTFASessionCode(Request $request)
    {
        TFAService::sendCode(Auth::user(), 'tfa_session');

        return response()->noContent();
    }

    public function tickets(Request $request)
    {
        $tickets = CompetitionTicket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate();

        $tickets->load('competition.plannedCompetition:id,uuid,title');

        return CompetitionTicketResource::collection($tickets);
    }

    public function cancelTicket(CancelTicketReques $request)
    {
        $ticket = CompetitionTicket::where('uuid', $request->ticket_uuid)->first();
        $ticket->bet_at = null;
        $ticket->user_id = null;
        $ticket->save();

        return response()->noContent();
    }

    public function transactions(Request $request)
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate();

        $transactions->load('method:id,uuid,name');

        return TransactionResource::collection($transactions);
    }

    public function balances (Request $request)
    {
        return response()->json([
            'data' => [
                'balance' => TransactionService::balance(Auth::user()),
                'withdrawable_balance' => TransactionService::withdrawableBalance(Auth::user()),
                'bonus_balance' => TransactionService::bonusBalance(Auth::user()),
            ]
        ]);
    }

    public function notifications (Request $request)
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate();

        return NotificationResource::collection($notifications);
    }

    public function readNotification (Request $request)
    {
        $notification = Auth::user()->notifications()
            ->where('uuid', $request->uuid)
            ->firstOrFail();

        $notification->update(['read_at' => now()]);

        return response()->noContent();
    }


    public function bonuses (Request $request)
    {
        $bonuses = Auth::user()->bonuses()
            ->orderBy('created_at', 'desc')
            ->paginate();

        return BonusResource::collection($bonuses);
    }


}
