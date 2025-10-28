<?php

namespace App\Service;

use App\Events\CompetitionNew;
use App\Events\CompetitionTicketPurchased;
use App\Traits\LoggerTrait;
use Carbon\Carbon;
use App\Models\User;
use App\Events\Balance;
use App\Models\Setting;
use App\Jobs\LotteryResult;
use App\Models\Competition;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Enum\TransactionType;
use App\Enum\CompetitionStatus;
use App\Enum\TransactionStatus;
use App\Enum\TransactionPurpose;
use App\Models\CompetitionTicket;
use App\Models\PlannedCompetition;
use App\Enum\CompetitionTicketType;
use App\Events\CompetitionStatusChanged;
use App\Events\CompetitionTicketCancelled;

class CompetitionService
{
    use LoggerTrait;

    private $rewards;
    public static function newCompetition(PlannedCompetition $plannedCompetition)
    {
        Log::channel('competition')->info("New competition creating... | Planned CID: {$plannedCompetition->uuid}");
        $competition = new Competition();
        $competition->uuid = Str::uuid();
        $competition->planned_competition_id = $plannedCompetition->id;
        $competition->status = CompetitionStatus::Preparing->value;

        /*
            REVIEW: planned_competition tablosundan type alanı kaldırılsınca bu kod anlamsız kaldı.
            Bu kısım nasıl düzenlenmeli?
        */
        // if ($plannedCompetition->type === 0)
        //    $competition->planned_finish_at = now()->addMinutes($plannedCompetition->interval_minutes);
        // else if ($plannedCompetition->type === 1)
        //    $competition->planned_finish_at = $plannedCompetition->planned_finish_at;

        $plannedFinishTime = now()->addMinutes($plannedCompetition->interval_minutes);
        $plannedFinishTime = $plannedFinishTime->addSeconds($plannedCompetition->octet * Setting::getByKey('lottery_wait_time'));
        $competition->planned_finish_at = $plannedFinishTime;
        $competition->save();

        Log::channel('competition')->info("New competition created! | Competition ID: {$competition->uuid}");
        self::makeTickets($competition);
        event(new CompetitionNew($competition));
    }

    public static function makeTickets(Competition $competition): void
    {
        Log::channel('competition')->info("Tickets generating... | Competition ID: {$competition->uuid}");

        $plannedCompetition = $competition->plannedCompetition;
        $tickets = self::generateTickets($plannedCompetition->ticket_count, $plannedCompetition->min_ticket_number, $plannedCompetition->max_ticket_number, $plannedCompetition->octet, '-');
        $totalTickets = count($tickets);

        Log::channel('competition')->info("{$totalTickets} Tickets generated! | Competition ID: {$competition->uuid}");

        Log::channel('competition')->info("Tickets inserting... | Competition ID: {$competition->uuid}");
        foreach ($tickets as $ticket) {
            $user = [];

            // Eğer test ortamındaysak biletlerin 100'de 30'u kadarı rastgele bir kullanıcıya atanır.
            /*if ($i <= ceil(count($tickets) * 0.3) && (config('app.env') === 'local' || config('app.env') === 'test'))
                $user = [
                    'user_id' => User::inRandomOrder()->first()->id,
                    'bet_at' => Carbon::now()
                ];*/

            $competition->availableTickets()->create([
                'uuid' => Str::uuid(),
                'number' => $ticket,
                'amount' => $plannedCompetition->ticket_amount,
                ...$user
            ]);
        }
        $count = $competition->availableTickets()->count();

        Log::channel('competition')->info("$count available tickets inserted! | Competition ID: {$competition->uuid}");


        if ($count + $competition->purchasedTickets()->count() >= $plannedCompetition->ticket_count)
            $competition->status = CompetitionStatus::Ready->value;
        else
            $competition->status = CompetitionStatus::Canceled->value;

        $competition->save();

        Log::channel('competition')->info("Status: {$competition->status} | Competition ID: {$competition->uuid}");

    }

