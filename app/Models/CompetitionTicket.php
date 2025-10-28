<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use App\Enum\CompetitionTicketType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompetitionTicket extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'amount' => 'float',
        'number' => 'string',
        'bet_at' => 'datetime',
    ];

    protected $fillable = [
        'uuid',
        'number',
        'amount',
        'type',
        'user_id',
        'bet_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function reward()
    {
        return $this->hasOneThrough(PlannedCompetitionReward::class, CompetitionLotteryResult::class, 'ticket_id', 'id', 'id', 'planned_competition_reward_id');
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->whereNull('user_id')->whereNull('bet_at')->whereNull('won');
    }

    public function scopeType(Builder $query, CompetitionTicketType $type): void
    {
        $query->where('type', $type->value);
    }

    public function scopeLastPurchased(Builder $query): void
    {
        $query->whereNotNull('user_id')->whereNotNull('bet_at')->latest('bet_at');
    }

    public function scopePurchased(Builder $query): void
    {
        $query->whereNotNull('user_id')->whereNotNull('bet_at');
    }

    public function scopeNotWon(Builder $query): void
    {
        $query->whereNull('won');
    }

    public function scopeFilterByNumber(Builder $query, $number): void
    {
        $number != null
            && $query->where('number', 'like', '%' . $number . '%');
    }

    public function scopeMe(Builder $query): void
    {
        $query->where('user_id', Auth::user()->id);
    }

    public function scopeUuid(Builder $query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }
}
