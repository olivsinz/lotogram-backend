<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Balance
 *
 * @property int $id
 * @property int $method_id
 * @property int $site_id
 * @property Carbon $date
 * @property int $deposit_count
 * @property float $deposit_amount
 * @property float $deposit_commission
 * @property int $withdraw_count
 * @property float $withdraw_amount
 * @property float $withdraw_commission
 * @property int $site_deposit_count
 * @property float $site_deposit_amount
 * @property int $site_withdraw_count
 * @property float $site_withdraw_amount
 * @property float $balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Site $site
 *
 * @package App\Models
 */
class Balance extends Model
{
	protected $table = 'balances';

	protected $casts = [
		'method_id' => 'int',
		'site_id' => 'int',
		'date' => 'datetime',
		'deposit_count' => 'int',
		'deposit_amount' => 'float',
		'deposit_commission' => 'float',
		'withdraw_count' => 'int',
		'withdraw_amount' => 'float',
		'withdraw_commission' => 'float',
		'site_deposit_count' => 'int',
		'site_deposit_amount' => 'float',
		'site_withdraw_count' => 'int',
		'site_withdraw_amount' => 'float',
		'balance' => 'float'
	];

	protected $fillable = [
		'method_id',
		'site_id',
		'date',
		'deposit_count',
		'deposit_amount',
		'deposit_commission',
		'withdraw_count',
		'withdraw_amount',
		'withdraw_commission',
		'site_deposit_count',
		'site_deposit_amount',
		'site_withdraw_count',
		'site_withdraw_amount',
		'balance'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

    public function scopeFilterUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
