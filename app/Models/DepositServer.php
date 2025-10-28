<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepositServer extends Model
{
    use HasUuid, SoftDeletes, HasFactory;

	protected $casts = [
		'is_active' => 'bool'
	];

	protected $fillable = [
		'name',
		'is_active',
		'form_domain'
	];

	public function sites()
	{
		return $this->hasMany(Site::class);
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

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
