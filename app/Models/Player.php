<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
	protected $casts = [
		'method_id' => 'int',
		'national_id' => 'int',
		'wallet_no' => 'int',
		'fake_ip' => 'inet',
		'proxy_id' => 'int',
		'phone' => 'int',
		'deposited_at' => 'datetime',
		'withdrawn_at' => 'datetime'
	];

	protected $fillable = [
		'uuid',
        'method_id',
		'national_id',
		'account_no',
		'wallet_no',
		'fake_ip',
		'fake_user_agent',
		'proxy_id',
		'first_name',
		'last_name',
		'phone',
		'email',
		'birthday',
		'deposited_at',
		'withdrawn_at'
	];

	public function method()
	{
		return $this->belongsTo(Method::class);
	}
}
