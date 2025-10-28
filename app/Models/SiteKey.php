<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SiteKey extends Model
{
    use HasUuid;

    protected $casts = [
		'method_id' => 'int',
		'provider_id' => 'int',
		'site_id' => 'int',
        'gateway_id' => 'int',
		'is_active' => 'bool',
		'expired_at' => 'datetime',
        'public_key' => 'encrypted',
        'private_key' => 'encrypted',

	];

	protected $fillable = [
        'uuid',
		'method_id',
		'provider_id',
		'site_id',
		'is_active',
		'private_key',
		'expired_at',
		'public_key',
        'gateway_id',
	];

	public function provider()
	{
		return $this->belongsTo(Provider::class);
	}

	public function site()
	{
		return $this->belongsTo(Site::class);
	}

    public function method()
    {
        return $this->belongsTo(Method::class);
    }

    public function gateway()
    {
        return $this->belongsTo(ProviderGateway::class);
    }
}
