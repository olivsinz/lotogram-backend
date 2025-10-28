<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class HistoryLogger extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'history_logs';

    protected $casts = [
        'created_at' => 'datetime',
        'original' => 'json',
        'changes' => 'json'
    ];


    protected $fillable = [
        'x-request-id',
        'user',
        'user_id',
        'ownerable_id',
        'ownerable_type',
        'action',
        'changes',
        'original',
        'created_at',
        'ip_address'
    ];

    public function scopeFilterByOwnerable ($query, $ownerableId, $ownerableType)
    {
        return $query->where('ownerable_id', $ownerableId)->where('ownerable_type', $ownerableType);
    }
}
