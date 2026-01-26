<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set($key, $value, $type = 'text', $group = 'general', $label = null)
    {
        Cache::forget("setting_{$key}");
        Cache::forget('all_settings');
        
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group, 'label' => $label]
        );
    }

    public static function getAll()
    {
        return Cache::remember('all_settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    public static function getByGroup($group)
    {
        return static::where('group', $group)->get();
    }

    public static function clearCache()
    {
        Cache::forget('all_settings');
        static::all()->each(function ($setting) {
            Cache::forget("setting_{$setting->key}");
        });
    }
}
