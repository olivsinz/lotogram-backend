<?php

namespace App\Models;

use App\Enum\GatewayType;
use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderGateway extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'is_active',
        'type',
        'deposit_callback_url',
        'withdraw_callback_url',
        'callback_timeout',
        'method_id',
        'provider_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'callback_timeout' => 'integer'
    ];

    public function getTypeAttribute($value)
    {
        return GatewayType::from($value)->toString();
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function method()
    {
        return $this->belongsTo(Method::class);
    }
}