    public static function disableBets(Competition $competition): void
    {
        Log::channel('competition')->info("Bets disabling... | Competition ID: {$competition->uuid}");

        $competition->bet_finished_at = Carbon::now();
        $competition->status = CompetitionStatus::WaitingResults->value;
        $competition->save();

        Log::channel('competition')->info("New status: {$competition->status} | Competition ID: {$competition->uuid}");
        Log::channel('competition')->info("Bets disabled! | Competition ID: {$competition->uuid}");

    }

    public static function handleLotteryForBots(Competition $competition): void {
        $purchasedTicketsAmount = $competition->purchasedTickets()->type(CompetitionTicketType::Bot)->sum('amount');
        $purchasedTicketsBotCount = $competition->purchasedTickets()->type(CompetitionTicketType::Bot)->pluck('user_id')->unique()->count();
        $purchasedTicketsAmount -= $purchasedTicketsAmount * $competition->plannedCompetition->cost_percentage / 100;

        $results = self::generateRewardsForBots(3, $purchasedTicketsAmount);



        Log::channel('competition')->info("Total Amount for Bots: {$purchasedTicketsAmount} TRY | Total Bots: {$purchasedTicketsBotCount} | Competition ID: {$competition->uuid}");
    }

    /**
     * Rastgele ama tutarlı ödül yüzdeleri üretir (her parça 10'un katı, toplam 100).
     *
     * @param int $baseCount   Hedeflenen ödül adedi (gerçekleşen -1, 0, +1 olabilir)
     * @param int $totalAmount Toplam ödül tutarı
     * @return array
     */
    public static function generateRewardsForBots(int $baseCount, int $totalAmount = 1000): array
    {
        // Adet sayısını belirle (-1, 0, +1 oynat)
        $delta = random_int(-1, 1);
        $count = max(1, $baseCount + $delta);

        $remaining = 100;
        $percents = [];

        // Her parça en az 10 olacak, kalan parçalar rastgele 10'un katları olacak
        for ($i = 0; $i < $count; $i++) {
            if ($i === $count - 1) {
                $percents[] = $remaining;
                break;
            }

            $maxForThis = $remaining - (($count - $i - 1) * 10);
            $possibleValues = range(10, $maxForThis, 10);
            $val = $possibleValues[array_rand($possibleValues)];

            $percents[] = $val;
            $remaining -= $val;
        }

        shuffle($percents);

        // Tutarları hesapla
        $prizes = [];
        $sumAmount = 0.0;
        foreach ($percents as $p) {
            $amount = round($totalAmount * ($p / 100), 2);
            $prizes[] = [
                'percent' => $p,
                'amount' => $amount
            ];
            $sumAmount += $amount;
        }

        // Yuvarlama farkını düzelt
        $diff = round($totalAmount - $sumAmount, 2);
        if (abs($diff) >= 0.01) {
            $maxIdx = array_keys($percents, max($percents))[0];
            $prizes[$maxIdx]['amount'] = round($prizes[$maxIdx]['amount'] + $diff, 2);
        }

        // Küçükten büyüğe sırala
        usort($prizes, fn($a, $b) => $a['percent'] <=> $b['percent']);

        return [
            'count'         => $count,
            'total_amount'  => $totalAmount,
            'percents'      => array_column($prizes, 'percent'),
            'prizes'        => $prizes,
            'total_percent' => array_sum(array_column($prizes, 'percent'))
        ];
    }

