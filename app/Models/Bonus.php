<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'is_active',
        'starter_at',
        'ended_at',
        'action_key'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'bonus_users');
    }
}
