<?php
namespace App\Models;

use App\Enum\MethodType;
use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Method extends Model
{
    use HasUuid, QueryCacheable, SoftDeletes, HasUuid;

    public $cacheFor = 3600;

    protected static $flushCacheOnUpdate = true;

	protected $casts = [
		'is_active' => 'bool',
		'deposit_status' => 'bool',
		'withdraw_status' => 'bool',
		'worker_status' => 'bool'
	];

	protected $fillable = [
		'uuid',
		'name',
		'is_active',
		'deposit_status',
		'withdraw_status',
		'worker_status',
		'slug',
		'panel_domain',
        'type'
	];

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

    public function scopeFilterByDepositStatus(Builder $query, ?bool $status): void
    {
        $status !== null
            && $query->where('deposit_status', $status);
    }

    public function scopeFilterByWithdrawStatus(Builder $query, ?bool $status): void
    {
        $status !== null
            && $query->where('withdraw_status', $status);
    }

    public function scopeFilterByWorkerStatus(Builder $query, ?bool $status): void
    {
        $status !== null
            && $query->where('worker_status', $status);
    }

    public function scopeSlug(Builder $query, ?string $slug): void
    {
        $slug !== null
        && $query->where('slug', $slug);
    }

    public function scopeDepositActive(Builder $query): void
    {
        $query->where('deposit_status', true);
    }

    public function scopeWithdrawActive(Builder $query): void
    {
        $query->where('withdraw_status', true);
    }

    public function scopeWorkerActive(Builder $query): void
    {
        $query->where('worker_status', true);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeVirtualWallet(Builder $query): void
    {
        $query->where('type', MethodType::VirtualWallet);
    }
}
