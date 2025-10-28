<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory, QueryCacheable, HasUuid;

    public $cacheFor = 3600;
    protected static $flushCacheOnUpdate = true;

    protected $fillable = [
        'value',
    ];

    public function scopeGetByKey($query, string $key)
    {
        return $query->where('key', $key)->first()->value;
    }

}
