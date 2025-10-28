<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rennokki\QueryCache\Traits\QueryCacheable;

class UserGroup extends Model
{
    use HasFactory, HasUuid;

    protected $cacheableAttributes = ['id', 'slug'];

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFilterBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}
