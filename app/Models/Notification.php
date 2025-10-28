<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'user_id',
        'key',
        'type',
        'notifiable_uuid',
        'notifiable_type',
        'read_at',
        'seen_at',
    ];
}
