<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MethodTrafficLogger extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'method_traffic';

    protected $fillable = [
        'transaction_id',
        'method_id',
        'method',
        'url',
        'status_code',
        'total_time',
        'interface_ip',
        'target_ip',
        'host',
        'request_headers',
        'request_payload',
        'response_headers',
        'response_body',
        'timing',
        'container',
        'created_at'
    ];

    protected $casts = [
        'timing' => 'array',
        'total_time' => 'float'
    ];

    public $timestamps = false;
} 