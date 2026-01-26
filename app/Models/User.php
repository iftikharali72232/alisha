<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'is_admin',
        'role_id',
        'avatar',
        'bio',
        'phone',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'profile_image',
        'email_notifications',
        'marketing_emails',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'integer',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the role of the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the posts created by the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments created by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        // Super admins have all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permission);
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        // Super admins have all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->hasAnyPermission($permissions);
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_admin && ($this->role && $this->role->slug === 'super-admin');
    }

    /**
     * Check if user can access admin panel.
     */
    public function canAccessAdmin(): bool
    {
        return $this->is_admin || $this->role_id !== null;
    }

    /**
     * Get user's permissions.
     */
    public function getPermissions(): array
    {
        if ($this->isSuperAdmin()) {
            return Permission::pluck('slug')->toArray();
        }

        if (!$this->role) {
            return [];
        }

        return $this->role->permissions()->pluck('slug')->toArray();
    }

    /**
     * Get allowed menu items based on permissions.
     */
    public function getAllowedMenuItems(): array
    {
        $permissions = $this->getPermissions();
        
        $menuMapping = [
            'dashboard' => 'view-dashboard',
            'posts' => 'view-posts',
            'categories' => 'view-categories',
            'tags' => 'view-tags',
            'comments' => 'view-comments',
            'pages' => 'view-pages',
            'sliders' => 'view-sliders',
            'galleries' => 'view-galleries',
            'users' => 'view-users',
            'settings' => 'view-settings',
        ];

        $allowedItems = [];
        foreach ($menuMapping as $menu => $permission) {
            if (in_array($permission, $permissions) || $this->isSuperAdmin()) {
                $allowedItems[] = $menu;
            }
        }

        return $allowedItems;
    }

    /**
     * Get the avatar URL for the user.
     */
    public function getAvatarUrlAttribute(): string
    {
        // Avatar presets with gradient backgrounds
        $presets = [
            ['gradient' => 'from-rose-400 to-pink-500', 'icon' => 'user'],
            ['gradient' => 'from-blue-400 to-indigo-500', 'icon' => 'user-tie'],
            ['gradient' => 'from-green-400 to-teal-500', 'icon' => 'user-astronaut'],
            ['gradient' => 'from-purple-400 to-violet-500', 'icon' => 'user-ninja'],
            ['gradient' => 'from-orange-400 to-red-500', 'icon' => 'user-secret'],
            ['gradient' => 'from-cyan-400 to-blue-500', 'icon' => 'user-graduate'],
            ['gradient' => 'from-yellow-400 to-orange-500', 'icon' => 'smile'],
            ['gradient' => 'from-pink-400 to-rose-500', 'icon' => 'heart'],
        ];

        if (!$this->avatar) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f43f5e&color=fff&size=200';
        }

        if (str_starts_with($this->avatar, 'preset:')) {
            $presetIndex = (int) substr($this->avatar, 7);
            $preset = $presets[$presetIndex] ?? $presets[0];
            // Return a placeholder URL for preset avatars
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=' . $this->getPresetColor($presetIndex) . '&color=fff&size=200';
        }

        return \Storage::url($this->avatar);
    }

    /**
     * Get preset avatar color.
     */
    private function getPresetColor(int $index): string
    {
        $colors = ['f43f5e', '6366f1', '14b8a6', '8b5cf6', 'f97316', '06b6d4', 'f59e0b', 'ec4899'];
        return $colors[$index] ?? 'f43f5e';
    }

    /**
     * Check if avatar is a preset.
     */
    public function hasPresetAvatar(): bool
    {
        return $this->avatar && str_starts_with($this->avatar, 'preset:');
    }

    /**
     * Get preset avatar index.
     */
    public function getPresetAvatarIndex(): ?int
    {
        if (!$this->hasPresetAvatar()) {
            return null;
        }
        return (int) substr($this->avatar, 7);
    }

    /**
     * Get the user's shop.
     */
    public function shop(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Shop::class);
    }
}
