<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasUuid, SoftDeletes;

	protected $fillable = [
		'name',
		'color',
		'description',
        'is_active'
	];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeFilterByName($query, $name)
    {
        return $query->when($name, function ($query, $name) {
            return $query->where('name', 'like', "%{$name}%");
        });
    }

    public function scopeFilterByColor($query, $color)
    {
        return $query->when($color, function ($query, $color) {
            return $query->where('color', 'like', "%{$color}%");
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->when($status, function ($query, $status) {
            return $query->where('is_active', $status);
        });
    }
}
