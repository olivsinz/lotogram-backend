<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderIpWhitelist extends Model
{
	use HasUuid, SoftDeletes;


    protected $fillable = [
		'uuid',
        'provider_id',
		'ip_address',
	];

	protected $casts = [
		'provider_id' => 'int'
	];

    public function scopeFilterByIpAddress($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }
}
