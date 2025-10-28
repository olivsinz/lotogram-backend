<?php

namespace App\Models;

use App\Enum\TransactionType;
use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuid;

	protected $casts = [
		'method_id' => 'int',
		'provider_id' => 'int',
		'user_id' => 'int',
		'account_id' => 'int',
		'amount' => 'float',
		'commission' => 'float',
		'purpose' => 'int',
		'type' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'uuid',
		'method_id',
		'method_tx_id',
		'provider_id',
		'user_id',
		'provider_tx_id',
		'account_id',
		'from_wallet',
		'to_wallet',
		'amount',
		'commission',
		'purpose',
		'type',
		'status',
        'visible_id'
	];

	public function method()
	{
		return $this->belongsTo(Method::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function competition()
	{
		return $this->belongsTo(Competition::class);
	}

	public function transactionDetail()
	{
		return $this->hasOne(TransactionDetail::class);
	}

    public function scopeFilterByVisibleId($query, $visibleId)
    {
        $visibleId !== null
            && $query->where('visible_id', $visibleId);
    }

    public function scopeFilterByMethod($query, $methodId)
    {
        $methodId !== null
            && $query->where('method_id', $methodId);
    }

    public function scopeFilterBySite($query, $siteId)
    {
        $siteId !== null
            && $query->where('method_id', $siteId);
    }

    public function scopeFilterByStatus($query, $status)
    {
        $status !== null
            && $query->where('status', $status);
    }

    public function scopeFilterByType($query, $type)
    {
        $type !== null
            && $query->where('type', $type);
    }

    public function scopeFilterByPurpose($query, $purpose)
    {
        $purpose !== null
            && $query->where('purpose', $purpose);
    }

    public function scopeMethod($query)
    {
        return $query->where('type', TransactionType::Method->value);
    }

    public function scopeCompetition($query)
    {
        return $query->where('type', TransactionType::Competition->value);
    }

    public function scopeUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

}
