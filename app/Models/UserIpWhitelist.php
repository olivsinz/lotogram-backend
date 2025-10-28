<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserIpWhitelist extends Model
{
    use HasUuid, SoftDeletes;


    protected $fillable = [
		'uuid',
        'user_id',
		'ip_address',
	];

	protected $casts = [
		'user_id' => 'int'
	];

    public function scopeFilterByIpAddress($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }
}
