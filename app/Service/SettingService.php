<?php

namespace App\Service;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting as SettingModel;

class Setting
{
    public static function get($key, $default = null)
    {
        return Cache::rememberForever('settings.' . $key, function () use ($key, $default) {
            return SettingModel::where('key', $key)->first()->value ?? $default;
        });
    }

    public static function set($key, $value)
    {
        $setting = SettingModel::where('key', $key)->first();
        $setting->value = $value;
        $setting->save();

        Cache::forget('settings.' . $key);
    }

    public static function forget($key)
    {
        Cache::forget('settings.' . $key);
    }

}
