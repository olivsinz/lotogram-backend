<?php

namespace App\Traits\Model;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ApolloCoreException;

trait Cachable
{
    public static function __callStatic($method, $arguments)
    {
        $model = new static;

        if (in_array($method, $model->cacheableAttributes)) {
            return static::getCached($method, $arguments[0]);
        }

        return parent::__callStatic($method, $arguments);
    }

    public static function bootCachable()
    {
        static::retrieved(function ($model) {

            if (!is_array($model->cacheableAttributes) || empty($model->cacheableAttributes)) {
                throw new ApolloCoreException('$cacheableAttributes özelliğini model (' . get_class($model) . ') için tanımlamalısınız.');
            }

            if (config('app.model_cached') === false){
                return;
            }

            foreach ($model->cacheableAttributes as $attribute) {
                $key = $model->getCacheKeyForAttribute($attribute);
                Cache::put($key, $model, $model->cacheDuration ?? 24 * 60 * 60);
            }
        });

        static::saved(function ($model) {

            if (!is_array($model->cacheableAttributes) || empty($model->cacheableAttributes)) {
                throw new ApolloCoreException('$cacheableAttributes özelliğini model (' . get_class($model) . ') için tanımlamalısınız.');
            }

            if (config('app.model_cached') === false){
                return;
            }

            foreach ($model->cacheableAttributes as $attribute) {
                $key = $model->getCacheKeyForAttribute($attribute);
                Cache::put($key, $model, $model->cacheDuration ?? 24 * 60 * 60);
            }
        });

        static::deleted(function ($model) {

            if (!is_array($model->cacheableAttributes) || empty($model->cacheableAttributes)) {
                throw new ApolloCoreException('$cacheableAttributes özelliğini model (' . get_class($model) . ') için tanımlamalısınız.');
            }

            if (config('app.model_cached') === false){
                return;
            }

            foreach ($model->cacheableAttributes as $attribute) {
                $key = $model->getCacheKeyForAttribute($attribute);
                Cache::forget($key);
            }
        });
    }

    protected function getCacheKeyForAttribute(String $attribute, $value = null): string
    {
        $key = sprintf('%s-%s-%s', get_class($this), $attribute, $value == null ? $this->{$attribute} : $value);
        $key = Str::replace('\\', '.', $key);
        return 'model.cached.' . Str::slug($key, '.');
    }

    public static function find ($id)
    {
        return self::getCached('id', $id);
    }

    public static function getCached(String $attribute, String $value): ?self
    {
        if (config('app.model_cached') === false){
            return static::where($attribute, $value)->first();
        }

        $model = new static;
        $key = $model->getCacheKeyForAttribute($attribute, $value);
        $cachedValue = Cache::get($key);

        if ($cachedValue !== null) {
            return $cachedValue;
        }

        $result = $model->where($attribute, $value)->first();

        if ($result !== null) {
            Cache::put($key, $result, $model->cacheDuration ?? 24 * 60 * 60);
        }

        return $result;
    }
}

