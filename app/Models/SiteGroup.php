<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteGroup extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
		'uuid',
        'method_id',
        'name',
        'description',
        'is_active'
	];

	protected $casts = [
		'method_id' => 'int',
		'is_active' => 'bool'
	];

    public function method()
    {
        return $this->belongsTo(Method::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'site_group_sites');
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

    public function scopeFilterByMethod(Builder $query, ?int $methodId): void
    {
        $methodId !== null
            && $query->where('method_id', $methodId);
    }
}
