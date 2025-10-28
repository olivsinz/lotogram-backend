<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasUuid, SoftDeletes, HasFactory;

	protected $casts = [
		'is_active' => 'bool',
		'can_login' => 'bool',
		'can_deposit' => 'bool',
		'can_api_withdraw' => 'bool',
		'can_cp_withdraw' => 'bool',
		'can_over_balance' => 'bool',
		'deposit_server_id' => 'int',
		'deposit_fullname_min_score' => 'float'
	];

	protected $fillable = [
		'uuid',
		'name',
		'is_active',
		'can_login',
		'can_deposit',
		'can_api_withdraw',
		'can_cp_withdraw',
		'can_over_balance',
		'deposit_server_id',
		'deposit_fullname_min_score',
		'slug'
	];

    public function methods()
    {
        return $this->hasMany(SiteMethod::class);
    }

	public function depositServer()
	{
		return $this->belongsTo(DepositServer::class);
	}

    public function keys()
    {
        return $this->hasMany(SiteKey::class);
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

    public function scopeFilterByCanLogin(Builder $query, ?bool $canLogin): void
    {
        $canLogin !== null
            && $query->where('can_login', $canLogin);
    }

    public function scopeFilterByCanDeposit(Builder $query, ?bool $canDeposit): void
    {
        $canDeposit !== null
            && $query->where('can_deposit', $canDeposit);
    }

    public function scopeFilterByCanApiWithdraw(Builder $query, ?bool $canApiWithdraw): void
    {
        $canApiWithdraw !== null
            && $query->where('can_api_withdraw', $canApiWithdraw);
    }

    public function scopeFilterByCanCpWithdraw(Builder $query, ?bool $canCpWithdraw): void
    {
        $canCpWithdraw !== null
            && $query->where('can_cp_withdraw', $canCpWithdraw);
    }

    public function scopeFilterByCanOverBalance(Builder $query, ?bool $canOverBalance): void
    {
        $canOverBalance !== null
            && $query->where('can_over_balance', $canOverBalance);
    }

    public function scopeFilterByDepositServer(Builder $query, ?bool $depositServerId): void
    {
        $depositServerId !== null
            && $query->where('deposit_server_id', $depositServerId);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
