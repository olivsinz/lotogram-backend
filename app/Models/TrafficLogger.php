<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class TrafficLogger extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'traffic_logs';

    protected $fillable = [
        'host',
        'container',
        'x-request-id',
        'user_id',
        'ip_address',
        'method',
        'status_code',
        'path',
        'query',
        'headers',
        'body',
        'response_body',
        'response_headers',
        'request_time'
    ];
}
