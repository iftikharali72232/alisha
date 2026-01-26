<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopGlobalSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
    ];

    protected $casts = [
        //
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'float' => (float) $setting->value,
            'array', 'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function set(string $key, $value, string $type = 'string', string $description = '', string $group = 'general'): static
    {
        $storedValue = match ($type) {
            'array', 'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'type' => $type,
                'description' => $description,
                'group' => $group,
            ]
        );
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->get()
            ->mapWithKeys(fn ($setting) => [$setting->key => static::get($setting->key)])
            ->toArray();
    }

    public static function getAllGroups(): array
    {
        return static::query()
            ->select('group')
            ->distinct()
            ->pluck('group')
            ->toArray();
    }

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
