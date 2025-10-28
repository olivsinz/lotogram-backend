<?php

namespace App\Traits\Model;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model)
        {
            $model->uuid = Str::uuid();
        });
    }

    public function scopeUuid(Builder $query, $uuid)
    {
        return $query->where('uuid', $uuid)->first();
    }
}
