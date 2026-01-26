<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'group',
        'description',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    /**
     * Get permissions grouped by their group.
     */
    public static function getGrouped(): array
    {
        return static::all()->groupBy('group')->toArray();
    }

    /**
     * Get all permission groups.
     */
    public static function getGroups(): array
    {
        return static::distinct()->pluck('group')->toArray();
    }
}
