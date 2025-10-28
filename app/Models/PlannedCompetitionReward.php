<?php

namespace App\Models;

use App\Enum\UserType;
use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlannedCompetitionReward extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'title',
        'planned_competition_id',
        'type',
        'percentage',
    ];

    public function scopeBot($query)
    {
        return $query->where('type', UserType::Bot->value);
    }
}
