<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PreDeposit
 * 
 * @property int $id
 * @property string $uuid
 * @property int $method_id
 * @property int $site_id
 * @property int $provider_id
 * @property int|null $receiver_account_id
 * @property int|null $transaction_id
 * @property string $provider_tx_id
 * @property string|null $player_username
 * @property string|null $player_fullname
 * @property string|null $player_id
 * @property float $amount
 * @property int $status
 * @property int $last_step
 * @property int|null $method_proxy_id
 * @property inet|null $method_fake_ip
 * @property string|null $method_login
 * @property string|null $method_temp_token
 * @property string|null $method_token
 * @property string|null $method_user_info
 * @property string $method_user_agent
 * @property string|null $method_last_step_response
 * @property int|null $method_tx_id
 * @property string|null $method_firstname
 * @property string|null $method_lastname
 * @property int|null $method_wallet_no
 * @property float|null $method_remained_limit
 * @property float|null $method_balance
 * @property inet|null $provider_ip
 * @property inet|null $player_ip
 * 
 * @property Method $method
 * @property Site $site
 * @property Provider $provider
 * @property Account|null $account
 *
 * @package App\Models
 */
class PreDeposit extends Model
{
	protected $table = 'pre_deposits';
	public $timestamps = false;

	protected $casts = [
		'method_id' => 'int',
		'site_id' => 'int',
		'provider_id' => 'int',
		'receiver_account_id' => 'int',
		'transaction_id' => 'int',
		'amount' => 'float',
		'status' => 'int',
		'last_step' => 'int',
		'method_proxy_id' => 'int',
		'method_fake_ip' => 'inet',
		'method_tx_id' => 'int',
		'method_wallet_no' => 'int',
		'method_remained_limit' => 'float',
		'method_balance' => 'float',
		'provider_ip' => 'inet',
		'player_ip' => 'inet'
	];

	protected $hidden = [
		'method_temp_token',
		'method_token'
	];

	protected $fillable = [
		'uuid',
		'method_id',
		'site_id',
		'provider_id',
		'receiver_account_id',
		'transaction_id',
		'provider_tx_id',
		'player_username',
		'player_fullname',
		'player_id',
		'amount',
		'status',
		'last_step',
		'method_proxy_id',
		'method_fake_ip',
		'method_login',
		'method_temp_token',
		'method_token',
		'method_user_info',
		'method_user_agent',
		'method_last_step_response',
		'method_tx_id',
		'method_firstname',
		'method_lastname',
		'method_wallet_no',
		'method_remained_limit',
		'method_balance',
		'provider_ip',
		'player_ip'
	];

	public function method()
	{
		return $this->belongsTo(Method::class);
	}

	public function site()
	{
		return $this->belongsTo(Site::class);
	}

	public function provider()
	{
		return $this->belongsTo(Provider::class);
	}

	public function account()
	{
		return $this->belongsTo(Account::class, 'receiver_account_id');
	}
}