    public static function doLottery(Competition $competition): void
    {
        Log::channel('competition')->info("Results starting... | Competition ID: {$competition->uuid}");

        $competition->result_started_at = Carbon::now();
        $competition->status = CompetitionStatus::ResultsStarted->value;
        $competition->save();

        Log::channel('competition')->info("New Status: {$competition->status} | Competition ID: {$competition->uuid}");

        event(new CompetitionStatusChanged($competition));

        self::handleLotteryForBots($competition);

        $rewards = $competition->plannedCompetition->rewards()->orderBy('percentage', 'asc')->get();
        $purchasedTicketsAmount = $competition->purchasedTickets()->type(CompetitionTicketType::User)->sum('amount');
        $purchasedTicketsAmount -= $purchasedTicketsAmount * $competition->plannedCompetition->cost_percentage / 100;
        $purchasedTicketsUser = $competition->purchasedTickets()->type(CompetitionTicketType::User)->pluck('user_id')->unique()->count();

        Log::channel('competition')->info("Total Amount: {$purchasedTicketsAmount} TRY | Total Users: {$purchasedTicketsUser} | Competition ID: {$competition->uuid}");
        Log::channel('competition')->info("Expected Min Users: {$competition->plannedCompetition->min_purchased_ticket_user} | Needed Users for defined rewards: {$rewards->count()} | Competition ID: {$competition->uuid}");

        // Biletleri burada iptal ediyoruz
        if ($purchasedTicketsUser < $competition->plannedCompetition->min_purchased_ticket_user || $purchasedTicketsUser < $rewards->count()) {
            self::cancel($competition);
            return;
        }

        $i = 0;
        foreach ($rewards as $reward) {
            $i++;
            Log::channel('competition')->info("Starting to Reward: {$reward->uuid} ({$reward->title}) | Type: {$reward->type} | Percentage: %{$reward->percentage} | Competition ID: {$competition->uuid}");

            $selectedTicket = $competition->purchasedTickets()->type(CompetitionTicketType::User)->whereHas('user', function ($query) use ($reward) {
                $query->where('type', $reward->type);
            })->notWon()->inRandomOrder()->take(1)->first();

            if (!$selectedTicket) {
                Log::channel('competition')->error("There is no purchased ticket found for reward: {$reward->uuid} | Type: {$reward->type} | Percentage: %{$reward->percentage} | Competition ID: {$competition->uuid}");
                if ($i == $competition->plannedCompetition->rewards()->count())
                    self::cancel($competition);

                continue;
            }

            $rewardMoney = floor($purchasedTicketsAmount * $reward->percentage / 100);
            $selectedTicket->bet_at = now();
            $selectedTicket->won = 1;
            $selectedTicket->save();

            Log::channel('competition')->info("Ticket {$selectedTicket->uuid} ({$selectedTicket->number}) won! | Total Reward: {$rewardMoney} | Competition ID: {$competition->uuid}");

            $competition->lotteryResults()->create([
                'uuid' => Str::uuid(),
                'planned_competition_reward_id' => $reward->id,
                'ticket_id' => $selectedTicket->id,
                'amount' => $rewardMoney,
                'result_at' => Carbon::now(),
            ]);

            Log::channel('competition')->info("Results saved for ticket {$selectedTicket->uuid}! | Competition ID: {$competition->uuid}");

            LotteryResult::dispatch($competition, $selectedTicket, $reward)
                ->onQueue('lottery');

            Log::channel('competition')->info("Result announce threw for {$selectedTicket->uuid}! | Competition ID: {$competition->uuid}");

            $rewardTX = TransactionService::newTransaction($selectedTicket->user, $rewardMoney, $rewardMoney, 0, TransactionPurpose::In, TransactionStatus::Pending,  TransactionType::Competition);
            Log::channel('competition')->info("Transaction saved for ticket {$selectedTicket->uuid}! | TXID: {$rewardTX->id} | Competition ID: {$competition->uuid}");

        }
        Log::channel('competition')->info("Results done! | Competition ID: {$competition->uuid}");
    }

    public static function cancel(Competition $competition) {
        Log::channel('competition')->info("Competition cancelling... | Competition ID: {$competition->uuid}");

        $competition->status = CompetitionStatus::Canceled->value;
        $competition->save();

        Log::channel('competition')->info("Competition cancelled! | Competition ID: {$competition->uuid}");

        event(new CompetitionStatusChanged($competition));

        Log::channel('competition')->info("Tickets cancelling... | Competition ID: {$competition->uuid}");

        $count = 0;
        foreach ($competition->purchasedTickets()->type(CompetitionTicketType::User)->get() as $ticket) {
            $count++;
            $refund = TransactionService::newTransaction($ticket->user, $ticket->amount, 0, 0, TransactionPurpose::In, TransactionStatus::Completed,  TransactionType::Competition);
            if (!$refund)
                continue; // @TODO: Buraya bir sey yapmak lazim. TX kaydedilemezse.

            NotificationService::send($ticket->user, 'competition_ticket_cancelled', $ticket->uuid, CompetitionTicket::class, [
                new Balance($ticket->user),
                new CompetitionTicketCancelled($ticket->user, $ticket)
            ]);

            $ticket->bet_at = null;
            $ticket->won = null;
            $ticket->user_id = null;
            $ticket->save();
        }

        Log::channel('competition')->info("Cancelled Tickets: {$count} | Competition ID: {$competition->uuid}");
    }

