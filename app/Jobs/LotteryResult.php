<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\Competition;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Enum\CompetitionStatus;
use App\Models\CompetitionTicket;
use App\Events\CompetitionTicketWon;
use Illuminate\Queue\SerializesModels;
use App\Events\CompetitionRewardStarted;
use App\Events\CompetitionStatusChanged;
use App\Models\PlannedCompetitionReward;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CompetitionResultAnnounced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class LotteryResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(protected Competition $competition, protected CompetitionTicket $ticket, protected PlannedCompetitionReward $reward)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new CompetitionRewardStarted($this->competition, $this->reward));

        foreach(explode('-', $this->ticket->number) as $key => $numberPart){
            $key = $key + 1;
            $this->ticket->number_order = $key;
            $this->ticket->save();

            echo 'Announcing result (#ticketId: '.$this->ticket->id.') = Order: ' . $key . PHP_EOL;

            event(new CompetitionResultAnnounced($this->competition, $this->reward, $numberPart, $key));

            if ($this->competition->plannedCompetition()->first()->octet == $key)
            {
                event(new CompetitionTicketWon($this->ticket));
            }

            echo 'Start to wait' . Carbon::now();
            sleep(Setting::getByKey('lottery_wait_time'));
            echo 'End to wait' . Carbon::now();

        }

        if ($this->competition->lotteryResults()->count() == $this->competition->plannedCompetition->rewards()->count()) {
            $this->competition->result_finished_at = Carbon::now();
            $this->competition->status = CompetitionStatus::Completed->value;
            $this->competition->save();

            event(new CompetitionStatusChanged($this->competition));
        }

    }
}
