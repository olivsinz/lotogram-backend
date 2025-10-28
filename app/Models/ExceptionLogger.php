<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ExceptionLogger extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'exception_logs';

    protected $fillable = [
        'host',
        'container',
        'x-request-id',
        'user_id',
        'ip_address',
        'message',
        'file',
        'line',
        'code',
        'trace',
        'ip_address',
        'country',
        'os',
        'user',
        'created_at',
    ];

    protected $casts = [
        'trace' => 'array',
        'created_at' => 'datetime',
        'user' => 'json',
    ];
}
