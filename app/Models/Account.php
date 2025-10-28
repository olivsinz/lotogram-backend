<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasUuid, HasFactory, SoftDeletes;

	protected $casts = [
		'method_id' => 'int',
		'national_id' => 'int',
		'wallet_no' => 'int',
		'method_balance' => 'float',
		'method_remained_limit' => 'float',
		'status' => 'int',
		'deposit_status' => 'bool',
		'withdraw_status' => 'bool',
		'phone' => 'int',
		'stats_daily_deposit_count' => 'int',
		'stats_daily_deposit_amount' => 'float',
		'stats_monthly_deposit_count' => 'int',
		'stats_monthly_deposit_amount' => 'float',
		'stats_daily_withdraw_count' => 'int',
		'stats_daily_wihtdraw_amount' => 'float',
		'stats_monthly_withdraw_count' => 'int',
		'stats_monthly_withdraw_amount' => 'float',
		'deposited_at' => 'datetime',
		'withdrawn_at' => 'datetime',
		'method_touched_at' => 'datetime'
	];

	protected $hidden = [
		'login_password',
		'login_2fa_secret'
	];

	protected $fillable = [
		'method_id',
		'first_name',
		'last_name',
		'national_id',
		'wallet_no',
		'method_balance',
		'method_remained_limit',
		'status',
		'deposit_status',
		'withdraw_status',
		'description',
		'email',
		'phone',
		'login_username',
		'login_password',
		'login_2fa_secret',
		'stats_daily_deposit_count',
		'stats_daily_deposit_amount',
		'stats_monthly_deposit_count',
		'stats_monthly_deposit_amount',
		'stats_daily_withdraw_count',
		'stats_daily_wihtdraw_amount',
		'stats_monthly_withdraw_count',
		'stats_monthly_withdraw_amount',
		'deposited_at',
		'withdrawn_at',
		'crypted_wallet_no',
		'method_touched_at'
	];

	public function method()
	{
		return $this->belongsTo(Method::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}

	public function pre_deposits()
	{
		return $this->hasMany(PreDeposit::class, 'receiver_account_id');
	}

	public function pre_withdraws()
	{
		return $this->hasMany(PreWithdraw::class, 'sender_account_id');
	}
}
