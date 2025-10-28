<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class UserInterfaceSetting extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'user_interface_settings';

    protected $fillable = [
        'user_id',
        'setting'
    ];

    protected $casts = [
        'setting' => 'object',
        'user_id' => 'int'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
