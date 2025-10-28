<?php

namespace App\Models;

use App\Enum\PlannedCompetitionStatus;
use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlannedCompetition extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'amount' => 'float',
        'real_amount' => 'float',
        'real_time_count' => 'int',
        'title' => 'string',
        'ticket_amount' => 'float',
        'ticket_count' => 'int',
        'manipulate_wait_secs_after_bot' => 'int',
        'manipulate_wait_secs_after_user' => 'int',
    ];

    protected $fillable = [
        'uuid',
        'title',
        'cost_percentage',
        'amount',
        'real_amount',
        'type',
        'status',
        'ticket_count',
        'ticket_amount',
        'min_ticket_number',
        'max_ticket_number',
        'octet',
        'daily_limit',
        'real_time_count',
        'manipulate_wait_secs_after_bot',
        'manipulate_wait_secs_after_user'
    ];

    public function competitions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function rewards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PlannedCompetitionReward::class);
    }

    public function scopeActive(Builder $query): void {
        $query->where('status', PlannedCompetitionStatus::Active->value);
    }
}
