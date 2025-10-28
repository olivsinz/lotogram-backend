<?php

namespace App\Models;

use App\Enum\CompetitionStatus;
use App\Traits\Model\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   Competition extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'planned_competition_id' => 'int',
        'bet_started_at' => 'datetime',
        'bet_finished_at' => 'datetime',
        'planned_finish_at' => 'datetime',
        'result_started_at' => 'datetime',
        'result_finished_at' => 'datetime',
    ];

    protected $fillable = [
        'uuid',
        'bet_started_at',
        'planned_competition_id',
        'status',
    ];


    public function plannedCompetition(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PlannedCompetition::class);
    }

    public function purchasedTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompetitionTicket::class)->purchased();
    }

    public function availableTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompetitionTicket::class)->available();
    }

    public function lotteryResults(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompetitionLotteryResult::class);
    }

    public function scopePreparing(Builder $query): void {
        $query->where('status', CompetitionStatus::Preparing->value);
    }

    public function scopeReady(Builder $query): void {
        $query->where('status', CompetitionStatus::Ready->value);
    }

    public function scopeActive(Builder $query): void {
        $query->where('status', CompetitionStatus::Active->value);
    }

    public function scopeWaitingResults(Builder $query): void {
        $query->where('status', CompetitionStatus::WaitingResults->value);
    }

    public function scopeStatuses(Builder $query, array $statuses): void {
        $query->whereIn('status', $statuses);
    }

    public function scopePlannedDateExpired(Builder $query): void {
        $query->where('planned_finish_at', '<=', Carbon::now());
    }

    public function scopeReadyForLottery(Builder $query): void {
        $query->waitingResults()->plannedDateExpired();
    }

    public function scopeWaitingForBots($query): void {
        $query->where('is_settled_for_bots', 0);
    }

    public function scopeFilterByStatus(Builder $query, ?string $status): void
    {
        $status !== null
            && $query->where('status', $status);
    }




}
