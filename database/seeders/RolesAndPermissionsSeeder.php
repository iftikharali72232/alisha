<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'group' => 'dashboard', 'description' => 'Can view the admin dashboard'],
            
            // Posts
            ['name' => 'View Posts', 'slug' => 'view-posts', 'group' => 'posts', 'description' => 'Can view all posts'],
            ['name' => 'Create Posts', 'slug' => 'create-posts', 'group' => 'posts', 'description' => 'Can create new posts'],
            ['name' => 'Edit Posts', 'slug' => 'edit-posts', 'group' => 'posts', 'description' => 'Can edit existing posts'],
            ['name' => 'Edit Own Posts', 'slug' => 'edit-own-posts', 'group' => 'posts', 'description' => 'Can edit only own posts'],
            ['name' => 'Delete Posts', 'slug' => 'delete-posts', 'group' => 'posts', 'description' => 'Can delete posts'],
            ['name' => 'Publish Posts', 'slug' => 'publish-posts', 'group' => 'posts', 'description' => 'Can publish/unpublish posts'],
            
            // Categories
            ['name' => 'View Categories', 'slug' => 'view-categories', 'group' => 'categories', 'description' => 'Can view all categories'],
            ['name' => 'Create Categories', 'slug' => 'create-categories', 'group' => 'categories', 'description' => 'Can create new categories'],
            ['name' => 'Edit Categories', 'slug' => 'edit-categories', 'group' => 'categories', 'description' => 'Can edit existing categories'],
            ['name' => 'Delete Categories', 'slug' => 'delete-categories', 'group' => 'categories', 'description' => 'Can delete categories'],
            
            // Tags
            ['name' => 'View Tags', 'slug' => 'view-tags', 'group' => 'tags', 'description' => 'Can view all tags'],
            ['name' => 'Create Tags', 'slug' => 'create-tags', 'group' => 'tags', 'description' => 'Can create new tags'],
            ['name' => 'Edit Tags', 'slug' => 'edit-tags', 'group' => 'tags', 'description' => 'Can edit existing tags'],
            ['name' => 'Delete Tags', 'slug' => 'delete-tags', 'group' => 'tags', 'description' => 'Can delete tags'],
            
            // Comments
            ['name' => 'View Comments', 'slug' => 'view-comments', 'group' => 'comments', 'description' => 'Can view all comments'],
            ['name' => 'Moderate Comments', 'slug' => 'moderate-comments', 'group' => 'comments', 'description' => 'Can approve/reject/spam comments'],
            ['name' => 'Delete Comments', 'slug' => 'delete-comments', 'group' => 'comments', 'description' => 'Can delete comments'],
            ['name' => 'Reply Comments', 'slug' => 'reply-comments', 'group' => 'comments', 'description' => 'Can reply to comments'],
            
            // Pages
            ['name' => 'View Pages', 'slug' => 'view-pages', 'group' => 'pages', 'description' => 'Can view all pages'],
            ['name' => 'Create Pages', 'slug' => 'create-pages', 'group' => 'pages', 'description' => 'Can create new pages'],
            ['name' => 'Edit Pages', 'slug' => 'edit-pages', 'group' => 'pages', 'description' => 'Can edit existing pages'],
            ['name' => 'Delete Pages', 'slug' => 'delete-pages', 'group' => 'pages', 'description' => 'Can delete pages'],
            
            // Sliders
            ['name' => 'View Sliders', 'slug' => 'view-sliders', 'group' => 'sliders', 'description' => 'Can view all sliders'],
            ['name' => 'Create Sliders', 'slug' => 'create-sliders', 'group' => 'sliders', 'description' => 'Can create new sliders'],
            ['name' => 'Edit Sliders', 'slug' => 'edit-sliders', 'group' => 'sliders', 'description' => 'Can edit existing sliders'],
            ['name' => 'Delete Sliders', 'slug' => 'delete-sliders', 'group' => 'sliders', 'description' => 'Can delete sliders'],
            
            // Galleries
            ['name' => 'View Galleries', 'slug' => 'view-galleries', 'group' => 'galleries', 'description' => 'Can view all galleries'],
            ['name' => 'Create Galleries', 'slug' => 'create-galleries', 'group' => 'galleries', 'description' => 'Can create new galleries'],
            ['name' => 'Edit Galleries', 'slug' => 'edit-galleries', 'group' => 'galleries', 'description' => 'Can edit existing galleries'],
            ['name' => 'Delete Galleries', 'slug' => 'delete-galleries', 'group' => 'galleries', 'description' => 'Can delete galleries'],
            
            // Users
            ['name' => 'View Users', 'slug' => 'view-users', 'group' => 'users', 'description' => 'Can view all users'],
            ['name' => 'Create Users', 'slug' => 'create-users', 'group' => 'users', 'description' => 'Can create new users'],
            ['name' => 'Edit Users', 'slug' => 'edit-users', 'group' => 'users', 'description' => 'Can edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'group' => 'users', 'description' => 'Can delete users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'group' => 'users', 'description' => 'Can assign roles to users'],
            
            // Settings
            ['name' => 'View Settings', 'slug' => 'view-settings', 'group' => 'settings', 'description' => 'Can view settings'],
            ['name' => 'Edit Settings', 'slug' => 'edit-settings', 'group' => 'settings', 'description' => 'Can edit settings'],
            
            // Roles (for managing roles)
            ['name' => 'View Roles', 'slug' => 'view-roles', 'group' => 'roles', 'description' => 'Can view all roles'],
            ['name' => 'Create Roles', 'slug' => 'create-roles', 'group' => 'roles', 'description' => 'Can create new roles'],
            ['name' => 'Edit Roles', 'slug' => 'edit-roles', 'group' => 'roles', 'description' => 'Can edit existing roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles', 'group' => 'roles', 'description' => 'Can delete roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to all features and settings',
                'is_default' => false,
                'permissions' => Permission::pluck('slug')->toArray(), // All permissions
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Can manage most content and users',
                'is_default' => false,
                'permissions' => [
                    'view-dashboard',
                    'view-posts', 'create-posts', 'edit-posts', 'delete-posts', 'publish-posts',
                    'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
                    'view-tags', 'create-tags', 'edit-tags', 'delete-tags',
                    'view-comments', 'moderate-comments', 'delete-comments', 'reply-comments',
                    'view-pages', 'create-pages', 'edit-pages', 'delete-pages',
                    'view-sliders', 'create-sliders', 'edit-sliders', 'delete-sliders',
                    'view-galleries', 'create-galleries', 'edit-galleries', 'delete-galleries',
                    'view-users',
                ],
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Can manage posts, categories, tags and comments',
                'is_default' => false,
                'permissions' => [
                    'view-dashboard',
                    'view-posts', 'create-posts', 'edit-posts', 'publish-posts',
                    'view-categories', 'create-categories', 'edit-categories',
                    'view-tags', 'create-tags', 'edit-tags',
                    'view-comments', 'moderate-comments', 'reply-comments',
                    'view-pages', 'create-pages', 'edit-pages',
                    'view-galleries', 'create-galleries', 'edit-galleries',
                ],
            ],
            [
                'name' => 'Author',
                'slug' => 'author',
                'description' => 'Can create and edit own posts',
                'is_default' => true,
                'permissions' => [
                    'view-dashboard',
                    'view-posts', 'create-posts', 'edit-own-posts',
                    'view-categories',
                    'view-tags',
                    'view-comments',
                ],
            ],
            [
                'name' => 'Contributor',
                'slug' => 'contributor',
                'description' => 'Can create posts but cannot publish',
                'is_default' => false,
                'permissions' => [
                    'view-dashboard',
                    'view-posts', 'create-posts', 'edit-own-posts',
                    'view-categories',
                    'view-tags',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
            
            $role->syncPermissions($permissions);
        }

        // Assign super-admin role to existing admin users
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        User::where('is_admin', true)->whereNull('role_id')->update(['role_id' => $superAdminRole->id]);
    }
}
