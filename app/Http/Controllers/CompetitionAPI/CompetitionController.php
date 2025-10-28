<?php

namespace App\Http\Controllers\CompetitionAPI;

use App\Events\Balance;
use App\Models\Setting;
use App\Models\Competition;
use App\Enum\TransactionType;
use App\Enum\CompetitionStatus;
use App\Enum\TransactionStatus;
use App\Enum\TransactionPurpose;
use App\Models\CompetitionTicket;
use App\Service\TransactionService;
use App\Http\Controllers\Controller;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Auth;
use App\Events\CompetitionTicketPurchased;
use App\Exceptions\PurchaseTicketException;
use App\Http\Resources\CompetitionAPI\CompetitionResource;
use App\Http\Requests\CompetitionAPI\Competition\ShowRequest;
use App\Http\Requests\CompetitionAPI\Competition\IndexRequest;
use App\Http\Requests\CompetitionAPI\Competition\LotteryRequest;
use App\Http\Resources\CompetitionAPI\CompetitionTicketResource;
use App\Http\Resources\CompetitionAPI\CompetitionLotteryResource;
use App\Http\Requests\CompetitionAPI\Competition\PurchaseTicketRequest;
use Illuminate\Support\Facades\Log;

class CompetitionController extends Controller
{
    public function index(IndexRequest $request)
    {
        $competitions = Competition::statuses([CompetitionStatus::Ready->value, CompetitionStatus::Active->value, CompetitionStatus::WaitingResults->value, CompetitionStatus::ResultsStarted->value])
            ->with('plannedCompetition')
            ->filterByStatus($request->status)
            ->orderBy('created_at', 'DESC')
            ->paginate($request->per_page);

        return CompetitionResource::collection($competitions);
    }

    public function show(ShowRequest $request)
    {
        $competition = Competition::with(['plannedCompetition'])
            ->when(Setting::getByKey('lottery_stat_detail'), function ($query) {
                $query->withCount('purchasedTickets');
            })
            ->uuid($request->uuid);

        $totalUsersCount = Setting::getByKey('lottery_stat_detail')
            ? $competition->purchasedTickets->pluck('user_id')->unique()->count()
            : null;

        $competition->setAttribute('total_users', $totalUsersCount);

        return CompetitionResource::make($competition);
    }

    public function purchasedTickets(ShowRequest $request)
    {
        $competition = Competition::uuid($request->uuid);
        $tickets = $competition->purchasedTickets()->with('user')->filterByNumber($request->number)->paginate(50);

        return CompetitionTicketResource::collection($tickets);
    }

    public function availableTickets(ShowRequest $request)
    {
        $competition = Competition::uuid($request->uuid);
        $tickets = $competition->availableTickets()->filterByNumber($request->number)->paginate(50);
        return CompetitionTicketResource::collection($tickets);
    }

    public function purchasedMeTickets(ShowRequest $request)
    {
        $competition = Competition::uuid($request->uuid);
        $tickets = $competition->purchasedTickets()->with('user')->filterByNumber($request->number)->me()->get();
        return CompetitionTicketResource::collection($tickets);
    }

    public function lottery (LotteryRequest $request)
    {
        // TODO: performans optimizasyonu yapılmalı
        /*
        $competition = Competition::select(['id', 'uuid', 'planned_competition_id'])->with([
                'plannedCompetition:id,uuid,title,octet',
                'lotteryResults:id,uuid,planned_competition_reward_id,ticket_id,competition_id',
                'lotteryResults.plannedCompetitionReward:id,uuid,percentage',
                'lotteryResults.ticket:id,uuid,number,number_order'
            ])->uuid($request->uuid);
        */

        $competition = Competition::with([
            'plannedCompetition',
            'lotteryResults',
            'lotteryResults.plannedCompetitionReward',
            'lotteryResults.ticket.user'
        ])->uuid($request->uuid);

        return new CompetitionLotteryResource($competition);
    }

    public function purchase(PurchaseTicketRequest $request)
    {
        $competition = Competition::uuid($request->uuid);

        if ($request->random)
            $ticket = CompetitionTicket::with('competition')->available()->where('competition_id', $competition->id)->first() ;
        else
            $ticket = CompetitionTicket::with('competition')->available()->uuid($request->input('ticket.id'))->where('amount', $request->amount)->first();

        if (empty($ticket))
            return throw PurchaseTicketException::notFound();

        if ($ticket->user_id !== null)
            return throw PurchaseTicketException::alreadyPurchased();

        if ($ticket->competition->status !== CompetitionStatus::Active->value)
            return throw PurchaseTicketException::notAvailable();

        if ($ticket->amount > TransactionService::balance(auth()->user()))
            return throw PurchaseTicketException::insufficientBalance();

        TransactionService::newTransaction(auth()->user(), $ticket->amount, $ticket->amount, 0,TransactionPurpose::Out, TransactionStatus::Completed, TransactionType::Competition, null, []);

        $ticket->user_id = auth()->id();
        $ticket->bet_at = now();
        $ticket->save();

        NotificationService::send(Auth::user(), 'competition_ticket_purchased', $ticket->uuid, CompetitionTicket::class, [
            new Balance(Auth::user()),
            new CompetitionTicketPurchased($ticket)
        ]);

        $by = auth()->user()->username;
        Log::channel('competition')->info("Ticket purchased: {$ticket->uuid} ($ticket->number) | By: {$by} | Competition ID: {$competition->uuid}");

        return CompetitionTicketResource::make($ticket);
    }
}
