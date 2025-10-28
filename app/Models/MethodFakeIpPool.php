<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MethodFakeIpPool
 * 
 * @property int $id
 * @property int $method_id
 * @property inet $ip_address
 * @property bool $is_active
 * @property Carbon $last_used_on_deposit
 * @property Carbon $last_used_on_withdraw
 * 
 * @property Method $method
 *
 * @package App\Models
 */
class MethodFakeIpPool extends Model
{
	protected $table = 'method_fake_ip_pools';
	public $timestamps = false;

	protected $casts = [
		'method_id' => 'int',
		'ip_address' => 'inet',
		'is_active' => 'bool',
		'last_used_on_deposit' => 'datetime',
		'last_used_on_withdraw' => 'datetime'
	];

	protected $fillable = [
		'method_id',
		'ip_address',
		'is_active',
		'last_used_on_deposit',
		'last_used_on_withdraw'
	];

	public function method()
	{
		return $this->belongsTo(Method::class);
	}
}