    public static function isNeedMore(PlannedCompetition $plannedCompetition): bool
    {
        $count = $plannedCompetition->competitions()
            ->statuses([CompetitionStatus::Preparing->value, CompetitionStatus::Ready->value, CompetitionStatus::Active->value, CompetitionStatus::WaitingResults->value, CompetitionStatus::ResultsStarted->value])
            //->whereNotNull('result_finished_at')
            //->where('result_finished_at', '>=', Carbon::now()) // FIXME: Burası check edilmeli?
            ->count();

        if ($count < $plannedCompetition->real_time_count) {
            Log::channel('competition')->info("New competition should be created! Active: {$count} | Planned CID: {$plannedCompetition->uuid}");
            return true;
        }

        return false;
    }

    public static function isReady(Competition $competition): bool
    {
        // @TODO: Daha sonra acilacak.

        return true;

        $countTickets = $competition->availableTickets()->count();
        $sumTickets = $competition->availableTickets()->sum('amount');
        if ($countTickets === $competition->plannedCompetition->ticket_count && $sumTickets >= $competition->plannedCompetition->ticket_amount * $competition->plannedCompetition->ticket_count) {
            Log::channel('competition')->info("Competition is ready! | Competition ID: {$competition->uuid}");
            return true;
        }

        Log::channel('competition')->error("Competition is not ready! | Competition ID: {$competition->uuid}");
        return false;
    }

    public static function activate(Competition $competition): bool
    {
        Log::channel('competition')->info("Competition activating... | Competition ID: {$competition->uuid}");
        $competition->status = CompetitionStatus::Active->value;
        $competition->bet_started_at = now();
        $competition->save();
        Log::channel('competition')->info("New Status: {$competition->status} | Competition activated! | Competition ID: {$competition->uuid}");

        return true;
    }

    public static function complete(Competition $competition): bool
    {
        Log::channel('competition')->info("Competition completing... | Competition ID: {$competition->uuid}");
        $competition->status = CompetitionStatus::Completed->value;
        $competition->result_finished_at = now();
        $competition->save();
        Log::channel('competition')->info("New Status: {$competition->status} | Competition completed! | Competition ID: {$competition->uuid}");

        return true;
    }

    public static function purchaseTicketForABot(Competition $competition): bool
    {
        $user = User::bot()->inRandomOrder()->first();

        $ticket = $competition->availableTickets()->inRandomOrder()->first();
        $ticket->user_id = $user->id;
        $ticket->type = CompetitionTicketType::Bot->value;
        $ticket->bet_at = now();
        $ticket->save();
        return true;
    }

    private static function generateTickets(int $count, int $min, int $max, int $octet, string $seperator): array {
        $tickets = [];
        $seriesLength = strlen((string)$count); // Seri numarasının maksimum uzunluğunu hesapla
        $fixedPartCount = $octet - $seriesLength; // Sabit kısmın hane sayısını hesapla

        // Eğer sabit kısım çok uzunsa, onu kısalt ve seri kısmını uzat
        if ($fixedPartCount + $seriesLength > $octet) {
            $fixedPartCount = $octet - $seriesLength;
        }

        $fixedNumbers = self::generateTicketNumbers($min, $max, $fixedPartCount); // Sabit kısmı oluştur

        for ($i=$count; $i<=($count+$count-1); $i++) { // REVIEW: 100'den başlamasının sebebi ne? $count olarak değiştirildi
            $seriesNumber = str_pad((string)$i, $seriesLength, "0", STR_PAD_LEFT); // Seri numarasını oluştur
            $numbers = array_merge($fixedNumbers, str_split($seriesNumber));
            $ticket = implode($seperator, $numbers);
            $tickets[] = $ticket;
        }
        return $tickets;
    }

    private static function generateTicketNumbers(int $min, int $max, int $count): array {
        $numbers = [];
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = mt_rand($min, $max);
        }

        return $numbers;
    }
}
