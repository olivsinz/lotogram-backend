<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasUuid, SoftDeletes, HasFactory;

	protected $casts = [
		'uuid',
        'method_id' => 'int',
		'is_active' => 'bool',
		'can_deposit' => 'bool',
		'can_withdraw' => 'bool',
	];

	protected $fillable = [
        'uuid',
		'method_id',
		'name',
		'is_active',
		'can_deposit',
		'can_withdraw',
	];

    public function gateways()
    {
        return $this->hasMany(ProviderGateway::class);
    }

    public function ipAddresses()
    {
        return $this->hasMany(ProviderIpWhitelist::class);
    }

	public function providerIpWhitelists()
	{
		return $this->hasMany(ProviderIpWhitelist::class);
	}

    public function scopeFilterByName(Builder $query, ?string $name): void
    {
        $name !== null
            && $query->where('name', 'ILIKE', '%' . $name . '%');
    }

    public function scopeFilterByStatus(Builder $query, ?bool $status): void
    {
        $status !== null
            && $query->where('is_active', $status);
    }

    public function scopeFilterByCanDeposit(Builder $query, ?bool $canDeposit): void
    {
        $canDeposit !== null
            && $query->where('can_deposit', $canDeposit);
    }

    public function scopeFilterByCanWithdraw(Builder $query, ?bool $canWithdraw): void
    {
        $canWithdraw !== null
            && $query->where('can_withdraw', $canWithdraw);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeDepositActive(Builder $query): void
    {
        $query->where('can_deposit', true);
    }

    public function scopeWithdrawActive(Builder $query): void
    {
        $query->where('can_withdraw', true);
    }
}
