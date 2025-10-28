<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionLotteryResult extends Model
{
    use HasFactory;

    protected $casts = [
        'competition_id' => 'int',
        'planned_competition_reward_id' => 'int',
        'ticket_id' => 'int',
        'won' => 'int',
    ];

    protected $fillable = [
        'uuid',
        'planned_competition_reward_id',
        'ticket_id',
        'won',
        'result_at',
        'amount'
    ];

    public function competition(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function plannedCompetitionReward(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PlannedCompetitionReward::class);
    }

    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompetitionTicket::class);
    }
}
