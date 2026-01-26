<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!User::where('email', 'admin@visionsphere.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@visionsphere.com',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'status' => 1,
            ]);
        }

        $this->call([
            CategorySeeder::class,
            RolesAndPermissionsSeeder::class,
            VisionSphereContentSeeder::class,
            BlogPostsSeeder::class,
            SliderGallerySeeder::class,
        ]);
    }
}
