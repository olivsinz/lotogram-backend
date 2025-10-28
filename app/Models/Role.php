<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Builder;

class Role extends SpatieRole
{
    use HasUuid, SoftDeletes;

    protected $guard_name = 'web';

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
